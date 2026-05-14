<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../../shared/db.php';

global $conn;

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM products
        WHERE user_id = ?
        ORDER BY id DESC";

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
    <title>My Ads</title>

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
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        .navbar a{
            color:white;
            text-decoration:none;
            font-weight:bold;
            margin-left:15px;
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
            margin-bottom:15px;
        }

        .actions{
            display:flex;
            gap:10px;
        }

        .edit-btn,
        .delete-btn{
            flex:1;
            text-align:center;
            padding:10px;
            color:white;
            text-decoration:none;
            border-radius:5px;
        }

        .edit-btn{
            background:#007bff;
        }

        .delete-btn{
            background:red;
        }

    </style>
</head>
<body>

<div class="navbar">

    <div>
        <a href="index.php">Home</a>
    </div>

    <div>
        <a href="post-ad.php">Post Ad</a>
        <a href="favorites.php">Favorites</a>
    </div>

</div>

<div class="container">

    <div class="products">

        <?php while($product = mysqli_fetch_assoc($result)) { ?>

            <div class="card">

                <img src="../uploads/products/<?php echo $product['image']; ?>">

                <div class="card-body">

                    <div class="price">
                        ₹ <?php echo $product['price']; ?>
                    </div>

                    <div class="title">
                        <?php echo $product['title']; ?>
                    </div>

                    <div class="actions">

                        <a
                            class="edit-btn"
                            href="edit-product.php?id=<?php echo $product['id']; ?>"
                        >
                            Edit
                        </a>

                        <a
                            class="delete-btn"
                            href="../modules/products/delete_product.php?id=<?php echo $product['id']; ?>"
                            onclick="return confirm('Delete this product?')"
                        >
                            Delete
                        </a>

                    </div>

                </div>

            </div>

        <?php } ?>

    </div>

</div>

</body>
</html>