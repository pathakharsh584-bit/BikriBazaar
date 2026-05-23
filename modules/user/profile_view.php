<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../shared/db.php';
require_once __DIR__ . '/../../shared/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch unread count for navbar
$unread_count = 0;
$unread_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM messages WHERE receiver_id = $user_id AND is_seen = 0");
if ($unread_res) {
    $unread_count = mysqli_fetch_assoc($unread_res)['total'];
}

// Fetch user data
$query = "SELECT name, email, about, profile_image FROM users WHERE id = $user_id LIMIT 1";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

$initial = strtoupper(substr($user['name'] ?? 'U', 0, 1));
$has_image = !empty($user['profile_image']);
// Build the image URL exactly as in your working version
$image_url = $has_image ? htmlspecialchars($user['profile_image']) : '';
// Add a cache-busting timestamp (changes on every page load)
$image_url_with_time = $image_url ? $image_url . '?t=' . time() : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile – BikriBazaar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Your existing CSS – unchanged (same as the "working good" version) */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            color: #1e2a3a;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .profile-form-container {
            max-width: 1100px;
        margin: 2rem auto;
        background: #fff;
        border-radius: 28px;
        box-shadow: 0 2px 18px #79797b;
        display: flex;
        gap: 2rem;
        padding: 2rem;
        flex-wrap: wrap;
        border: 1px solid #8f9091;
        }
        .sidebar {
            flex: 0 0 240px;
            background: #ffffff;
            border-radius: 20px;
            padding: 1.5rem 1rem;
            text-align: center;
            border: 1px solid #cbd5e1;
        }
        .avatar-wrapper {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto 1.2rem auto;
        }
        .avatar-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #1a3fc4;
            background: #fff;
        }
        .avatar-text {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a3fc4, #0ea5a0);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 700;
            border: 3px solid #1a3fc4;
        }
        .delete-pic-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #fff;
            border: 1px solid #cbd5e1;
            border-radius: 50%;
            width: 34px;
            height: 34px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            transition: all 0.2s ease;
        }
        .delete-pic-btn:hover {
            background: #fee2e2;
            color: #dc2626;
            transform: scale(1.05);
        }
        .file-input-wrapper {
            margin: 1rem 0 0.5rem;
        }
        .file-input-wrapper label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #334155;
            display: block;
            margin-bottom: 6px;
            text-align: left;
        }
        .file-input-wrapper input[type="file"] {
            font-size: 0.75rem;
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #838b95;
            border-radius: 12px;
            background: #fafcff;
            cursor: pointer;
        }
        .main-card {
            flex: 1;
            background: #ffffff;
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid #cbd5e1;
        }
        h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a3fc4;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 3px solid #0ea5a0;
            display: inline-block;
        }
        .form-group {
            margin-bottom: 1.4rem;
        }
        label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }
        .olx-input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1.5px solid #838b95;
            border-radius: 14px;
            font-size: 0.9rem;
            font-family: inherit;
            background: #fff;
            transition: all 0.2s;
        }
        .olx-input:focus {
            outline: none;
            border-color: #1a3fc4;
            box-shadow: 0 0 0 3px rgba(26,63,196,0.15);
        }
        textarea.olx-input {
            resize: vertical;
            min-height: 100px;
        }
        input.olx-input:disabled {
            background: #f1f5f9;
            color: #64748b;
            cursor: not-allowed;
        }
        .footer-actions {
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
        }
        .save-btn {
            background: linear-gradient(135deg, #1a3fc4, #0ea5a0);
            color: white;
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 40px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .save-btn:hover {
            opacity: 0.92;
            transform: translateY(-2px);
        }
        @media (max-width: 768px) {
            .profile-form-container {
                flex-direction: column;
                padding: 1.5rem;
            }
            .sidebar {
                flex: auto;
                max-width: 280px;
                margin: 0 auto;
            }
        }
    </style>
</head>
<body>

<!-- SHARED NAVBAR -->
<?php include __DIR__ . '/../../shared/components/navbar.php'; ?>

<!-- FORM – action remains exactly as in your working version -->
<form action="<?php echo BASE_URL; ?>../modules/user/user_actions.php" method="POST" enctype="multipart/form-data" class="profile-form-container">
    
    <input type="hidden" name="action" value="update_profile">
    <input type="hidden" name="delete_image_flag" id="deleteImageFlag" value="0">

    <div class="sidebar">
        <div class="avatar-wrapper">
            <button type="button" class="delete-pic-btn" id="deleteBtn" title="Remove picture">
                <i class="fa-solid fa-trash-can"></i>
            </button>
            <!-- Image with cache-busting timestamp -->
            <img id="avatarImg" class="avatar-img" 
                 src="<?php echo $image_url_with_time; ?>" 
                 style="display: <?php echo $has_image ? 'block' : 'none'; ?>;"
                 onerror="this.style.display='none'; document.getElementById('avatarText').style.display='flex';">
            <div id="avatarText" class="avatar-text" style="display: <?php echo $has_image ? 'none' : 'flex'; ?>;">
                <?php echo $initial; ?>
            </div>
        </div>
        <div class="file-input-wrapper">
            <label><i class="fa-regular fa-image"></i> Change Picture</label>
            <input type="file" name="profile_image" id="profileImageInput" accept="image/jpeg, image/png, image/webp">
        </div>
        <div style="font-size: 0.7rem; color: #64748b; margin-top: 0.5rem;">JPG, PNG, WebP (max 5MB)</div>
    </div>

    <div class="main-card">
        <h2><i class="fa-regular fa-user"></i> Edit Profile</h2>
        
        <div class="form-group">
            <label><i class="fa-regular fa-user"></i> Full Name</label>
            <input class="olx-input" type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label><i class="fa-regular fa-envelope"></i> Email Address</label>
            <input class="olx-input" type="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled>
        </div>

        <div class="form-group">
            <label><i class="fa-regular fa-address-card"></i> About Me</label>
            <textarea class="olx-input" name="about" rows="4" placeholder="Tell something about yourself..."><?php echo htmlspecialchars($user['about'] ?? ''); ?></textarea>
        </div>

        <div class="footer-actions">
            <button class="save-btn" type="submit">
                <i class="fa-regular fa-floppy-disk"></i> Save Changes
            </button>
        </div>
    </div>
</form>
<?php include __DIR__ . '/../../shared/components/footer.php'; ?>
<script>
    // Avatar preview & delete (unchanged logic from your working version)
    const fileInput = document.getElementById('profileImageInput');
    const avatarImg = document.getElementById('avatarImg');
    const avatarText = document.getElementById('avatarText');
    const deleteBtn = document.getElementById('deleteBtn');
    const deleteFlag = document.getElementById('deleteImageFlag');

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarImg.src = e.target.result;
                avatarImg.style.display = 'block';
                avatarText.style.display = 'none';
                deleteFlag.value = '0';
                // Remove onerror so it doesn't hide manually previewed image
                avatarImg.onerror = null;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    deleteBtn.addEventListener('click', function() {
        fileInput.value = '';
        avatarImg.src = '';
        avatarImg.style.display = 'none';
        avatarText.style.display = 'flex';
        deleteFlag.value = '1';
    });
</script>

</body>
</html>