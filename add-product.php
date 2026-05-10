<?php

session_start();

include 'includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(isset($_POST['add_product'])){

 $title = mysqli_real_escape_string($conn, $_POST['title']);

$description = mysqli_real_escape_string($conn, $_POST['description']);

$price = mysqli_real_escape_string($conn, $_POST['price']);

    $image = mysqli_real_escape_string($conn, $_FILES['image']['name']);
    $temp_name = mysqli_real_escape_string($conn, $_FILES['image']['tmp_name']);

    move_uploaded_file($temp_name, "uploads/$image");

    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO products(user_id,title,description,price,image)
              VALUES('$user_id','$title','$description','$price','$image')";

    mysqli_query($conn,$query);

    echo "Product Added Successfully";
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Add Product</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-5">

    <h2>Add Product</h2>

    <form method="POST" enctype="multipart/form-data">

        <input type="text"
               name="title"
               class="form-control mb-3"
               placeholder="Product Title"
               required>

        <textarea name="description"
                  class="form-control mb-3"
                  placeholder="Description"
                  required></textarea>

        <input type="text"
               name="price"
               class="form-control mb-3"
               placeholder="Price"
               required>

        <input type="file"
               name="image"
               class="form-control mb-3"
               required>

        <button type="submit"
                name="add_product"
                class="btn btn-primary">

            Add Product

        </button>

    </form>

</div>

</body>
</html>