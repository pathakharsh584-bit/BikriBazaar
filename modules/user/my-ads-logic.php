<?php

session_start();

// 1. Included config.php so we can use BASE_URL for redirects and placeholders
require_once __DIR__ . '/../../shared/config.php';
require_once __DIR__ . '/../../shared/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: " . BASE_URL . "login.php");
    exit();
}

global $conn;

$user_id = $_SESSION['user_id'];

$unread_count = 0;
$unread_sql = "SELECT COUNT(*) as total FROM messages WHERE receiver_id = ? AND is_seen = 0";
$unread_stmt = mysqli_prepare($conn, $unread_sql);
mysqli_stmt_bind_param($unread_stmt, 'i', $user_id);
mysqli_stmt_execute($unread_stmt);
$unread_res = mysqli_stmt_get_result($unread_stmt);

if($unread_res){
    $unread_data = mysqli_fetch_assoc($unread_res);
    $unread_count = $unread_data['total'];
}

mysqli_stmt_close($unread_stmt);

// Fetch all products and tally their statuses
$sql = "SELECT products.*, 
        (SELECT image_path FROM product_images WHERE product_id = products.id ORDER BY id ASC LIMIT 1) as image 
        FROM products 
        WHERE user_id = ? 
        ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$products = [];
$active_count = 0;
$sold_count = 0;

while($row = mysqli_fetch_assoc($result)) {

    $products[] = $row;

    $status = isset($row['status']) ? $row['status'] : 'active';

    if($status == 'active'){
        $active_count++;
    } else {
        $sold_count++;
    }
}

$total_ads = count($products);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>My Ads - BikriBazaar</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

:root {
    --primary: #1a3fc4;
    --primary-dark: #1530a0;
    --teal: #0ea5a0;
    --teal-dark: #0b8a86;
    --grad: linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
    --text: #1a1a2e;
    --muted: #6b7280;
    --border: #e2e8f0;
    --surface: #eef2ff;
}

*, *::before, *::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', sans-serif;
    background: var(--surface);
    color: var(--text);
    min-height: 100vh;
}

a {
    text-decoration: none;
    color: inherit;
}

.page {
    max-width: 1100px;
    margin: 2rem auto;
    padding: 0px 1px;
    border: 1px solid #989fbb;
    border-radius: 17px;
}

.top-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
    background: #c5cef1;
    border-radius: 10px;
    padding: 9px;
}

.top-bar-left h1 {
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.top-bar-left h1 i {
    color: var(--primary);
    font-size: 1.1rem;
}

.top-bar-left p {
    font-size: 0.8rem;
    color: var(--muted);
    margin-top: 0.15rem;
}

.top-bar-right a {
    background: var(--grad);
    color: #fff;
    font-size: 0.85rem;
    font-weight: 700;
    padding: 0.5rem 1.1rem;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.stat-bar {
    display: flex;
    gap: 0.8rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    padding: 0 1rem;
}

.stat-pill {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 9px;
    padding: 0.6rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.82rem;
    color: var(--muted);
}

.stat-pill strong {
    color: var(--text);
    font-size: 1rem;
    font-weight: 800;
}

.ads-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1.1rem;
    padding: 0 1rem 1rem;
}

.ad-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    position: relative;
}

.boosted-ad-card {
    border: 2px solid #f59e0b;
    box-shadow: 0 6px 24px rgba(245, 158, 11, 0.18);
}

.ad-card.sold-card {
    opacity: 0.8;
    background: #f8fafc;
}

.ad-img-wrap {
    position: relative;
    overflow: hidden;
    height: 170px;
    background: #f1f5f9;
}

.ad-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.ad-category {
    position: absolute;
    top: 9px;
    left: 9px;
    background: var(--grad);
    color: #fff;
    font-size: 0.62rem;
    font-weight: 700;
    padding: 0.18rem 0.5rem;
    border-radius: 20px;
    text-transform: uppercase;
}

.sold-overlay {
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0.4);
    display: flex;
    align-items: center;
    justify-content: center;
}

.sold-stamp {
    background: #dc2626;
    color: #fff;
    font-weight: 900;
    padding: 0.5rem 1.5rem;
    transform: rotate(-15deg);
    border: 2px solid #fff;
    border-radius: 8px;
}

.ad-body {
    padding: 0.9rem 1rem 0;
    flex: 1;
}

.ad-price {
    font-size: 1.1rem;
    font-weight: 800;
    color: var(--primary);
}

.ad-title {
    font-size: 0.85rem;
    margin-top: 0.2rem;
}

.ad-meta {
    font-size: 0.72rem;
    color: var(--muted);
    margin-top: 0.45rem;
}

.ad-actions {
    display: flex;
    gap: 0.5rem;
    padding: 0.75rem 1rem 0.9rem;
}

.btn-edit,
.btn-delete,
.btn-sold,
.btn-sold-static,
.btn-boost {
    flex: 1;
    text-align: center;
    padding: 0.48rem 0;
    border-radius: 7px;
    font-weight: 600;
    font-size: 0.8rem;
    border: none;
    cursor: pointer;
}

.btn-boost {
    background: linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
    color: #fff;
}

.btn-edit {
    background: #eef2ff;
    color: var(--primary);
}

.btn-delete {
    background: #fff0f0;
    color: #b91c1c;
}

.btn-sold {
    background: #ecfdf5;
    color: #059669;
}

.btn-sold-static {
    background: #f3f4f6;
    color: #6b7280;
}

</style>

</head>

<body>

<?php include __DIR__ . '/../../shared/components/navbar.php'; ?>

<div class="page">

<div class="top-bar">

<div class="top-bar-left">
<h1>
<i class="fa-solid fa-list-check"></i>
My Ads
</h1>

<p>Manage and track all your active listings</p>
</div>

<div class="top-bar-right">
<a href="post-ad.php">
<i class="fa-solid fa-plus"></i>
Post New Ad
</a>
</div>

</div>

<div class="stat-bar">

<div class="stat-pill">
Total Ads:
<strong><?php echo $total_ads; ?></strong>
</div>

<div class="stat-pill">
Active:
<strong><?php echo $active_count; ?></strong>
</div>

<div class="stat-pill">
Sold:
<strong><?php echo $sold_count; ?></strong>
</div>

</div>

<?php if($total_ads > 0): ?>

<div class="ads-grid">

<?php foreach($products as $product): ?>

<?php

$is_sold = (
    isset($product['status']) &&
    $product['status'] === 'sold'
);

$has_paid_plan = (
    !empty($product['boost_type']) &&
    strtolower($product['boost_type']) != 'free' &&
    !empty($product['boost_expires_at']) &&
    strtotime($product['boost_expires_at']) > time()
);

?>

<div class="ad-card 
<?php echo $is_sold ? 'sold-card' : ''; ?> 
<?php echo $has_paid_plan ? 'boosted-ad-card' : ''; ?>">

<div class="ad-img-wrap">

<?php
$displayImage = !empty($product['image'])
? $product['image']
: BASE_URL . 'assets/images/default-placeholder.png';
?>

<img
src="<?php echo htmlspecialchars($displayImage); ?>"
alt="<?php echo htmlspecialchars($product['title']); ?>"
loading="lazy"
>

<?php if($has_paid_plan): ?>

<span class="ad-category">
<?php echo strtoupper(htmlspecialchars($product['boost_type'])); ?>
</span>

<?php endif; ?>

<?php if($is_sold): ?>

<div class="sold-overlay">
<div class="sold-stamp">SOLD</div>
</div>

<?php endif; ?>

</div>

<div class="ad-body">

<div class="ad-price">
₹ <?php echo number_format($product['price']); ?>
</div>

<div class="ad-title">
<?php echo htmlspecialchars($product['title']); ?>
</div>

<div class="ad-meta">
<i class="fa-solid fa-location-dot"></i>
<?php echo htmlspecialchars($product['location'] ?? 'Location not set'); ?>
</div>

</div>

<div class="ad-actions">

<?php if(!$is_sold): ?>

<?php if($has_paid_plan): ?>

<button
class="btn-boost"
disabled
style="opacity:0.8; cursor:not-allowed;"
>
<i class="fa-solid fa-circle-check"></i>
Boosted
</button>

<?php else: ?>

<a
href="../modules/payments/plans_controller.php?product_id=<?php echo $product['id']; ?>"
class="btn-boost"
>
<i class="fa-solid fa-bolt"></i>
Boost
</a>

<?php endif; ?>

<form
method="POST"
action="../modules/products/mark_sold.php"
style="flex:1;"
>

<input
type="hidden"
name="product_id"
value="<?php echo $product['id']; ?>"
>

<button
type="submit"
class="btn-sold"
onclick="return confirm('Mark this item as sold?');"
>

<i class="fa-solid fa-check"></i>
Sold

</button>

</form>

<a
href="edit-product.php?id=<?php echo $product['id']; ?>"
class="btn-edit"
>

<i class="fa-solid fa-pen"></i>

</a>

<?php else: ?>

<span class="btn-sold-static">
<i class="fa-solid fa-tag"></i>
Item Sold
</span>

<?php endif; ?>

<a
href="../modules/products/delete_product.php?id=<?php echo $product['id']; ?>"
class="btn-delete"
style="flex:0.5;"
onclick="return confirm('Delete this ad?');"
>

<i class="fa-solid fa-trash"></i>

</a>

</div>

</div>

<?php endforeach; ?>

</div>

<?php else: ?>

<div class="empty-state">

<h3>No ads posted yet</h3>

<p>
You haven't listed anything for sale.
Start now — it's free!
</p>

<a href="post-ad.php">

<i class="fa-solid fa-plus"></i>

Post Your First Ad

</a>

</div>

<?php endif; ?>

</div>

</body>
</html>