<?php
require_once __DIR__ . '/../../../shared/db.php';

/* TOTAL USERS */
$total_users_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total_users FROM users"
);
$total_users = mysqli_fetch_assoc($total_users_query)['total_users'] ?? 0;

/* TOTAL ADS (FIXED: Only count active ads) */
$total_ads_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total_ads 
     FROM products 
     WHERE is_deleted = 0"
);
$total_ads = mysqli_fetch_assoc($total_ads_query)['total_ads'] ?? 0;

/* PREMIUM ADS (FIXED: Don't count deleted premium ads) */
$premium_ads_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS premium_ads
     FROM products
     WHERE is_boosted = 1 
     AND is_deleted = 0"
);
$premium_ads = mysqli_fetch_assoc($premium_ads_query)['premium_ads'] ?? 0;

/* TOTAL REVENUE */
$revenue_query = mysqli_query(
    $conn,
    "SELECT SUM(amount) AS total_revenue
     FROM payment
     WHERE payment_status = 'success'"
);
$total_revenue = mysqli_fetch_assoc($revenue_query)['total_revenue'] ?? 0;


/* RECENT ADS */
$recent_ads_query = mysqli_query(
    $conn,
    "SELECT
        products.id,
        products.title,
        products.category,
        products.status,
        products.boost_type,
        users.name AS seller_name,
        (SELECT image_path FROM product_images WHERE product_images.product_id = products.id LIMIT 1) AS image
     FROM products
     JOIN users ON products.user_id = users.id
     WHERE products.is_deleted = 0
     ORDER BY products.created_at DESC 
     LIMIT 5"
);

/* DELETED ADS */

$deleted_ads_query = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS deleted_ads
     FROM products
     WHERE is_deleted = 1"

);

$deleted_ads = mysqli_fetch_assoc($deleted_ads_query)['deleted_ads'] ?? 0;


/* RECENT ACTIVITIES */

$recent_activity_query = mysqli_query(

    $conn,

    "SELECT *
     FROM activity_logs
     ORDER BY created_at DESC
     LIMIT 5"

);
?>