<?php

session_start(); 

require_once __DIR__ . '/../../shared/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once __DIR__ . '/auth_actions.php';
    $message = registerUser($conn);
}
$hide_register = true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BikriBazaar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #1a3fc4;
            --teal: #0ea5a0;
            --grad: linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
            --surface: #f4f7ff;
            --text: #1a1a2e;
            --muted: #6b7280;
            --border: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: var(--surface);
            color: var(--text);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        a {
            text-decoration: none;
        }

        /* ── NAVBAR (same as login page) ── */
        .navbar {
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            height: 66px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
        }
        .logo span { color: var(--teal); }
        .logo-img {
            height: 44px; width: 44px;
            border-radius: 50%; object-fit: cover;
            border: 2px solid var(--primary);
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-left: auto;
        }
        .nav-links a {
            position: relative;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 0;
            height: 3px;
            background: #4338ca;
            border-radius: 10px;
            transition: 0.3s;
        }
        .nav-links a:hover::after {
            width: 100%;
        }
        /* Styles for normal links (Home, Login) */
        .nav-links a:not(.btn-login):not(.btn-register) {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text);
            padding: 0.4rem 0.7rem;
            border-radius: 6px;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .nav-links a:not(.btn-login):not(.btn-register):hover {
            background: var(--surface);
        }
        /* Login button (same gradient as Register) */
        .btn-login {
            background: var(--grad);
            color: #fff !important;
            font-weight: 700;
            border-radius: 8px;
            padding: 0.45rem 1.2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: opacity 0.2s;
        }
        .btn-login:hover {
            opacity: 0.9;
        }
        /* Register button (identical to Login) – added for consistency */
        .btn-register {
            background: var(--grad);
            color: #fff !important;
            font-weight: 700;
            border-radius: 8px;
            padding: 0.45rem 1.2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: opacity 0.2s;
        }
        .btn-register:hover {
            opacity: 0.9;
        }

        /* ── MAIN LAYOUT (split) ── */
        .auth-container {
            display: flex;
            flex: 1;
            min-height: calc(100vh - 66px);
        }

        /* LEFT PANEL – logo left of BIKRIBAZAAR */
        .left-panel {
            flex: 1;
            background: var(--grad);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .left-content {
            max-width: 380px;
            color: #fff;
            text-align: left;
        }
        .left-logo-wrapper {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 1rem;
        }
        .left-logo-img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.4);
        }
        .left-logo-text {
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #fff;
        }
        .left-logo-text span {
            color: rgba(255,255,255,0.75);
        }
        .left-content p {
            font-size: 1rem;
            opacity: 0.9;
            line-height: 1.4;
            margin-top: 0.25rem;
        }

        /* RIGHT PANEL – with labels and icons */
        .auth-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            background: var(--surface);
        }
        .auth-card {
            width: 100%;
            max-width: 500px;
            background: #fff;
            padding: 2rem;
            border-radius: 24px;
            box-shadow: 0 20px 35px -12px rgba(0,0,0,0.08);
            border: 2px solid #d4d4d8;
        }
        .auth-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .auth-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.3rem;
        }
        .auth-header p {
            font-size: 0.85rem;
            color: var(--muted);
        }

        /* Form group with label and icon input */
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            font-size: 0.83rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 0.45rem;
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
            padding: 12px 12px 12px 40px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-size: 0.9rem;
            font-family: inherit;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fafcff;
             border: 1px solid #b6b8c3;
        }
        .input-wrap input:focus {
            border-color: var(--teal);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(14,165,160,0.10);
        }

        .password-rules {
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: -0.3rem;
            margin-bottom: 1rem;
            padding-left: 0.5rem;
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
            transition: opacity 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        .auth-btn:hover {
            opacity: 0.9;
        }
        .auth-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--muted);
        }
        .login-link a {
            color: var(--primary);
            font-weight: 600;
        }

        .message {
            padding: 10px;
            border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 0.8rem;
            text-align: center;
            display: none;
        }
        .msg-error {
            background: #fee2e2;
            color: #b91c1c;
            display: block;
        }
        .msg-success {
            background: #d1fae5;
            color: #065f46;
            display: block;
        }

        @media (max-width: 800px) {
            .auth-container {
                flex-direction: column;
            }
            .left-panel {
                padding: 3rem 1rem;
                min-height: auto;
            }
            .left-logo-text {
                font-size: 1.6rem;
            }
            .left-logo-img {
                width: 50px;
                height: 50px;
            }
        }
        @media (max-width: 480px) {
            .auth-card {
                padding: 1.5rem;
            }
            .navbar {
                padding: 0 1rem;
            }
            .logo {
                font-size: 1.2rem;
            }
            .logo-img {
                height: 36px;
                width: 36px;
            }
            .left-logo-text {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>

<!-- ==================== SHARED NAVBAR ==================== -->
<?php include __DIR__ . '/../../shared/components/navbar.php'; ?>
<!-- ======================================================= -->

<!-- MAIN SPLIT LAYOUT -->
<div class="auth-container">
    <!-- LEFT PANEL: logo left of BIKRIBAZAAR + tagline -->
    <div class="left-panel">
        <div class="left-content">
            <div class="left-logo-wrapper">
                <img src="assets/images/logo.png" alt="BikriBazaar Logo" class="left-logo-img"
                     onerror="this.style.display='none'">
                <div class="left-logo-text">BIKRI<span>BAZAAR</span></div>
            </div>
            <p>Access your marketplace — buy, sell, and explore listings only at BikriBazaar</p>
        </div>
    </div>

    <!-- RIGHT PANEL: registration form with labels and icons -->
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Create Account</h1>
                <p>Fill in your details below</p>
            </div>

            <div id="ajax-message" class="message <?php echo ($message != "") ? 'msg-error' : ''; ?>">
                <?php echo $message; ?>
            </div>

            <form method="POST" id="registerForm">
                <!-- Full Name -->
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" id="name" name="name" placeholder="Your full name" required>
                    </div>
                </div>
                <!-- Email Address -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="you@example.com" required>
                    </div>
                </div>
                <!-- Phone Number -->
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-phone"></i>
                        <input type="tel" id="phone" name="phone" placeholder="Your phone number" required>
                    </div>
                </div>
                <!-- City -->
                <div class="form-group">
                    <label for="city">City</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-location-dot"></i>
                        <input type="text" id="city" name="city" placeholder="Your city" required>
                    </div>
                </div>
                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                    </div>
                </div>
                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                </div>

                <div class="password-rules">
                    <i class="fa-solid fa-info-circle"></i> Password must contain uppercase, lowercase, number, special character (min. 8 chars)
                </div>

                <button class="auth-btn" id="submitBtn" type="submit">
                    <i class="fa-solid fa-user-check"></i> Sign Up
                </button>
            </form>

            <div class="login-link">
                Already have an account? <a href="login.php">Log in</a>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = document.getElementById('submitBtn');
    const messageBox = document.getElementById('ajax-message');
    const originalText = submitBtn.innerText;
    submitBtn.innerText = "Creating account...";
    submitBtn.disabled = true;

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
            messageBox.innerHTML = data.message;
            messageBox.className = "message msg-success";
            messageBox.style.display = "block";
            setTimeout(() => {
                window.location.href = data.redirect + "?message=" + encodeURIComponent(data.message);
            }, 1500);
        } else {
            messageBox.innerHTML = data.message;
            messageBox.className = "message msg-error";
            messageBox.style.display = "block";
            submitBtn.innerText = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        messageBox.innerHTML = "An unexpected error occurred.";
        messageBox.className = "message msg-error";
        messageBox.style.display = "block";
        submitBtn.innerText = originalText;
        submitBtn.disabled = false;
    });
});
</script>
</body>
</html>