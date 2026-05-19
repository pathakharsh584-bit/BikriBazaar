<?php

function postProduct($conn)
{
    
    $user_id = intval($_SESSION['user_id']);

    
    $title       = mysqli_real_escape_string($conn, trim($_POST['title']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $location    = mysqli_real_escape_string($conn, trim($_POST['location']));
    $category    = mysqli_real_escape_string($conn, trim($_POST['category']));
    $condition   = mysqli_real_escape_string($conn, trim($_POST['condition']));
    
    
    $price       = floatval($_POST['price']);

    // STEP 1: Insert the core product data first
    $sql = "INSERT INTO products 
            (user_id, title, description, price, location, `condition`, category) 
            VALUES 
            ($user_id, '$title', '$description', $price, '$location', '$condition', '$category')";

    if(mysqli_query($conn, $sql)){
        
        // STEP 2: Grab the ID of the product we literally just created
        $product_id = mysqli_insert_id($conn);

        if(isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            
            $totalFiles = count($_FILES['images']['name']);

            // Loop through each file one by one
            for($i = 0; $i < $totalFiles; $i++) {
                $tempName = $_FILES['images']['tmp_name'][$i];
                $originalName = $_FILES['images']['name'][$i];

                
                if($tempName != "") {
                    
                    $imageName = time() . "_" . uniqid() . "_" . basename($originalName);
                    $uploadPath = dirname(__DIR__, 2) . '/public/uploads/products/' . $imageName;

                    // Move the file
                    if (move_uploaded_file($tempName, $uploadPath)) {
                        
                        $imgSql = "INSERT INTO product_images (product_id, image_path) VALUES ($product_id, '$imageName')";
                        mysqli_query($conn, $imgSql);
                    }
                }
            }
        }

        // Success! Send them to the homepage
        header("Location: " . BASE_URL . "index.php");
        exit();

    } else {
        // Core product insertion failed
        return "Failed To Post Product: " . mysqli_error($conn);
    }
}

?>