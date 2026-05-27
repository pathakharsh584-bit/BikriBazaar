<?php

require_once __DIR__ . '/../../../shared/db.php';

$activity_logs_query = mysqli_query(

    $conn,

    "SELECT *
     FROM activity_logs

     WHERE created_at >= NOW() - INTERVAL 1 DAY

     ORDER BY created_at DESC"

);
?>