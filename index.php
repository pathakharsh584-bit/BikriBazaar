<?php

session_start();

include 'includes/db.php';

if(isset($_GET['search'])){

    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $query = "SELECT * FROM products
              WHERE title LIKE '%$search%'
              ORDER BY id DESC";

} else {

    $query = "SELECT * FROM products
              ORDER BY id DESC";
}

$result = mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>BikriBazaar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>
<body>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand"
           href="index.php">

            BikriBazaar

        </a>

        <div>

            <a href="add-product.php"
               class="btn btn-warning me-2">

                Sell Product

            </a>

            <a href="favorites.php"
               class="btn btn-danger me-2">

                ❤️ Favorites

            </a>

            <a href="my-products.php"
               class="btn btn-light me-2">

                My Products

            </a>

            <?php if(isset($_SESSION['user_id'])) { ?>

                <a href="logout.php"
                   class="btn btn-danger">

                    Logout

                </a>

            <?php } else { ?>

                <a href="login.php"
                   class="btn btn-light">

                    Login

                </a>

            <?php } ?>

        </div>

    </div>

</nav>

<!-- Hero -->

<div class="container text-center mt-5">

    <h1 class="display-4">
        Buy & Sell Anything
    </h1>

    <p class="lead">
        India's Smart Marketplace
    </p>

    <!-- Search -->

    <form method="GET" class="mt-4">

        <div class="row justify-content-center">

            <div class="col-md-6">

                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Search products...">

            </div>

            <div class="col-md-2">

                <button type="submit"
                        class="btn btn-dark w-100">

                    Search

                </button>

            </div>

        </div>

    </form>

</div>

<!-- Products -->

<div class="container mt-5">

    <div class="row">

        <?php while($product = mysqli_fetch_assoc($result)) { ?>

        <div class="col-md-4 mb-4">

            <div class="card shadow h-100">

                <img src="uploads/<?php echo $product['image']; ?>"
                     class="card-img-top"
                     style="height:250px; object-fit:cover;">

                <div class="card-body d-flex flex-column">

                    <h5 class="card-title">

                        <?php echo $product['title']; ?>

                    </h5>

                    <p class="text-success fw-bold">

                        ₹<?php echo $product['price']; ?>

                    </p>

                    <p>

                        <?php echo $product['description']; ?>

                    </p>

                    <div class="mt-auto">

                        <a href="product-details.php?id=<?php echo $product['id']; ?>"
                           class="btn btn-primary w-100 mb-2">

                           View Details

                        </a>

                        <a href="add-favorite.php?id=<?php echo $product['id']; ?>"
                           class="btn btn-danger w-100">

                           ❤️ Add to Favorites

                        </a>

                    </div>

                </div>

            </div>

        </div>

        <?php } ?>

    </div>

</div>

</body>
</html>