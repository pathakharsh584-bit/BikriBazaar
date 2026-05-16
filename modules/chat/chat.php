<?php
session_start();

require_once __DIR__ . '/../../shared/db.php'; 
require_once __DIR__ . '/../../shared/config.php'; 

// 1. Security Check using your BASE_URL logic
if(!isset($_SESSION['user_id'])){
    header("Location: " . BASE_URL . "login.php");
    exit();
}

// 2. Dynamic Routing Logic (Parses URLs like chat.php/5/2)
// $_SERVER['PATH_INFO'] captures anything after the .php extension
$path_info = isset($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : [];

if (count($path_info) >= 2) {
    $product_id = (int)$path_info[0]; // First dynamic segment
    $receiver_id = (int)$path_info[1]; // Second dynamic segment
} elseif (isset($_GET['product_id']) && isset($_GET['receiver_id'])) {
    // Fallback just in case standard query parameters are used
    $product_id = (int)$_GET['product_id'];
    $receiver_id = (int)$_GET['receiver_id'];
} else {
    die("Invalid Chat Request. Missing IDs.");
}

$current_user_id = $_SESSION['user_id'];

// 3. Load the UI File (The View)
require_once __DIR__ . '/chat_view.php';
?>