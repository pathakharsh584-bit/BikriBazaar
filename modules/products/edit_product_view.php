<?php

session_start();

require_once __DIR__ . '/../../shared/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: " . BASE_URL . "login.php");
    exit();
}


if(!isset($_GET['id'])){
    die("Product Not Found");
}

$product_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM products
        WHERE id='$product_id'
        AND user_id='$user_id'";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) == 0){
    die("Unauthorized Access");
}

$product = mysqli_fetch_assoc($result);

$message = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $location = trim($_POST['location']);
    $category = trim($_POST['category']);

    $update = "UPDATE products SET
               title='$title',
               description='$description',
               price='$price',
               location='$location',
               category='$category'
               WHERE id='$product_id'";

    if(mysqli_query($conn, $update)){
        header("Location: " . BASE_URL . "my-ads.php");
        exit();

    } else {
        $message = "Update Failed!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial,sans-serif;
        }

        body{
            background:#f5f5f5;
            padding:40px;
        }

        .container{
            max-width:600px;
            margin:auto;
            background:white;
            padding:30px;
            border-radius:10px;
            box-shadow:0px 0px 10px rgba(0,0,0,0.1);
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }

        input,
        textarea,
        select{
            width:100%;
            padding:12px;
            margin-bottom:15px;
            border:1px solid #ccc;
            border-radius:5px;
        }

        textarea{
            resize:none;
            height:120px;
        }

        button{
            width:100%;
            padding:12px;
            background:#002f34;
            color:white;
            border:none;
            border-radius:5px;
            font-size:16px;
            cursor:pointer;
        }

    </style>
</head>
<body>

<div class="container">

    <h2>Edit Product</h2>

    <form method="POST">

        <input
            type="text"
            name="title"
            value="<?php echo $product['title']; ?>"
            required
        >

        <textarea name="description" required><?php echo $product['description']; ?></textarea>

        <input
            type="number"
            name="price"
            value="<?php echo $product['price']; ?>"
            required
        >

        <input
            type="text"
            name="location"
            value="<?php echo $product['location']; ?>"
            required
        >

        <select name="category" required>

            <option value="Mobiles" <?php if($product['category']=="Mobiles") echo "selected"; ?>>
                Mobiles
            </option>

            <option value="Cars" <?php if($product['category']=="Cars") echo "selected"; ?>>
                Cars
            </option>

            <option value="Bikes" <?php if($product['category']=="Bikes") echo "selected"; ?>>
                Bikes
            </option>

            <option value="Electronics" <?php if($product['category']=="Electronics") echo "selected"; ?>>
                Electronics
            </option>

            <option value="Furniture" <?php if($product['category']=="Furniture") echo "selected"; ?>>
                Furniture
            </option>

        </select>

        <button type="submit">
            Update Product
        </button>

    </form>

</div>

</body>
</html>