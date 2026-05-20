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


// DELETE IMAGE LOGIC
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

    global $conn;

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

            echo json_encode([
                'status' => 'success'
            ]);

            exit();
        }
    }

    echo json_encode([
        'status' => 'error'
    ]);

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


// UPDATE PRODUCT
if($_SERVER['REQUEST_METHOD'] == "POST" && !isset($_POST['ajax_delete_image'])){

    $title       = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $location    = mysqli_real_escape_string($conn, trim($_POST['location'] ?? ''));
    $category    = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));
    $condition   = mysqli_real_escape_string($conn, trim($_POST['condition'] ?? 'used')); // Default to 'used' if missing
    $price       = floatval($_POST['price'] ?? 0);

    // 2. DATA VALIDATION: Check if condition is valid before hitting the database
    $valid_conditions = ['new', 'used', 'refurbished'];
    if (!in_array($condition, $valid_conditions)) {
        $condition = 'used'; // Force a valid fallback
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

        // HANDLE NEW IMAGE UPLOADS
        if(isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {

            $totalFiles = count($_FILES['images']['name']);

            for($i = 0; $i < $totalFiles; $i++) {

                $tempName = $_FILES['images']['tmp_name'][$i];
                $originalName = $_FILES['images']['name'][$i];

                if($tempName != "") {

                    $imageName = time() . "_" . uniqid() . "_" . basename($originalName);

                    $uploadPath = dirname(__DIR__, 2) . '/public/uploads/products/' . $imageName;

                    if(move_uploaded_file($tempName, $uploadPath)) {

                        $imgSql = "
                            INSERT INTO product_images (product_id, image_path)
                            VALUES ('$product_id', '$imageName')
                        ";

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
    <title>Edit Product | Bikri Bazaar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0d4b8f 0%, #00a896 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .container {
            max-width: 680px;
            width: 100%;
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0px 15px 35px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #1e293b;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .alert-error {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            color: #ef4444;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #334155;
            font-size: 14px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 16px;
            color: #00a896;
            font-size: 16px;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 14px 16px 14px 45px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 15px;
            color: #334155;
            background-color: #f8fafc;
            transition: all 0.3s ease;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #00a896;
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(0, 168, 150, 0.15);
        }

        textarea {
            resize: none;
            height: 110px;
        }

        /* Modernized Image Tray Wrap Base Layout */
        .preview-section {
            background: #f8fafc;
            padding: 24px;
            border-radius: 16px;
            margin-bottom: 25px;
            border: 2px dashed #cbd5e1;
            transition: border-color 0.2s ease;
        }

        .thumbnail-container {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        /* Added Position Relative Frame for Cross Positioning Placement Anchor */
        .thumb-wrapper {
            position: relative;
            width: 85px;
            height: 85px;
            border-radius: 12px;
            overflow: visible; /* Allows the close badge element to pop slightly out over margins */
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: transform 0.2s ease;
        }

        .thumb-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        /* Functional Absolute Positioned Cross Style Button Rules */
        .delete-photo-cross-overlay {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 22px;
            height: 22px;
            background: #ef4444;
            color: #ffffff;
            border: 2px solid #ffffff;
            border-radius: 50%;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.35);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 10;
        }

        .delete-photo-cross-overlay:hover {
            background: #dc2626;
            transform: scale(1.15);
        }

        /* Upgraded Visual Text Box Area Styling Rules Container */
        .file-upload-box {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 2px dashed #94a3b8;
            border-radius: 12px;
            padding: 25px 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .file-upload-box:hover {
            background: #f0fdfa;
            border-color: #00a896;
        }

        .file-upload-box i {
            font-size: 32px;
            color: #0d4b8f;
            margin-bottom: 10px;
            transition: transform 0.2s ease;
        }

        .file-upload-box:hover i {
            transform: translateY(-3px);
            color: #00a896;
        }

        .file-upload-box .primary-upload-text {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 4px;
        }

        .file-upload-box .secondary-upload-subtext {
            font-size: 12px;
            color: #64748b;
        }

        .file-upload-box input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 5;
        }

        button[type="submit"] {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #0d4b8f 0%, #0a3d75 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.5px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(13, 75, 143, 0.3);
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(13, 75, 143, 0.4);
            background: linear-gradient(135deg, #10529c 0%, #0d4b8f 100%);
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>

<div class="container">

    <h2>Edit Product</h2>
    <p class="subtitle">Modify the details below to update your listed asset parameters</p>

    <?php if(!empty($message)): ?>
        <div class="alert-error">
            <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label class="form-label">Product Title</label>
        <div class="input-group">
            <i class="fa-solid fa-tag"></i>
            <input
                type="text"
                name="title"
                value="<?php echo htmlspecialchars($product['title']); ?>"
                placeholder="e.g. Dell XPS 13 Laptop"
                required
            >
        </div>

        <label class="form-label">Description</label>
        <div class="input-group">
            <i class="fa-solid fa-align-left" style="top: 16px;"></i>
            <textarea name="description" placeholder="Describe the item's condition..." required><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>

        <label class="form-label">Price (INR)</label>
        <div class="input-group">
            <i class="fa-solid fa-indian-rupee-sign"></i>
            <input
                type="number"
                name="price"
                value="<?php echo htmlspecialchars($product['price']); ?>"
                placeholder="e.g. 45000"
                required
            >
        </div>

        <label class="form-label">Location</label>
        <div class="input-group">
            <i class="fa-solid fa-location-dot"></i>
            <input
                type="text"
                name="location"
                value="<?php echo htmlspecialchars($product['location']); ?>"
                placeholder="City, State"
                required
            >
        </div>

        <div class="form-group">
        <label class="form-label">Condition</label>
        <div class="input-group">
            <i class="fa-solid fa-clock"></i> <select name="condition" required>
                <option value="new" <?php if($product['condition'] == 'new') echo 'selected'; ?>>New</option>
                <option value="used" <?php if($product['condition'] == 'used') echo 'selected'; ?>>Used</option>
                <option value="refurbished" <?php if($product['condition'] == 'refurbished') echo 'selected'; ?>>Refurbished</option>
            </select>
        </div>
    </div>

        <label class="form-label">Category</label>
        <div class="input-group">
            <i class="fa-solid fa-layer-group"></i>
            <select name="category" required>
                <option value="Mobiles" <?php if($product['category']=="Mobiles") echo "selected"; ?>>Mobiles</option>
                <option value="Cars" <?php if($product['category']=="Cars") echo "selected"; ?>>Cars</option>
                <option value="Bikes" <?php if($product['category']=="Bikes") echo "selected"; ?>>Bikes</option>
                <option value="Electronics" <?php if($product['category']=="Electronics") echo "selected"; ?>>Electronics</option>
                <option value="Furniture" <?php if($product['category']=="Furniture") echo "selected"; ?>>Furniture</option>
            </select>
        </div>

        <label class="form-label">Product Photos</label>

<div class="preview-section">

    <?php if(count($existing_images) > 0): ?>

        <p style="font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 12px;">
            Currently Uploaded Photos
        </p>

        <div class="thumbnail-container" id="existing-preview-container">

            <?php foreach($existing_images as $img): ?>

                <div class="thumb-wrapper" id="image-<?php echo $img['id']; ?>">

                    <img
                        src="<?php echo BASE_URL; ?>uploads/products/<?php echo htmlspecialchars($img['image_path']); ?>"
                        alt="Product Image"
                    >

                    <button
                        type="button"
                        class="delete-photo-cross-overlay"
                        onclick="deleteImageAJAX(<?php echo $img['id']; ?>)"
                    >
                        &times;
                    </button>

                </div>

            <?php endforeach; ?>

        </div>

    <?php else: ?>

        <p style="font-size:13px; color:#64748b; margin-bottom:15px;">
            No images uploaded yet.
        </p>

        <div class="thumbnail-container" id="existing-preview-container"></div>

    <?php endif; ?>


    <!-- NEWLY SELECTED IMAGE PREVIEW -->
    <div class="thumbnail-container" id="new-image-preview"></div>


    <div class="file-upload-box">

        <i class="fa-solid fa-cloud-arrow-up"></i>

        <span class="primary-upload-text" id="interactiveUploadLabel">
            Drag & drop your items here or
            <span style="color: #00a896; text-decoration: underline;">
                browse files
            </span>
        </span>

        <span class="secondary-upload-subtext">
            Supports PNG, JPG, or JPEG formats up to 5MB
        </span>

        <input
            type="file"
            name="images[]"
            id="fileFieldInput"
            multiple
            accept="image/*"
            onchange="previewSelectedImages()"
        >

    </div>

    <p style="font-size: 11px; color: #64748b; margin-top: 8px; text-align: center;">
        Newly uploaded images will be added to existing photos.
    </p>

</div>

        <button type="submit">
            <i class="fa-solid fa-square-check"></i> Update Product Ad
        </button>

    </form>
</div>

<script>

let selectedFiles = [];


/* =========================
   PREVIEW NEWLY SELECTED IMAGES
========================= */

function previewSelectedImages() {

    const fileInput = document.getElementById('fileFieldInput');
    const previewContainer = document.getElementById('existing-preview-container');
    const label = document.getElementById('interactiveUploadLabel');

    const newFiles = Array.from(fileInput.files);

    // APPEND FILES IN MEMORY
    selectedFiles = [...selectedFiles, ...newFiles];

    // UPDATE LABEL
    label.innerHTML = selectedFiles.length + " file(s) selected";

    // CLEAR INPUT VALUE
    fileInput.value = "";

    renderSelectedImages();
}



/* =========================
   RENDER SELECTED IMAGES
========================= */

function renderSelectedImages(){

    // REMOVE OLD TEMP PREVIEWS
    document.querySelectorAll('.temp-image').forEach(el => el.remove());

    const previewContainer = document.getElementById('existing-preview-container');

    selectedFiles.forEach((file, index) => {

        const reader = new FileReader();

        reader.onload = function(e){

            const wrapper = document.createElement('div');

            wrapper.className = 'thumb-wrapper temp-image';

            wrapper.innerHTML = `
                <img src="${e.target.result}" alt="Preview">

                <button
                    type="button"
                    class="delete-photo-cross-overlay"
                    onclick="removeSelectedImage(${index})"
                >
                    &times;
                </button>
            `;

            previewContainer.appendChild(wrapper);
        };

        reader.readAsDataURL(file);
    });

    updateFileInputFiles();
}



/* =========================
   REMOVE NEWLY SELECTED IMAGE
========================= */

function removeSelectedImage(index){

    selectedFiles.splice(index, 1);

    renderSelectedImages();

    const label = document.getElementById('interactiveUploadLabel');

    if(selectedFiles.length > 0){

        label.innerHTML = selectedFiles.length + " file(s) selected";

    } else {

        label.innerHTML = `
            Drag & drop your items here or
            <span style="color: #00a896; text-decoration: underline;">
                browse files
            </span>
        `;
    }
}



/* =========================
   UPDATE REAL INPUT FILES
========================= */

function updateFileInputFiles(){

    const dataTransfer = new DataTransfer();

    selectedFiles.forEach(file => {
        dataTransfer.items.add(file);
    });

    document.getElementById('fileFieldInput').files = dataTransfer.files;
}



/* =========================
   AJAX DELETE EXISTING IMAGE
========================= */

function deleteImageAJAX(imageId){

    if(!confirm('Delete this image?')){
        return;
    }

    const formData = new FormData();
    formData.append('ajax_delete_image', imageId);

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {

        if(data.status === 'success'){

            const imageBox = document.getElementById('image-' + imageId);

            if(imageBox){

                imageBox.style.opacity = '0';

                setTimeout(() => {
                    imageBox.remove();
                }, 300);
            }

        } else {

            alert('Failed to delete image');
        }
    })
    .catch(error => {

        console.error(error);
        alert('Something went wrong');
    });
}

</script>

</body>
</html>