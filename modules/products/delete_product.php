<?php

session_start();

require_once __DIR__ . '/../../shared/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if(isset($_GET['id'])){

    $product_id = intval($_GET['id']);
    $user_id = intval($_SESSION['user_id']);

    // STEP 1: fetch all associated image paths
    $img_sql = "SELECT pi.image_path 
                FROM product_images pi 
                JOIN products p ON pi.product_id = p.id 
                WHERE p.id = ? AND p.user_id = ?";
                
    $stmt = mysqli_prepare($conn, $img_sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ii', $product_id, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // STEP 2: Loop through and delete the physical files from the server folder
        while ($row = mysqli_fetch_assoc($result)) {
            // Reconstruct the exact path used during upload
            $file_path = dirname(__DIR__, 2) . '/public/uploads/products/' . $row['image_path'];
            
            // Check if file exists and delete it
            if (file_exists($file_path) && is_file($file_path)) {
                unlink($file_path); 
            }
        }
        mysqli_stmt_close($stmt);

        // STEP 3: Delete the product from the database
        $del_sql = "DELETE FROM products WHERE id = ? AND user_id = ?";
        $del_stmt = mysqli_prepare($conn, $del_sql);
        
        if ($del_stmt) {
            mysqli_stmt_bind_param($del_stmt, 'ii', $product_id, $user_id);
            mysqli_stmt_execute($del_stmt);
            mysqli_stmt_close($del_stmt);
        }
    }
}

header("Location: " . BASE_URL . "my-ads.php");
exit();

?>