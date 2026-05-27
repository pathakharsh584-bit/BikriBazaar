<?php

require_once __DIR__ . '/../../../shared/db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $site_name = mysqli_real_escape_string(
        $conn,
        $_POST['site_name']
    );

    $support_email = mysqli_real_escape_string(
        $conn,
        $_POST['support_email']
    );

    $maintenance_mode = mysqli_real_escape_string(
        $conn,
        $_POST['maintenance_mode']
    );

    $theme_mode = mysqli_real_escape_string(
        $conn,
        $_POST['theme_mode']
    );

    mysqli_query(

        $conn,

        "UPDATE settings
         SET

            site_name = '$site_name',
            support_email = '$support_email',
            maintenance_mode = '$maintenance_mode',
            theme_mode = '$theme_mode'

         WHERE id = 1"

    );

    echo "success";

}
?>