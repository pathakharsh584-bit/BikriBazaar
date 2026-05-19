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

$product_id = mysqli_real_escape_string($conn, trim($_GET['id']));
$user_id = intval($_SESSION['user_id']);

// Fetch Product Details
$sql = "SELECT * FROM products WHERE id='$product_id' AND user_id='$user_id'";
$result = mysqli_query($conn, $sql);

if(!$result || mysqli_num_rows($result) == 0){
    die("Unauthorized Access or Product Not Found");
}

$product = mysqli_fetch_assoc($result);

// Fetch Existing Images
$img_sql = "SELECT * FROM product_images WHERE product_id = '$product_id'";
$img_result = mysqli_query($conn, $img_sql);
$existing_images = [];
while($row = mysqli_fetch_assoc($img_result)){
    $existing_images[] = $row['image_path'];
}

$message = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){

    
    $title       = mysqli_real_escape_string($conn, trim($_POST['title']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $location    = mysqli_real_escape_string($conn, trim($_POST['location']));
    $category    = mysqli_real_escape_string($conn, trim($_POST['category']));
    $condition   = mysqli_real_escape_string($conn, trim($_POST['condition']));
    $price       = floatval($_POST['price']);

    // 1. Update Core Product Details
    $update = "UPDATE products SET
               title='$title',
               description='$description',
               price='$price',
               location='$location',
               `condition`='$condition',
               category='$category'
               WHERE id='$product_id' AND user_id='$user_id'";

    if(mysqli_query($conn, $update)){
        
        // 2. Handle NEW Additional Image Uploads
        if(isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $totalFiles = count($_FILES['images']['name']);

            for($i = 0; $i < $totalFiles; $i++) {
                $tempName = $_FILES['images']['tmp_name'][$i];
                $originalName = $_FILES['images']['name'][$i];

                if($tempName != "") {
                    $imageName = time() . "_" . uniqid() . "_" . basename($originalName);
                    
                    $uploadPath = dirname(__DIR__, 2) . '/public/uploads/products/' . $imageName;

                    if (move_uploaded_file($tempName, $uploadPath)) {
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
    <title>Edit Product</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial,sans-serif;
        }

        body{
            background:#f5f5f5;
            padding:40px;
        }

        .container{
            max-width:600px;
            margin:auto;
            background:white;
            padding:30px;
            border-radius:10px;
            box-shadow:0px 0px 10px rgba(0,0,0,0.1);
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }

        input,
        textarea,
        select{
            width:100%;
            padding:12px;
            margin-bottom:15px;
            border:1px solid #ccc;
            border-radius:5px;
            font-size: 14px;
        }

        textarea{
            resize:none;
            height:120px;
        }

        /* Group layout for row fields */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-group {
            width: 100%;
        }

        button{
            width:100%;
            padding:12px;
            background:#002f34;
            color:white;
            border:none;
            border-radius:5px;
            font-size:16px;
            cursor:pointer;
            margin-top: 15px;
        }
        
        .error-msg {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
            font-weight: bold;
            border: 1px solid #fecaca;
        }

        /* Styles for Existing Images */
        .existing-images-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .existing-images-box h4 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #4b5563;
        }
        .img-grid {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .img-grid img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #d1d5db;
        }

        /* Simple file upload styling */
        .file-upload-box {
            border: 1px dashed #9ca3af;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            background: #fdfdfd;
            margin-bottom: 15px;
        }
        .file-upload-box label {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
            color: #374151;
        }
        .file-upload-box input[type="file"] {
            border: none;
            padding: 0;
            margin-bottom: 0;
        }
    </style>
</head>
<body>

<div class="container">

    <h2>Edit Product</h2>

    <?php if($message != ""): ?>
        <div class="error-msg"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <input
            type="text"
            name="title"
            value="<?php echo htmlspecialchars($product['title']); ?>"
            placeholder="Product Title"
            required
        >

        <textarea name="description" placeholder="Description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

        <input
            type="number"
            name="price"
            value="<?php echo htmlspecialchars($product['price']); ?>"
            placeholder="Price"
            min="0"
            step="any"
            required
        >

        <div class="form-row">
            <div class="form-group">
                <input
                    type="text"
                    name="location"
                    value="<?php echo htmlspecialchars($product['location']); ?>"
                    placeholder="Location"
                    required
                >
            </div>
            
            <div class="form-group">
                <select name="condition" required>
                    <option value="" disabled>Select Condition</option>
                    <option value="new" <?php if(isset($product['condition']) && $product['condition'] == "new") echo "selected"; ?>>New</option>
                    <option value="used" <?php if(!isset($product['condition']) || $product['condition'] == "used") echo "selected"; ?>>Used</option>
                    <option value="refurbished" <?php if(isset($product['condition']) && $product['condition'] == "refurbished") echo "selected"; ?>>Refurbished</option>
                </select>
            </div>
        </div>

        <select name="category" required>
            <option value="" disabled>Select Category</option>
            <option value="Mobiles" <?php if($product['category']=="Mobiles") echo "selected"; ?>>Mobiles</option>
            <option value="Cars" <?php if($product['category']=="Cars") echo "selected"; ?>>Cars</option>
            <option value="Bikes" <?php if($product['category']=="Bikes") echo "selected"; ?>>Bikes</option>
            <option value="Electronics" <?php if($product['category']=="Electronics") echo "selected"; ?>>Electronics</option>
            <option value="Furniture" <?php if($product['category']=="Furniture") echo "selected"; ?>>Furniture</option>
        </select>

        <div class="existing-images-box">
            <h4>Currently Uploaded Photos</h4>
            <div class="img-grid">
                <?php if(count($existing_images) > 0): ?>
                    <?php foreach($existing_images as $img): ?>
                        <img src="/olx/public/uploads/products/<?php echo htmlspecialchars($img); ?>" alt="Product image">
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="font-size: 12px; color: #6b7280;">No photos currently uploaded.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="file-upload-box">
            <label for="images">Upload Additional Photos (Optional)</label>
            <input type="file" name="images[]" id="images" accept="image/*" multiple>
        </div>

        <button type="submit">
            Update Product
        </button>

    </form>

</div>

</body>
</html>