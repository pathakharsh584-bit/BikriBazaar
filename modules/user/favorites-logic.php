<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../../shared/db.php';

global $conn;

$user_id = $_SESSION['user_id'];

$sql = "SELECT products.*
        FROM favorites
        INNER JOIN products
        ON favorites.product_id = products.id
        WHERE favorites.user_id = ?
        ORDER BY favorites.id DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial,sans-serif;
        }

        body{
            background:#f5f5f5;
        }

        .navbar{
            background:#002f34;
            color:white;
            padding:15px 40px;
        }

        .navbar a{
            color:white;
            text-decoration:none;
            font-size:20px;
            font-weight:bold;
        }

        .container{
            width:90%;
            margin:40px auto;
        }

        .products{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
            gap:20px;
        }

        .card{
            background:white;
            border-radius:10px;
            overflow:hidden;
            text-decoration:none;
            color:black;
            box-shadow:0px 0px 10px rgba(0,0,0,0.1);
        }

        .card img{
            width:100%;
            height:220px;
            object-fit:cover;
        }

        .card-body{
            padding:15px;
        }

        .price{
            font-size:24px;
            font-weight:bold;
            margin-bottom:10px;
        }

        .title{
            font-size:18px;
            margin-bottom:10px;
        }

    </style>
</head>
<body>

<div class="navbar">
    <a href="index.php">← Back To Home</a>
</div>

<div class="container">

    <div class="products">

        <?php while($product = mysqli_fetch_assoc($result)) { ?>

            <a class="card" href="product.php?id=<?php echo $product['id']; ?>">

                <img src="uploads/products/<?php echo $product['image']; ?>" alt="Product Image">

                <div class="card-body">

                    <div class="price">
                        ₹ <?php echo $product['price']; ?>
                    </div>

                    <div class="title">
                        <?php echo $product['title']; ?>
                    </div>

                </div>

            </a>

        <?php } ?>

    </div>

</div>

</body>
</html>