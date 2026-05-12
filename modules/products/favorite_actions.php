<?php

session_start();

require_once __DIR__ . '/../../shared/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: /BikriBazaar/public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_GET['product_id'])){

    $product_id = $_GET['product_id'];

    $check = "SELECT * FROM favorites
              WHERE user_id='$user_id'
              AND product_id='$product_id'";

    $result = mysqli_query($conn, $check);

    if(mysqli_num_rows($result) == 0){

        $sql = "INSERT INTO favorites(user_id, product_id)
                VALUES('$user_id','$product_id')";

        mysqli_query($conn, $sql);
    }
}

header("Location: /BikriBazaar/public/favorites.php");
exit();

?>