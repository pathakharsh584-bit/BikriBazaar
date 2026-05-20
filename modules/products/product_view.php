<?php
session_start();
require_once __DIR__ . '/../../shared/db.php';


if(!isset($_GET['id'])){
    die("Product Not Found");
}

$product_id = intval($_GET['id']);

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

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f2f4f5; color: #002f34; }

        /* Navbar */
        .navbar { background: #002f34; padding: 15px 40px; color: white; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; font-size: 16px; font-weight: bold; margin-left: 20px; }

        /* Layout Grid */
        .layout-container {
            width: 95%;
            max-width: 1200px;
            margin: 30px auto;
            display: grid;
            grid-template-columns: 2fr 1fr; /* Left side is twice as big as right sidebar */
            gap: 25px;
            align-items: start;
        }

        /* --- LEFT COLUMN: Product Content --- */
        .main-content { display: flex; flex-direction: column; gap: 20px; }
        
        /* NEW: Interactive Image Gallery Styles */
        .gallery-container {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .main-image-wrap { 
            background: black; 
            border-radius: 8px; 
            overflow: hidden; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 450px; 
            margin-bottom: 10px;
        }
        .main-image-wrap img { 
            width: 100%; 
            height: 100%; 
            object-fit: contain; 
            transition: opacity 0.2s ease-in-out;
        }
        .thumbnails-wrap {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 5px; /* Space for scrollbar if many images */
        }
        .thumb-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            border: 2px solid transparent;
            opacity: 0.6;
            transition: all 0.2s;
        }
        .thumb-img:hover { opacity: 0.9; }
        .thumb-img.active {
            border-color: #0ea5a0;
            opacity: 1;
        }

        .details-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .details-card h3 { font-size: 24px; margin-bottom: 15px; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; }
        .description { font-size: 16px; line-height: 1.6; color: #4b5563; }
        .metadata { margin-top: 20px; display: flex; gap: 30px; color: #6b7280; font-size: 14px; }

        /* --- RIGHT COLUMN: Sidebar Action Hub --- */
        .sidebar { display: flex; flex-direction: column; gap: 20px; }

        .price-card, .seller-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        
        .price-card .price { font-size: 38px; font-weight: 800; margin-bottom: 10px; }
        .price-card .title { font-size: 18px; color: #4b5563; margin-bottom: 20px; }
        .price-card .location-row { display: flex; justify-content: space-between; font-size: 12px; color: #6b7280; margin-bottom: 15px;}

        /* Seller Info */
        .seller-info { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
        .seller-avatar { width: 60px; height: 60px; border-radius: 50%; background: #002f34; color: white; display: flex; justify-content: center; align-items: center; font-size: 24px; font-weight: bold; text-transform: uppercase; }
        .seller-name { font-size: 18px; font-weight: bold; }
        .seller-date { font-size: 13px; color: #6b7280; }

        /* Buttons */
        .btn { display: block; width: 100%; text-align: center; padding: 14px; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; border: none; transition: 0.2s; text-decoration: none; }
        
        .btn-chat { background: #002f34; color: white; border: 2px solid #002f34; }
        .btn-chat:hover { background: white; color: #002f34; }
        
        .btn-edit { background: white; color: #002f34; border: 2px solid #002f34; }
        .btn-edit:hover { background: #f3f4f6; }

        .btn-fav-add { background: white; color: #002f34; border: 2px solid #002f34; margin-top: 10px; }
        .btn-fav-add:hover { background: #f3f4f6; }
        .btn-fav-remove { background: #002f34; color: white; border: 2px solid #002f34; margin-top: 10px; }

        /* Mobile Responsiveness */
        @media (max-width: 850px) {
            .layout-container { grid-template-columns: 1fr; }
            .main-image-wrap { height: 350px; }
        }
    </style>
</head>
<body>

<div class="navbar">
    <div style="font-size: 22px; font-weight: 800; letter-spacing: -0.5px;">BikriBazaar</div>
    <div>
        <a href="index.php">Home</a>
        <a href="favorites.php">Favorites</a>
    </div>
</div>

<div class="layout-container">

    <div class="main-content">
        
        <div class="gallery-container">
            <div class="main-image-wrap">
                <img id="mainImage" src="/olx/public/uploads/products/<?php echo htmlspecialchars($images[0]); ?>" alt="Product Image">
            </div>
            
            <?php if(count($images) > 1): ?>
                <div class="thumbnails-wrap">
                    <?php foreach($images as $index => $img): ?>
                        <img src="/olx/public/uploads/products/<?php echo htmlspecialchars($img); ?>" 
                             class="thumb-img <?php echo ($index == 0) ? 'active' : ''; ?>" 
                             onclick="changeMainImage(this, '/olx/public/uploads/products/<?php echo htmlspecialchars($img); ?>')"
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
                <div>📂 Category: <strong><?php echo htmlspecialchars($product['category']); ?></strong></div>
                <div>🆔 Ad ID: <strong><?php echo $product['id']; ?></strong></div>
                <?php if(isset($product['condition'])): ?>
                    <div>✨ Condition: <strong><?php echo ucfirst(htmlspecialchars($product['condition'])); ?></strong></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="sidebar">
        
        <div class="price-card">
            <div class="price">₹ <?php echo number_format($product['price']); ?></div>
            <div class="title"><?php echo htmlspecialchars($product['title']); ?></div>
            
            <div class="location-row">
                <span>📍 <?php echo htmlspecialchars($product['location']); ?></span>
                <span>📅 <?php echo date("d M Y", strtotime($product['created_at'])); ?></span>
            </div>

            <button id="favBtn" class="btn <?php echo $is_favorited ? 'btn-fav-remove' : 'btn-fav-add'; ?>" onclick="toggleFavorite(<?php echo $product['id']; ?>)">
                <?php echo $is_favorited ? '🤍 Remove from Favorites' : '❤️ Add to Favorites'; ?>
            </button>
        </div>

        <div class="seller-card">
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
                <a href="<?php echo BASE_URL; ?>login.php" class="btn btn-chat">🔒 Login to Chat</a>
            
            <?php elseif ($_SESSION['user_id'] == $product['user_id']): ?>
                <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-edit">✏️ Edit Your Ad</a>
            
            <?php elseif (isset($product['status']) && $product['status'] === 'sold'): ?>
                 <button class="btn" style="background:#f3f4f6; color:#9ca3af; border:2px solid #d1d5db; cursor:not-allowed;" disabled>🚫 Item Sold</button>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>chat.php?product_id=<?php echo $product['id']; ?>&receiver_id=<?php echo $product['user_id']; ?>" class="btn btn-chat">
                    💬 Chat with Seller
                </a>
            <?php endif; ?>
            
        </div>
    </div>

</div>

<script>

function changeMainImage(thumbnailElement, newImageSrc) {
    // 1. Update the main image source
    document.getElementById('mainImage').src = newImageSrc;
    
    // 2. Remove 'active' class from all thumbnails
    const thumbnails = document.querySelectorAll('.thumb-img');
    thumbnails.forEach(thumb => thumb.classList.remove('active'));
    
    // 3. Add 'active' class to the clicked thumbnail
    thumbnailElement.classList.add('active');
}


function toggleFavorite(productId) {
    const btn = document.getElementById('favBtn');
    const originalText = btn.innerHTML;
    const isAdding = btn.classList.contains('btn-fav-add');
    
    btn.innerHTML = '⏳ Processing...';
    btn.disabled = true;

    fetch(`../modules/products/favorite_actions.php?product_id=${productId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
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
                btn.innerHTML = '🤍 Remove from Favorites';
                btn.className = 'btn btn-fav-remove';
            } else if (data.action === 'removed') {
                btn.innerHTML = '❤️ Add to Favorites';
                btn.className = 'btn btn-fav-add';
            }
        } else {
            btn.innerHTML = originalText;
            alert("Something went wrong!");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}
</script>

</body>
</html>