<?php

function registerUser($conn)
{
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone = trim($_POST['phone']);
    $city = trim($_POST['city']);

    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $emailResult = mysqli_query($conn, $checkEmail);

    if(mysqli_num_rows($emailResult) > 0){
        return "Email already exists!";
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password, phone, city)
            VALUES ('$name', '$email', '$hashedPassword', '$phone', '$city')";

    if(mysqli_query($conn, $sql)){
        return "Registration Successful!";
    } else {
        return "Registration Failed!";
    }
}

function loginUser($conn)
{
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = '$email'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1){

        $user = mysqli_fetch_assoc($result);

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            header("Location: /BikriBazaar/public/index.php");
            exit();

        } else {

            return "Incorrect Password!";
        }

    } else {

        return "Email not found!";
    }
}

?>