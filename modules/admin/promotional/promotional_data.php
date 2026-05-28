<?php
require_once __DIR__ . '/../../../shared/db.php';

$promotional_query = mysqli_query(
    $conn,
    "SELECT
        products.*,
        users.name AS seller_name,
        (SELECT image_path FROM product_images WHERE product_images.product_id = products.id LIMIT 1) AS image
     FROM products
     JOIN users ON products.user_id = users.id
     WHERE products.boost_type IS NOT NULL
     AND products.boost_type != ''
     AND products.is_deleted = 0
     ORDER BY products.created_at DESC"
);
?>