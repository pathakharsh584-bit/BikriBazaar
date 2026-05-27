<?php

require_once __DIR__ . '/../../../shared/db.php';

/* PAGINATION */

$limit = 10;

$page_number = max(1, intval($_GET['pageno'] ?? 1));

$offset = ($page_number - 1) * $limit;


/* DELETED PRODUCTS */

$deleted_ads_query = mysqli_query(

    $conn,

    "SELECT
        products.id,
        products.title,
        products.category,
        products.price,
        products.status,
        products.boost_type,
        products.deleted_at,
        users.name AS seller_name

     FROM products

     JOIN users
     ON products.user_id = users.id

     WHERE products.is_deleted = 1

     ORDER BY products.deleted_at DESC

     LIMIT $limit OFFSET $offset"

);


/* TOTAL DELETED ADS */

$total_deleted_result = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS total

     FROM products

     WHERE is_deleted = 1"

);

$total_deleted_ads = mysqli_fetch_assoc($total_deleted_result)['total'];

$total_pages = ceil($total_deleted_ads / $limit);

?>