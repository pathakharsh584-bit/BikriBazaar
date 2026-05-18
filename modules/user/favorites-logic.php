<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../../shared/db.php';

global $conn;

$user_id = $_SESSION['user_id'];

// Fetch unread count for navbar (optional)
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

$sql = "SELECT products.*
        FROM favorites
        INNER JOIN products
        ON favorites.product_id = products.id
        WHERE favorites.user_id = ?
        ORDER BY favorites.id DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total_favorites = mysqli_num_rows($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites - BikriBazaar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary:      #1a3fc4;
            --primary-dark: #1530a0;
            --teal:         #0ea5a0;
            --teal-dark:    #0b8a86;
            --grad:         linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
            --text:         #1a1a2e;
            --muted:        #6b7280;
            --border:       #e2e8f0;
            --surface:      #eef2ff;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--surface);
            color: var(--text);
            min-height: 100vh;
        }

        a { text-decoration: none; color: inherit; }

       
        .navbar {
            background: #fff;
            box-shadow: 0 1px 6px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--primary);
        }
        .logo span { color: var(--teal); }
        .logo-img {
            height: 40px;
            width: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: auto;
        }
        .nav-links a {
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--text);
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            position: relative;
            text-decoration: none;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 0;
            height: 3px;
            background: #4338ca;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        .nav-links a:hover::after {
            width: 100%;
        }
        .nav-links a:not(.btn-primary):hover {
            background: #f4f7ff;
        }
        .btn-primary {
            background: var(--grad) !important;
            color: #fff !important;
            font-weight: 700;
            border-radius: 8px;
            padding: 0.42rem 1.1rem !important;
            transition: opacity 0.2s, transform 0.15s;
        }
        .btn-primary:hover {
            background: var(--grad) !important;
            opacity: 0.9;
            transform: translateY(-1px);
        }
        .profile-dropdown {
            position: relative;
        }
        .nav-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--grad);
            color: #fff;
            font-weight: 800;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid #dde4f5;
            transition: opacity 0.2s;
        }
        .nav-avatar:hover {
            opacity: 0.9;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: -4px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.10);
            min-width: 200px;
            border: 1px solid #dde4f5;
            overflow: visible;
            z-index: 200;
        }
        .profile-dropdown:hover .dropdown-content {
            display: block;
        }
        .profile-dropdown::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 100%;
            height: 12px;
            background: transparent;
        }
        .dropdown-user-meta {
            padding: 0.75rem 1rem 0.45rem;
            font-size: 0.86rem;
            color: #6b7280;
        }
        .dropdown-content hr {
            border: none;
            border-top: 1px solid #dde4f5;
            margin: 3px 0;
        }
        .dropdown-content a {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.8rem 1rem;
            font-size: 0.86rem;
            color: #1a1a2e;
            transition: background 0.2s;
            position: relative;
        }
        .dropdown-content a i {
            width: 15px;
            color: var(--primary);
        }
        .dropdown-content a:hover {
            background: #f4f7ff;
        }
        .dropdown-badge {
            background: #ef4444;
            color: #fff;
            font-size: 0.66rem;
            font-weight: 700;
            padding: 0.1rem 0.4rem;
            border-radius: 20px;
            margin-left: auto;
        }
        .dropdown-content a.logout-link,
        .dropdown-content a.logout-link i {
            color: #ef4444 !important;
        }

        /* PAGE CONTENT*/
        .container {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 11px 3.5rem;
            border: 1px solid #9ca4bf;
            border-radius: 9px;
        }

        .page-header {
            margin-bottom: 1.5rem;
        }
        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text);
        }
        .page-header p {
            color: var(--muted);
            margin-top: 0.25rem;
        }

        /* PRODUCTS GRID */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.5rem;
        }
        .product-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid var(--border);
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(26,63,196,0.1);
            border-color: var(--teal);
        }
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
        }
        .product-content {
            padding: 1rem;
        }
        .price {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.25rem;
        }
        .title {
            font-size: 0.95rem;
            color: var(--text);
            margin-bottom: 0.5rem;
            font-weight: 500;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .location {
            font-size: 0.75rem;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .location i {
            font-size: 0.7rem;
            color: var(--teal);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 1.5rem;
            background: #fff;
            border-radius: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 2px 12px rgba(26,63,196,0.05);
        }
        .empty-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #eef2ff;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .empty-icon i { font-size: 1.5rem; color: var(--primary); }
        .empty-state h3 {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text);
        }
        .empty-state p {
            font-size: 0.85rem;
            color: var(--muted);
            margin-top: 0.5rem;
        }
        .empty-state a {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 1.2rem;
            background: var(--grad);
            color: #fff;
            padding: 0.6rem 1.4rem;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.88rem;
            transition: opacity 0.2s, transform 0.15s;
        }
        .empty-state a:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* RESPONSIVE */
        @media (max-width: 640px) {
            .navbar { padding: 0 1rem; }
            .container { padding: 0 1rem; }
            .products-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- SHARED NAVBAR -->
<div class="navbar">
    <div class="logo">
        <img src="assets/images/logo.png" alt="BikriBazaar" class="logo-img"
             onerror="this.style.display='none'">
        Bikri<span>Bazaar</span>
    </div>
    <div class="nav-links">
        <a href="index.php"><i class="fa-solid fa-house"></i> Home</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="post-ad.php" class="btn-primary">
                <i class="fa-solid fa-plus"></i> SELL
            </a>
            <div class="profile-dropdown">
                <div class="nav-avatar">
                    <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)); ?>
                </div>
                <div class="dropdown-content">
                    <div class="dropdown-user-meta">
                        <strong>Hi, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></strong>
                    </div>
                    <hr>
                    <a href="my-ads.php"><i class="fa-solid fa-list"></i> My Ads</a>
                    <a href="favorites.php"><i class="fa-solid fa-heart"></i> Favorites</a>
                    <a href="inbox.php">
                        <i class="fa-solid fa-message"></i> Messages
                        <?php if($unread_count > 0): ?>
                            <span class="dropdown-badge"><?php echo $unread_count; ?></span>
                        <?php endif; ?>
                    </a>
                    <hr>
                    <a href="logout.php" class="logout-link">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                </div>
            </div>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php" class="btn-primary">Register</a>
        <?php endif; ?>
    </div>
</div>

<!-- PAGE CONTENT -->
<div class="container">
    <div class="page-header">
        <h1><i class="fa-solid fa-heart" style="color:#ef4444;"></i> My Favorites</h1>
        <p>Products you've saved – ready to buy or browse later</p>
    </div>

    <?php if($total_favorites > 0): ?>
        <div class="products-grid">
            <?php while($product = mysqli_fetch_assoc($result)): ?>
                <a class="product-card" href="product.php?id=<?php echo $product['id']; ?>">
                    <img class="product-image" 
                         src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['title']); ?>">
                    <div class="product-content">
                        <div class="price">₹ <?php echo number_format($product['price']); ?></div>
                        <div class="title"><?php echo htmlspecialchars($product['title']); ?></div>
                        <div class="location">
                            <i class="fa-solid fa-location-dot"></i>
                            <?php echo htmlspecialchars($product['location'] ?? 'Location not set'); ?>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fa-solid fa-heart-broken"></i>
            </div>
            <h3>No favorites yet</h3>
            <p>You haven't saved any products. Start hearting items you love!</p>
            <a href="index.php"><i class="fa-solid fa-shop"></i> Browse Products</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>