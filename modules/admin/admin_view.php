<?php
require_once __DIR__ . '/../../shared/config.php';
require_once __DIR__ . '/../../shared/db.php';
session_start();

if (!isset($_SESSION['is_admin'])) {

    header("Location: " . BASE_URL . "public/admin.php");
    exit();
}
// ======================================
// ADMIN PANEL - DASHBOARD PLANNING
// ======================================


// ======================================
// STEP 1: DATABASE CONNECTION
// Goal:
// Connect admin panel with database
// so admin can fetch users/products data.
// ======================================

require_once __DIR__ . '/../../shared/db.php';
$projectRoot = explode('/modules/', $_SERVER['SCRIPT_NAME'])[0];


// ======================================
// STEP 2: DELETE PRODUCT FEATURE
// Goal:
// Admin can delete a product/ad.
// First delete related favorites,
// then delete product.
// ======================================

if (isset($_GET['delete_product'])) {

    $deleteId = $_GET['delete_product'];

    mysqli_query($conn, "DELETE FROM favorites WHERE product_id = $deleteId");

    mysqli_query($conn, "DELETE FROM products WHERE id = $deleteId");

    header("Location: " . BASE_URL . "admin_view.php");

    exit();
}


// ======================================
// STEP 3: DELETE USER FEATURE
// Goal:
// Admin can delete a user.
// First delete user's favorites/products,
// then delete user.
// ======================================

if (isset($_GET['delete_user'])) {

    $deleteUser = $_GET['delete_user'];

    mysqli_query($conn, "DELETE FROM favorites WHERE user_id = $deleteUser");

    mysqli_query($conn, "DELETE FROM products WHERE user_id = $deleteUser");

    mysqli_query($conn, "DELETE FROM users WHERE id = $deleteUser");

    header("Location: " . BASE_URL . "admin_view.php");

    exit();
}


// ======================================
// STEP 4: DASHBOARD COUNTS
// Goal:
// Show important website statistics.
// ======================================


// Total Users Count
$totalUsers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users")
)['total'];


// Total Products Count
$totalProducts = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM products")
)['total'];


// Total Favorites Count
$totalFavorites = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM favorites")
)['total'];




// ======================================
// STEP 5: RECENT USERS SECTION
// Goal:
// Show recently registered users.
// ======================================

$recentUsers = mysqli_query(

    $conn,

    "SELECT 
        id,
        name,
        email,
        phone,
        city,
        created_at

     FROM users

     ORDER BY created_at DESC

     LIMIT 5"
);




// ======================================
// STEP 6: RECENT PRODUCTS SECTION
// Goal:
// Show recent products with seller info.
// ======================================

$recentProducts = mysqli_query(

    $conn,

    "SELECT 
        products.id,
        products.title,
        products.price,
        products.category,
        products.location,
        products.created_at,

        users.name AS seller_name,

        MIN(product_images.image_path) AS image

     FROM products

     JOIN users 
     ON products.user_id = users.id

     LEFT JOIN product_images
     ON products.id = product_images.product_id

     GROUP BY products.id

     ORDER BY products.created_at DESC

     LIMIT 5"
);
     // ======================================
// STEP 7: FAVORITE PRODUCTS ANALYTICS
// Goal:
// Show which products are most favorited.
// ======================================
$favoriteProducts = mysqli_query(

    $conn,

    "SELECT 
        products.id,
        products.title,

        users.name AS seller_name,

        COUNT(favorites.id) AS total_favorites,

        MIN(product_images.image_path) AS image

     FROM favorites

     JOIN products
     ON favorites.product_id = products.id

     JOIN users
     ON products.user_id = users.id

     LEFT JOIN product_images
     ON products.id = product_images.product_id

     GROUP BY products.id

     ORDER BY total_favorites DESC

     LIMIT 10"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <style>

:root{
    --primary:#1a3fc4;
    --primary-dark:#1530a0;
    --teal:#0ea5a0;
    --surface:#f4f7ff;
    --text:#1a1a2e;
    --muted:#6b7280;
    --border:#dde4f5;
    --grad:linear-gradient(135deg,#1a3fc4 0%,#0ea5a0 100%);
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    background:var(--surface);
    color:var(--text);
}

/* HEADER */

.admin-navbar{
    background:#fff;
    box-shadow:0 2px 14px rgba(26,63,196,0.10);
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 2rem;
    height:78px;
    border-bottom:1px solid var(--border);
    position:sticky;
    top:0;
    z-index:100;
}

.admin-logo{
    display:flex;
    align-items:center;
    gap:14px;
}

.admin-logo img{
    width:50px;
    height:50px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid var(--primary);
    background:#fff;
}

.admin-logo h2{
    font-size:1.8rem;
    font-weight:800;
    color:var(--primary);
}

.admin-logo span{
    color:var(--teal);
}

.admin-nav-right{
    display:flex;
    align-items:center;
    gap:14px;
}

.admin-badge{
    background:#eef2ff;
    color:var(--primary);
    padding:10px 16px;
    border-radius:12px;
    font-size:0.85rem;
    font-weight:700;
}

.logout-btn{
    background:var(--grad);
    color:white;
    padding:11px 18px;
    border-radius:12px;
    text-decoration:none;
    font-weight:700;
    transition:0.25s;
    box-shadow:0 4px 12px rgba(26,63,196,0.15);
}

.logout-btn:hover{
    transform:translateY(-2px);
    opacity:0.92;
}

/* MAIN WRAPPER */

.main-wrapper{
    padding:30px;
}

/* DASHBOARD HEADER */

.dashboard-header{
    background:#fff;
    padding:32px;
    border-radius:24px;
    box-shadow:0 10px 30px rgba(0,0,0,0.06);
    border:1px solid var(--border);
    margin-bottom:30px;
}

.dashboard-header h1{
    font-size:2.3rem;
    color:var(--primary);
    margin-bottom:8px;
    font-weight:800;
}

.dashboard-header p{
    color:var(--muted);
    font-size:0.98rem;
    line-height:1.6;
}

/* CARDS */

.admin-cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:24px;
    margin-bottom:35px;
}

.admin-card{
    background:#fff;
    padding:28px;
    border-radius:22px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
    border:1px solid #edf2ff;
    transition:0.3s ease;
    position:relative;
    overflow:hidden;
}

.admin-card::before{
    content:'';
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:5px;
    background:var(--grad);
}

.admin-card:hover{
    transform:translateY(-6px);
    box-shadow:0 18px 40px rgba(0,0,0,0.10);
}

.admin-card h3{
    color:var(--muted);
    font-size:1rem;
    margin-bottom:16px;
}

.admin-card p{
    font-size:2.7rem;
    font-weight:800;
    color:var(--primary);
}

/* SECTION BOX */

.section-box{
    background:#fff;
    padding:28px;
    border-radius:24px;
    box-shadow:0 10px 25px rgba(0,0,0,0.05);
    border:1px solid #edf2ff;
    margin-top:35px;
    overflow-x:auto;
}

.section-box h2{
    font-size:1.6rem;
    color:var(--text);
    margin-bottom:20px;
}

/* TABLE */

table{
    width:100%;
    border-collapse:collapse;
}

th{
    padding:16px;
    color:#fff;
    font-size:0.85rem;
    text-transform:uppercase;
    letter-spacing:0.5px;
}

.users-header{
    background:var(--primary);
}

.products-header{
    background:var(--teal);
}

td{
    padding:16px;
    border-bottom:1px solid #eef2ff;
    text-align:center;
    font-size:0.92rem;
    color:#374151;
}


tbody tr{
    transition:0.25s ease;
}

tbody tr:hover{
    background:#0EA1A1;
    transform:scale(1.003);
}

tbody tr:hover td{
    color:white;
}

/* BUTTONS */

.delete-btn{
    background:#ef4444;
    color:white;
    padding:9px 15px;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
    transition:0.2s;
    display:inline-block;
}

.delete-btn:hover{
    background:#dc2626;
    transform:translateY(-1px);
}

.view-btn{
    background:var(--grad);
    color:white;
    padding:10px 16px;
    border-radius:10px;
    text-decoration:none;
    font-weight:600;
    display:inline-block;
    margin-top:12px;
}

/* IMAGES */

.product-image{
    width:75px;
    height:75px;
    border-radius:14px;
    object-fit:cover;
    border:2px solid #eef2ff;
}

/* NO IMAGE */

.no-image{
    background:#f3f4f6;
    color:#6b7280;
    padding:12px;
    border-radius:10px;
    font-size:0.8rem;
    font-weight:600;
}

/* RESPONSIVE */

@media(max-width:768px){

    .admin-navbar{
        padding:1rem;
        height:auto;
        flex-direction:column;
        gap:15px;
    }

    .admin-nav-right{
        width:100%;
        justify-content:center;
        flex-wrap:wrap;
    }

    .main-wrapper{
        padding:18px;
    }

    .dashboard-header{
        padding:24px;
    }

    .dashboard-header h1{
        font-size:1.8rem;
    }

    .admin-card p{
        font-size:2.2rem;
    }

    th,td{
        padding:12px;
        font-size:0.82rem;
    }

}

</style>
</head>

<body>

<!-- TOP HEADER -->

<div style="
    width:100%;
    background:linear-gradient(135deg,#1a3fc4,#0ea5a0);
    padding:18px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 4px 20px rgba(0,0,0,0.08);
    flex-wrap:wrap;
    gap:15px;
">

    <!-- LOGO SECTION -->

    <div style="
        display:flex;
        align-items:center;
        gap:15px;
    ">

        <img 
            src="<?php echo BASE_URL; ?>assets/images/logo.png"
            alt="BikriBazaar Logo"

            style="
                width:58px;
                height:58px;
                border-radius:50%;
                object-fit:cover;
                background:white;
                padding:4px;
                box-shadow:0 4px 12px rgba(0,0,0,0.15);
            "
        >

        <div style="
            font-size:30px;
            font-weight:800;
            color:white;
            letter-spacing:1px;
        ">

            Bikri<span style="color:#127174;">Bazar</span>

        </div>

    </div>

    <!-- LOGOUT BUTTON -->

    <a href="<?php echo BASE_URL; ?>admin_logout.php"
       style="
            background:white;
            color:#111827;
            padding:12px 20px;
            border-radius:12px;
            text-decoration:none;
            font-weight:700;
            box-shadow:0 4px 12px rgba(0,0,0,0.08);
            transition:0.3s;
       "

       onmouseover="this.style.transform='translateY(-2px)'"
       onmouseout="this.style.transform='translateY(0px)'"
    >

        Logout

    </a>

</div>
<div style="padding:30px;">

<!-- DASHBOARD HEADER -->

<div class="dashboard-header">

    <h1 style="
        font-size:42px;
        margin-bottom:10px;
    ">
        Admin Dashboard
    </h1>

    <p style="
        font-size:16px;
    ">
        Manage users, products, favorites and marketplace activity easily.
    </p>

</div>

<!-- CARDS -->

<div class="admin-cards">

    <div class="admin-card">

        <h3>Total Users</h3>

        <p><?php echo $totalUsers; ?></p>

    </div>

    <div class="admin-card">

        <h3>Total Products</h3>

        <p><?php echo $totalProducts; ?></p>

    </div>

    <div class="admin-card">

        <h3>Favorite Analytics</h3>

        <p><?php echo $totalFavorites; ?></p>

        <a href="#favorites-section"
           style="
                display:inline-block;
                margin-top:12px;
                background:#2563eb;
                color:white;
                padding:10px 16px;
                border-radius:10px;
                text-decoration:none;
                font-weight:600;
           ">

            View Details

        </a>

    </div>

</div>

<!-- RECENT USERS -->

<div class="section-box">

    <h2>Recent Users</h2>

    <table>

        <tr class="users-header">

            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>City</th>
            <th>Joined</th>
            <th>Action</th>

        </tr>

        <?php while($user = mysqli_fetch_assoc($recentUsers)) { ?>

            <tr>

                <td><?php echo $user['id']; ?></td>

                <td><?php echo $user['name']; ?></td>

                <td><?php echo $user['email']; ?></td>

                <td><?php echo $user['phone']; ?></td>

                <td><?php echo $user['city']; ?></td>

                <td><?php echo $user['created_at']; ?></td>

                <td>

                    <a class="delete-btn"
                       href="<?php echo BASE_URL; ?>admin_view.php?delete_user=<?php echo $user['id']; ?>"
                       onclick="return confirm('Delete this user?');">

                        Delete

                    </a>

                </td>

            </tr>

        <?php } ?>

    </table>

</div>

<!-- RECENT PRODUCTS -->

<div class="section-box">

    <h2>Recent Products</h2>

    <table>

        <tr class="products-header">

            <th>ID</th>
            <th>Image</th>
            <th>Title</th>
            <th>Price</th>
            <th>Category</th>
            <th>Location</th>
            <th>Seller</th>
            <th>Action</th>

        </tr>

        <?php while($product = mysqli_fetch_assoc($recentProducts)) { ?>

            <tr>

                <td><?php echo $product['id']; ?></td>

                <td>

                    <?php if(!empty($product['image'])) { ?>

                        <img 
                       src="<?php echo htmlspecialchars($product['image']); ?>"
                            width="80"
                            height="80"
                            style="object-fit:cover;"
                        >

                    <?php } else { ?>

                        No Image

                    <?php } ?>

                </td>

                <td><?php echo $product['title']; ?></td>

                <td>₹ <?php echo $product['price']; ?></td>

                <td><?php echo $product['category']; ?></td>

                <td><?php echo $product['location']; ?></td>

                <td><?php echo $product['seller_name']; ?></td>

                <td>

                    <a class="delete-btn"
                       href="<?php echo BASE_URL; ?>admin_view.php?delete_product=<?php echo $product['id']; ?>"
                       onclick="return confirm('Delete this product?');">

                        Delete

                    </a>

                </td>

            </tr>

        <?php } ?>

    </table>

</div>

<!-- FAVORITES -->

<div class="section-box" id="favorites-section">

    <h2>Wishlist Products</h2>

    <table>

        <tr class="products-header">

            <th>ID</th>
            <th>Image</th>
            <th>Product</th>
            <th>Seller</th>
            <th>Wishlist Count</th>

        </tr>

        <?php while($favorite = mysqli_fetch_assoc($favoriteProducts)) { ?>

            <tr>

                <td><?php echo $favorite['id']; ?></td>

                <td>

                    <?php if(!empty($favorite['image'])) { ?>

                        <img 
                      src="<?php echo htmlspecialchars($favorite['image']); ?>" 
                            width="80"
                            height="80"
                            style="object-fit:cover;"
                        >

                    <?php } else { ?>

                        No Image

                    <?php } ?>

                </td>

                <td><?php echo $favorite['title']; ?></td>

                <td><?php echo $favorite['seller_name']; ?></td>

                <td>

                    <strong style="
                        color:#7c3aed;
                        font-size:18px;
                    ">

                        <?php echo $favorite['total_favorites']; ?>

                    </strong>

                </td>

            </tr>

        <?php } ?>

    </table>

</div>

</div>

</body>
</html>