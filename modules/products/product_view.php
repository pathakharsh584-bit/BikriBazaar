<?php
session_start();
require_once __DIR__ . '/../../shared/db.php';

if(!isset($_GET['id'])){
    die("Product Not Found");
}

$product_id = intval($_GET['id']);

// Fetch unread count for navbar (shared component expects $unread_count)
$unread_count = 0;
if(isset($_SESSION['user_id'])){
    $current_uid = intval($_SESSION['user_id']);
    $unread_sql = "SELECT COUNT(*) as total FROM messages WHERE receiver_id = $current_uid AND is_seen = 0";
    $unread_res = mysqli_query($conn, $unread_sql);
    if($unread_res){
        $unread_data = mysqli_fetch_assoc($unread_res);
        $unread_count = $unread_data['total'];
    }
}

// 1. Fetch Product and Seller Details
$sql = "SELECT p.*, u.name as seller_name, u.created_at as seller_join_date 
        FROM products p 
        LEFT JOIN users u ON p.user_id = u.id 
        WHERE p.id = '$product_id'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){
    die("Product Not Found");
}

$product = mysqli_fetch_assoc($result);

// Fetch All Images for this Product
$img_sql = "SELECT image_path FROM product_images WHERE product_id = $product_id ORDER BY id ASC";
$img_result = mysqli_query($conn, $img_sql);

$images = [];
if ($img_result && mysqli_num_rows($img_result) > 0) {
    while($row = mysqli_fetch_assoc($img_result)) {
        $images[] = $row['image_path'];
    }
} else {
    $images[] = 'default-placeholder.png'; 
}

// 3. Check if favorited
$is_favorited = false;
if(isset($_SESSION['user_id'])){
    $user_id = intval($_SESSION['user_id']);
    $fav_check = "SELECT * FROM favorites WHERE user_id=$user_id AND product_id=$product_id";
    $fav_result = mysqli_query($conn, $fav_check);
    if(mysqli_num_rows($fav_result) > 0){
        $is_favorited = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?> - BikriBazaar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a3fc4;
            --primary-dark: #1530a0;
            --teal: #0ea5a0;
            --teal-dark: #0b8a86;
            --grad: linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
            --surface: #f4f7ff;
            --card-bg: #ffffff;
            --text: #1a1a2e;
            --muted: #6b7280;
            --border: #dde4f5;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--surface);
            color: var(--text);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        a { text-decoration: none; color: inherit; }

        /* ===== NAVBAR CSS REMOVED – styles come from shared/components/navbar.php ===== */

        /* PAGE LAYOUT */
        .product-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 2rem;
        }

        /* LEFT COLUMN */
        .gallery-card {
            background: #fff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            border: 2px solid #babcc1;
            margin-bottom: 1.5rem;
        }
        .main-image-wrap {
            background: #fafcff;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 420px;
            padding: 1rem;
        }
        .main-image-wrap img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .thumbnails-wrap {
            display: flex;
            gap: 0.75rem;
            padding: 1rem;
            border-top: 1px solid var(--border);
            overflow-x: auto;
            background: #fff;
        }
        .thumb-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 12px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s;
        }
        .thumb-img.active {
            border-color: var(--teal);
            box-shadow: 0 0 0 2px rgba(14,165,160,0.2);
        }

        .details-card {
            background: #fff;
            border-radius: 24px;
            padding: 1.5rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            border: 1px solid var(--border);
        }
        .details-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--teal);
            display: inline-block;
        }
        .description {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #4b5563;
            margin: 1rem 0;
        }
        .metadata {
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
            font-size: 0.85rem;
            color: var(--muted);
        }
        .metadata strong {
            color: var(--text);
        }

        /* RIGHT COLUMN */
        .sidebar-card {
            background: #fff;
            border-radius: 24px;
            padding: 1.5rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.05);
            border: 1px solid var(--border);
            margin-bottom: 1.5rem;
        }
        .price {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
        }
        .product-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0.5rem 0;
            color: var(--text);
        }
        .location-date {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--muted);
            margin: 0.75rem 0;
        }
        .fav-btn {
            width: 100%;
            padding: 0.75rem;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            background: var(--grad);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .fav-btn.btn-fav-remove {
            background: #fee2e2;
            color: #b91c1c;
        }
        .fav-btn.btn-fav-add {
            background: var(--grad);
            color: #fff;
        }
        .fav-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .seller-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--grad);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .seller-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.2rem;
        }
        .seller-name {
            font-weight: 700;
            font-size: 1rem;
        }
        .seller-date {
            font-size: 0.75rem;
            color: var(--muted);
        }
        .action-btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 0.75rem;
            border-radius: 40px;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            margin-top: 0.5rem;
        }
        .btn-chat {
            background: var(--grad);
            color: #fff;
        }
        .btn-chat:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        .btn-edit {
            background: #fff;
            color: var(--primary);
            border: 1.5px solid var(--primary);
        }
        .btn-edit:hover {
            background: #f4f7ff;
        }
        .btn-sold {
            background: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
        }

        /* RESPONSIVE */
        @media (max-width: 850px) {
            .product-container {
                grid-template-columns: 1fr;
            }
            .main-image-wrap {
                height: 300px;
            }
        }
    </style>
</head>
<body>

<!-- SHARED NAVBAR (provides its own CSS and markup) -->
<?php include __DIR__ . '/../../shared/components/navbar.php'; ?>

<div class="product-container">
    <!-- LEFT COLUMN -->
    <div class="left-col">
        <div class="gallery-card">
            <div class="main-image-wrap">
                <img id="mainImage" src="uploads/products/<?php echo htmlspecialchars($images[0]); ?>" alt="Product Image">
            </div>
            <?php if(count($images) > 1): ?>
                <div class="thumbnails-wrap">
                    <?php foreach($images as $index => $img): ?>
                        <img src="uploads/products/<?php echo htmlspecialchars($img); ?>" 
                             class="thumb-img <?php echo ($index == 0) ? 'active' : ''; ?>" 
                             onclick="changeMainImage(this, 'uploads/products/<?php echo htmlspecialchars($img); ?>')"
                             alt="Thumbnail">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="details-card">
            <h3>Description</h3>
            <div class="description">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </div>
            <div class="metadata">
                <div><i class="fa-solid fa-tag"></i> Category: <strong><?php echo htmlspecialchars($product['category']); ?></strong></div>
                <div><i class="fa-solid fa-barcode"></i> Ad ID: <strong><?php echo $product['id']; ?></strong></div>
                <?php if(isset($product['condition'])): ?>
                    <div><i class="fa-solid fa-clipboard-list"></i> Condition: <strong><?php echo ucfirst(htmlspecialchars($product['condition'])); ?></strong></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="right-col">
        <div class="sidebar-card">
            <div class="price">₹ <?php echo number_format($product['price']); ?></div>
            <div class="product-title"><?php echo htmlspecialchars($product['title']); ?></div>
            <div class="location-date">
                <span><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($product['location']); ?></span>
                <span><i class="fa-regular fa-calendar"></i> <?php echo date("d M Y", strtotime($product['created_at'])); ?></span>
            </div>
            <button id="favBtn" class="fav-btn <?php echo $is_favorited ? 'btn-fav-remove' : 'btn-fav-add'; ?>" onclick="toggleFavorite(<?php echo $product['id']; ?>)">
                <i class="<?php echo $is_favorited ? 'fa-solid fa-heart' : 'fa-regular fa-heart'; ?>"></i>
                <?php echo $is_favorited ? ' Remove from Favorites' : ' Add to Favorites'; ?>
            </button>
        </div>

        <div class="sidebar-card">
            <div class="seller-info">
                <div class="seller-avatar">
                    <?php echo substr(htmlspecialchars($product['seller_name'] ?? 'U'), 0, 1); ?>
                </div>
                <div>
                    <div class="seller-name"><?php echo htmlspecialchars($product['seller_name'] ?? 'Unknown Seller'); ?></div>
                    <div class="seller-date">Member since <?php echo date("M Y", strtotime($product['seller_join_date'] ?? $product['created_at'])); ?></div>
                </div>
            </div>

            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="login.php" class="action-btn btn-chat"><i class="fa-solid fa-lock"></i> Login to Chat</a>
            <?php elseif ($_SESSION['user_id'] == $product['user_id']): ?>
                <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="action-btn btn-edit"><i class="fa-solid fa-pen"></i> Edit Your Ad</a>
            <?php elseif (isset($product['status']) && $product['status'] === 'sold'): ?>
                <button class="action-btn btn-sold" disabled><i class="fa-solid fa-ban"></i> Item Sold</button>
            <?php else: ?>
                <a href="chat.php?product_id=<?php echo $product['id']; ?>&receiver_id=<?php echo $product['user_id']; ?>" class="action-btn btn-chat">
                    <i class="fa-solid fa-comment"></i> Chat with Seller
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function changeMainImage(thumbElement, newSrc) {
        document.getElementById('mainImage').src = newSrc;
        document.querySelectorAll('.thumb-img').forEach(thumb => thumb.classList.remove('active'));
        thumbElement.classList.add('active');
    }

    function toggleFavorite(productId) {
        const btn = document.getElementById('favBtn');
        const originalHTML = btn.innerHTML;
        const originalClass = btn.className;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
        btn.disabled = true;

        fetch(`../modules/products/favorite_actions.php?product_id=${productId}`, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            if (data.status === 'unauthorized') {
                window.location.href = data.redirect;
                return;
            }
            if (data.status === 'success') {
                if (data.action === 'added') {
                    btn.innerHTML = '<i class="fa-solid fa-heart"></i> Remove from Favorites';
                    btn.className = 'fav-btn btn-fav-remove';
                } else {
                    btn.innerHTML = '<i class="fa-regular fa-heart"></i> Add to Favorites';
                    btn.className = 'fav-btn btn-fav-add';
                }
            } else {
                btn.innerHTML = originalHTML;
                btn.className = originalClass;
                alert("Something went wrong!");
            }
        })
        .catch(err => {
            console.error(err);
            btn.disabled = false;
            btn.innerHTML = originalHTML;
            btn.className = originalClass;
        });
    }
</script>

</body>
</html>