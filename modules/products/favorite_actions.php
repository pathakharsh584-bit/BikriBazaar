<?php
session_start();
require_once __DIR__ . '/../../shared/db.php';


$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if(!isset($_SESSION['user_id'])){
    if ($is_ajax) {

        echo json_encode(['status' => 'unauthorized', 'redirect' => BASE_URL . "login.php"]);
        exit();
    }
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']); 

if(isset($_GET['product_id'])){

    $product_id = intval($_GET['product_id']); 

    $check = "SELECT * FROM favorites 
              WHERE user_id=$user_id 
              AND product_id=$product_id";

    $result = mysqli_query($conn, $check);

    if(mysqli_num_rows($result) > 0){
        // Product is ALREADY favorited -> Remove it (Toggle Off)
        $delete = "DELETE FROM favorites 
                   WHERE user_id=$user_id 
                   AND product_id=$product_id";
        mysqli_query($conn, $delete);

        if ($is_ajax) {
            echo json_encode(['status' => 'success', 'action' => 'removed']);
            exit();
        }
    } else {
        // Product is NOT favorited -> Add it (Toggle On)
        $insert = "INSERT INTO favorites(user_id, product_id) 
                   VALUES($user_id, $product_id)";
        mysqli_query($conn, $insert);

        if ($is_ajax) {
            echo json_encode(['status' => 'success', 'action' => 'added']);
            exit();
        }
    }
}

header("Location: " . BASE_URL . "favorites.php");
exit();
?>