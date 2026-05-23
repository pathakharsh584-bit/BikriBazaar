<?php

require_once __DIR__ . '/../../shared/config.php';

session_start();

if (!isset($_SESSION['admin_temp_auth'])) {
    header("Location: " . BASE_URL . "admin.php");
    exit();
}

$error = "";

if (!isset($_SESSION['admin_otp'])) {

    $_SESSION['admin_otp'] = rand(100000, 999999);

    require_once __DIR__ . '/../../vendor/autoload.php';

    $dotenv = parse_ini_file(__DIR__ . '/../../.env');

    $adminEmail = $dotenv['ADMIN_EMAIL'];
    $appPassword = $dotenv['ADMIN_APP_PASSWORD'];

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {

        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';

        $mail->SMTPAuth = true;

        $mail->Username = $adminEmail;

        $mail->Password = $appPassword;

        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;

        $mail->setFrom($adminEmail, 'BikriBazaar Admin');

        $mail->addAddress($adminEmail);

        $mail->isHTML(true);

        $mail->Subject = 'BikriBazaar Admin OTP Verification';

        $mail->Body = '

            <div style="
                font-family:Segoe UI,sans-serif;
                padding:20px;
            ">

                <h2 style="color:#1a3fc4;">
                    BikriBazaar Admin Verification
                </h2>

                <p>Your OTP is:</p>

                <h1 style="
                    letter-spacing:4px;
                    color:#0ea5a0;
                ">
                    ' . $_SESSION['admin_otp'] . '
                </h1>

                <p>
                    This OTP is valid for this session only.
                </p>

            </div>
        ';

        $mail->send();

    } catch (Exception $e) {

        die("OTP Mail Failed: " . $mail->ErrorInfo);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $enteredOtp = trim($_POST['otp']);

    if ($enteredOtp == $_SESSION['admin_otp']) {

        $_SESSION['is_admin'] = true;

        unset($_SESSION['admin_temp_auth']);
        unset($_SESSION['admin_otp']);

        header("Location: " . BASE_URL . "admin_view.php");
        exit();

    } else {

        $error = "Invalid OTP!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Verify OTP - BikriBazaar</title>

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

.otp-card{
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
    margin-bottom:20px;
}

label{
    display:block;
    margin-bottom:8px;
    font-size:14px;
    font-weight:600;
}

input{
    width:100%;
    padding:15px;
    border:1.5px solid #cbd5e1;
    border-radius:12px;
    font-size:18px;
    outline:none;
    text-align:center;
    letter-spacing:4px;
    box-sizing:border-box;
}

input:focus{
    border-color:#0ea5a0;
}

.verify-btn{
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

.note{
    background:#eef2ff;
    color:#1e3a8a;
    padding:14px;
    border-radius:12px;
    margin-bottom:20px;
    font-size:14px;
    text-align:center;
}

</style>

</head>

<body>

<div class="otp-card">

    <div class="logo">
        <h1>Bikri<span>Bazaar</span></h1>
    </div>

    <div class="title">
        <h2>OTP Verification</h2>
        <p>Enter the OTP sent to admin email</p>
    </div>

    <?php if($error != ""): ?>

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <div class="form-group">

            <label>Enter OTP</label>

            <input type="text"
                   name="otp"
                   maxlength="6"
                   required>

        </div>

        <button class="verify-btn" type="submit">

            <i class="fa-solid fa-shield-halved"></i>

            Verify OTP

        </button>

    </form>

</div>

</body>

</html>