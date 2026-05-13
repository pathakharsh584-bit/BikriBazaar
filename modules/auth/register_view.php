<?php

require_once __DIR__ . '/../../shared/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once __DIR__ . '/auth_actions.php';

    $message = registerUser($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - BikriBazaar</title>

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
            max-width:550px;
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

        .form-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:18px;
        }

        .form-group{
            margin-bottom:18px;
        }

        .full-width{
            grid-column:1/3;
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

        .password-rules{
            background:#eef2ff;
            padding:16px;
            border-radius:16px;
            margin-bottom:20px;
            color:#4338ca;
            font-size:14px;
            line-height:1.8;
        }

        .auth-links{
            margin-top:25px;
            text-align:center;
        }

        .auth-links a{
            text-decoration:none;
            color:#4338ca;
            font-weight:700;
        }

        @media(max-width:700px){

            .form-grid{
                grid-template-columns:1fr;
            }

            .full-width{
                grid-column:auto;
            }

        }

    </style>

</head>

<body>

<div class="auth-wrapper">

    <div class="auth-card">

        <h1 class="auth-title">
            Create Account
        </h1>

        <p class="auth-subtitle">
            Join BikriBazaar marketplace today.
        </p>

        <?php if($message != "") { ?>

            <div class="message">
                <?php echo $message; ?>
            </div>

        <?php } ?>

        <form method="POST">

            <div class="form-grid">

                <div class="form-group">

                    <input
                        type="text"
                        name="name"
                        placeholder="Full Name"
                        required
                    >

                </div>

                <div class="form-group">

                    <input
                        type="email"
                        name="email"
                        placeholder="Email Address"
                        required
                    >

                </div>

                <div class="form-group">

                    <input
                        type="text"
                        name="phone"
                        placeholder="Phone Number"
                        required
                    >

                </div>

                <div class="form-group">

                    <input
                        type="text"
                        name="city"
                        placeholder="City"
                        required
                    >

                </div>

                <div class="form-group full-width">

                    <input
                        type="password"
                        name="password"
                        placeholder="Password"
                        required
                    >

                </div>

                <div class="form-group full-width">

                    <input
                        type="password"
                        name="confirm_password"
                        placeholder="Confirm Password"
                        required
                    >

                </div>

            </div>

            <div class="password-rules">

                Password must contain:
                uppercase, lowercase, number,
                special character and minimum 8 characters.

            </div>

            <button class="auth-btn" type="submit">
                Create Account
            </button>

        </form>

        <div class="auth-links">

            <a href="login.php">
                Already have an account?
            </a>

        </div>

    </div>

</div>

</body>
</html>