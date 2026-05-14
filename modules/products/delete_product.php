<?php

session_start();

require_once __DIR__ . '/../../shared/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if(isset($_GET['id'])){

    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $getProduct = "SELECT * FROM products
                   WHERE id='$product_id'
                   AND user_id='$user_id'";

    $result = mysqli_query($conn, $getProduct);

    if(mysqli_num_rows($result) > 0){

        $product = mysqli_fetch_assoc($result);

        $imagePath = __DIR__ . '/../../uploads/products/' . $product['image'];

        if(file_exists($imagePath)){
            unlink($imagePath);
        }

        mysqli_query($conn, "DELETE FROM products WHERE id='$product_id'");
    }
}

header("Location: " . BASE_URL . "my-ads.php");
exit();

?>