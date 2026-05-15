<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../shared/db.php';

$message = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $email = trim($_POST['email']);
    

    $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

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

            $mail->setFrom('bikribazaar.project@gmail.com', 'BikriBazaar');
            $mail->addAddress($email);
            $mail->isHTML(true);

            $mail->Subject = 'BikriBazaar Password Reset OTP';
            $mail->Body = "
                <h2>Your OTP Code</h2>
                <h1>$otp</h1>
                <p>This OTP will expire in 10 minutes.</p>
            ";

            $mail->send();

            if ($is_ajax) {
                echo json_encode(['status' => 'success', 'redirect' => BASE_URL . "verify-otp.php?email=" . urlencode($email)]);
                exit();
            }

            header("Location: " . BASE_URL . "verify-otp.php?email=" . urlencode($email));
            exit();

        } catch (Exception $e) {
            $msg = "Mailer Error: {$mail->ErrorInfo}";
            if ($is_ajax) {
                echo json_encode(['status' => 'error', 'message' => $msg]);
                exit();
            }
            $message = $msg;
        }

    } else {
        $msg = "Email not found!";
        if ($is_ajax) {
            echo json_encode(['status' => 'error', 'message' => $msg]);
            exit();
        }
        $message = $msg;
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
            box-sizing: border-box; 
        }
        button {
            background-color: #002f34;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:disabled {
            background-color: #55777a;
            cursor: not-allowed;
        }
        .message{
            color:red;
            margin-bottom:10px;
            font-weight: bold;
            display: none; 
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Forgot Password</h2>

    <div id="ajax-message" class="message" style="<?php echo ($message != '') ? 'display:block;' : 'display:none;'; ?>">
        <?php echo $message; ?>
    </div>

    <form method="POST" id="forgotForm">
        <input type="email" name="email" placeholder="Enter Your Email" required>
        
        <button type="submit" id="submitBtn">
            Send OTP
        </button>
    </form>
</div>

<script>
document.getElementById('forgotForm').addEventListener('submit', function(e) {
    e.preventDefault(); 

    const submitBtn = document.getElementById('submitBtn');
    const messageBox = document.getElementById('ajax-message');
    

    const originalText = submitBtn.innerText;
    submitBtn.innerText = "Sending OTP...";
    submitBtn.disabled = true;
    messageBox.style.display = 'none'; 

    const formData = new FormData(this);

    fetch(window.location.href, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            messageBox.innerHTML = "OTP Sent! Redirecting...";
            messageBox.style.color = "green";
            messageBox.style.display = "block";
            
            window.location.href = data.redirect;
        } else {

            messageBox.innerHTML = data.message;
            messageBox.style.color = "red";
            messageBox.style.display = "block";
            
            submitBtn.innerText = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        messageBox.innerHTML = "An unexpected error occurred.";
        messageBox.style.color = "red";
        messageBox.style.display = "block";
        
        submitBtn.innerText = originalText;
        submitBtn.disabled = false;
    });
});
</script>

</body>
</html>