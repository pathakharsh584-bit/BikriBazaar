<?php

require_once __DIR__ . '/../../../shared/db.php';

$settings_query = mysqli_query(

    $conn,

    "SELECT *
     FROM settings
     LIMIT 1"

);

if(!$settings_query){

    die(
        "Settings Query Failed: " .
        mysqli_error($conn)
    );

}

$settings = mysqli_fetch_assoc($settings_query);

if(!$settings){

    $default_insert = mysqli_query(

        $conn,

        "INSERT INTO settings (

            site_name,
            support_email,
            maintenance_mode,
            theme_mode

        )

        VALUES (

            'BikriBazaar',
            'support@bikribazaar.com',
            'off',
            'light'

        )"

    );

    $settings_query = mysqli_query(

        $conn,

        "SELECT *
         FROM settings
         LIMIT 1"

    );

    $settings = mysqli_fetch_assoc($settings_query);

}

?>