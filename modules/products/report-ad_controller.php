<?php

session_start();

require_once __DIR__ . '/../../shared/config.php';

require_once __DIR__ . '/../../shared/db.php';

/* LOGIN CHECK */

if(!isset($_SESSION['user_id'])){

    header(

        "Location: " .

        BASE_URL .

        "login.php"

    );

    exit();

}

/* PRODUCT CHECK */

if(!isset($_GET['id'])){

    die("Invalid Product");

}

$product_id = intval($_GET['id']);

$user_id = intval($_SESSION['user_id']);

/* SAME PRODUCT DUPLICATE CHECK */

$duplicate_query = mysqli_query(

    $conn,

    "SELECT id

     FROM reported_ads

     WHERE

     product_id = $product_id

     AND

     user_id = $user_id"

);

$already_reported =

    mysqli_num_rows(
        $duplicate_query
    ) > 0;

/* 24 HOUR REPORT LIMIT */

$limit_query = mysqli_query(

    $conn,

    "SELECT COUNT(*) as total

     FROM reported_ads

     WHERE

     user_id = $user_id

     AND

     created_at >= NOW() - INTERVAL 24 HOUR"

);

$limit_data = mysqli_fetch_assoc(
    $limit_query
);

$total_reports_24hrs =

    intval(
        $limit_data['total']
    );

/* SUBMIT */

if(

    $_SERVER['REQUEST_METHOD']
    === 'POST'

){

    $reason = mysqli_real_escape_string(

        $conn,

        trim($_POST['reason'])

    );

    /* ALLOWED REASONS */

    $allowed = [

        'Scam',

        'Fake Product'

    ];

    if(

        !in_array(

            $reason,

            $allowed

        )

    ){

        die("Invalid Reason");

    }

    /* SAME PRODUCT REPORTED */

    if($already_reported){

        $error =

        "You already reported this product.";

    }

    /* 24 HOUR LIMIT */

    elseif($total_reports_24hrs >= 5){

        $error =

        "Report limit reached. Try again after 24 hours.";

    }

    /* INSERT REPORT */

    else{

        $insert = mysqli_query(

            $conn,

            "INSERT INTO reported_ads (

                product_id,
                user_id,
                reason

            )

            VALUES (

                $product_id,
                $user_id,
                '$reason'

            )"

        );

        if($insert){

            header(

                "Location: " .

                BASE_URL .

                "product.php?id=" .

                $product_id

            );

            exit();

        }

        else{

            $error =

            "Failed to submit report.";

        }

    }

}

?>