<?php

session_start();

require_once __DIR__ . '/../../shared/db.php';

if(!isset($_GET['id'])){
    die("Product Not Found");
}

$product_id = intval($_GET['id']);

$sql = "SELECT * FROM products WHERE id = '$product_id'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){
    die("Product Not Found");
}

$product = mysqli_fetch_assoc($result);

// Check if the current user has already favorited this product
$is_favorited = false;
if(isset($_SESSION['user_id'])){
    $user_id = intval($_SESSION['user_id']);
    $fav_check = "SELECT * FROM favorites WHERE user_id=$user_id AND product_id=$product_id";
    $fav_result = mysqli_query($conn, $fav_check);
    if(mysqli_num_rows($fav_result) > 0){
        $is_favorited = true;
    }
}

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

        /* Updated to support Button element */
        .favorite-btn{
            display:inline-block;
            margin-top:20px;
            padding:12px 25px;
            color:white;
            border:none;
            border-radius:5px;
            font-size:18px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-add {
            background: red;
        }
        
        .btn-remove {
            background: #555;
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
        <img src="../uploads/products/<?php echo $product['image']; ?>" alt="Product Image">
    </div>

    <div class="product-details">

        <div class="price">
            ₹ <?php echo number_format($product['price']); ?>
        </div>

        <div class="title">
            <?php echo $product['title']; ?>
        </div>

        <div class="description">
            <?php echo nl2br($product['description']); ?>
        </div>

        <div class="info">
            📍 Location: <?php echo $product['location']; ?>
        </div>

        <div class="info">
            📂 Category: <?php echo $product['category']; ?>
        </div>

        <button 
            id="favBtn"
            class="favorite-btn <?php echo $is_favorited ? 'btn-remove' : 'btn-add'; ?>"
            onclick="toggleFavorite(<?php echo $product['id']; ?>)"
        >
            <?php echo $is_favorited ? '🤍 Remove From Favorites' : '❤️ Add To Favorites'; ?>
        </button>

    </div>

</div>

<script>
function toggleFavorite(productId) {
    const btn = document.getElementById('favBtn');
    
    // Optional: Visual feedback while processing
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳ Processing...';
    btn.disabled = true;

    // Send the AJAX request to your newly updated logic file
    fetch(`../modules/products/favorite_actions.php?product_id=${productId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;

        if (data.status === 'unauthorized') {
            // User isn't logged in, redirect them
            window.location.href = data.redirect;
            return;
        }

        if (data.status === 'success') {
            // Toggle the UI based on whether it was added or removed
            if (data.action === 'added') {
                btn.innerHTML = '🤍 Remove From Favorites';
                btn.className = 'favorite-btn btn-remove';
            } else if (data.action === 'removed') {
                btn.innerHTML = '❤️ Add To Favorites';
                btn.className = 'favorite-btn btn-add';
            }
        } else {
            // Revert on unexpected error
            btn.innerHTML = originalText;
            alert("Something went wrong!");
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}
</script>

</body>
</html>