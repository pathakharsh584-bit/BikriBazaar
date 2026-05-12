<?php

session_start();

require_once __DIR__ . '/../../shared/db.php';

if(!isset($_GET['id'])){
    die("Product Not Found");
}

$product_id = $_GET['id'];

$sql = "SELECT * FROM products WHERE id = '$product_id'";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){
    die("Product Not Found");
}

$product = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['title']; ?></title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            background:#f5f5f5;
        }

        .navbar{
            background:#002f34;
            padding:15px 40px;
            color:white;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        .navbar a{
            color:white;
            text-decoration:none;
            font-size:18px;
            font-weight:bold;
            margin-left:15px;
        }

        .container{
            width:90%;
            max-width:1000px;
            margin:40px auto;
            background:white;
            border-radius:10px;
            overflow:hidden;
            box-shadow:0px 0px 10px rgba(0,0,0,0.1);
        }

        .product-image img{
            width:100%;
            height:500px;
            object-fit:cover;
        }

        .product-details{
            padding:30px;
        }

        .price{
            font-size:36px;
            font-weight:bold;
            margin-bottom:20px;
        }

        .title{
            font-size:28px;
            margin-bottom:20px;
        }

        .description{
            font-size:18px;
            line-height:1.6;
            margin-bottom:20px;
        }

        .info{
            font-size:18px;
            margin-bottom:10px;
            color:#555;
        }

        .favorite-btn{
            display:inline-block;
            margin-top:20px;
            padding:12px 25px;
            background:red;
            color:white;
            text-decoration:none;
            border-radius:5px;
            font-size:18px;
        }

    </style>
</head>
<body>

<div class="navbar">

    <div>
        <a href="index.php">Home</a>
    </div>

    <div>
        <a href="favorites.php">Favorites</a>
    </div>

</div>

<div class="container">

    <div class="product-image">
        <img src="../uploads/products/<?php echo $product['image']; ?>">
    </div>

    <div class="product-details">

        <div class="price">
            ₹ <?php echo $product['price']; ?>
        </div>

        <div class="title">
            <?php echo $product['title']; ?>
        </div>

        <div class="description">
            <?php echo $product['description']; ?>
        </div>

        <div class="info">
            📍 Location: <?php echo $product['location']; ?>
        </div>

        <div class="info">
            📂 Category: <?php echo $product['category']; ?>
        </div>

        <a
            class="favorite-btn"
            href="../modules/products/favorite_actions.php?product_id=<?php echo $product['id']; ?>"
        >
            ❤️ Add To Favorites
        </a>

    </div>

</div>

</body>
</html>