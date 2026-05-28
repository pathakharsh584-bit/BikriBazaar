<?php

session_start();

require_once __DIR__ . '/../../../shared/config.php';

require_once __DIR__ . '/../../../shared/db.php';

if(!isset($_GET['id'])){

    die("Invalid Request");

}

$product_id = intval($_GET['id']);

/* DELETE PRODUCT */

mysqli_query(

    $conn,

    "DELETE FROM products
     WHERE id = $product_id"

);

/* DELETE REPORTS */

mysqli_query(

    $conn,

    "DELETE FROM reported_ads
     WHERE product_id = $product_id"

);

/* REDIRECT */

header(

    "Location: " .

    BASE_URL .

    "admin_page.php?page=reported_ads"

);

exit();

?>