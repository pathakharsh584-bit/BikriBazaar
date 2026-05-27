<?php

require_once __DIR__ . '/../../../shared/db.php';
require_once __DIR__ . '/../../../shared/activity_log.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    exit('Invalid Request');
}

$product_id = intval($_POST['product_id'] ?? 0);

$title = trim($_POST['title'] ?? '');

$price = floatval($_POST['price'] ?? 0);

$category = trim($_POST['category'] ?? '');

$status = trim($_POST['status'] ?? 'active');

$boost_type = trim($_POST['boost_type'] ?? 'basic');

if($product_id <= 0){
    exit('Invalid Product');
}

$title = mysqli_real_escape_string($conn, $title);

$category = mysqli_real_escape_string($conn, $category);

$status = mysqli_real_escape_string($conn, $status);

$boost_type = mysqli_real_escape_string($conn, $boost_type);

/* UPDATE PRODUCT */

$product_query = mysqli_query(

    $conn,

    "SELECT title
     FROM products
     WHERE id = $product_id"

);

$product = mysqli_fetch_assoc($product_query);

$product_title = $product['title'] ?? 'Unknown Product';

$update_query = mysqli_query(

    $conn,

    "UPDATE products

     SET
        title = '$title',
        price = '$price',
        category = '$category',
        status = '$status',
        boost_type = '$boost_type'

     WHERE id = $product_id"

);

if($update_query){

    echo 'success';

}else{

    echo 'failed';

}