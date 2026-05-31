<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../../shared/db.php';
require_once __DIR__ . '/../../../shared/activity_log.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    exit('Invalid Request');
}

$product_id = intval($_POST['product_id'] ?? 0);

if($product_id <= 0){
    exit('Invalid Product ID');
}

/* FETCH TITLE FOR LOGGING */
$product_query = mysqli_query($conn, "SELECT title FROM products WHERE id = $product_id");
$product = mysqli_fetch_assoc($product_query);
$product_title = $product['title'] ?? 'Unknown Product';

/* RESTORE PRODUCT */

$restore_query = mysqli_query(

    $conn,

    "UPDATE products

     SET
        is_deleted = 0,
        deleted_at = NULL,
        deleted_by = NULL

     WHERE id = $product_id"

);

if($restore_query){
    //logActivity($conn, 'admin_restore', "Admin restored ad: $product_title");
    logActivity($conn, 'admin_restore', "Admin restored ad: $product_title");
    echo 'success';

}else{

    echo 'failed';

}