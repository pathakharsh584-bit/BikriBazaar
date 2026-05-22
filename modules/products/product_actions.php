<?php

require_once __DIR__ . '/../../shared/config.php';
use Cloudinary\Api\Upload\UploadApi;

function postProduct($conn)
{
    
    $user_id = intval($_SESSION['user_id']);

    
    $title       = mysqli_real_escape_string($conn, trim($_POST['title']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $location    = mysqli_real_escape_string($conn, trim($_POST['location']));
    $city        = mysqli_real_escape_string($conn, trim($_POST['city']));
    $category    = mysqli_real_escape_string($conn, trim($_POST['category']));
    $condition   = mysqli_real_escape_string($conn, trim($_POST['condition']));
    $price       = floatval($_POST['price']);

    // STEP 1: Insert the core product data first
    $sql = "INSERT INTO products 
            (user_id, title, description, price, city, location, `condition`, category) 
            VALUES 
            ($user_id, '$title', '$description', $price, '$city', '$location', '$condition', '$category')";

    if(mysqli_query($conn, $sql)){
        
        // STEP 2: Grab the ID of the product we literally just created
        $product_id = mysqli_insert_id($conn);

        // STEP 3: Handle Multiple Image Uploads to Cloudinary
        if(isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            
            $totalFiles = count($_FILES['images']['name']);

            // Loop through each file one by one
            for($i = 0; $i < $totalFiles; $i++) {
                $tempName = $_FILES['images']['tmp_name'][$i];
                //$originalName = $_FILES['images']['name'][$i];

                
                if($tempName != "") {
                    try{
                        // Upload directly from the temp file path to Cloudinary
                        $uploadResult = (new UploadApi())->upload($tempName, [
                            'folder' => 'olx_replica/products',
                            'resource_type' => 'image'
                        ]);

                        // Extract the permanent HTTPS URL from Cloudinary's response
                        $imageUrl = $uploadResult['secure_url'];
                        
                        // Insert the full Cloudinary URL into your database instead of a local filename
                        $escapedUrl = mysqli_real_escape_string($conn, $imageUrl);
                        $imgSql = "INSERT INTO product_images (product_id, image_path) VALUES ($product_id, '$escapedUrl')";

                        mysqli_query($conn, $imgSql);
                    }
                    
                    catch (Exception $e) {
                        // Log the error so you can debug without breaking the user experience
                        error_log("Cloudinary Upload Failed for Product ID $product_id: " . $e->getMessage());
                    }

                }
            }
        }
        // Success! Send them to the homepage
        header("Location: " . BASE_URL . "index.php");
        exit();
    }

    else {
        // Core product insertion failed
        return "Failed To Post Product: " . mysqli_error($conn);
    }
}

?>