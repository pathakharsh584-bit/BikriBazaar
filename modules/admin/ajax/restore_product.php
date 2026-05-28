<?php

require_once __DIR__ . '/../../../shared/db.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    exit('Invalid Request');
}

$product_id = intval($_POST['product_id'] ?? 0);

if($product_id <= 0){
    exit('Invalid Product ID');
}

/* RESTORE PRODUCT */

$restore_query = mysqli_query(

    $conn,

    "UPDATE products

     SET
        is_deleted = 0,
        deleted_at = NULL

     WHERE id = $product_id"

);

if($restore_query){

    echo 'success';

}else{

    echo 'failed';

}