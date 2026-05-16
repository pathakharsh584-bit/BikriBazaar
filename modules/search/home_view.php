<?php

session_start();

require_once __DIR__ . '/../../shared/db.php';

$search = "";
$category = "";

$sql = "SELECT * FROM products WHERE 1";

// FIXED: Sanitized input values to ensure your database is protected from SQL Injection
if(isset($_GET['search']) && $_GET['search'] != ""){
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
    $sql .= " AND title LIKE '%$search%'";
}

if(isset($_GET['category']) && $_GET['category'] != ""){
    $category = mysqli_real_escape_string($conn, trim($_GET['category']));
    $sql .= " AND category='$category'";
}

$sql .= " ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

// STEP 2: Fetch the live total of unread messages targeting this specific session user
$unread_count = 0;
if (isset($_SESSION['user_id'])) {
    $current_uid = intval($_SESSION['user_id']);
    $unread_sql = "SELECT COUNT(*) as total FROM messages WHERE receiver_id = $current_uid AND is_seen = 0";
    $unread_res = mysqli_query($conn, $unread_sql);
    if ($unread_res) {
        $unread_data = mysqli_fetch_assoc($unread_res);
        $unread_count = $unread_data['total'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BikriBazaar</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="navbar">

    <div class="logo">
        BikriBazaar
    </div>

    <div class="nav-links">
    <a href="index.php">Home</a>

    <?php if(isset($_SESSION['user_id'])) { ?>
        
        <a href="post-ad.php" class="btn-sell">+ SELL</a>

        <div class="profile-dropdown">
            
            <div class="nav-avatar">
                <?php echo isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 1)) : 'U'; ?>
            </div>
            
            <div class="dropdown-content">
                <div class="dropdown-user-meta">
                    <strong>Hi, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User'; ?></strong>
                </div>
                <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 5px 0;">
                
                <a href="my-ads.php">📋 My Ads</a>
                <a href="favorites.php">❤️ Favorites</a>
                <a href="inbox.php">
                    💬 Messages 
                    <?php if (isset($unread_count) && $unread_count > 0): ?>
                        <span class="dropdown-badge"><?php echo $unread_count; ?></span>
                    <?php endif; ?>
                </a>
                
                <hr style="border: 0; border-top: 1px solid #e5e7eb;">
                <a href="logout.php" style="color: #ff4d4f !important;">🚪 Logout</a>
            </div>

        </div>

    <?php } else { ?>

        <a href="login.php">Login</a>
        <a href="register.php" class="btn-register">Register</a>

    <?php } ?>
</div>

</div>

<div class="container">

    <div class="hero">
        <h1>Buy & Sell Anything Easily</h1>
        <p>Explore premium deals and connect with buyers & sellers instantly.</p>
    </div>

    <div class="search-box">

        <form class="search-form" method="GET">

            <input
                type="text"
                name="search"
                placeholder="Search products, gadgets, cars..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <select name="category">
                <option value="">All Categories</option>
                <option value="Mobiles" <?php echo $category == 'Mobiles' ? 'selected' : ''; ?>>Mobiles</option>
                <option value="Cars" <?php echo $category == 'Cars' ? 'selected' : ''; ?>>Cars</option>
                <option value="Bikes" <?php echo $category == 'Bikes' ? 'selected' : ''; ?>>Bikes</option>
                <option value="Electronics" <?php echo $category == 'Electronics' ? 'selected' : ''; ?>>Electronics</option>
                <option value="Furniture" <?php echo $category == 'Furniture' ? 'selected' : ''; ?>>Furniture</option>
            </select>

            <button type="submit">
                Search
            </button>

        </form>

    </div>

    <h2 class="section-title">
        Latest Products
    </h2>

    <?php if(mysqli_num_rows($result) > 0) { ?>

        <div class="products-grid">

            <?php while($product = mysqli_fetch_assoc($result)) { ?>

                <a class="product-card" href="product.php?id=<?php echo $product['id']; ?>">

                    <img src="/olx/public/uploads/products/<?php echo $product['image']; ?>" alt="Product image">

                    <div class="product-content">

                        <div class="price">
                            ₹ <?php echo number_format($product['price']); ?>
                        </div>

                        <div class="title">
                            <?php echo htmlspecialchars($product['title']); ?>
                        </div>

                        <div class="location">
                            📍 <?php echo htmlspecialchars($product['location']); ?>
                        </div>

                    </div>

                </a>

            <?php } ?>

        </div>

    <?php } else { ?>

        <div class="empty-state">
            <h3>No Products Found</h3>
        </div>

    <?php } ?>

</div>

</body>
</html>