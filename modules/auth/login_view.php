<?php

session_start();

require_once __DIR__ . '/../../shared/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once __DIR__ . '/auth_actions.php';

    $message = loginUser($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - BikriBazaar</title>

    <link
        rel="stylesheet"
        href="http://localhost/BikriBazaar/public/assets/css/style.css"
    >

    <style>

        .auth-wrapper{
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:40px 20px;
        }

        .auth-card{
            width:100%;
            max-width:470px;
            background:rgba(255,255,255,0.75);
            backdrop-filter:blur(15px);
            padding:40px;
            border-radius:30px;
            box-shadow:0 15px 40px rgba(0,0,0,0.12);
        }

        .auth-title{
            text-align:center;
            font-size:40px;
            margin-bottom:10px;
            font-weight:800;
            color:#312e81;
        }

        .auth-subtitle{
            text-align:center;
            color:#6b7280;
            margin-bottom:30px;
        }

        .form-group{
            margin-bottom:20px;
        }

        .form-group input{
            width:100%;
            padding:16px;
            border:none;
            border-radius:18px;
            background:#f3f4f6;
            font-size:15px;
            outline:none;
        }

        .form-group input:focus{
            background:white;
            box-shadow:0 0 0 4px rgba(99,102,241,0.15);
        }

        .auth-btn{
            width:100%;
            border:none;
            padding:16px;
            border-radius:18px;
            background:linear-gradient(135deg,#4338ca,#0f766e);
            color:white;
            font-size:16px;
            font-weight:700;
            cursor:pointer;
            transition:0.35s;
        }

        .auth-btn:hover{
            transform:translateY(-3px);
        }

        .message{
            padding:14px;
            border-radius:15px;
            margin-bottom:20px;
            background:#fee2e2;
            color:#b91c1c;
            font-weight:600;
            text-align:center;
        }

        .auth-links{
            margin-top:25px;
            text-align:center;
        }

        .auth-links a{
            display:block;
            margin-top:12px;
            text-decoration:none;
            color:#4338ca;
            font-weight:700;
        }

    </style>

</head>

<body>

<div class="auth-wrapper">

    <div class="auth-card">

        <h1 class="auth-title">
            Welcome Back
        </h1>

        <p class="auth-subtitle">
            Login to continue using BikriBazaar.
        </p>

        <?php if($message != "") { ?>

            <div class="message">
                <?php echo $message; ?>
            </div>

        <?php } ?>

        <form method="POST">

            <div class="form-group">

                <input
                    type="email"
                    name="email"
                    placeholder="Enter Email"
                    required
                >

            </div>

            <div class="form-group">

                <input
                    type="password"
                    name="password"
                    placeholder="Enter Password"
                    required
                >

            </div>

            <button class="auth-btn" type="submit">
                Login Now
            </button>

        </form>

        <div class="auth-links">

            <a href="register.php">
                Create New Account
            </a>

            <a href="forgot-password.php">
                Forgot Password?
            </a>

        </div>

    </div>

</div>

</body>
</html>