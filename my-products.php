<?php

session_start();

include 'includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM products
          WHERE user_id='$user_id'
          ORDER BY id DESC";

$result = mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Products</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>
<body>

<!-- Navbar -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand" href="index.php">
            BikriBazaar
        </a>

        <div>

            <a href="dashboard.php"
               class="btn btn-light me-2">

               Dashboard

            </a>

            <a href="logout.php"
               class="btn btn-danger">

               Logout

            </a>

        </div>

    </div>

</nav>

<!-- My Products Section -->

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            My Products
        </h2>

        <a href="add-product.php"
           class="btn btn-primary">

           Add New Product

        </a>

    </div>

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

                    <p class="card-text text-success fw-bold">

                        ₹<?php echo $product['price']; ?>

                    </p>

                    <p class="card-text">

                        <?php echo $product['description']; ?>

                    </p>

                    <div class="mt-auto">

                        <a href="product-details.php?id=<?php echo $product['id']; ?>"
                           class="btn btn-primary w-100 mb-2">

                           View Details

                        </a>

                        <a href="edit-product.php?id=<?php echo $product['id']; ?>"
                           class="btn btn-warning w-100 mb-2">

                           Edit

                        </a>

                        <a href="delete-product.php?id=<?php echo $product['id']; ?>"
                           class="btn btn-danger w-100">

                           Delete

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