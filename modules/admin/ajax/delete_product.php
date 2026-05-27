<?php
require_once __DIR__ . '/../../../shared/db.php';
require_once __DIR__ . '/../../../shared/activity_log.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    exit('Invalid Request');
}

$product_id = intval($_POST['product_id'] ?? 0);

if($product_id <= 0){
    exit('Invalid Product ID');
}

/* SOFT DELETE */

$product_query = mysqli_query(

    $conn,

    "SELECT title
     FROM products
     WHERE id = $product_id"

);

$product = mysqli_fetch_assoc($product_query);

$product_title = $product['title'] ?? 'Unknown Product';

$delete_query = mysqli_query(

    $conn,

    "UPDATE products

     SET 
        is_deleted = 1,
        deleted_at = NOW()

     WHERE id = $product_id"

);

if($delete_query){

logActivity(

    $conn,

    'delete_product',

    "Product deleted: $product_title"

);

    echo 'success';

}else{

    echo 'failed';

}