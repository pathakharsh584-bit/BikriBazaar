<?php
session_start(;

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

    <link
        rel="stylesheet"
        href="http://localhost/BikriBazaar/public/assets/css/style.css"
    >

    <style>

        .auth-wrapper{
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:40px 20px;
        }

        .auth-card{
            width:100%;
            max-width:470px;
            background:rgba(255,255,255,0.75);
            backdrop-filter:blur(15px);
            padding:40px;
            border-radius:30px;
            box-shadow:0 15px 40px rgba(0,0,0,0.12);
        }

        .auth-title{
            text-align:center;
            font-size:40px;
            margin-bottom:10px;
            font-weight:800;
            color:#312e81;
        }

        .auth-subtitle{
            text-align:center;
            color:#6b7280;
            margin-bottom:30px;
        }

        .form-group{
            margin-bottom:20px;
        }

</html>