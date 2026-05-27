<?php

session_start();

// 1. Include config FIRST to load Cloudinary environment and the BASE_URL constant
require_once __DIR__ . '/../../shared/config.php';
require_once __DIR__ . '/../../shared/db.php';
require_once __DIR__ . '/../../shared/activity_log.php';

use Cloudinary\Api\Upload\UploadApi;

if(!isset($_SESSION['user_id'])){
    header("Location: " . BASE_URL . "login.php");
    exit();
}

// Helper Function: Extracts Cloudinary Public ID from the secure URL
function extractCloudinaryPublicId($url) {
    $parts = explode('/upload/', $url);
    if(isset($parts[1])) {
        // Remove the version tag (e.g., v17123456/)
        $pathWithoutVersion = preg_replace('/^v\d+\//', '', $parts[1]);
        // Strip the extension (e.g., .jpg)
        $pathInfo = pathinfo($pathWithoutVersion);
        $dir = $pathInfo['dirname'] === '.' ? '' : $pathInfo['dirname'] . '/';
        return $dir . $pathInfo['filename']; // Returns: olx_replica/products/xyz123
    }
    return null;
}

if(isset($_GET['id'])){

    $product_id = intval($_GET['id']);
    $user_id = intval($_SESSION['user_id']);

    $user_query = mysqli_query(

    $conn,

    "SELECT name
     FROM users
     WHERE id = $user_id"

);

$user = mysqli_fetch_assoc($user_query);

$user_name = $user['name'] ?? 'Unknown User';

    // STEP 1: Fetch all associated image paths (which are now Cloudinary URLs)
    $img_sql = "SELECT pi.image_path 
                FROM product_images pi 
                JOIN products p ON pi.product_id = p.id 
                WHERE p.id = ? AND p.user_id = ?";
                
    $stmt = mysqli_prepare($conn, $img_sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ii', $product_id, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // STEP 2: Loop through and delete the remote assets from Cloudinary
        while ($row = mysqli_fetch_assoc($result)) {
            $imageUrl = $row['image_path'];
            
            try {
                $publicId = extractCloudinaryPublicId($imageUrl);
                if($publicId) {
                    (new UploadApi())->destroy($publicId);
                }
            } catch (Exception $e) {
                // We log the error but do NOT stop the script. 
                // We still want the user to be able to delete their ad from the DB!
                error_log("Failed to delete Cloudinary asset during product deletion: " . $e->getMessage());
            }
        }
        mysqli_stmt_close($stmt);

        // STEP 3: Delete the product from the database
        // Note: This assumes your DB has 'ON DELETE CASCADE' set up for product_images.
        // If not, you should run a query to DELETE FROM product_images first!

$product_query = mysqli_query(

    $conn,

    "SELECT title
     FROM products
     WHERE id = $product_id"

);

$product = mysqli_fetch_assoc($product_query);

$product_title = $product['title'] ?? 'Unknown Product';

        $del_sql = "DELETE FROM products WHERE id = ? AND user_id = ?";
        $del_stmt = mysqli_prepare($conn, $del_sql);
        
        if ($del_stmt) {
            mysqli_stmt_bind_param($del_stmt, 'ii', $product_id, $user_id);
            mysqli_stmt_execute($del_stmt);
            logActivity(

    $conn,

    'delete_product',

    "$user_name deleted ad: $product_title"

);
            mysqli_stmt_close($del_stmt);
        }
    }
}

header("Location: " . BASE_URL . "my-ads.php");
exit();

?>