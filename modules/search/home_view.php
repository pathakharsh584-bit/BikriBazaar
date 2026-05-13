<?php

session_start();

require_once __DIR__ . '/../../shared/db.php';

$search = "";
$category = "";

$sql = "SELECT * FROM products WHERE 1";

if(isset($_GET['search']) && $_GET['search'] != ""){

    $search = trim($_GET['search']);

    $sql .= " AND title LIKE '%$search%'";
}

if(isset($_GET['category']) && $_GET['category'] != ""){

    $category = trim($_GET['category']);

    $sql .= " AND category='$category'";
}

$sql .= " ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BikriBazaar</title>

    <link
        rel="stylesheet"
        href="http://localhost/BikriBazaar/public/assets/css/style.css"
    >

</head>

<body>

<div class="navbar">

    <div class="logo">
        BikriBazaar
    </div>

    <div class="nav-links">

        <a href="index.php">Home</a>

        <a href="post-ad.php">Post Ad</a>

        <a href="my-ads.php">My Ads</a>

        <a href="favorites.php">Favorites</a>

        <?php if(isset($_SESSION['user_id'])) { ?>

            <a href="logout.php">Logout</a>

        <?php } else { ?>

            <a href="login.php">Login</a>

            <a href="register.php">Register</a>

        <?php } ?>

    </div>

</div>

<div class="container">

    <div class="hero">

        <h1>
            Buy & Sell Anything Easily
        </h1>

        <p>
            Explore premium deals and connect with buyers & sellers instantly.
        </p>

    </div>

    <div class="search-box">

        <form class="search-form" method="GET">

            <input
                type="text"
                name="search"
                placeholder="Search products, gadgets, cars..."
                value="<?php echo $search; ?>"
            >

            <select name="category">

                <option value="">All Categories</option>

                <option value="Mobiles">
                    Mobiles
                </option>

                <option value="Cars">
                    Cars
                </option>

                <option value="Bikes">
                    Bikes
                </option>

                <option value="Electronics">
                    Electronics
                </option>

                <option value="Furniture">
                    Furniture
                </option>

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

                <a
                    class="product-card"
                    href="product.php?id=<?php echo $product['id']; ?>"
                >

                    <img
                        src="../uploads/products/<?php echo $product['image']; ?>"
                    >

                    <div class="product-content">

                        <div class="price">
                            ₹ <?php echo number_format($product['price']); ?>
                        </div>

                        <div class="title">
                            <?php echo $product['title']; ?>
                        </div>

                        <div class="location">
                            📍 <?php echo $product['location']; ?>
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