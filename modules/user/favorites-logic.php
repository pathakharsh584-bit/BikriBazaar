<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// 1. Include config FIRST to establish BASE_URL
require_once __DIR__ . '/../../shared/config.php';
require_once __DIR__ . '/../../shared/db.php';

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

$sql = "SELECT products.*, 
        (SELECT image_path FROM product_images WHERE product_id = products.id ORDER BY id ASC LIMIT 1) as image
        FROM favorites
        INNER JOIN products ON favorites.product_id = products.id
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
            display: flex;
            flex-direction: column;
        }

        a { text-decoration: none; color: inherit; }

        /* ===== NAVBAR CSS REMOVED – styles come from shared/components/navbar.php ===== */

        /* PAGE CONTENT */
        .container {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 11px 3.5rem;
            border: 1px solid #fff;
            border-radius: 9px;
            flex: 1; 
            width:68vw;
        }

        .page-header {
            margin-bottom: 1.5rem;
        }
        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #072fbd;
        }
        .page-header p {
            color: #0d1016;;
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

        /* EMPTY STATE – centered card */
        .empty-state {
            text-align: center;
            background: #fff;
            padding: 2.5rem 2rem;
            border-radius: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            max-width: 450px;
            width: 90%;
            margin: 2rem auto;
        }
        .empty-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #eef2ff;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .empty-icon i { font-size: 1.8rem; color: var(--primary); }
        .empty-state h3 {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 0.5rem;
        }
        .empty-state p {
            font-size: 0.85rem;
            color: var(--muted);
            margin-bottom: 1.5rem;
        }
        .empty-state a {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--grad);
            color: #fff;
            padding: 0.6rem 1.4rem;
            border-radius: 40px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .empty-state a:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        /* RESPONSIVE */
        @media (max-width: 640px) {
            .container { padding: 0 1rem; margin: 1rem auto; }
            .products-grid { grid-template-columns: 1fr; }
            .empty-state { width: 95%; padding: 1.5rem; }
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/../../shared/components/navbar.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fa-solid fa-heart" style="color:#ef4444;"></i> My Favorites</h1>
        <p>Products you've saved – ready to buy or browse later</p>
    </div>

    <?php if($total_favorites > 0): ?>
        <div class="products-grid">
            <?php while($product = mysqli_fetch_assoc($result)): ?>
                <a class="product-card" href="product.php?id=<?php echo $product['id']; ?>">
                    <?php 
                        $displayImage = !empty($product['image']) ? $product['image'] : BASE_URL . 'assets/images/default-placeholder.png';
                    ?>
                    <img class="product-image"
                         src="<?php echo htmlspecialchars($displayImage); ?>"
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

<!-- Footer included here -->
<?php include __DIR__ . '/../../shared/components/footer.php'; ?>

</body>
</html>