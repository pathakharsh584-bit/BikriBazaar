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

    if($password !== $confirm_password){
        return "Passwords do not match!";
    }

    $validation = validatePassword($password);

    if($validation !== true){
        return $validation;
    }

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $checkEmail);

    if(mysqli_num_rows($result) > 0){
        return "Email already exists!";
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(name,email,password,phone,city)
            VALUES('$name','$email','$hashedPassword','$phone','$city')";

    if(mysqli_query($conn, $sql)){
        return "Registration Successful!";
    }

    return "Registration Failed!";
}

function loginUser($conn)
{
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            header("Location: " . BASE_URL . "index.php");
            exit();

        } else {

            return "Incorrect Password!";
        }

    } else {

        return "Email not found!";
    }
}

?>