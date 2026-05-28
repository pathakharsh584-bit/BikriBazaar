<?php

require_once __DIR__ . '/../../../shared/db.php';

$reports_query = mysqli_query(

    $conn,

    "SELECT

        reported_ads.reason,

        products.id as product_id,

        products.title,

        products.price,

        products.category

     FROM reported_ads

     LEFT JOIN products

     ON reported_ads.product_id = products.id

     ORDER BY reported_ads.created_at DESC"

);

?>