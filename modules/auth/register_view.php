<?php

require_once __DIR__ . '/../../shared/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once 'auth_actions.php';

    $message = registerUser($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BikriBazaar</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }

        body{
            background:#f5f5f5;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .register-box{
            background:white;
            width:400px;
            padding:30px;
            border-radius:10px;
            box-shadow:0px 0px 10px rgba(0,0,0,0.1);
        }

        h2{
            text-align:center;
            margin-bottom:20px;
        }

        input{
            width:100%;
            padding:12px;
            margin-bottom:15px;
            border:1px solid #ccc;
            border-radius:5px;
        }

        button{
            width:100%;
            padding:12px;
            background:#002f34;
            color:white;
            border:none;
            border-radius:5px;
            cursor:pointer;
            font-size:16px;
        }

        button:hover{
            background:#014b52;
        }

        .message{
            text-align:center;
            margin-bottom:15px;
            font-weight:bold;
            color:green;
        }

        .login-link{
            text-align:center;
            margin-top:15px;
            font-size:14px;
        }

        .login-link a{
            color:#002f34;
            text-decoration:none;
            font-weight:bold;
        }

    </style>
</head>
<body>

<div class="register-box">

    <h2>Create Account</h2>

    <?php if($message != "") { ?>
        <div class="message">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <input type="text" name="name" placeholder="Full Name" required>

        <input type="email" name="email" placeholder="Email Address" required>

        <input type="password" name="password" placeholder="Password" required>

        <input type="text" name="phone" placeholder="Phone Number" required>

        <input type="text" name="city" placeholder="City" required>

        <button type="submit">
            Register
        </button>

    </form>

    <div class="login-link">
        Already have an account? <a href="login.php">Login</a>
    </div>

</div>

</body>
</html>