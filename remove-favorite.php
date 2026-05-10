<?php

session_start();

include 'includes/db.php';

if(!isset($_SESSION['user_id'])){

    header("Location: login.php?message=Please login first");

    exit();
}

$user_id = $_SESSION['user_id'];

$product_id = $_GET['id'];

$query = "DELETE FROM favorites
          WHERE user_id='$user_id'
          AND product_id='$product_id'";

mysqli_query($conn,$query);

header("Location: favorites.php");

exit();

?>