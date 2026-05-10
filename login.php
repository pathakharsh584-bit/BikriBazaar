<?php

session_start();

include 'includes/db.php';

$message = "";

if(isset($_GET['message'])){
    $message = $_GET['message'];
}

if(isset($_POST['login'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn,$query);

    $user = mysqli_fetch_assoc($result);

    if($user){

        if(password_verify($password, $user['password'])){

            $_SESSION['user_id'] = $user['id'];

            $_SESSION['user_name'] = $user['name'];

            header("Location: dashboard.php");

            exit();

        } else {

            $message = "Wrong Password";

        }

    } else {

        $message = "User Not Found";

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>
<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow p-4">

                <h2 class="text-center mb-4">
                    Login
                </h2>

                <?php if($message != "") { ?>

                    <div class="alert alert-danger">

                        <?php echo $message; ?>

                    </div>

                <?php } ?>

                <form method="POST">

                    <input type="email"
                           name="email"
                           class="form-control mb-3"
                           placeholder="Email"
                           required>

                    <input type="password"
                           name="password"
                           class="form-control mb-3"
                           placeholder="Password"
                           required>

                    <button type="submit"
                            name="login"
                            class="btn btn-dark w-100">

                        Login

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>