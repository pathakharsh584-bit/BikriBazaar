<?php
session_start();

// 1. Include config and dependencies
require_once __DIR__ . '/../../shared/config.php';
require_once __DIR__ . '/../../shared/db.php';
require_once __DIR__ . '/../../shared/activity_log.php';

// Ensure user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if(isset($_GET['id'])){

    $product_id = intval($_GET['id']);
    $user_id = intval($_SESSION['user_id']);

    // Fetch User Name for Activity Log
    $user_query = mysqli_query($conn, "SELECT name FROM users WHERE id = $user_id");
    $user = mysqli_fetch_assoc($user_query);
    $user_name = $user['name'] ?? 'Unknown User';

    // Fetch Product Title for Activity Log
    // Security check: We verify the product belongs to this user before fetching/deleting
    $product_query = mysqli_query(
        $conn, 
        "SELECT title FROM products WHERE id = $product_id AND user_id = $user_id"
    );

    if(mysqli_num_rows($product_query) > 0) {
        $product = mysqli_fetch_assoc($product_query);
        $product_title = $product['title'];

        // SOFT DELETE THE PRODUCT
        // Note: We DO NOT delete Cloudinary images here so the Admin can still see them in the recycle bin.
        $del_sql = "UPDATE products SET is_deleted = 1 WHERE id = ? AND user_id = ?";
        $del_stmt = mysqli_prepare($conn, $del_sql);
        
        if ($del_stmt) {
            mysqli_stmt_bind_param($del_stmt, 'ii', $product_id, $user_id);
            mysqli_stmt_execute($del_stmt);
            
            // Log the activity
            logActivity($conn, 'delete_product', "$user_name deleted ad: $product_title");
            
            mysqli_stmt_close($del_stmt);
        }
    }
}

// Redirect back to the user's ads dashboard
header("Location: " . BASE_URL . "my-ads.php");
exit();
?>