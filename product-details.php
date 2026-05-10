<?php

session_start();

include 'includes/db.php';

$id = $_GET['id'];

$query = "SELECT * FROM products WHERE id='$id'";

$result = mysqli_query($conn,$query);

$product = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Product Details</title>

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

<!-- Product Details -->

<div class="container mt-5">

    <div class="row">

        <!-- Image -->

        <div class="col-md-6">

            <img src="uploads/<?php echo $product['image']; ?>"
                 class="img-fluid rounded shadow">

        </div>

        <!-- Details -->

        <div class="col-md-6">

            <h2 class="mb-3">

                <?php echo $product['title']; ?>

            </h2>

            <h3 class="text-success mb-3">

                ₹<?php echo $product['price']; ?>

            </h3>

            <p class="mb-4">

                <?php echo $product['description']; ?>

            </p>

            <!-- Scam Warning -->

            <?php if(isset($product['risk_level']) && $product['risk_level'] == "Risky") { ?>

                <div class="alert alert-danger">

                    ⚠️ Warning: This listing may be risky.

                </div>

            <?php } ?>

            <!-- Buttons -->

            <div class="d-grid gap-2">

                <!-- Favorites -->

                <?php if(isset($_SESSION['user_id'])) { ?>

                    <a href="add-favorite.php?id=<?php echo $product['id']; ?>"
                       class="btn btn-danger">

                        ❤️ Add to Favorites

                    </a>

                <?php } else { ?>

                    <a href="login.php?message=Please login first"
                       class="btn btn-danger">

                        ❤️ Add to Favorites

                    </a>

                <?php } ?>

                <!-- Contact Seller -->

                <?php if(isset($_SESSION['user_id'])) { ?>

                    <button class="btn btn-primary">

                        Contact Seller

                    </button>

                <?php } else { ?>

                    <a href="login.php?message=Please login first"
                       class="btn btn-primary">

                        Contact Seller

                    </a>

                <?php } ?>

            </div>

        </div>

    </div>

</div>

</body>
</html>