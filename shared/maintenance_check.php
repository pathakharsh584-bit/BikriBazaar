<?php

require_once __DIR__ . '/db.php';

$maintenance_query = mysqli_query(

    $conn,

    "SELECT maintenance_mode
     FROM settings
     LIMIT 1"

);

$maintenance = mysqli_fetch_assoc(
    $maintenance_query
);

$is_maintenance =
    $maintenance['maintenance_mode'] ?? 'off';

/* CURRENT PAGE */

$current_page =
    $_SERVER['PHP_SELF'];

/* ALLOW ADMIN */

$is_admin =
    strpos($current_page, 'admin') !== false;

/* ALLOW AJAX */

$is_ajax =

    !empty($_SERVER['HTTP_X_REQUESTED_WITH'])

    &&

    strtolower(
        $_SERVER['HTTP_X_REQUESTED_WITH']
    ) === 'xmlhttprequest';

/* BLOCK USERS */

if(

    $is_maintenance === 'on'

    &&

    !$is_admin

    &&

    !$is_ajax

){

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Maintenance</title>

<style>

body{

    margin:0;

    height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

    flex-direction:column;

    background:#111827;

    color:white;

    font-family:Arial;

}

h1{

    font-size:48px;

    margin-bottom:10px;

}

p{

    color:#d1d5db;

    font-size:18px;

}

</style>

</head>

<body>

<h1>
Site Under Maintenance
</h1>

<p>
Please come back later.
</p>

</body>

</html>

<?php

exit();

}
?>