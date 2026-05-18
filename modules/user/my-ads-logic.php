<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

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

$sql = "SELECT * FROM products WHERE user_id = ? ORDER BY id DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total_ads = mysqli_num_rows($result);

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

        /* navbar css */
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
            font-weight: bold;
            color: var(--text);
            padding: 0.4rem 0.75rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            position: relative;
            text-decoration: none;
        }
        /* transitionn*/
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
        /* hover on nav */
        .nav-links a:not(.btn-primary):hover {
            background: #f4f7ff;
        }
        /* SELL button*/
        .btn-primary {
            background: linear-gradient(135deg, #1a3fc4, #0ea5a0) !important;
            color: #fff !important;
            font-weight: 700;
            border-radius: 8px;
            padding: 0.42rem 1.1rem !important;
            transition: opacity 0.2s, transform 0.15s;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1a3fc4, #0ea5a0) !important;
            opacity: 0.9;
            transform: translateY(-1px);
        }
        /* Profile icon  dropdown */
        .profile-dropdown {
            position: relative;
        }
        .nav-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a3fc4, #0ea5a0);
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
        .top-bar-left h1 i { color: var(--primary); font-size: 1.1rem; }
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
            transition: opacity 0.2s, transform 0.15s;
        }
        .top-bar-right a:hover { opacity: 0.9; transform: translateY(-1px); }

    
        .stat-bar {
            display: flex;
            gap: 0.8rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
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
        .stat-pill strong { color: var(--text); font-size: 1rem; font-weight: 800; }
        .stat-pill i { color: var(--primary); font-size: 0.85rem; }

     
        .ads-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.1rem;
        }

        .ad-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            transition: transform 0.18s, box-shadow 0.18s, border-color 0.18s;
            display: flex;
            flex-direction: column;
        }
        .ad-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(26,63,196,0.10);
            border-color: #b8c8f0;
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
            transition: transform 0.3s;
        }
        .ad-card:hover .ad-img-wrap img { transform: scale(1.04); }

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
            letter-spacing: 0.3px;
        }

        .ad-body {
            padding: 0.9rem 1rem 0;
            flex: 1;
        }
        .ad-price {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.3px;
        }
        .ad-title {
            font-size: 0.85rem;
            color: #374151;
            margin-top: 0.2rem;
            font-weight: 500;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .ad-meta {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.72rem;
            color: var(--muted);
            margin-top: 0.45rem;
        }
        .ad-meta i { font-size: 0.68rem; color: var(--teal); }

       
        .ad-actions {
            display: flex;
            gap: 0.5rem;
            padding: 0.75rem 1rem 0.9rem;
        }
        .btn-edit, .btn-delete {
            flex: 1;
            text-align: center;
            padding: 0.48rem 0;
            border-radius: 7px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            transition: background 0.15s, transform 0.12s;
            border: none;
            cursor: pointer;
        }
        .btn-edit {
            background: #eef2ff;
            color: var(--primary);
            border: 1.5px solid #c7d2fe;
        }
        .btn-edit:hover { background: #e0e7ff; transform: translateY(-1px); }
        .btn-delete {
            background: #fff0f0;
            color: #b91c1c;
            border: 1.5px solid #fecaca;
        }
        .btn-delete:hover { background: #fee2e2; transform: translateY(-1px); }

        .empty-state {
            text-align: center;
            background: #fff;
            padding: 2rem 1.5rem;
            border-radius: 16px;
            border: 1px dashed cyan;
            box-shadow: 0 2px 12px rgba(26, 63, 196, 0.05);
            width: 30em;
            position: relative;
            left: 18em;
            bottom: 22px;
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
        .empty-state h3 { font-size: 1.1rem; font-weight: 800; color: var(--text); }
        .empty-state p { font-size: 0.85rem; color: var(--muted); margin-top: 0.35rem; }
        .empty-state a {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-top: 1.2rem;
            background: var(--grad);
            color: #fff;
            padding: 0.6rem 1.4rem;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.88rem;
            transition: opacity 0.2s, transform 0.15s;
        }
        .empty-state a:hover { opacity: 0.9; transform: translateY(-1px); }

        
        @media (max-width: 640px) {
            .navbar { padding: 0 1rem; }
            .ads-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 420px) {
            .ads-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>


<div class="navbar">
    <div class="logo">
        <img src="assets/images/logo.png" alt="BikriBazaar" class="logo-img"
             onerror="this.style.display='none'">
        Bikri<span>Bazaar</span>
    </div>
    <div class="nav-links">
        <a href="index.php"><i class="fa-solid fa-house"></i> <span style="font-weight: bold;">Home</span></a>
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


<div class="page">
    <!-- check changes-->
    <div class="top-bar">
        <div class="top-bar-left">
            <h1><i class="fa-solid fa-list-check"></i> My Ads</h1>
            <p>Manage and track all your active listings</p>
        </div>
        <div class="top-bar-right">
            <a href="post-ad.php">
                <i class="fa-solid fa-plus"></i> Post New Ad
            </a>
        </div>
    </div>

    <div class="stat-bar">
        <div class="stat-pill">
            <i class="fa-solid fa-layer-group"></i>
            <span>Total Ads: <strong><?php echo $total_ads; ?></strong></span>
        </div>
        <div class="stat-pill">
            <i class="fa-solid fa-circle-check"></i>
            <span>Status: <strong style="color:#059669;">Active</strong></span>
        </div>
    </div>

    <?php if($total_ads > 0): ?>
        <div class="ads-grid">
            <?php while($product = mysqli_fetch_assoc($result)): ?>
                <div class="ad-card">
                    <div class="ad-img-wrap">
                        <img src="uploads/products/<?php echo htmlspecialchars($product['image']); ?>"
                             alt="<?php echo htmlspecialchars($product['title']); ?>"
                             loading="lazy">
                        <?php if(!empty($product['category'])): ?>
                            <span class="ad-category"><?php echo htmlspecialchars($product['category']); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="ad-body">
                        <div class="ad-price">&#8377; <?php echo number_format($product['price']); ?></div>
                        <div class="ad-title"><?php echo htmlspecialchars($product['title']); ?></div>
                        <div class="ad-meta">
                            <i class="fa-solid fa-location-dot"></i>
                            <?php echo htmlspecialchars($product['location'] ?? 'Location not set'); ?>
                        </div>
                    </div>

                    <div class="ad-actions">
                        <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn-edit">
                            <i class="fa-solid fa-pen"></i> Edit
                        </a>
                        <a href="../modules/products/delete_product.php?id=<?php echo $product['id']; ?>"
                           class="btn-delete"
                           onclick="return confirm('Delete this ad? This cannot be undone.');">
                            <i class="fa-solid fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <h3>No ads posted yet</h3>
            <p>You haven't listed anything for sale. Start now — it's free!</p>
            <a href="post-ad.php">
                <i class="fa-solid fa-plus"></i> Post Your First Ad
            </a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>