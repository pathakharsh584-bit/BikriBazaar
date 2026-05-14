<?php


require_once __DIR__ . '/../../shared/db.php';
require_once __DIR__ . '/auth_actions.php';

$message = "";


if (!isset($_GET['email'])) {
    die("Invalid Access");
}

$email = $_GET['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        $validation = validatePassword($password);

        if ($validation !== true) {
            $message = $validation;
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            
            $update = "UPDATE users 
                       SET password='$hashedPassword', 
                           otp=NULL, 
                           otp_expiry=NULL 
                       WHERE email='$email'";

            if (mysqli_query($conn, $update)) {
                
                
                header("Location: " . BASE_URL . "login.php?message=Password updated successfully!");
                exit();

            } else {
                $message = "Failed to update password.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Reset Password</title>

    <style>

        body{
            font-family:Arial;
            background:#f5f5f5;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .box{
            width:400px;
            background:white;
            padding:30px;
            border-radius:10px;
            box-shadow:0px 0px 10px rgba(0,0,0,0.1);
        }

        input,button{
            width:100%;
            padding:12px;
            margin-top:15px;
        }

        .message{
            color:red;
            margin-bottom:10px;
            font-weight:bold;
        }

    </style>

</head>
<body>

<div class="box">

    <h2>Reset Password</h2>

    <div class="message">
        <?php echo $message; ?>
    </div>

    <form method="POST">

        <input
            type="password"
            name="password"
            placeholder="New Password"
            required
        >

        <input
            type="password"
            name="confirm_password"
            placeholder="Confirm Password"
            required
        >

        <button type="submit">
            Update Password
        </button>

    </form>

</div>

</body>
</html>