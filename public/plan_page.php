<?php

session_start();

require_once __DIR__ . '/../shared/config.php';
require_once __DIR__ . '/../shared/db.php';

$product_id = intval($_GET['product_id'] ?? 0);

if($product_id <= 0){

    die("Invalid Product");
}

$stmt = $conn->prepare("

    SELECT *
    FROM products
    WHERE id = ?

");

$stmt->bind_param("i", $product_id);

$stmt->execute();

$productResult = $stmt->get_result();

if($productResult->num_rows === 0){

    die("Product not found");
}

$product = $productResult->fetch_assoc();

$imageStmt = $conn->prepare("

    SELECT image_path
    FROM product_images
    WHERE product_id = ?
    LIMIT 1

");

$imageStmt->bind_param("i", $product_id);

$imageStmt->execute();

$imageResult = $imageStmt->get_result();

$productImage = $imageResult->fetch_assoc();

$plans = require_once __DIR__ . '/../shared/plans.php';

$_SESSION['boost_product_id'] = $product_id;

require_once __DIR__ . '/../modules/payments/plans_page_view.php';