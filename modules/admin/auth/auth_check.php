<?php
// 1. Start the session to read the user's data
session_start();

// 2. Bring in your config so you can use BASE_URL for the redirect
require_once __DIR__ . '/../../../shared/config.php'; 

// 3. THE CHECKPOINT: Is the user the verified admin?
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    
    // If they bypass the login, kick them out immediately!
    // Note: Make sure this URL matches where your admin login page actually lives
    header("Location: ../modules/admin/auth/admin_login.php");
    exit();
    
}
?>