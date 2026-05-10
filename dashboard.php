<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

<h1>Welcome <?php echo $_SESSION['user_name']; ?></h1>

<p>You are logged in.</p>
<a href="logout.php"
   class="btn btn-danger">

   Logout

</a>
<a href="my-products.php"
   class="btn btn-dark">

   My Products

</a>
</body>
</html>