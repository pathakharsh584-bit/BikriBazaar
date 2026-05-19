<?php
session_start();
require_once __DIR__ . '/../../shared/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../public/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $user_id = intval($_SESSION['user_id']);

    
    $sql = "UPDATE products SET status = 'sold' WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ii', $product_id, $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}


header("Location: ../../public/my-ads.php");
exit();
?>