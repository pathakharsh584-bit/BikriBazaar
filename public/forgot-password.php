<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
require '../shared/db.php';

$message = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $email = trim($_POST['email']);

    $check = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn, $check);

    if(mysqli_num_rows($result) == 1){

        $otp = rand(100000, 999999);

        $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $update = "UPDATE users
                   SET otp='$otp',
                       otp_expiry='$expiry'
                   WHERE email='$email'";

        mysqli_query($conn, $update);

        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();

            $mail->Host = 'smtp.gmail.com';

            $mail->SMTPAuth = true;

            $mail->Username = 'bikribazaar.project@gmail.com';

            $mail->Password = 'qqbh jsiz rebf akrl';

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = 587;

            $mail->setFrom(
                'bikribazaar.project@gmail.com',
                'BikriBazaar'
            );

            $mail->addAddress($email);

            $mail->isHTML(true);

            $mail->Subject = 'BikriBazaar Password Reset OTP';

            $mail->Body = "
                <h2>Your OTP Code</h2>

                <h1>$otp</h1>

                <p>
                    This OTP will expire in 10 minutes.
                </p>
            ";

            $mail->send();

            header("Location: verify-otp.php?email=$email");
            exit();

        } catch (Exception $e) {

            $message = "Mailer Error: {$mail->ErrorInfo}";
        }

    } else {

        $message = "Email not found!";
    }
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Forgot Password</title>

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
        }

    </style>

</head>
<body>

<div class="box">

    <h2>Forgot Password</h2>

    <div class="message">
        <?php echo $message; ?>
    </div>

    <form method="POST">

        <input
            type="email"
            name="email"
            placeholder="Enter Your Email"
            required
        >

        <button type="submit">
            Send OTP
        </button>

    </form>

</div>

</body>
</html>