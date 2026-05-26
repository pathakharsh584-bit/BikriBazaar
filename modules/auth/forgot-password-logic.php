<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../shared/config.php';
require_once __DIR__ . '/../../shared/db.php';

$message = "";

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $email = trim($_POST['email']);

    $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

    $check = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check);

    if(mysqli_num_rows($result) == 1){

        $otp = rand(100000, 999999);

        $expiry = date(
            'Y-m-d H:i:s',
            strtotime('+10 minutes')
        );

        $update = "
            UPDATE users 
            SET otp='$otp',
                otp_expiry='$expiry'
            WHERE email='$email'
        ";

        mysqli_query($conn, $update);

        $mail = new PHPMailer(true);

        try {

    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USERNAME'];
    $mail->Password = $_ENV['SMTP_PASSWORD'];

    $encryption = $_ENV['SMTP_SECURE'];

    if ($encryption === 'tls') {

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    } elseif ($encryption === 'ssl') {

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

    } else {

        $mail->SMTPSecure = false;
    }

    $mail->Port = $_ENV['SMTP_PORT'];

    $mail->setFrom(
        $_ENV['SMTP_USERNAME'],
        'BikriBazaar'
    );

    $mail->addAddress($email);

    $mail->isHTML(true);

    $mail->Subject = 'BikriBazaar Password Reset OTP';

            $mail->Body = "

                <div style='
                    font-family:Segoe UI,sans-serif;
                    padding:20px;
                '>

                    <h2 style='color:#1a3fc4;'>
                        BikriBazaar OTP Verification
                    </h2>

                    <p>Your password reset OTP is:</p>

                    <h1 style='
                        color:#0ea5a0;
                        letter-spacing:4px;
                    '>
                        $otp
                    </h1>

                    <p>
                        This OTP will expire in 10 minutes.
                    </p>

                </div>

            ";

            $mail->send();

            $redirect_url =
                BASE_URL .
                "verify-otp.php?email=" .
                urlencode($email);

            if ($is_ajax) {

                echo json_encode([
                    'status' => 'success',
                    'redirect' => $redirect_url
                ]);

                exit();
            }

            header("Location: " . $redirect_url);

            exit();

        } catch (Exception $e) {

            $msg = "Mailer Error: " . $mail->ErrorInfo;

            if ($is_ajax) {

                echo json_encode([
                    'status' => 'error',
                    'message' => $msg
                ]);

                exit();
            }

            $message = $msg;
        }

    } else {

        $msg = "No account found with that email address.";

        if ($is_ajax) {

            echo json_encode([
                'status' => 'error',
                'message' => $msg
            ]);

            exit();
        }

        $message = $msg;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - BikriBazaar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary:      #1a3fc4;
            --primary-dark: #1530a0;
            --teal:         #0ea5a0;
            --text:         #1a1a2e;
            --muted:        #6b7280;
            --border:       #e2e8f0;
            --surface:      #eef2ff;
            --grad: linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--surface);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a { text-decoration: none; color: inherit; }

        /* Main content wrapper – centers the form and pushes footer down */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        /* Logo & card container (no extra margin) */
        .logo-wrap {
            display: flex; align-items: center; gap: 9px;
            font-size: 1.4rem; font-weight: 800; color: var(--primary);
            margin-bottom: 1.8rem;
        }
        .logo-wrap span { color: var(--teal); }
        .logo-wrap img {
            height: 40px; width: 40px; border-radius: 50%;
            object-fit: cover; border: 2px solid var(--primary);
        }

        /* Card */
        .card {
            width: 100%; max-width: 420px;
            background: #fff;
            border-radius: 18px;
            border: 1px solid #afafaf;
            box-shadow: 0 4px 24px #a5a5a5;
            overflow: hidden;
            
        }

        .card-header {
            padding: 1.6rem 1.8rem 1.3rem;
            border-bottom: 1px solid var(--border);
            text-align: center;
        }
        .icon-circle {
            width: 52px; height: 52px; border-radius: 50%;
            background: #eef2ff;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 0.9rem;
        }
        .icon-circle i { font-size: 1.2rem; color: var(--primary); }

        .card-header h1 {
            font-size: 1.2rem; font-weight: 800; color: var(--text);
            margin-bottom: 0.2rem;
        }
        .card-header p {
            font-size: 0.81rem; color: var(--muted); line-height: 1.5;
        }

        .card-body { padding: 1.4rem 1.8rem 1.6rem; }

        /* Alert */
        .alert {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 10px 13px; border-radius: 9px;
            font-size: 0.83rem; font-weight: 600;
            margin-bottom: 1.1rem;
        }
        .alert i { font-size: 0.83rem; flex-shrink: 0; }
        .alert-error   { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; display: flex; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; display: flex; }
        .alert-hidden  { display: none !important; }

        .form-group { margin-bottom: 1rem; }
        .form-group label {
            display: block; font-size: 0.8rem; font-weight: 600;
            color: var(--text); margin-bottom: 0.38rem;
        }
        .inp { position: relative; }
        .inp i {
            position: absolute; left: 11px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8; font-size: 0.8rem; pointer-events: none;
        }
        .inp input {
            width: 100%;
            padding: 11px 11px 11px 33px;
            border: 1.5px solid #a1aebf;
            border-radius: 9px;
            font-size: 0.9rem; font-family: inherit;
            color: var(--text); background: #f8faff;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
        }
        .inp input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26,63,196,0.08);
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
        .auth-btn:hover:not(:disabled) {
            opacity: 0.92;
            transform: translateY(-2px);
        }
        .auth-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .spinner {
            width: 15px; height: 15px; border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #fff;
            animation: spin 0.7s linear infinite;
            display: none;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .back-link {
            text-align: center; margin-top: 1.1rem;
            font-size: 0.8rem; color: var(--muted);
        }
        .back-link a {
            color: var(--primary); font-weight: 600;
        }
        .back-link a:hover { text-decoration: underline; }

        .hint {
            display: flex; align-items: center; gap: 0.3rem;
            font-size: 0.72rem; color: var(--muted); margin-top: 0.25rem;
        }
        .hint i { color: var(--teal); font-size: 0.68rem; }

        /* Ensure footer sticks */
        .footer {
            margin-top: auto;
        }
    </style>
</head>
<body>

<!-- MAIN WRAPPER (centers the content and pushes footer) -->
<div class="main-wrapper">
    <a href="index.php" class="logo-wrap">
        <img src="assets/images/logo.png" alt="BikriBazaar" onerror="this.style.display='none'">
        Bikri<span>Bazaar</span>
    </a>

    <div class="card">
        <div class="card-header">
            <div class="icon-circle">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1>Forgot Password?</h1>
            <p>Enter your email and let’s get you connected again.</p>
        </div>

        <div class="card-body">
            <div id="ajax-message" class="alert alert-hidden">
                <i class="fa-solid fa-circle-exclamation" id="alert-icon"></i>
                <span id="alert-text"><?php echo htmlspecialchars($message); ?></span>
            </div>

            <?php if($message != ''): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function(){
                    const box = document.getElementById('ajax-message');
                    box.classList.remove('alert-hidden');
                    box.classList.add('alert-error');
                });
            </script>
            <?php endif; ?>

            <form method="POST" id="forgotForm">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="inp">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="email"
                               placeholder="you@example.com" required>
                    </div>
                </div>

                <button type="submit" class="auth-btn" id="submitBtn">
                    <div class="spinner" id="spinner"></div>
                    <i class="fa-solid fa-paper-plane" id="btn-icon"></i>
                    <span id="btn-text">Send OTP</span>
                </button>
            </form>

            <div class="back-link">
                Remember your password? <a href="login.php">Sign in</a>
            </div>
        </div>
    </div>
</div>

<!-- SHARED FOOTER -->
<?php include __DIR__ . '/../../shared/components/footer.php'; ?>

<script>
document.getElementById('forgotForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn  = document.getElementById('submitBtn');
    const spinner    = document.getElementById('spinner');
    const btnIcon    = document.getElementById('btn-icon');
    const btnText    = document.getElementById('btn-text');
    const messageBox = document.getElementById('ajax-message');

    submitBtn.disabled  = true;
    spinner.style.display  = 'block';
    btnIcon.style.display  = 'none';
    btnText.textContent    = 'Sending OTP...';
    messageBox.className   = 'alert alert-hidden';

    const formData = new FormData(this);

    fetch(window.location.href, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'success') {
            messageBox.className = 'alert alert-success';
            document.getElementById('alert-icon').className = 'fa-solid fa-circle-check';
            document.getElementById('alert-text').textContent = 'OTP sent! Redirecting...';
            btnText.textContent = 'OTP Sent!';
            setTimeout(() => { window.location.href = data.redirect; }, 1200);
        } else {
            messageBox.className = 'alert alert-error';
            document.getElementById('alert-icon').className = 'fa-solid fa-circle-exclamation';
            document.getElementById('alert-text').textContent = data.message;
            submitBtn.disabled    = false;
            spinner.style.display = 'none';
            btnIcon.style.display = 'inline';
            btnText.textContent   = 'Send OTP';
        }
    })
    .catch(() => {
        messageBox.className = 'alert alert-error';
        document.getElementById('alert-icon').className = 'fa-solid fa-circle-exclamation';
        document.getElementById('alert-text').textContent = 'An unexpected error occurred. Please try again.';
        submitBtn.disabled    = false;
        spinner.style.display = 'none';
        btnIcon.style.display = 'inline';
        btnText.textContent   = 'Send OTP';
    });
});
</script>

</body>
</html>