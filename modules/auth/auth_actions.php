<?php

function validatePassword($password)
{
    if(strlen($password) < 8){
        return "Password must be at least 8 characters.";
    }

    if(preg_match('/\s/', $password)){
        return "Password must not contain spaces.";
    }

    if(!preg_match('/[A-Z]/', $password)){
        return "Password must contain uppercase letter.";
    }

    if(!preg_match('/[a-z]/', $password)){
        return "Password must contain lowercase letter.";
    }

    if(!preg_match('/[0-9]/', $password)){
        return "Password must contain number.";
    }

    if(!preg_match('/[^A-Za-z0-9]/', $password)){
        return "Password must contain special character.";
    }

    return true;
}

function registerUser($conn)
{
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $phone = trim($_POST['phone']);
    $city = trim($_POST['city']);

    $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

    if($password !== $confirm_password){
        $msg = "Passwords do not match!";
        if($is_ajax) { echo json_encode(['status' => 'error', 'message' => $msg]); exit; }
        return $msg;
    }

    $validation = validatePassword($password);
    if($validation !== true){
        if($is_ajax) { echo json_encode(['status' => 'error', 'message' => $validation]); exit; }
        return $validation;
    }

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $checkEmail);
    if(mysqli_num_rows($result) > 0){
        $msg = "Email already exists!";
        if($is_ajax) { echo json_encode(['status' => 'error', 'message' => $msg]); exit; }
        return $msg;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users(name,email,password,phone,city) 
            VALUES('$name','$email','$hashedPassword','$phone','$city')";

    if(mysqli_query($conn, $sql)){
        $msg = "Registration Successful!";
        if($is_ajax) { 
            // After registration, we usually want them to log in
            echo json_encode(['status' => 'success', 'message' => $msg, 'redirect' => BASE_URL . "login.php"]); 
            exit; 
        }
        return $msg;
    }

    $msg = "Registration Failed!";
    if($is_ajax) { echo json_encode(['status' => 'error', 'message' => $msg]); exit; }
    return $msg;
}

function loginUser($conn)
{
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    
    // Check if the request is an AJAX call from JavaScript
    $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

    if(mysqli_num_rows($result) == 1){

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            if ($is_ajax) {
                // Send success JSON and exit immediately
                echo json_encode(['status' => 'success', 'redirect' => BASE_URL . "index.php"]);
                exit();
            }

            // Fallback for non-AJAX
            header("Location: " . BASE_URL . "index.php");
            exit();

        } else {
            if ($is_ajax) {
                echo json_encode(['status' => 'error', 'message' => 'Incorrect Password!']);
                exit();
            }
            return "Incorrect Password!";
        }

    } else {
        if ($is_ajax) {
            echo json_encode(['status' => 'error', 'message' => 'Email not found!']);
            exit();
        }
        return "Email not found!";
    }
}
?>