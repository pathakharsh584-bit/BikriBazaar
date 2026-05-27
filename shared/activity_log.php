<?php

function logActivity(
    $conn,
    $type,
    $message
){

    $type = mysqli_real_escape_string($conn, $type);

    $message = mysqli_real_escape_string($conn, $message);

    mysqli_query(

        $conn,

        "INSERT INTO activity_logs (

            activity_type,
            activity_message

        )

        VALUES (

            '$type',
            '$message'

        )"

    );

}
?>