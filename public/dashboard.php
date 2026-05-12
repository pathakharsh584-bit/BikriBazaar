<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BikriBazaar</title>

    <style>

        body{
            font-family:Arial, sans-serif;
            background:#f5f5f5;
            padding:50px;
        }

        .dashboard-box{
            background:white;
            padding:30px;
            border-radius:10px;
            box-shadow:0px 0px 10px rgba(0,0,0,0.1);
            max-width:600px;
            margin:auto;
        }

        h1{
            margin-bottom:20px;
        }

        .info{
            font-size:18px;
            margin-bottom:10px;
        }

        .logout-btn{
            display:inline-block;
            margin-top:20px;
            padding:12px 20px;
            background:red;
            color:white;
            text-decoration:none;
            border-radius:5px;
        }

    </style>
</head>
<body>

<div class="dashboard-box">

    <h1>Welcome to BikriBazaar</h1>

    <div class="info">
        Name: <?php echo $_SESSION['user_name']; ?>
    </div>

    <div class="info">
        Email: <?php echo $_SESSION['user_email']; ?>
    </div>

    <a href="logout.php" class="logout-btn">
        Logout
    </a>

</div>

</body>
</html>