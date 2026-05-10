<?php

session_start();

include 'includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$user_id = $_SESSION['user_id'];

$query = "DELETE FROM products
          WHERE id='$id'
          AND user_id='$user_id'";

mysqli_query($conn,$query);

header("Location: my-products.php");

exit();

?>