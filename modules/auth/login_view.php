<?php

session_start();
require_once __DIR__ . '/../../shared/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once __DIR__ . '/auth_actions.php';
    $message = loginUser($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BikriBazaar</title>
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

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--surface);
            color: var(--text);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        a { text-decoration: none; color: inherit; }


        .navbar {
            background: #fff;
            box-shadow: 0 2px 12px rgba(26,63,196,0.10);
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
            gap: 0.8rem;
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

        .nav-links a:not(.btn-register) {
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
        .nav-links a:not(.btn-register):hover {
            background: var(--surface);
        }
      
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

        .auth-container {
            display: flex;
            flex: 1;
            min-height: calc(100vh - 66px);
        }

        .left-panel {
            flex: 1;
            background: var(--grad);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 50px;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            width: 350px; height: 350px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            top: -80px; left: -80px;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 250px; height: 250px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            bottom: -60px; right: -60px;
        }
        .left-content { position: relative; z-index: 1; text-align: center; color: #fff; }
        .left-logo {
            display: flex; align-items: center;
            justify-content: center; gap: 12px;
            margin-bottom: 2.5rem;
        }
        .left-logo img {
            height: 60px; width: 60px;
            border-radius: 50%; object-fit: cover;
            border: 3px solid rgba(255,255,255,0.4);
        }
        .left-logo-text {
            font-size: 2rem; font-weight: 800; color: #fff;
        }
        .left-logo-text span { color: rgba(255,255,255,0.75); }

        .left-content h2 {
            font-size: 2.2rem; font-weight: 800;
            line-height: 1.25; margin-bottom: 1rem;
        }
        .left-content p {
            font-size: 1rem; color: rgba(255,255,255,0.78);
            max-width: 320px; line-height: 1.6; margin: 0 auto;
        }
        .features-list {
            margin-top: 2.5rem;
            display: flex; flex-direction: column; gap: 1rem;
            text-align: left;
        }
        .feature-item {
            display: flex; align-items: center; gap: 0.9rem;
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 0.75rem 1.1rem;
            color: #fff; font-size: 0.9rem; font-weight: 500;
        }
        .feature-item i {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.85);
            width: 20px; text-align: center;
        }

     
        .auth-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            background: var(--surface);
        }

        .auth-card {
            width: 100%;
            max-width: 460px;
            background: #fff;
            padding: 44px 40px;
            border-radius: 24px;
            box-shadow: 0 20px 50px -10px rgba(26,63,196,0.14);
            border: 2px solid #d4d4d8;
        }

        .auth-header { margin-bottom: 2rem; }
        .auth-header h1 {
            font-size: 1.9rem; font-weight: 800;
            color: var(--text); margin-bottom: 0.3rem;
        }
        .auth-header p {
            font-size: 0.92rem; color: var(--muted);
        }

        .form-group {
            margin-bottom: 1.1rem;
            position: relative;
        }
        .form-group label {
            display: block;
            font-size: 0.83rem; font-weight: 600;
            color: var(--text); margin-bottom: 0.45rem;
        }
        .input-wrap {
            position: relative; display: flex; align-items: center;  
        }
        .input-wrap i {
            position: absolute; left: 14px;
            color: var(--muted); font-size: 0.9rem;
            pointer-events: none;
        }
        .input-wrap input {
            width: 100%;
            padding: 13px 14px 13px 40px;
          border: 1.5px solid #868b95;
            border-radius: 12px;
            outline: none;
            font-size: 0.95rem;
            font-family: inherit;
            color: var(--text);
            background: #fafbff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-wrap input:focus {
            border-color: var(--teal);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(14,165,160,0.10);
        }
        .input-wrap .toggle-pwd {
            position: absolute; right: 14px;
            background: none; border: none; cursor: pointer;
            color: var(--muted); font-size: 0.9rem;
            transition: color 0.2s;
        }
        .input-wrap .toggle-pwd:hover { color: var(--primary); }

        /* FORGOT */
        .forgot-row {
            display: flex; justify-content: flex-end;
            margin-top: -0.4rem; margin-bottom: 1.3rem;
        }
        .forgot-row a {
            font-size: 0.82rem; color: var(--primary);
            font-weight: 600;
        }
        .forgot-row a:hover { text-decoration: underline; }

     
        .auth-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: var(--grad);
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            display: flex; align-items: center;
            justify-content: center; gap: 0.5rem;
            transition: opacity 0.2s, transform 0.15s;
        }
        .auth-btn:hover { opacity: 0.92; transform: translateY(-2px); }

      
        .divider {
            display: flex; align-items: center;
            gap: 0.75rem; margin: 1.4rem 0;
            font-size: 0.8rem; color: var(--muted);
        }
        .divider::before, .divider::after {
            content: ''; flex: 1;
            height: 1px; background: var(--border);
        }

       
        .register-link {
            text-align: center;
            font-size: 0.88rem; color: var(--muted);
        }
        .register-link a {
            color: var(--primary); font-weight: 700;
        }
        .register-link a:hover { text-decoration: underline; }

     
        .message {
            display: flex; align-items: center; gap: 0.6rem;
            background: #fee2e2; color: #b91c1c;
            padding: 11px 14px; border-radius: 10px;
            margin-bottom: 1.2rem; font-size: 0.88rem;
            border: 1px solid #fecaca;
        }
        .message i { flex-shrink: 0; }

      
        .message.success {
            background: #d1fae5; color: #065f46;
            border-color: #a7f3d0;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .left-panel { display: none; }
            .auth-wrapper { background: var(--surface); }
        }
        @media (max-width: 480px) {
            .auth-card { padding: 32px 22px; border-radius: 18px; }
            .auth-header h1 { font-size: 1.6rem; }
            .navbar { padding: 0 1rem; }
        }
    </style>
</head>

<body>


<?php include __DIR__ . '/../../shared/components/navbar.php'; ?>


<!-- MAIN -->
<div class="auth-container">

    <!-- LEFT PANEL -->
    <div class="left-panel">
        <div class="left-content">
            <div class="left-logo">
                <img src="assets/images/logo.png" alt="Logo" onerror="this.style.display='none'">
                <div class="left-logo-text">Bikri<span>Bazaar</span></div>
            </div>

            <h2>Welcome Back!</h2>
            <p>Access your marketplace — buy, sell, and explore listings only at BikriBazaar</p>

            <div class="features-list">
                <div class="feature-item">
                    <i class="fa-solid fa-tag"></i>
                    Post and manage your ads easily
                </div>
                <div class="feature-item">
                    <i class="fa-solid fa-message"></i>
                    Chat with buyers and sellers
                </div>
                <div class="feature-item">
                    <i class="fa-solid fa-heart"></i>
                    Save your favourite listings
                </div>
                
            </div>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="auth-wrapper">
        <div class="auth-card">

            <div class="auth-header">
                <h1>Sign In</h1>
                <p>Enter your credentials to continue</p>
            </div>

            <?php if($message != ""): ?>
                <div class="message">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" id="email" name="email"
                               placeholder="you@example.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock"></i>
                        <input type="password" id="password" name="password"
                               placeholder="Enter your password" required>
                        <button type="button" class="toggle-pwd"
                                onclick="togglePwd(this)"
                                title="Show / hide password">
                          
                        </button>
                    </div>
                </div>

                <div class="forgot-row">
                    <a href="forgot-password.php">Forgot password?</a>
                </div>

                <button class="auth-btn" type="submit">
                    <i class="fa-solid fa-right-to-bracket"></i> Sign In
                </button>

            </form>

            <div class="divider">or</div>

            <div class="register-link">
                Don't have an account? <a href="register.php">Create one free</a>
            </div>

        </div>
    </div>

</div>

<script>
    function togglePwd(btn) {
        const input = btn.closest('.input-wrap').querySelector('input');
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

</body>
</html>