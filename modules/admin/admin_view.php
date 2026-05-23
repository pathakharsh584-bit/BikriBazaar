<?php
session_start();

if (!isset($_SESSION['is_admin'])) {

    header("Location: admin_login.php");
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

    header("Location: admin_view.php");

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

    header("Location: admin_view.php");

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
        body{
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
            padding:30px;
            color:#111827;
        }

        .dashboard-header{
            background:white;
            padding:25px;
            border-radius:16px;
            box-shadow:0 4px 15px rgba(0,0,0,0.08);
            margin-bottom:25px;
        }

        .dashboard-header h1{
            margin:0;
            color:#1e3a8a;
        }

        .dashboard-header p{
            color:#6b7280;
            margin-top:8px;
        }

        .admin-cards{
            display:flex;
            gap:20px;
            flex-wrap:wrap;
            margin-bottom:35px;
        }

        .admin-card{
            background:white;
            padding:25px;
            width:230px;
            border-radius:16px;
            box-shadow:0 4px 15px rgba(0,0,0,0.08);
            text-align:center;
            transition:0.3s;
        }

        .admin-card:hover{
            transform:translateY(-6px);
            box-shadow:0 8px 25px rgba(0,0,0,0.15);
        }

        .admin-card h3{
            color:#374151;
        }

        .admin-card p{
            font-size:36px;
            font-weight:bold;
            color:#2563eb;
        }

        .section-box{
            background:white;
            padding:25px;
            border-radius:16px;
            box-shadow:0 4px 15px rgba(0,0,0,0.08);
            margin-top:30px;
            overflow-x:auto;
        }

        table{
            margin-top:20px;
            width:100%;
            border-collapse:collapse;
        }

        th{
            padding:14px;
            color:white;
            font-size:15px;
        }

        td{
            padding:13px;
            border-bottom:1px solid #e5e7eb;
            text-align:center;
        }

        tbody tr:hover td{
            background:#f9fafb;
        }

        .users-header{
            background:#2563eb;
        }

        .products-header{
            background:#0f766e;
        }

        .delete-btn{
            background:#dc2626;
            color:white;
            padding:8px 14px;
            border-radius:8px;
            text-decoration:none;
            transition:0.3s;
        }

        .delete-btn:hover{
            background:#991b1b;
        }

        img{
            border-radius:10px;
            border:1px solid #e5e7eb;
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
">

    <div style="
        font-size:30px;
        font-weight:800;
        color:white;
        letter-spacing:1px;
    ">
        BIKRI<span style="color:#d1fae5;">BAZAAR</span>
    </div>

    <a href="logout.php"
       style="
            background:white;
            color:#111827;
            padding:12px 18px;
            border-radius:12px;
            text-decoration:none;
            font-weight:700;
            box-shadow:0 4px 12px rgba(0,0,0,0.08);
       ">

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
                       href="admin_view.php?delete_user=<?php echo $user['id']; ?>"
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
                            src="/BikriBazaar/public/uploads/products/<?php echo rawurlencode($product['image']); ?>"
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
                       href="admin_view.php?delete_product=<?php echo $product['id']; ?>"
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
                            src="/BikriBazaar/public/uploads/products/<?php echo rawurlencode($favorite['image']); ?>"
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