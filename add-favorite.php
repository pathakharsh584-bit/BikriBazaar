<?php

session_start();

include 'includes/db.php';

if(!isset($_SESSION['user_id'])){

    header("Location: login.php?message=Please login first");

    exit();
}

$user_id = $_SESSION['user_id'];

$product_id = $_GET['id'];

$check_query = "SELECT * FROM favorites
                WHERE user_id='$user_id'
                AND product_id='$product_id'";

$check_result = mysqli_query($conn,$check_query);

if(mysqli_num_rows($check_result) == 0){

    $query = "INSERT INTO favorites(user_id, product_id)
              VALUES('$user_id','$product_id')";

    mysqli_query($conn,$query);
}

header("Location: index.php");

exit();

?>