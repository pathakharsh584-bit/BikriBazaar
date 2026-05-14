<?php

require_once __DIR__ . '/../../shared/db.php';

$message = "";

if(!isset($_GET['email'])){
    die("Invalid Access");
}

$email = trim($_GET['email']);

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $otp = trim($_POST['otp']);

    $sql = "SELECT * FROM users
            WHERE email='$email'
            AND otp='$otp'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){

        $user = mysqli_fetch_assoc($result);

        $expiry_time = strtotime($user['otp_expiry']);

        $current_time = time();

        if($current_time <= $expiry_time){

            header("Location: " . BASE_URL . "reset-password.php?email=" . urlencode($email));
            exit();

        } else {

            $message = "OTP Expired!";
        }

    } else {

        $message = "Invalid OTP!";
    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Verify OTP</title>

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

    <h2>Verify OTP</h2>

    <div class="message">
        <?php echo $message; ?>
    </div>

    <form method="POST">

        <input
            type="text"
            name="otp"
            placeholder="Enter OTP"
            required
        >

        <button type="submit">
            Verify OTP
        </button>

    </form>

</div>

</body>
</html>