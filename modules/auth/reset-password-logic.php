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

    $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

    if ($password !== $confirm_password) {
        $msg = "Passwords do not match!";
        if ($is_ajax) { echo json_encode(['status' => 'error', 'message' => $msg]); exit(); }
        $message = $msg;
    } else {
        $validation = validatePassword($password);

        if ($validation !== true) {
            if ($is_ajax) { echo json_encode(['status' => 'error', 'message' => $validation]); exit(); }
            $message = $validation;
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $update = "UPDATE users 
                       SET password='$hashedPassword', 
                           otp=NULL, 
                           otp_expiry=NULL 
                       WHERE email='$email'";

            if (mysqli_query($conn, $update)) {
                $redirect_url = BASE_URL . "login.php?message=" . urlencode("Password updated successfully!");
                
                if ($is_ajax) {
                    echo json_encode(['status' => 'success', 'redirect' => $redirect_url]);
                    exit();
                }

                header("Location: " . $redirect_url);
                exit();
            } else {
                $msg = "Failed to update password.";
                if ($is_ajax) { echo json_encode(['status' => 'error', 'message' => $msg]); exit(); }
                $message = $msg;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - BikriBazaar</title>
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
            --border: #e2e8f0;
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

        /* Main wrapper – centers content and pushes footer down */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        /* Logo */
        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 1.8rem;
        }
        .logo-wrap span { color: var(--teal); }
        .logo-wrap img {
            height: 40px;
            width: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }

        /* Card */
        .card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 18px;
            border: 1px solid #a1aebf;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .card-header {
            padding: 1.6rem 1.8rem 1.3rem;
            border-bottom: 1px solid var(--border);
            text-align: center;
        }
        .icon-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #eef2ff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.9rem;
        }
        .icon-circle i {
            font-size: 1.2rem;
            color: var(--primary);
        }
        .card-header h1 {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 0.2rem;
        }
        .card-header p {
            font-size: 0.81rem;
            color: var(--muted);
            line-height: 1.5;
        }

        /* Body */
        .card-body {
            padding: 1.4rem 1.8rem 1.6rem;
        }

        /* Alert */
        .alert {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 10px 13px;
            border-radius: 9px;
            font-size: 0.83rem;
            font-weight: 600;
            margin-bottom: 1.1rem;
        }
        .alert i { font-size: 0.83rem; flex-shrink: 0; }
        .alert-error   { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; display: flex; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; display: flex; }
        .alert-hidden  { display: none !important; }

        /* Form */
        .form-group { margin-bottom: 1rem; }
        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.38rem;
        }
        .inp {
            position: relative;
        }
        .inp i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.8rem;
            pointer-events: none;
        }
        .inp input {
            width: 100%;
            padding: 11px 11px 11px 33px;
            border: 1.5px solid #a1aebf;
            border-radius: 9px;
            font-size: 0.9rem;
            font-family: inherit;
            color: var(--text);
            background: #f8faff;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
        }
        .inp input:focus {
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26,63,196,0.08);
        }

        /* Gradient button */
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

        /* Spinner */
        .spinner {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #fff;
            animation: spin 0.7s linear infinite;
            display: none;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Back link */
        .back-link {
            text-align: center;
            margin-top: 1.1rem;
            font-size: 0.8rem;
            color: var(--muted);
        }
        .back-link a {
            color: var(--primary);
            font-weight: 600;
        }
        .back-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .card-header { padding: 1.2rem 1rem; }
            .card-body { padding: 1.2rem 1rem 1.4rem; }
        }
    </style>
</head>
<body>

<div class="main-wrapper">
    <!-- Logo -->
    <a href="index.php" class="logo-wrap">
        <img src="assets/images/logo.png" alt="BikriBazaar" onerror="this.style.display='none'">
        Bikri<span>Bazaar</span>
    </a>

    <div class="card">
        <div class="card-header">
            <div class="icon-circle">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1>Reset Password</h1>
            <p>Enter your new password below</p>
        </div>

        <div class="card-body">
            <div id="ajax-message" class="alert alert-hidden">
                <i class="fa-solid fa-circle-exclamation" id="alert-icon"></i>
                <span id="alert-text"></span>
            </div>

            <?php if($message != ''): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function(){
                    const box = document.getElementById('ajax-message');
                    box.classList.remove('alert-hidden');
                    box.classList.add('alert-error');
                    document.getElementById('alert-text').innerText = <?php echo json_encode($message); ?>;
                });
            </script>
            <?php endif; ?>

            <form method="POST" id="resetForm">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <div class="inp">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Enter new password" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="inp">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                    </div>
                </div>

                <button type="submit" class="auth-btn" id="submitBtn">
                    <div class="spinner" id="spinner"></div>
                    <i class="fa-solid fa-check-circle" id="btn-icon"></i>
                    <span id="btn-text">Update Password</span>
                </button>
            </form>

            <div class="back-link">
                <a href="forgot-password.php"><i class="fa-solid fa-arrow-left"></i> Back to Forgot Password</a>
            </div>
        </div>
    </div>
</div>

<!-- SHARED FOOTER -->
<?php include __DIR__ . '/../../shared/components/footer.php'; ?>

<script>
document.getElementById('resetForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn  = document.getElementById('submitBtn');
    const spinner    = document.getElementById('spinner');
    const btnIcon    = document.getElementById('btn-icon');
    const btnText    = document.getElementById('btn-text');
    const messageBox = document.getElementById('ajax-message');

    // loading state
    submitBtn.disabled  = true;
    spinner.style.display  = 'inline-block';
    btnIcon.style.display  = 'none';
    btnText.textContent    = 'Updating...';
    messageBox.className   = 'alert alert-hidden';

    const formData = new FormData(this);

    fetch(window.location.href, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            messageBox.className = 'alert alert-success';
            document.getElementById('alert-icon').className = 'fa-solid fa-circle-check';
            document.getElementById('alert-text').textContent = 'Password updated! Redirecting...';
            btnText.textContent = 'Success!';
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            messageBox.className = 'alert alert-error';
            document.getElementById('alert-icon').className = 'fa-solid fa-circle-exclamation';
            document.getElementById('alert-text').textContent = data.message;
            // reset button
            submitBtn.disabled    = false;
            spinner.style.display = 'none';
            btnIcon.style.display = 'inline';
            btnText.textContent   = 'Update Password';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        messageBox.className = 'alert alert-error';
        document.getElementById('alert-icon').className = 'fa-solid fa-circle-exclamation';
        document.getElementById('alert-text').textContent = 'An unexpected error occurred. Please try again.';
        submitBtn.disabled    = false;
        spinner.style.display = 'none';
        btnIcon.style.display = 'inline';
        btnText.textContent   = 'Update Password';
    });
});
</script>

</body>
</html>