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

    $uploadPath = dirname(__DIR__, 2) . '/public/uploads/products/' . $imageName;

    if (!move_uploaded_file($tempName, $uploadPath)) {
        return "Target folder upload failed! Verify your directory path permissions.";
    }

    $sql = "INSERT INTO products
            (user_id, title, description, price, location, category, image)
            VALUES
            ('$user_id', '$title', '$description', '$price', '$location', '$category', '$imageName')";

    if(mysqli_query($conn, $sql)){
        header("Location: " . BASE_URL . "index.php");
        exit();
    } else {
        return "Failed To Post Product!";
    }
}

?>