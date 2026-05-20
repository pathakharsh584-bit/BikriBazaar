<?php
session_start();
require_once __DIR__ . '/../../shared/db.php';

// Define BASE_URL safely if not specified globally
if (!defined('BASE_URL')) {
    define('BASE_URL', '/BikriBazaar/public/');
}

if(!isset($_GET['id'])){
    die("Product Not Found");
}

$product_id = intval($_GET['id']);

// LEFT JOIN grabs the seller's details, registration timestamps, and user id
$sql = "SELECT p.*, u.id as seller_id, u.name as seller_name, u.created_at as seller_join_date 
        FROM products p 
        LEFT JOIN users u ON p.user_id = u.id 
        WHERE p.id = '$product_id'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){
    die("Product Not Found");
}

$product = mysqli_fetch_assoc($result);

// Split comma-separated string fields into a clean array list
$image_list = !empty($product['image']) ? explode(',', $product['image']) : ['default.jpg'];
$total_images = count($image_list);

// Check if favorited
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
        
        body { 
            /* Signature horizontal blue-to-teal gradient layout theme */
            background: linear-gradient(135deg, #0d4b8f 0%, #00a896 100%); 
            color: #002f34; 
            min-height: 100vh;
        }

        /* Navbar */
        .navbar { background: white; padding: 15px 40px; color: #0d4b8f; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .navbar a { color: #0d4b8f; text-decoration: none; font-size: 16px; font-weight: bold; margin-left: 20px; transition: color 0.2s; }
        .navbar a:hover { color: #00a896; }

        /* Layout Grid */
        .layout-container {
            width: 95%;
            max-width: 1200px;
            margin: 30px auto;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            align-items: start;
        }

        /* --- LEFT COLUMN: Product Content --- */
        .main-content { display: flex; flex-direction: column; gap: 20px; }
        
        /* Interactive Glowing Image Frame Window Container */
        .image-card { 
            background: #000000; 
            border-radius: 12px; 
            overflow: hidden; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 500px; 
            position: relative;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .image-card img { width: 100%; height: 100%; object-fit: contain; z-index: 2; }

        /* Absolute Floating Slider Nav items */
        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 47, 52, 0.75);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.4);
            width: 46px;
            height: 46px;
            border-radius: 50%;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: all 0.2s ease;
        }
        .slider-btn:hover { background: #002f34; scale: 1.1; border-color: white; }
        .prev-btn { left: 16px; }
        .next-btn { right: 16px; }

        /* Custom 1/2 Numeric Photo Index Counter Badge */
        .image-counter-badge {
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.75);
            color: #ffffff;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .details-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .details-card h3 { font-size: 24px; margin-bottom: 15px; border-bottom: 1px solid #e5e7eb; padding-bottom: 10px; color: #0d4b8f; }
        .description { font-size: 16px; line-height: 1.6; color: #334155; }
        .metadata { margin-top: 20px; display: flex; gap: 30px; color: #64748b; font-size: 14px; }

        /* --- RIGHT COLUMN: Sidebar Action Hub --- */
        .sidebar { display: flex; flex-direction: column; gap: 20px; }

        .price-card, .seller-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        
        /* Flex Alignment for Price Label and Visibility of the Share Button Option */
        .price-header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        
        /* Custom requested color treatments */
        .price-card .price { font-size: 38px; font-weight: 800; color: #00a896 !important; } /* Uses branding teal colors */
        
        /* Prominent High-Visibility Share Layout Trigger */
        .btn-share-trigger {
            background: #f0fdf4;
            color: #00a896; 
            border: 1px solid #c6f6d5;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        .btn-share-trigger:hover { background: #00a896; color: white; border-color: #00a896; }

        .price-card .title { font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 20px; }
        .price-card .location-row { display: flex; justify-content: space-between; font-size: 13px; color: #64748b; margin-bottom: 15px;}

        /* Interactive Clickable Profile Card Layout Rules */
        .clickable-seller-link { text-decoration: none; color: inherit; display: block; cursor: pointer; }
        .seller-card { transition: transform 0.2s ease, box-shadow 0.2s ease; border: 1px solid transparent; }
        .seller-card:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.15); border-color: #bfdbfe; }

        .seller-info { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
        .seller-avatar { width: 60px; height: 60px; border-radius: 50%; background: #0d4b8f; color: white; display: flex; justify-content: center; align-items: center; font-size: 24px; font-weight: bold; text-transform: uppercase; }
        .seller-name { font-size: 18px; font-weight: bold; color: #1e293b; }
        .seller-card:hover .seller-name { color: #0d4b8f; text-decoration: underline; }
        .seller-date { font-size: 13px; color: #64748b; margin-top: 2px; }
        
        .seller-view-more-meta {
            font-size: 13px;
            color: #0d4b8f;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
            padding-top: 10px;
            border-top: 1px dashed #e2e8f0;
            margin-bottom: 15px;
        }

        /* General Buttons layout definitions */
        .btn { display: block; width: 100%; text-align: center; padding: 14px; border-radius: 10px; font-size: 16px; font-weight: bold; cursor: pointer; border: none; transition: 0.2s; text-decoration: none; }
        
        .btn-chat { background: linear-gradient(135deg, #0d4b8f 0%, #0a3d75 100%); color: white; box-shadow: 0 4px 12px rgba(13, 75, 143, 0.3); }
        .btn-chat:hover { transform: translateY(-1px); box-shadow: 0 6px 15px rgba(13, 75, 143, 0.4); }
        
        .btn-edit { background: white; color: #0d4b8f; border: 2px solid #0d4b8f; }
        .btn-edit:hover { background: #f8fafc; }

        .btn-fav-add { background: white; color: #64748b; border: 1px solid #cbd5e1; margin-top: 10px; }
        .btn-fav-add:hover { background: #f8fafc; color: #ef4444; }
        .btn-fav-remove { background: #fef2f2; color: #ef4444; border: 1px solid #fee2e2; margin-top: 10px; }

        /* Toast Popup element alert layout when active URL copy events fire */
        .share-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #111827;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: none;
            z-index: 2000;
        }

        @media (max-width: 850px) {
            .layout-container { grid-template-columns: 1fr; }
            .image-card { height: 350px; }
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
        <div class="image-card">
            <img id="displayMainImage" src="<?php echo BASE_URL; ?>uploads/products/<?php echo htmlspecialchars($image_list[0]); ?>" alt="Product Image">
            
            <?php if ($total_images > 1): ?>
                <button class="slider-btn prev-btn" id="sliderPrevArrow">&#10094;</button>
                <button class="slider-btn next-btn" id="sliderNextArrow">&#10095;</button>
                <div class="image-counter-badge" id="imageIndexDisplay">1 / <?php echo $total_images; ?></div>
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
            </div>
        </div>
    </div>

    <div class="sidebar">
        
        <div class="price-card">
            <div class="price-header-row">
                <div class="price">₹ <?php echo number_format($product['price']); ?></div>
                
                <button class="btn-share-trigger" onclick="copyAdShareLink()">
                    🔗 <span>Share Ad</span>
                </button>
            </div>
            <div class="title"><?php echo htmlspecialchars($product['title']); ?></div>
            
            <div class="location-row">
                <span>📍 <?php echo htmlspecialchars($product['location']); ?></span>
                <span>📅 <?php echo date("d M Y", strtotime($product['created_at'])); ?></span>
            </div>

            <button id="favBtn" class="btn <?php echo $is_favorited ? 'btn-fav-remove' : 'btn-fav-add'; ?>" onclick="toggleFavorite(<?php echo $product['id']; ?>)">
                <?php echo $is_favorited ? '🤍 Remove from Favorites' : '❤️ Add to Favorites'; ?>
            </button>
        </div>

        <a href="seller-profile.php?id=<?php echo urlencode($product['seller_id']); ?>" class="clickable-seller-link" title="Click to view seller profile and other active catalog advertisements">
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
                
                <div class="seller-view-more-meta">
                    <span>🛍️ View other listings by this user</span> &#10230;
                </div>
            </div>
        </a>

        <div class="action-buttons-box">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="../auth/login.php" class="btn btn-chat">🔒 Login to Chat</a>
            
            <?php elseif ($_SESSION['user_id'] == $product['user_id']): ?>
                <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn btn-edit">✏️ Edit Your Ad</a>
            
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>chat.php?product_id=<?php echo $product['id']; ?>&receiver_id=<?php echo $product['user_id']; ?>" class="btn btn-chat">
                    💬 Chat with Seller
                </a>
            <?php endif; ?>
        </div>
            
    </div>
</div>

<div class="share-toast" id="toastAlertFrame">📋 Ad Link copied to clipboard!</div>

<script>
// Safely pass down the clean image array data mapping layer
const productImages = <?php echo json_encode($image_list); ?>;
// Dynamically build path directly using your PHP config settings to avoid double public folders
const imageBasePath = "<?php echo BASE_URL; ?>uploads/products/";
let currentImgIndex = 0;

if (productImages.length > 1) {
    const mainImgEl = document.getElementById('displayMainImage');
    const badgeEl = document.getElementById('imageIndexDisplay');
    
    document.getElementById('sliderNextArrow').addEventListener('click', () => {
        currentImgIndex = (currentImgIndex + 1) % productImages.length;
        updateSliderView();
    });

    document.getElementById('sliderPrevArrow').addEventListener('click', () => {
        currentImgIndex = (currentImgIndex - 1 + productImages.length) % productImages.length;
        updateSliderView();
    });

    function updateSliderView() {
        mainImgEl.src = imageBasePath + productImages[currentImgIndex];
        badgeEl.innerHTML = `${currentImgIndex + 1} / ${productImages.length}`;
    }
}

// Action execution string script for the Visible Share Button link copy trigger
function copyAdShareLink() {
    const shareUrl = window.location.href;
    navigator.clipboard.writeText(shareUrl).then(() => {
        const toast = document.getElementById('toastAlertFrame');
        toast.style.display = 'block';
        setTimeout(() => { toast.style.display = 'none'; }, 2500);
    }).catch(err => {
        console.error('Could not copy link: ', err);
    });
}

function toggleFavorite(productId) {
    const btn = document.getElementById('favBtn');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '⏳ Processing...';
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