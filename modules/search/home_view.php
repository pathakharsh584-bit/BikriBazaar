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

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--surface); color: var(--text); }
        a { text-decoration: none; color: inherit; }

        /* NAVBAR */
        .navbar {
            background: #ffffff;
            box-shadow: 0 2px 12px rgba(26,63,196,0.10);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            height: 66px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            flex-shrink: 0;
        }
        .logo span { color: var(--teal); }
        .logo-img {
            height: 44px;
            width: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-left: auto;
        }
        .nav-links > a {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text);
            padding: 0.4rem 0.7rem;
            border-radius: 6px;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .nav-links > a:hover { background: var(--surface); }

        .btn-sell {
            background: var(--grad) !important;
            color: #fff !important;
            font-weight: 700;
            border-radius: 8px !important;
            padding: 0.45rem 1.2rem !important;
            transition: opacity 0.2s, transform 0.15s !important;
            display: inline-flex; align-items: center; gap: 0.4rem;
        }
        .btn-sell:hover { opacity: 0.9; transform: translateY(-1px); }

        .btn-register {
            background: var(--grad) !important;
            color: #fff !important;
            font-weight: 700;
            border-radius: 8px !important;
            padding: 0.45rem 1.2rem !important;
            display: inline-flex; align-items: center; gap: 0.4rem;
        }
        .btn-register:hover { opacity: 0.9; }

        /* PROFILE DROPDOWN */
        .profile-dropdown { position: relative; }
        .nav-avatar {
            width: 38px; height: 38px; border-radius: 50%;
            background: var(--grad); color: #fff;
            font-weight: 800; font-size: 1rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; border: 2px solid var(--border);
            transition: border-color 0.2s;
        }
        .nav-avatar:hover { border-color: var(--teal); }
        .dropdown-content {
            display: none; position: absolute; right: 0;
            top: calc(100% + 10px); background: #fff;
            border-radius: 12px; box-shadow: 0 8px 30px rgba(26,63,196,0.13);
            min-width: 210px; border: 1px solid var(--border);
            overflow: hidden; z-index: 200;
        }
        .profile-dropdown:hover .dropdown-content { display: block; }
        .dropdown-user-meta { padding: 0.8rem 1rem 0.5rem; font-size: 0.88rem; color: var(--muted); }
        .dropdown-content hr { border: none; border-top: 1px solid var(--border); margin: 4px 0; }
        .dropdown-content a {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.6rem 1rem; font-size: 0.88rem; color: var(--text);
            transition: background 0.15s;
        }
        .dropdown-content a i { width: 16px; text-align: center; color: var(--primary); }
        .dropdown-content a:hover { background: var(--surface); }
        .dropdown-badge {
            background: #ef4444; color: #fff;
            font-size: 0.68rem; font-weight: 700;
            padding: 0.12rem 0.42rem; border-radius: 20px; margin-left: auto;
        }

        /* HERO */
        .hero-banner {
            background: var(--grad);
            padding: 3.2rem 1.5rem 2.8rem;
            text-align: center; color: #fff;
            position: relative; overflow: hidden;
        }
        .hero-banner::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 75% 50%, rgba(255,255,255,0.10) 0%, transparent 65%);
            pointer-events: none;
        }
        .hero-banner h1 {
            font-size: 2.6rem; font-weight: 800;
            letter-spacing: -0.5px; color: #fff; position: relative;
        }
        .hero-banner p {
            margin-top: 0.5rem; font-size: 1rem;
            color: rgba(255,255,255,0.80); position: relative;
        }

        /* SEARCH */
        .search-box {
            max-width: 780px; margin: 0 auto;
            padding: 0 1rem; position: relative; top: -22px;
        }
        .search-form {
            display: flex; background: #fff;
            border-radius: 12px; overflow: hidden;
            box-shadow: 0 8px 30px rgba(26,63,196,0.18);
            border: 2px solid transparent; transition: border-color 0.2s;
        }
        .search-form:focus-within { border-color: var(--teal); }
        .search-form input {
            flex: 1; border: none; outline: none;
            padding: 0.95rem 1.2rem;
            font-size: 0.97rem; font-family: inherit;
        }
        .search-form select {
            border: none; border-left: 1px solid var(--border); outline: none;
            padding: 0 1rem; font-size: 0.88rem;
            background: #fff; cursor: pointer; min-width: 140px; font-family: inherit;
        }
        .search-form button {
            background: var(--grad); border: none; cursor: pointer;
            padding: 0 1.8rem; font-size: 0.97rem;
            font-weight: 700; color: #fff;
            display: flex; align-items: center; gap: 0.5rem;
            transition: opacity 0.2s;
        }
        .search-form button:hover { opacity: 0.88; }

        /* CONTAINER */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }

        /* CATEGORIES */
        .section-title {
            font-size: 1.2rem; font-weight: 800;
            margin: 1.8rem 0 1rem; color: var(--text);
        }
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 0.75rem; margin: 1rem 0;
        }
        .category-card {
            background: #fff;
            border: 1.5px solid var(--border);
            padding: 1.1rem 0.7rem 0.9rem;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            font-size: 0.82rem;
            cursor: pointer;
            display: flex; flex-direction: column;
            align-items: center; gap: 0.55rem;
            transition: border-color 0.2s, transform 0.18s, box-shadow 0.18s;
            color: var(--text);
        }
        .category-card i {
            font-size: 1.55rem;
            background: var(--grad);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .category-card:hover {
            border-color: var(--teal);
            transform: translateY(-3px);
            box-shadow: 0 4px 16px rgba(14,165,160,0.13);
        }

        /* SAFETY TIP */
        .tips-strip {
            background: #eef4ff;
            border: 1.5px solid #c7d8fb;
            border-radius: 10px;
            padding: 0.8rem 1.2rem;
            margin: 1.2rem 0;
            display: flex; align-items: center;
            gap: 0.8rem; font-size: 0.83rem; color: #dc2626;;
        }
        .tips-strip i { font-size: 1.1rem; flex-shrink: 0; }

        /* PRODUCTS GRID */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.1rem;
        }
        .product-card {
            background: var(--card-bg);
            border: 1.5px solid var(--border);
            border-radius: 14px; overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
            cursor: pointer; position: relative;
            display: flex; flex-direction: column;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 28px rgba(26,63,196,0.13);
            border-color: var(--teal);
        }
        .product-card img {
            width: 100%; height: 175px;
            object-fit: cover; display: block;
            transition: transform 0.35s;
        }
        .product-card:hover img { transform: scale(1.04); }

        .featured-badge {
            position: absolute; top: 9px; left: 9px;
            background: var(--grad); color: #fff;
            font-size: 0.62rem; font-weight: 800;
            padding: 0.18rem 0.55rem; border-radius: 20px;
            text-transform: uppercase; letter-spacing: 0.3px;
        }
        .fav-btn {
            position: absolute; top: 8px; right: 10px;
            background: rgba(255,255,255,0.92); border: none; cursor: pointer;
            width: 30px; height: 30px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem; color: #aaa;
            box-shadow: 0 1px 4px rgba(0,0,0,0.10);
            transition: color 0.2s, transform 0.15s;
        }
        .fav-btn:hover { color: #ef4444; transform: scale(1.15); }

        .product-content {
            padding: 0.85rem 0.9rem 1rem;
            flex: 1; display: flex; flex-direction: column;
        }
        .price {
            font-size: 1.1rem; font-weight: 800;
            color: var(--primary); letter-spacing: -0.3px;
        }
        .title {
            font-size: 0.87rem; color: #444;
            margin-top: 0.25rem; font-weight: 500; line-height: 1.35;
            display: -webkit-box; -webkit-line-clamp: 2;
            -webkit-box-orient: vertical; overflow: hidden;
        }
        .location {
            font-size: 0.76rem; color: var(--muted);
            margin-top: 0.4rem;
            display: flex; align-items: center; gap: 0.35rem;
        }
        .location i { color: var(--primary); font-size: 0.75rem; }
        .product-time {
            font-size: 0.72rem; color: var(--muted);
            margin-top: auto; padding-top: 0.5rem;
            display: flex; align-items: center; gap: 0.35rem;
        }
        .product-time i { font-size: 0.72rem; }

        /* EMPTY STATE */
        .empty-state {
            text-align: center; padding: 4rem 1rem;
            color: var(--muted); grid-column: 1/-1;
        }
        .empty-state i { font-size: 3rem; color: var(--border); display: block; margin-bottom: 0.8rem; }
        .empty-state h3 { font-size: 1.2rem; font-weight: 700; color: var(--text); }

        /* CTA */
        .cta-section {
            background: var(--grad);
            border-radius: 20px; padding: 2.8rem 2rem;
            text-align: center; color: #fff;
            margin: 3rem 0; position: relative; overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute; right: -40px; top: -40px;
            width: 200px; height: 200px; border-radius: 50%;
            background: rgba(255,255,255,0.06); pointer-events: none;
        }
        .cta-section h2 { font-size: 1.6rem; font-weight: 800; }
        .cta-section p { margin-top: 0.4rem; color: rgba(255,255,255,0.80); font-size: 0.95rem; }
        .cta-btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: #fff; color: var(--primary);
            font-weight: 800; font-size: 0.95rem;
            padding: 0.75rem 2rem; border-radius: 10px;
            margin-top: 1.2rem;
            transition: background 0.2s, transform 0.15s;
        }
        .cta-btn:hover { background: #e8eeff; transform: translateY(-2px); }

        /* CITY CHIPS */
        .city-chips { display: flex; flex-wrap: wrap; gap: 0.55rem; margin: 0.7rem 0 2rem; }
        .city-chip {
            background: #fff; border: 1.5px solid var(--border);
            border-radius: 30px; padding: 0.38rem 0.9rem;
            font-size: 0.8rem; font-weight: 600; cursor: pointer;
            display: inline-flex; align-items: center; gap: 0.4rem;
            transition: background 0.18s, border-color 0.18s, color 0.18s;
        }
        .city-chip i { font-size: 0.7rem; color: var(--teal); }
        .city-chip:hover { background: var(--grad); color: #fff; border-color: transparent; }
        .city-chip:hover i { color: #fff; }

        /* FOOTER */
        .footer {
            background: #0d1b5e;
            color: #d1d5db; padding: 3rem 0 1rem; margin-top: 4rem;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 2rem;
        }
        .footer-col h4 {
            font-size: 0.82rem; font-weight: 800;
            letter-spacing: 0.6px; color: #fff;
            margin-bottom: 0.9rem; text-transform: uppercase;
        }
        .footer-col a {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.83rem; color: #9ca3af;
            margin-bottom: 0.45rem; transition: color 0.15s;
        }
        .footer-col a:hover { color: var(--teal); }
        .social-icons { display: flex; gap: 0.7rem; margin-top: 0.3rem; flex-wrap: wrap; }
        .social-icons a {
            background: rgba(255,255,255,0.08);
            border-radius: 8px; width: 36px; height: 36px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem; color: #9ca3af;
            transition: background 0.18s, color 0.18s;
        }
        .social-icons a:hover { background: var(--teal); color: #fff; }
        .copyright {
            text-align: center; padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 2rem; font-size: 0.78rem; color: #6b7280;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .categories-grid { grid-template-columns: repeat(3, 1fr); }
            .products-grid { grid-template-columns: repeat(2, 1fr); }
            .hero-banner h1 { font-size: 1.9rem; }
            .search-form { flex-direction: column; }
            .search-form select { border-left: none; border-top: 1px solid var(--border); }
            .search-form button { padding: 0.75rem; justify-content: center; }
        }
        @media (max-width: 480px) {
            .categories-grid { grid-template-columns: repeat(2, 1fr); }
            .products-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">
        <img src="assets/images/logo.png" alt="BikriBazaar Logo" class="logo-img"
             onerror="this.style.display='none'">
        Bikri<span>Bazaar</span>
    </div>

    <div class="nav-links">
        <!-- Home link with icon -->
        <a href="index.php"><i class="fa-solid fa-house"></i> Home</a>

        <?php if(isset($_SESSION['user_id'])) { ?>
            <a href="post-ad.php" class="btn-sell">
                <i class="fa-solid fa-plus"></i> SELL
            </a>
            <div class="profile-dropdown">
                <div class="nav-avatar">
                    <?php echo isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 1)) : 'U'; ?>
                </div>
                <div class="dropdown-content">
                    <div class="dropdown-user-meta">
                        <strong>Hi, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User'; ?></strong>
                    </div>
                    <hr>
                    <a href="my-ads.php"><i class="fa-solid fa-list"></i> My Ads</a>
                    <a href="favorites.php"><i class="fa-solid fa-heart"></i> Favorites</a>
                    <a href="inbox.php">
                        <i class="fa-solid fa-message"></i> Messages
                        <?php if (isset($unread_count) && $unread_count > 0): ?>
                            <span class="dropdown-badge"><?php echo $unread_count; ?></span>
                        <?php endif; ?>
                    </a>
                    <hr>
                    <a href="logout.php" style="color:#ef4444!important;">
                        <i class="fa-solid fa-right-from-bracket" style="color:#ef4444!important;"></i> Logout
                    </a>
                </div>
            </div>
        <?php } else { ?>
            <!-- Login link with icon -->
            <a href="login.php"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
            <!-- Register link with icon (already has icon in btn-register class, but ensure it's there) -->
            <a href="register.php" class="btn-register">
                <i class="fa-solid fa-user-plus"></i> Register
            </a>
        <?php } ?>
    </div>
</div>

<!-- HERO -->
<div class="hero-banner">
    <h1>Discover Great Deals Near You</h1>
    <p>Find Cars, Mobile Phones, Properties &amp; more near you</p>
</div>

<!-- SEARCH -->
<div class="search-box">
    <form class="search-form" method="GET">
        <input type="text" name="search" placeholder="Search products, gadgets, cars..."
               value="<?php echo htmlspecialchars($search); ?>">
        <select name="category">
            <option value="">All Categories</option>
            <option value="Mobiles"     <?php echo $category=='Mobiles'     ?'selected':''; ?>>Mobiles</option>
            <option value="Cars"        <?php echo $category=='Cars'        ?'selected':''; ?>>Cars</option>
            <option value="Bikes"       <?php echo $category=='Bikes'       ?'selected':''; ?>>Bikes</option>
            <option value="Electronics" <?php echo $category=='Electronics' ?'selected':''; ?>>Electronics</option>
            <option value="Furniture"   <?php echo $category=='Furniture'   ?'selected':''; ?>>Furniture</option>
        </select>
        <button type="submit">
            <i class="fa-solid fa-magnifying-glass"></i> Search
        </button>
    </form>
</div>

<div class="container">

    <!-- CATEGORIES -->
    <h2 class="section-title">All Categories</h2>
    <div class="categories-grid">
        <a href="?category=Cars"        class="category-card"><i class="fa-solid fa-car"></i>Cars</a>
        <a href="?category=Bikes"       class="category-card"><i class="fa-solid fa-motorcycle"></i>Motorcycles</a>
        <a href="?category=Mobiles"     class="category-card"><i class="fa-solid fa-mobile-screen"></i>Mobile Phones</a>
        <a href="?category=Properties"  class="category-card"><i class="fa-solid fa-house"></i>Houses for Sale</a>
        <a href="?category=Rent"        class="category-card"><i class="fa-solid fa-key"></i>Houses for Rent</a>
        <a href="?category=Furniture"   class="category-card"><i class="fa-solid fa-couch"></i>Furniture</a>
        <a href="?category=Electronics" class="category-card"><i class="fa-solid fa-tv"></i>TVs &amp; Electronics</a>
        <a href="?category=Fashion"     class="category-card"><i class="fa-solid fa-shirt"></i>Fashion</a>
        <a href="?category=Books"       class="category-card"><i class="fa-solid fa-book"></i>Books </a>
        <a href="?category=Services"    class="category-card"><i class="fa-solid fa-screwdriver-wrench"></i>Services</a>
        <a href="?category=Others"      class="category-card"><i class="fa-solid fa-box"></i>Others</a>
    </div>

    <!-- SAFETY TIP -->
    <div class="tips-strip">
        <i class="fa-solid fa-shield-halved"></i>
        <span><strong>Stay safe:</strong> Never share OTP or pay in advance. Meet sellers in public places.</span>
    </div>

    <!-- PRODUCTS -->
    <?php if(mysqli_num_rows($result) > 0) { ?>
        <div class="products-grid">
            <?php while($product = mysqli_fetch_assoc($result)) { ?>
                <a class="product-card" href="product.php?id=<?php echo $product['id']; ?>">
                    <div class="featured-badge">FEATURED</div>
                    <img src="uploads/products/<?php echo $product['image']; ?>"
                         alt="Product image" loading="lazy">
                    <button class="fav-btn" onclick="return false;" title="Save">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                    <div class="product-content">
                        <div class="price">&#8377; <?php echo number_format($product['price']); ?></div>
                        <div class="title"><?php echo htmlspecialchars($product['title']); ?></div>
                        <div class="location">
                            <i class="fa-solid fa-location-dot"></i>
                            <?php echo htmlspecialchars($product['location']); ?>
                        </div>
                        <div class="product-time" data-timestamp="<?php echo $product['created_at']; ?>">
                            <i class="fa-regular fa-clock"></i>
                            <span class="time-text">Loading...</span>
                        </div>
                    </div>
                </a>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="products-grid">
            <div class="empty-state">
                <i class="fa-solid fa-magnifying-glass"></i>
                <h3>No Products Found</h3>
                <p style="margin-top:.4rem;font-size:.9rem;">Try different keywords or browse all categories.</p>
            </div>
        </div>
    <?php } ?>

    <!-- CTA -->
    <div class="cta-section">
        <h2>Want to see your stuff here?</h2>
        <p>Make some extra cash by selling things in your community. It's quick and easy!</p>
        <a href="post-ad.php" class="cta-btn">
            <i class="fa-solid fa-plus"></i> Post Free Ad
        </a>
    </div>

    <!-- CITIES -->
    <h2 class="section-title">Browse by City</h2>
    <div class="city-chips">
        <?php foreach(['Mumbai','Delhi','Bengaluru','Hyderabad','Chennai','Kolkata','Pune','Jaipur','Ahmedabad','Surat','Lucknow','Chandigarh','Bhubaneswar','Indore','Ranchi'] as $city): ?>
            <a href="?search=<?php echo urlencode($city); ?>" class="city-chip">
                <i class="fa-solid fa-location-dot"></i>
                <?php echo $city; ?>
            </a>
        <?php endforeach; ?>
    </div>

</div>

<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>Popular Locations</h4>
                <a href="#"><i class="fa-solid fa-location-dot"></i> Mumbai</a>
                <a href="#"><i class="fa-solid fa-location-dot"></i> Delhi</a>
                <a href="#"><i class="fa-solid fa-location-dot"></i> Bengaluru</a>
                <a href="#"><i class="fa-solid fa-location-dot"></i> Chennai</a>
                <a href="#"><i class="fa-solid fa-location-dot"></i> Kolkata</a>
            </div>
            <div class="footer-col">
                <h4>Trending Locations</h4>
                <a href="#"><i class="fa-solid fa-location-dot"></i> Pune</a>
                <a href="#"><i class="fa-solid fa-location-dot"></i> Jaipur</a>
                <a href="#"><i class="fa-solid fa-location-dot"></i> Bhubaneswar</a>
                <a href="#"><i class="fa-solid fa-location-dot"></i> Chandigarh</a>
                <a href="#"><i class="fa-solid fa-location-dot"></i> Nashik</a>
            </div>
            <div class="footer-col">
                <h4>About Us</h4>
                <a href="#"><i class="fa-solid fa-chevron-right"></i> About BikriBazaar</a>
                <a href="#"><i class="fa-solid fa-chevron-right"></i> Tech Blog</a>
                <a href="#"><i class="fa-solid fa-chevron-right"></i> Careers</a>
                <a href="#"><i class="fa-solid fa-chevron-right"></i> Press</a>
            </div>
            <div class="footer-col">
                <h4>Help &amp; Legal</h4>
                <a href="#"><i class="fa-solid fa-chevron-right"></i> Help Center</a>
                <a href="#"><i class="fa-solid fa-chevron-right"></i> Safety Tips</a>
                <a href="#"><i class="fa-solid fa-chevron-right"></i> Privacy Policy</a>
                <a href="#"><i class="fa-solid fa-chevron-right"></i> Terms of Use</a>
            </div>
            <div class="footer-col">
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <a href="#" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" title="Twitter / X"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="copyright">
            All rights reserved &copy; &ndash; BikriBazaar
        </div>
    </div>
</footer>

<script>
    document.querySelectorAll('.product-time[data-timestamp]').forEach(el => {
        const timestamp = el.getAttribute('data-timestamp');
        const date = new Date(timestamp);
        const today = new Date();
        today.setHours(0,0,0,0);
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        let relative = '';
        if (date >= today) {
            relative = 'Today';
        } else if (date >= yesterday) {
            relative = 'Yesterday';
        } else {
            const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            relative = `${date.getDate()} ${months[date.getMonth()]}`;
        }
        el.querySelector('.time-text').innerText = relative;
    });
</script>

</body>
</html>