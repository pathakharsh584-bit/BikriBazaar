<?php

require_once __DIR__ . '/../../shared/config.php';

session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $adminEmail = "bikribazaar.project@gmail.com";
    $adminPassword = "123456_123456";

    if ($email === $adminEmail && $password === $adminPassword) {

        $_SESSION['admin_temp_auth'] = true;

        header("Location: " . BASE_URL . "verify_otp.php");
        exit();

    } else {

        $error = "Invalid admin credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Login - BikriBazaar</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>

body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:#f4f7ff;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

.login-card{
    width:100%;
    max-width:420px;
    background:white;
    padding:40px;
    border-radius:24px;
    box-shadow:0 20px 50px rgba(0,0,0,0.08);
    border:2px solid #d4d4d8;
}

.logo{
    text-align:center;
    margin-bottom:25px;
}

.logo h1{
    margin:0;
    color:#1a3fc4;
    font-size:32px;
}

.logo span{
    color:#0ea5a0;
}

.title{
    text-align:center;
    margin-bottom:25px;
}

.title h2{
    margin:0;
    color:#111827;
}

.title p{
    color:#6b7280;
    margin-top:8px;
}

.form-group{
    margin-bottom:18px;
}

label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
    font-weight:600;
}

input{
    width:100%;
    padding:14px;
    border:1.5px solid #cbd5e1;
    border-radius:12px;
    font-size:15px;
    outline:none;
    box-sizing:border-box;
}

input:focus{
    border-color:#0ea5a0;
}

.login-btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#1a3fc4,#0ea5a0);
    color:white;
    font-size:16px;
    font-weight:700;
    cursor:pointer;
}

.error{
    background:#fee2e2;
    color:#b91c1c;
    padding:12px;
    border-radius:10px;
    margin-bottom:18px;
    font-size:14px;
}

.back{
    text-align:center;
    margin-top:20px;
}

.back a{
    color:#1a3fc4;
    text-decoration:none;
    font-weight:600;
}

</style>
</head>

<body>

<div class="login-card">

    <div class="logo">
        <h1>Bikri<span>Bazaar</span></h1>
    </div>

    <div class="title">
        <h2>Administrator Login</h2>
        <p>Secure access to admin dashboard</p>
    </div>

    <?php if($error != ""): ?>
        <div class="error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label>Email Address</label>

            <input type="email"
                   name="email"
                   placeholder="Enter admin email"
                   required>
        </div>

        <div class="form-group">
            <label>Password</label>

            <input type="password"
                   name="password"
                   placeholder="Enter admin password"
                   required>
        </div>

        <button class="login-btn" type="submit">
            <i class="fa-solid fa-user-shield"></i>
            Login as Administrator
        </button>

    </form>

    <div class="back">
        <a href="<?php echo BASE_URL; ?>login.php">
            ← Back to User Login
        </a>
    </div>

</div>

</body>
</html>