<?php

function postProduct($conn)
{
    $user_id = $_SESSION['user_id'];

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $location = trim($_POST['location']);
    $category = trim($_POST['category']);

    $imageName = time() . "_" . $_FILES['image']['name'];

    $tempName = $_FILES['image']['tmp_name'];

    $uploadPath = __DIR__ . '/../../uploads/products/' . $imageName;

    move_uploaded_file($tempName, $uploadPath);

    $sql = "INSERT INTO products
            (user_id, title, description, price, location, category, image)
            VALUES
            ('$user_id', '$title', '$description', '$price', '$location', '$category', '$imageName')";

    if(mysqli_query($conn, $sql)){
        return "Product Posted Successfully!";
    } else {
        return "Failed To Post Product!";
    }
}

?>