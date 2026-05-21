<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start();

session_start();

require_once __DIR__ . '/../../shared/db.php';
require_once __DIR__ . '/../../shared/config.php';

if(!isset($_SESSION['user_id'])){
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if(!isset($_GET['id'])){
    die("Product Not Found");
}

$product_id = mysqli_real_escape_string($conn, trim($_GET['id']));
$user_id = intval($_SESSION['user_id']);

// FETCH PRODUCT DETAILS
$sql = "SELECT * FROM products WHERE id='$product_id' AND user_id='$user_id'";
$result = mysqli_query($conn, $sql);

if(!$result || mysqli_num_rows($result) == 0){
    die("Unauthorized Access or Product Not Found");
}

$product = mysqli_fetch_assoc($result);

// DELETE IMAGE LOGIC (via GET)
if(isset($_GET['delete_image'])){
    $image_id = intval($_GET['delete_image']);

    $check_sql = "
        SELECT pi.image_path
        FROM product_images pi
        INNER JOIN products p ON pi.product_id = p.id
        WHERE pi.id = '$image_id'
        AND p.user_id = '$user_id'
        LIMIT 1
    ";

    $check_result = mysqli_query($conn, $check_sql);

    if($check_result && mysqli_num_rows($check_result) > 0){
        $img_data = mysqli_fetch_assoc($check_result);
        $file_path = dirname(__DIR__, 2) . '/public/uploads/products/' . $img_data['image_path'];

        $delete_sql = "DELETE FROM product_images WHERE id='$image_id'";
        if(mysqli_query($conn, $delete_sql)){
            if(file_exists($file_path)){
                unlink($file_path);
            }
        }
    }

    header("Location: edit-product.php?id=" . $product_id);
    exit();
}

// AJAX IMAGE DELETE
if(isset($_POST['ajax_delete_image'])){
    header('Content-Type: application/json');
    $image_id = intval($_POST['ajax_delete_image']);

    $check_sql = "
        SELECT pi.image_path
        FROM product_images pi
        INNER JOIN products p ON pi.product_id = p.id
        WHERE pi.id = '$image_id'
        AND p.user_id = '$user_id'
        LIMIT 1
    ";

    $check_result = mysqli_query($conn, $check_sql);

    if($check_result && mysqli_num_rows($check_result) > 0){
        $img_data = mysqli_fetch_assoc($check_result);
        $file_path = dirname(__DIR__, 2) . '/public/uploads/products/' . $img_data['image_path'];

        $delete_sql = "DELETE FROM product_images WHERE id='$image_id'";
        if(mysqli_query($conn, $delete_sql)){
            if(file_exists($file_path)){
                unlink($file_path);
            }
            echo json_encode(['status' => 'success']);
            exit();
        }
    }
    echo json_encode(['status' => 'error']);
    exit();
}

// FETCH EXISTING IMAGES
$img_sql = "SELECT * FROM product_images WHERE product_id = '$product_id'";
$img_result = mysqli_query($conn, $img_sql);
$existing_images = [];
while($row = mysqli_fetch_assoc($img_result)){
    $existing_images[] = [
        'id' => $row['id'],
        'image_path' => $row['image_path']
    ];
}

$message = "";

// UPDATE PRODUCT (after conflict resolution – keep the validated version)
if($_SERVER['REQUEST_METHOD'] == "POST" && !isset($_POST['ajax_delete_image'])){
    $title       = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $location    = mysqli_real_escape_string($conn, trim($_POST['location'] ?? ''));
    $category    = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));
    $condition   = mysqli_real_escape_string($conn, trim($_POST['condition'] ?? 'used'));
    $price       = floatval($_POST['price'] ?? 0);

    // Validate condition
    $valid_conditions = ['new', 'used', 'refurbished'];
    if (!in_array($condition, $valid_conditions)) {
        $condition = 'used';
    }

    $update = "UPDATE products SET
               title='$title',
               description='$description',
               price='$price',
               location='$location',
               `condition`='$condition',
               category='$category'
               WHERE id='$product_id'
               AND user_id='$user_id'";

    if(mysqli_query($conn, $update)){
        // Handle new image uploads
        if(isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $totalFiles = count($_FILES['images']['name']);
            for($i = 0; $i < $totalFiles; $i++) {
                $tempName = $_FILES['images']['tmp_name'][$i];
                $originalName = $_FILES['images']['name'][$i];
                if($tempName != "") {
                    $imageName = time() . "_" . uniqid() . "_" . basename($originalName);
                    $uploadPath = dirname(__DIR__, 2) . '/public/uploads/products/' . $imageName;
                    if(move_uploaded_file($tempName, $uploadPath)) {
                        $imgSql = "INSERT INTO product_images (product_id, image_path) VALUES ('$product_id', '$imageName')";
                        mysqli_query($conn, $imgSql);
                    }
                }
            }
        }
        header("Location: " . BASE_URL . "my-ads.php");
        exit();
    } else {
        $message = "Update Failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - BikriBazaar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a3fc4;
            --primary-dark: #1530a0;
            --teal: #0ea5a0;
            --teal-dark: #0b8a86;
            --grad: linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
            --surface: #f4f7ff;
            --card-bg: #ffffff;
            --text: #1a1a2e;
            --muted: #6b7280;
            --border: #dde4f5;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--surface);
            color: var(--text);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        a { text-decoration: none; color: inherit; }

        /* ===== NAVBAR (shared component) ===== */
        .navbar {
            background: #fff;
            box-shadow: 0 1px 6px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            height: 66px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--primary);
        }
        .logo span { color: var(--teal); }
        .logo-img {
            height: 40px;
            width: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-left: auto;
        }
        .nav-links a {
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text);
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            transition: background 0.18s;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            position: relative;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 0;
            height: 3px;
            background: #4338ca;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        .nav-links a:hover::after {
            width: 100%;
        }
        .nav-links a:not(.btn-primary):hover {
            background: #f4f7ff;
        }
        .btn-primary {
            background: var(--grad) !important;
            color: #fff !important;
            font-weight: 700;
            border-radius: 8px;
            padding: 0.42rem 1.1rem !important;
            transition: opacity 0.2s, transform 0.15s;
        }
        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .profile-dropdown { position: relative; }
        .nav-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--grad);
            color: #fff;
            font-weight: 800;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid #dde4f5;
            transition: opacity 0.2s;
        }
        .nav-avatar:hover { opacity: 0.9; }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: -4px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.10);
            min-width: 200px;
            border: 1px solid #dde4f5;
            overflow: hidden;
            z-index: 200;
        }
        .profile-dropdown:hover .dropdown-content { display: block; }
        .profile-dropdown::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 100%;
            height: 12px;
            background: transparent;
        }
        .dropdown-user-meta {
            padding: 0.75rem 1rem 0.45rem;
            font-size: 0.86rem;
            color: #6b7280;
        }
        .dropdown-content hr {
            border: none;
            border-top: 1px solid #dde4f5;
            margin: 3px 0;
        }
        .dropdown-content a {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.55rem 1rem;
            font-size: 0.86rem;
            color: #1a1a2e;
            transition: background 0.2s;
            position: relative;
        }
        .dropdown-content a i { width: 15px; color: var(--primary); }
        .dropdown-content a:hover { background: #f4f7ff; }
        .dropdown-badge {
            background: #ef4444;
            color: #fff;
            font-size: 0.66rem;
            font-weight: 700;
            padding: 0.1rem 0.4rem;
            border-radius: 20px;
            margin-left: auto;
        }

        /* ===== EDIT FORM (modern card) ===== */
        .edit-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            flex: 1;
        }
        .form-card {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
            border: 1px solid #a5a5a5;
            padding: 2rem;
        }
        .form-header {
            text-align: center;
            margin-bottom: 1.8rem;
        }
        .form-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
        }
        .form-header p {
            font-size: 0.85rem;
            color: var(--muted);
            margin-top: 0.25rem;
        }
        .form-group {
            margin-bottom: 1.2rem;
        }
        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.45rem;
        }
        .form-group label i {
            margin-right: 0.5rem;
            color: #6b7280;
            font-size: 0.85rem;
        }
        .input-wrap {
            position: relative;
        }
        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 0.9rem;
            pointer-events: none;
            z-index: 2;
        }
        .input-wrap.textarea-wrap i {
            top: 16px;
            transform: none;
        }
        .input-wrap input, 
        .input-wrap select, 
        .input-wrap textarea {
            width: 100%;
            padding: 12px 12px 12px 42px;
            border: 1.5px solid #999999;
            border-radius: 14px;
            font-size: 0.92rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fafcff;
        }
        .input-wrap textarea {
            min-height: 110px;
            padding-top: 12px;
            resize: vertical;
        }
        .input-wrap select {
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="%236b7280" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>');
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }
        .input-wrap input:focus,
        .input-wrap select:focus,
        .input-wrap textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(26,63,196,0.15);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .existing-images-box {
            background: #f9fafb;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1rem;
            margin: 1rem 0;
        }
        .existing-images-box h4 {
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: var(--text);
        }
        .img-grid {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .img-grid .img-wrapper {
            position: relative;
            width: 70px;
            height: 70px;
            border-radius: 12px;
            border: 2px solid var(--border);
            overflow: hidden;
            transition: transform 0.2s;
        }
        .img-grid .img-wrapper:hover {
            transform: scale(1.05);
            border-color: var(--teal);
        }
        .img-grid img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .img-grid .delete-img-btn {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 20px;
            height: 20px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }
        .img-grid .delete-img-btn:hover {
            transform: scale(1.1);
            background: #dc2626;
        }

        /* File upload zone */
        .upload-zone {
            border: 2px dashed #999999;
            border-radius: 12px;
            padding: 1.8rem 1rem;
            text-align: center;
            cursor: pointer;
            background: #fafcff;
            position: relative;
            transition: border-color 0.2s;
            margin: 1rem 0;
        }
        .upload-zone:hover {
            border-color: var(--primary);
        }
        .upload-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
            z-index: 10;
        }
        .upload-zone i {
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
            display: block;
        }
        .upload-zone p {
            font-size: 0.85rem;
            color: var(--muted);
        }
        .upload-zone span {
            color: var(--primary);
            font-weight: 600;
        }
        #preview-wrap {
            display: none;
            margin-top: 1rem;
            flex-wrap: wrap;
            gap: 10px;
        }
        .preview-item {
            position: relative;
            width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            border: 1.5px solid var(--border);
        }
        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .remove-img-btn {
            position: absolute;
            top: 4px;
            right: 4px;
            background: rgba(0,0,0,0.6);
            color: white;
            border: none;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            transition: background 0.2s;
            z-index: 20;
        }
        .remove-img-btn:hover {
            background: #ef4444;
        }
        .clear-btn-wrap {
            width: 100%;
            text-align: right;
            margin-top: 5px;
        }
        .clear-images-btn {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }
        .clear-images-btn:hover {
            background: #fca5a5;
        }
        .auth-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 40px;
            background: var(--grad);
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .auth-btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        .error-msg {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 14px;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid #fecaca;
            text-align: center;
        }
        @media (max-width: 640px) {
            .form-row { grid-template-columns: 1fr; gap: 0; }
            .edit-container { padding: 0 1rem; }
            .form-card { padding: 1.5rem; }
        }
    </style>
</head>
<body>

<!-- SHARED NAVBAR (consistent with other pages) -->
<?php include __DIR__ . '/../../shared/components/navbar.php'; ?>

<div class="edit-container">
    <div class="form-card">
        <div class="form-header">
            <h1><i class="fa-solid fa-pen-to-square"></i> Edit Product</h1>
            <p>Update your listing details and add more photos</p>
        </div>

        <?php if($message != ""): ?>
            <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" id="editForm">
            <div class="form-group">
                <label><i class="fa-solid fa-tag"></i> Product Title</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-heading"></i>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']); ?>" placeholder="e.g., iPhone 14 Pro Max" required>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-align-left"></i> Description</label>
                <div class="input-wrap textarea-wrap">
                    <i class="fa-solid fa-align-left"></i>
                    <textarea name="description" placeholder="Describe your product in detail..." required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fa-solid fa-indian-rupee-sign"></i> Price</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-indian-rupee-sign"></i>
                        <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" placeholder="e.g., 25000" min="0" step="any" required>
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-location-dot"></i> Location</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-location-dot"></i>
                        <input type="text" name="location" value="<?php echo htmlspecialchars($product['location']); ?>" placeholder="City, Area" required>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fa-solid fa-folder-open"></i> Category</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-tag"></i>
                        <select name="category" required>
                            <option value="" disabled>Select Category</option>
                            <option value="Mobiles" <?php if($product['category']=="Mobiles") echo "selected"; ?>>Mobiles</option>
                            <option value="Cars" <?php if($product['category']=="Cars") echo "selected"; ?>>Cars</option>
                            <option value="Bikes" <?php if($product['category']=="Bikes") echo "selected"; ?>>Bikes</option>
                            <option value="Electronics" <?php if($product['category']=="Electronics") echo "selected"; ?>>Electronics</option>
                            <option value="Furniture" <?php if($product['category']=="Furniture") echo "selected"; ?>>Furniture</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label><i class="fa-solid fa-clipboard-list"></i> Condition</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <select name="condition" required>
                            <option value="" disabled>Select Condition</option>
                            <option value="new" <?php if(isset($product['condition']) && $product['condition'] == "new") echo "selected"; ?>>New</option>
                            <option value="used" <?php if(!isset($product['condition']) || $product['condition'] == "used") echo "selected"; ?>>Used</option>
                            <option value="refurbished" <?php if(isset($product['condition']) && $product['condition'] == "refurbished") echo "selected"; ?>>Refurbished</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Existing Images with Delete Buttons -->
            <div class="existing-images-box">
                <h4><i class="fa-solid fa-images"></i> Current Photos</h4>
                <div class="img-grid" id="existingImagesGrid">
                    <?php if(count($existing_images) > 0): ?>
                        <?php foreach($existing_images as $img): ?>
                            <div class="img-wrapper" id="img-wrapper-<?php echo $img['id']; ?>">
                                <img src="uploads/products/<?php echo htmlspecialchars($img['image_path']); ?>" alt="Product image">
                                <button type="button" class="delete-img-btn" onclick="deleteImage(<?php echo $img['id']; ?>)">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="font-size: 0.8rem; color: var(--muted);">No photos uploaded yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Upload New Photos (with preview) -->
            <div class="form-group">
                <label><i class="fa-solid fa-camera"></i> Add More Photos (Optional)</label>
                <div class="upload-zone" id="uploadZone">
                    <input type="file" id="imageInput" name="images[]" accept="image/*" multiple onchange="previewImages(this)">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <p><span>Click to upload</span> or drag &amp; drop</p>
                    <p style="margin-top:0.3rem;">JPG, PNG, GIF &bull; Max 5MB (Multiple Allowed)</p>
                </div>
                <div id="preview-wrap"></div>
            </div>

            <button type="submit" class="auth-btn">
                <i class="fa-solid fa-save"></i> Update Product
            </button>
        </form>
    </div>
</div>

<script>
    // ==================== EXISTING IMAGE DELETE (AJAX) ====================
    function deleteImage(imageId) {
        if (!confirm('Delete this image? This action cannot be undone.')) return;

        const formData = new FormData();
        formData.append('ajax_delete_image', imageId);

        fetch(window.location.href, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const wrapper = document.getElementById('img-wrapper-' + imageId);
                if (wrapper) wrapper.remove();
                // If no images left, show "No photos" message
                const grid = document.getElementById('existingImagesGrid');
                if (grid && grid.children.length === 0) {
                    grid.innerHTML = '<p style="font-size: 0.8rem; color: var(--muted);">No photos uploaded yet.</p>';
                }
            } else {
                alert('Failed to delete image');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Something went wrong');
        });
    }

    // ==================== NEW IMAGE PREVIEW (DataTransfer) ====================
    const dt = new DataTransfer();

    function previewImages(input) {
        if (input.files) {
            for (let i = 0; i < input.files.length; i++) {
                dt.items.add(input.files[i]);
            }
        }
        input.files = dt.files;
        renderGallery();
    }

    function renderGallery() {
        const previewWrap = document.getElementById('preview-wrap');
        previewWrap.innerHTML = '';
        if (dt.files.length > 0) {
            previewWrap.style.display = 'flex';
            Array.from(dt.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.className = 'preview-item';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove-img-btn" onclick="removeFile(${index})" title="Remove">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    `;
                    previewWrap.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
            const clearWrap = document.createElement('div');
            clearWrap.className = 'clear-btn-wrap';
            clearWrap.innerHTML = `<button type="button" class="clear-images-btn" onclick="clearImages()"><i class="fa-solid fa-trash"></i> Clear All</button>`;
            previewWrap.appendChild(clearWrap);
        } else {
            previewWrap.style.display = 'none';
        }
    }

    function removeFile(index) {
        const dtCopy = new DataTransfer();
        const currentFiles = dt.files;
        for (let i = 0; i < currentFiles.length; i++) {
            if (i !== index) dtCopy.items.add(currentFiles[i]);
        }
        dt.items.clear();
        for (let i = 0; i < dtCopy.files.length; i++) dt.items.add(dtCopy.files[i]);
        document.getElementById('imageInput').files = dt.files;
        renderGallery();
    }

    function clearImages() {
        dt.items.clear();
        document.getElementById('imageInput').files = dt.files;
        renderGallery();
    }

    // Drag & drop visual feedback
    const zone = document.getElementById('uploadZone');
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor = 'var(--primary)'; });
    zone.addEventListener('dragleave', () => { zone.style.borderColor = '#999999'; });
    zone.addEventListener('drop', () => { zone.style.borderColor = '#999999'; });
</script>

</body>
</html>