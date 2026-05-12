<?php

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

    $sql .= " AND category = '$category'";
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
            flex-wrap:wrap;
            gap:15px;
        }

        .navbar h2{
            font-size:28px;
        }

        .navbar-links a{
            color:white;
            text-decoration:none;
            margin-left:15px;
            font-weight:bold;
        }

        .search-box{
            width:90%;
            margin:30px auto;
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0px 0px 10px rgba(0,0,0,0.1);
        }

        .search-form{
            display:flex;
            gap:15px;
            flex-wrap:wrap;
        }

        .search-form input,
        .search-form select{
            flex:1;
            padding:12px;
            border:1px solid #ccc;
            border-radius:5px;
        }

        .search-form button{
            padding:12px 25px;
            background:#002f34;
            color:white;
            border:none;
            border-radius:5px;
            cursor:pointer;
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
            text-decoration:none;
            color:black;
            transition:0.3s;
        }

        .card:hover{
            transform:translateY(-5px);
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

        .location{
            color:gray;
        }

        .no-products{
            text-align:center;
            font-size:22px;
            margin-top:50px;
            color:gray;
        }

    </style>
</head>
<body>

<div class="navbar">

    <h2>BikriBazaar</h2>

    <div class="navbar-links">
        <a href="index.php">Home</a>
        <a href="post-ad.php">Post Ad</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </div>

</div>

<div class="search-box">

    <form class="search-form" method="GET">

        <input
            type="text"
            name="search"
            placeholder="Search products..."
            value="<?php echo $search; ?>"
        >

        <select name="category">

            <option value="">All Categories</option>

            <option value="Mobiles" <?php if($category=="Mobiles") echo "selected"; ?>>
                Mobiles
            </option>

            <option value="Cars" <?php if($category=="Cars") echo "selected"; ?>>
                Cars
            </option>

            <option value="Bikes" <?php if($category=="Bikes") echo "selected"; ?>>
                Bikes
            </option>

            <option value="Electronics" <?php if($category=="Electronics") echo "selected"; ?>>
                Electronics
            </option>

            <option value="Furniture" <?php if($category=="Furniture") echo "selected"; ?>>
                Furniture
            </option>

        </select>

        <button type="submit">
            Search
        </button>

    </form>

</div>

<div class="container">

    <?php if(mysqli_num_rows($result) > 0) { ?>

        <div class="products">

            <?php while($product = mysqli_fetch_assoc($result)) { ?>

                <a class="card" href="product.php?id=<?php echo $product['id']; ?>">

                    <img src="../uploads/products/<?php echo $product['image']; ?>">

                    <div class="card-body">

                        <div class="price">
                            ₹ <?php echo $product['price']; ?>
                        </div>

                        <div class="title">
                            <?php echo $product['title']; ?>
                        </div>

                        <div class="location">
                            <?php echo $product['location']; ?>
                        </div>

                    </div>

                </a>

            <?php } ?>

        </div>

    <?php } else { ?>

        <div class="no-products">
            No Products Found
        </div>

    <?php } ?>

</div>

</body>
</html>