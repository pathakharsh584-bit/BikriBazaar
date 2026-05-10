<?php

session_start();

include 'includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM products
          WHERE id='$id'
          AND user_id='$user_id'";

$result = mysqli_query($conn,$query);

$product = mysqli_fetch_assoc($result);

if(isset($_POST['update_product'])){

    $title = mysqli_real_escape_string($conn, $_POST['title']);

    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $price = mysqli_real_escape_string($conn, $_POST['price']);

    $update_query = "UPDATE products
                     SET title='$title',
                         description='$description',
                         price='$price'
                     WHERE id='$id'
                     AND user_id='$user_id'";

    mysqli_query($conn,$update_query);

    header("Location: my-products.php");

    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Product</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>
<body>

<div class="container mt-5">

    <h2>Edit Product</h2>

    <form method="POST">

        <input type="text"
               name="title"
               class="form-control mb-3"
               value="<?php echo $product['title']; ?>"
               required>

        <textarea name="description"
                  class="form-control mb-3"
                  required><?php echo $product['description']; ?></textarea>

        <input type="text"
               name="price"
               class="form-control mb-3"
               value="<?php echo $product['price']; ?>"
               required>

        <button type="submit"
                name="update_product"
                class="btn btn-primary">

            Update Product

        </button>

    </form>

</div>

</body>
</html>