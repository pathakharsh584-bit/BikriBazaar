<?php
require_once __DIR__ . '/../../../shared/db.php';

if(!isset($_GET['id'])){
    die("Invalid Request");
}

$product_id = intval($_GET['id']);

/* 1. SOFT DELETE THE PRODUCT */
// Instead of erasing the row, we flip the flag to 1. 
// This removes it from the public marketplace but keeps it in the database for the Admin's Deleted Ads list.
mysqli_query(
    $conn,
    "UPDATE products SET is_deleted = 1 WHERE id = $product_id"
);

/* 2. RETAIN THE REPORT EVIDENCE */
// We deliberately DO NOT run a DELETE query on the 'reported_ads' table.
// By leaving it here, your Deleted Ads page can use a LEFT JOIN to see that this ad was banned and display the badge.

?>
<script>
window.location.href = 'admin_page.php?page=reported_ads';
</script>