<?php

require_once __DIR__ . '/../../../shared/db.php';

$search = trim($_GET['search'] ?? '');

$search_sql = '';

$filter = trim($_GET['filter'] ?? '');

$filter_sql = '';

if($filter === 'premium'){

    $filter_sql = " AND products.boost_type = 'premium' ";

}

elseif($filter === 'special'){

    $filter_sql = " AND products.boost_type = 'special' ";

}

elseif($filter === 'basic'){

    $filter_sql = " AND (
        products.boost_type IS NULL
        OR products.boost_type = ''
        OR products.boost_type = 'basic'
    ) ";

}

elseif($filter === 'sold'){

    $filter_sql = " AND products.status = 'sold' ";

}

if($search !== ''){

    $safe_search = mysqli_real_escape_string($conn, $search);

    $search_sql = "

        AND (

            products.title LIKE '%$safe_search%'

            OR users.name LIKE '%$safe_search%'

            OR products.category LIKE '%$safe_search%'

        )

    ";

}

/* PAGINATION */

$limit = 10;

$page_number = max(1, intval($_GET['pageno'] ?? 1));

$offset = ($page_number - 1) * $limit;



/* ALL PRODUCTS */

$all_ads_query = mysqli_query(

    $conn,

    "SELECT
        products.id,
        products.title,
        products.category,
        products.price,
        products.status,
        products.boost_type,
        users.name AS seller_name

     FROM products

     JOIN users
     ON products.user_id = users.id

     WHERE products.is_deleted = 0

     $search_sql

     $filter_sql

     ORDER BY products.created_at DESC

LIMIT $limit OFFSET $offset"
);

/* TOTAL PAGES */

$total_ads_result = mysqli_query(

    $conn,

    "SELECT COUNT(*) AS total

     FROM products

     JOIN users
     ON products.user_id = users.id

     WHERE products.is_deleted = 0

     $search_sql

     $filter_sql"

);

$total_ads = mysqli_fetch_assoc($total_ads_result)['total'];

$total_pages = ceil($total_ads / $limit);

?>