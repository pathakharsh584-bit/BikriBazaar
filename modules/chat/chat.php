<?php
session_start();
require_once __DIR__ . '/../../shared/db.php'; 
require_once __DIR__ . '/../../shared/config.php'; 

if(!isset($_SESSION['user_id'])){
    header("Location: " . BASE_URL . "login.php");
    exit();
}

// Route Handling
$path_info = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];
if (count($path_info) >= 2) {
    $product_id = (int)$path_info[0];
    $receiver_id = (int)$path_info[1];
} elseif (isset($_GET['product_id']) && isset($_GET['receiver_id'])) {
    $product_id = (int)$_GET['product_id'];
    $receiver_id = (int)$_GET['receiver_id'];
} else {
    die("Invalid Chat Request.");
}

// Fetching Receiver Name
$receiver_name = "Seller"; 
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $receiver_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $receiver_name = $res->fetch_assoc()['name'];
}

$current_user_id = $_SESSION['user_id'];
require_once __DIR__ . '/chat_view.php';
?>