<?php

session_start();

require_once __DIR__ . '/../../shared/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$message = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){

    require_once __DIR__ . '/product_actions.php';

    $message = postProduct($conn);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Ad - BikriBazaar</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            background:#f5f5f5;
            padding:40px;
        }

        .container{
            max-width:600px;
            background:white;
            margin:auto;
            padding:30px;
            border-radius:10px;
            box-shadow:0px 0px 10px rgba(0,0,0,0.1);
        }

        h2{
            margin-bottom:20px;
            text-align:center;
        }

        input, textarea, select{
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

        .message{
            margin-bottom:15px;
            text-align:center;
            font-weight:bold;
            color:green;
        }

    </style>
</head>
<body>

<div class="container">

    <h2>Post Your Product</h2>

    <?php if($message != "") { ?>
        <div class="message">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <form method="POST" enctype="multipart/form-data">

        <input type="text" name="title" placeholder="Product Title" required>

        <textarea name="description" placeholder="Product Description" required></textarea>

        <input type="number" name="price" placeholder="Price" required>

        <input type="text" name="location" placeholder="Location" required>

        <select name="category" required>
            <option value="">Select Category</option>
            <option value="Mobiles">Mobiles</option>
            <option value="Cars">Cars</option>
            <option value="Bikes">Bikes</option>
            <option value="Electronics">Electronics</option>
            <option value="Furniture">Furniture</option>
        </select>

        <input type="file" name="image" required>

        <button type="submit">
            Post Ad
        </button>

    </form>

</div>

</body>
</html>