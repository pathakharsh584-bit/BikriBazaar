<?php

require_once __DIR__ . '/../../../shared/db.php';

$reports_query = mysqli_query(
    $conn,
    "SELECT
        reported_ads.reason,
        products.id as product_id,
        products.title,
        products.price,
        products.category,
        (SELECT image_path FROM product_images WHERE product_images.product_id = products.id LIMIT 1) AS image
     FROM reported_ads
     LEFT JOIN products
     ON reported_ads.product_id = products.id
     WHERE products.is_deleted = 0
     ORDER BY reported_ads.created_at DESC"
);
?>