<?php

require_once __DIR__ . '/../../shared/db.php';

$message = "";

if(!isset($_GET['email'])){
    die("Invalid Access");
}

$email = trim($_GET['email']);

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $otp = trim($_POST['otp']);

    
    $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

    $sql = "SELECT * FROM users 
            WHERE email='$email' 
            AND otp='$otp'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){

        $user = mysqli_fetch_assoc($result);

        $expiry_time = strtotime($user['otp_expiry']);
        $current_time = time();

        if($current_time <= $expiry_time){
            
            $redirect_url = BASE_URL . "reset-password.php?email=" . urlencode($email);
            
            if ($is_ajax) {
                echo json_encode(['status' => 'success', 'redirect' => $redirect_url]);
                exit();
            }

            header("Location: " . $redirect_url);
            exit();

        } else {
            $msg = "OTP Expired!";
            if ($is_ajax) { echo json_encode(['status' => 'error', 'message' => $msg]); exit(); }
            $message = $msg;
        }

    } else {
        $msg = "Invalid OTP!";
        if ($is_ajax) { echo json_encode(['status' => 'error', 'message' => $msg]); exit(); }
        $message = $msg;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - BikriBazaar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #1a3fc4;
            --primary-dark: #1530a0;
            --teal: #0ea5a0;
            --teal-dark: #0b8a86;
            --grad: linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
            --surface: #f4f7ff;
            --text: #1a1a2e;
            --muted: #6b7280;
            --border: #dde4f5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--surface);
            color: var(--text);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        a { text-decoration: none; color: inherit; }

        /* OTP CARD */
        .otp-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 1rem;
        }
        .otp-card {
            max-width: 450px;
            width: 100%;
            background: #fff;
            border-radius: 28px;
            padding: 2rem;
            box-shadow: 0 20px 35px -8px rgba(26,63,196,0.1);
            border: 1px solid #989ca7;;
            text-align: center;
        }
        .otp-icon {
            width: 70px;
            height: 70px;
            background: var(--grad);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.2rem;
        }
        .otp-icon i {
            font-size: 2rem;
            color: #fff;
        }
        .otp-card h2 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        .otp-card p {
            font-size: 0.85rem;
            color: var(--muted);
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.2rem;
            border: 1px solid #989ca7;
            border-radius: 14px;
}
        }
        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-wrap i {
            position: absolute;
            left: 14px;
            color: var(--muted);
            font-size: 0.9rem;
            pointer-events: none;
        }
        .input-wrap input {
            width: 100%;
            padding: 12px 12px 12px 42px;
            border: 1.5px solid var(--border);
            border-radius: 14px;
            font-size: 0.95rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fafcff;
        }
        .input-wrap input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(26,63,196,0.1);
        }
        .auth-btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 40px;
            background: var(--grad);
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.15s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.3rem;
        }
        .auth-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .auth-btn:hover:not(:disabled) {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        .back-link {
            margin-top: 1.2rem;
        }
        .back-link a {
            color: var(--primary);
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }
        .message {
            padding: 10px;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            text-align: center;
            display: none;
        }
        .msg-error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            display: block;
        }
        .msg-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            display: block;
        }

        @media (max-width: 480px) {
            .otp-card { padding: 1.5rem; }
            .otp-icon { width: 55px; height: 55px; }
            .otp-icon i { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

<div class="otp-wrapper">
    <div class="otp-card">
        <div class="otp-icon">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <h2>Verify OTP</h2>
        <p>Enter the 6‑digit code sent to your email</p>

        <div id="ajax-message" class="message <?php echo ($message != '') ? 'msg-error' : ''; ?>">
            <?php echo $message; ?>
        </div>

        <form method="POST" id="otpForm">
            <div class="form-group">
                <div class="input-wrap">
                    
                    <input type="text" name="otp" placeholder="Enter OTP" required autocomplete="off">
                </div>
            </div>
            <button class="auth-btn" type="submit" id="submitBtn">
                <i class="fa-solid fa-check-circle"></i> Verify OTP
            </button>
        </form>

        <div class="back-link">
            <a href="forgot-password.php"><i class="fa-solid fa-arrow-left"></i> Back to Forgot Password</a>
        </div>
    </div>
</div>

<script>
document.getElementById('otpForm').addEventListener('submit', function(e) {
    e.preventDefault(); 

    const submitBtn = document.getElementById('submitBtn');
    const messageBox = document.getElementById('ajax-message');
    
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Verifying...';
    submitBtn.disabled = true;
    messageBox.style.display = 'none';
    messageBox.className = 'message';

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
            messageBox.innerHTML = '<i class="fa-solid fa-check-circle"></i> OTP Verified! Redirecting...';
            messageBox.className = "message msg-success";
            messageBox.style.display = "block";
            
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            messageBox.innerHTML = data.message;
            messageBox.className = "message msg-error";
            messageBox.style.display = "block";
            
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        messageBox.innerHTML = "An unexpected error occurred.";
        messageBox.className = "message msg-error";
        messageBox.style.display = "block";
        
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>

</body>
</html>