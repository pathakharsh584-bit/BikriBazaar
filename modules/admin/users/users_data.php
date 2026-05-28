<?php

require_once __DIR__ . '/../../../shared/db.php';

$search = trim($_GET['search'] ?? '');

$search_sql = '';

if($search !== ''){

    $safe_search = mysqli_real_escape_string($conn, $search);

    $search_sql = "

        AND (

            users.name LIKE '%$safe_search%'

            OR users.email LIKE '%$safe_search%'

        )

    ";

}

/* PAGINATION */

$limit = 10;

$page_number = max(1, intval($_GET['pageno'] ?? 1));

$offset = ($page_number - 1) * $limit;


/* ALL USERS */

$all_users_query = mysqli_query(

    $conn,

    "SELECT
        users.id,
        users.name,
        users.email,
        users.created_at,

        (
            SELECT COUNT(*)
            FROM products
            WHERE products.user_id = users.id
            AND products.is_deleted = 0
        ) AS total_ads

     FROM users

     WHERE 1=1

     $search_sql

     ORDER BY users.created_at DESC

     LIMIT $limit OFFSET $offset"

);


/* TOTAL USERS */

$total_users_result = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS total

     FROM users

     WHERE 1=1

     $search_sql"

);

$total_users = mysqli_fetch_assoc($total_users_result)['total'];

$total_pages = ceil($total_users / $limit);

?>