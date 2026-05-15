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
            margin: 0;
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
            padding: 10px;
            border-radius: 5px;
            margin-bottom:10px;
            font-weight:bold;
            text-align: center;
            display: none; 
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Reset Password</h2>

    <div id="ajax-message" class="message" style="<?php echo ($message != '') ? 'display:block; background:#fee2e2; color:#b91c1c;' : 'display:none;'; ?>">
        <?php echo $message; ?>
    </div>

    <form method="POST" id="resetForm">
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
        
        <button type="submit" id="submitBtn">
            Update Password
        </button>
    </form>
</div>

<script>
document.getElementById('resetForm').addEventListener('submit', function(e) {
    e.preventDefault(); 

    const submitBtn = document.getElementById('submitBtn');
    const messageBox = document.getElementById('ajax-message');
    

    const originalText = submitBtn.innerText;
    submitBtn.innerText = "Updating...";
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

            messageBox.innerHTML = "Success! Redirecting to login...";
            messageBox.style.background = "#dcfce7";
            messageBox.style.color = "#15803d";
            messageBox.style.display = "block";
            
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {

            messageBox.innerHTML = data.message;
            messageBox.style.background = "#fee2e2";
            messageBox.style.color = "#b91c1c";
            messageBox.style.display = "block";
            
            submitBtn.innerText = originalText;
            submitBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        messageBox.innerHTML = "An unexpected error occurred.";
        messageBox.style.background = "#fee2e2";
        messageBox.style.color = "#b91c1c";
        messageBox.style.display = "block";
        
        submitBtn.innerText = originalText;
        submitBtn.disabled = false;
    });
});
</script>

</body>
</html>