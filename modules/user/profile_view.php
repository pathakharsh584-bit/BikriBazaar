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

// Fetch user data
$query = "SELECT name, email, about, profile_image FROM users WHERE id = $user_id LIMIT 1";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

$status = isset($_GET['status']) ? $_GET['status'] : '';
$initial = strtoupper(substr($user['name'] ?? 'U', 0, 1));
$has_image = !empty($user['profile_image']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f2f4f5; color: #002f34; margin: 0; }
        
        .profile-form-container { max-width: 800px; margin: 40px auto; padding: 20px; display: flex; gap: 24px; align-items: flex-start; width: 100%; box-sizing: border-box; }
        
       
        .sidebar { width: 230px; background: #fff; border: 1px solid #e0e0e0; border-radius: 4px; padding: 20px; text-align: center; flex-shrink: 0; }
        
        .avatar-wrapper { position: relative; width: 120px; height: 120px; margin: 0 auto 15px auto; }
        .avatar-img { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; object-position: center; border: 1px solid #e0e0e0; }
        .avatar-text { width: 100%; height: 100%; border-radius: 50%; background: #002f34; color: white; display: flex; align-items: center; justify-content: center; font-size: 48px; font-weight: bold; }
        
        .delete-pic-btn { position: absolute; top: 0; left: 0; background: #fff; border: 1px solid #ccc; border-radius: 50%; width: 32px; height: 32px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #002f34; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .delete-pic-btn:hover { background: #f2f4f5; color: #ff0000; }

        .file-input-wrapper { margin-bottom: 15px; }
        .file-input-wrapper input[type=file] { font-size: 12px; width: 100%; }

        
        .main-card { flex: 1; background: #fff; border: 1px solid #e0e0e0; border-radius: 4px; padding: 30px; }
        h2 { margin-top: 0; margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: 700; margin-bottom: 8px; font-size: 14px; }
        .olx-input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; box-sizing: border-box; }
        .footer-actions { margin-top: 30px; display: flex; justify-content: flex-end; }
        .save-btn { background: #002f34; color: #fff; padding: 12px 32px; border: none; border-radius: 4px; cursor: pointer; font-weight: 700; }
    </style>
</head>
<body>

<form action="<?php echo BASE_URL; ?>../modules/user/user_actions.php" method="POST" enctype="multipart/form-data" class="profile-form-container">
    
    <input type="hidden" name="action" value="update_profile">
    <input type="hidden" name="delete_image_flag" id="deleteImageFlag" value="0">

    <div class="sidebar">
        <div class="avatar-wrapper">
            <button type="button" class="delete-pic-btn" id="deleteBtn" title="Remove picture">
                <i class="fa-solid fa-trash-can"></i>
            </button>
            
            <img id="avatarImg" class="avatar-img" src="<?php echo $has_image ? BASE_URL . 'uploads/profiles/' . htmlspecialchars($user['profile_image']) : ''; ?>" style="display: <?php echo $has_image ? 'block' : 'none'; ?>;">
            
            <div id="avatarText" class="avatar-text" style="display: <?php echo $has_image ? 'none' : 'flex'; ?>;">
                <?php echo $initial; ?>
            </div>
        </div>

        <div class="file-input-wrapper">
            <label style="font-size: 12px; color: #555; margin-bottom: 5px;">Change Picture:</label>
            <input type="file" name="profile_image" id="profileImageInput" accept="image/jpeg, image/png, image/webp">
        </div>
    </div>

    <div class="main-card">
        <h2>Edit Profile</h2>
        
        <div class="form-group">
            <label>Name</label>
            <input class="olx-input" type="text" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input class="olx-input" type="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled>
        </div>

        <div class="form-group">
            <label>About me</label>
            <textarea class="olx-input" name="about" rows="4"><?php echo htmlspecialchars($user['about'] ?? ''); ?></textarea>
        </div>

        <div class="footer-actions">
            <button class="save-btn" type="submit">Save changes</button>
        </div>
    </div>
</form>

<script>
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