<?php
session_start();
require_once __DIR__ . '/../../shared/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if(!isset($_GET['id'])){
    die("Product Not Found");
}

$product_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Secure incoming parameter
$product_id = mysqli_real_escape_string($conn, $product_id);

// --- ASYNCHRONOUS AJAX PHOTO DELETION HANDLER BLOCK ---
if (isset($_GET['action']) && $_GET['action'] === 'ajax_delete' && isset($_GET['image_name'])) {
    header('Content-Type: application/json');
    
    $image_to_delete = $_GET['image_name'];
    
    // Fetch current product record to verify ownership and extract current image array
    $verify_sql = "SELECT image, user_id FROM products WHERE id='$product_id' AND user_id='$user_id'";
    $verify_res = mysqli_query($conn, $verify_sql);
    
    if (mysqli_num_rows($verify_res) === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized or record missing.']);
        exit();
    }
    
    $p_data = mysqli_fetch_assoc($verify_res);
    $img_list = !empty($p_data['image']) ? explode(',', $p_data['image']) : [];
    
    // Find item array key position match and slice it out
    if (($key = array_search($image_to_delete, $img_list)) !== false) {
        unset($img_list[$key]);
        
        $updated_img_string = implode(',', $img_list);
        $escaped_img_string = mysqli_real_escape_string($conn, $updated_img_string);
        
        $update_img_sql = "UPDATE products SET image='$escaped_img_string' WHERE id='$product_id' AND user_id='$user_id'";
        
        if (mysqli_query($conn, $update_img_sql)) {
            // Delete target file asset physically from local folder directory storage
            $file_disk_path = dirname(__DIR__, 2) . '/public/uploads/products/' . $image_to_delete;
            if (file_exists($file_disk_path) && $image_to_delete !== 'default.jpg') {
                @unlink($file_disk_path);
            }
            echo json_encode(['status' => 'success']);
            exit();
        }
    }
    
    echo json_encode(['status' => 'error', 'message' => 'Image reference target mismatch operations failure.']);
    exit();
}

// Re-verify general access baseline parameters
$sql = "SELECT * FROM products WHERE id='$product_id' AND user_id='$user_id'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){
    die("Unauthorized Access");
}

$product = mysqli_fetch_assoc($result);
$message = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price       = trim($_POST['price']);
    $location    = trim($_POST['location']);
    $category    = trim($_POST['category']);

    // Retain old images remaining as historical fallback baseline arrays
    $existingImages = !empty($product['image']) ? explode(',', $product['image']) : [];

    // Handle new incoming images if uploaded
    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        $uploadedImages = [];
        $totalFiles = count($_FILES['images']['name']);
        $limit = min($totalFiles, 6);

        for ($i = 0; $i < $limit; $i++) {
            if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                $imageName = time() . "_" . $i . "_" . $_FILES['images']['name'][$i];
                $tempName  = $_FILES['images']['tmp_name'][$i];
                $uploadPath = dirname(__DIR__, 2) . '/public/uploads/products/' . $imageName;

                if (move_uploaded_file($tempName, $uploadPath)) {
                    $uploadedImages[] = $imageName;
                }
            }
        }
        
        // Append newly added images onto the remaining active image elements selection array
        $finalImagesArray = array_merge($existingImages, $uploadedImages);
        $finalImagesString = implode(',', $finalImagesArray);
    } else {
        $finalImagesString = $product['image'];
    }

    // Escape form text properties to prevent crashes or structural query breaks
    $titleEscaped       = mysqli_real_escape_string($conn, $title);
    $descriptionEscaped = mysqli_real_escape_string($conn, $description);
    $priceEscaped       = mysqli_real_escape_string($conn, $price);
    $locationEscaped    = mysqli_real_escape_string($conn, $location);
    $categoryEscaped    = mysqli_real_escape_string($conn, $category);
    $imagesEscaped      = mysqli_real_escape_string($conn, $finalImagesString);

    $update = "UPDATE products SET
               title='$titleEscaped',
               description='$descriptionEscaped',
               price='$priceEscaped',
               location='$locationEscaped',
               category='$categoryEscaped',
               image='$imagesEscaped'
               WHERE id='$product_id' AND user_id='$user_id'";

    if(mysqli_query($conn, $update)){
        header("Location: " . BASE_URL . "public/product.php?id=" . $product_id);
        exit();
    } else {
        $message = "Update Failed! " . mysqli_error($conn);
    }
}

// Convert column values into iterable active image item instances
$currentImages = !empty($product['image']) ? explode(',', $product['image']) : [];
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
            <?php if(!empty($currentImages)): ?>
                <p style="font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 8px;">Currently Saved Layout:</p>
                <div class="thumbnail-container">
                    <?php foreach($currentImages as $index => $imageItem): if(empty(trim($imageItem))) continue; ?>
                        <div class="thumb-wrapper" id="photo-card-<?php echo $index; ?>">
                           <img src="<?php echo BASE_URL; ?>uploads/products/<?php echo htmlspecialchars($imageItem); ?>" alt="Preview Asset">
                           <button type="button" class="delete-photo-cross-overlay" onclick="triggerAsynchronousPhotoDelete('<?php echo htmlspecialchars($imageItem); ?>', <?php echo $index; ?>)">&times;</button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="file-upload-box">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span class="primary-upload-text" id="interactiveUploadLabel">Drag & drop your items here or <span style="color: #00a896; text-decoration: underline;">browse files</span></span>
                <span class="secondary-upload-subtext">Supports PNG, JPG, or JPEG formats up to 5MB (Max 6 files total)</span>
                <input type="file" name="images[]" id="fileFieldInput" multiple accept="image/*" onchange="refreshUploadFieldIndicatorText()">
            </div>
            <p style="font-size: 11px; color: #64748b; margin-top: 8px; text-align: center;">New selections will be appended to your active product collection above.</p>
        </div>

        <button type="submit">
            <i class="fa-solid fa-square-check"></i> Update Product Ad
        </button>

    </form>
</div>

<script>
// Dynamic label text updater when local files are chosen
function refreshUploadFieldIndicatorText() {
    const fileSelector = document.getElementById('fileFieldInput');
    const dynamicLabel = document.getElementById('interactiveUploadLabel');
    if (fileSelector.files.length > 0) {
        dynamicLabel.innerHTML = `🔥 Selected: <span style="color: #00a896; font-weight: 700;">${fileSelector.files.length} new photos ready to attach</span>`;
    }
}

// Asynchronous handler script eliminating target asset images from view/DB seamlessly
function triggerAsynchronousPhotoDelete(imageFileName, wrapperCardIndex) {
    if (!confirm("Are you sure you want to permanently remove this photo from your listing?")) return;
    
    // Request calls back to this file directly matching target query variables flags
    const currentProductId = "<?php echo $product_id; ?>";
    const requestApiUrl = `edit-product.php?id=${currentProductId}&action=ajax_delete&image_name=${encodeURIComponent(imageFileName)}`;
    
    fetch(requestApiUrl, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(payload => {
        if (payload.status === 'success') {
            const targetContainerCard = document.getElementById(`photo-card-${wrapperCardIndex}`);
            if (targetContainerCard) {
                targetContainerCard.style.transform = 'scale(0)';
                setTimeout(() => { targetContainerCard.remove(); }, 200);
            }
        } else {
            alert(payload.message || "Failed to successfully detach selected photo item.");
        }
    })
    .catch(error => {
        console.error("Error running async image deletion processing lifecycle:", error);
        alert("An error occurred during transaction communication with processing routines.");
    });
}
</script>

</body>
</html>