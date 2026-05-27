<?php

require_once __DIR__ . '/../../../shared/db.php';

$categories_query = mysqli_query(

    $conn,

    "SELECT

        category,

        COUNT(*) AS total_ads,

        SUM(
            CASE
                WHEN status = 'active'
                THEN 1
                ELSE 0
            END
        ) AS active_ads

     FROM products

     WHERE is_deleted = 0

     GROUP BY category

     ORDER BY total_ads DESC"

);

$chart_labels = [];

$chart_data = [];

while($row = mysqli_fetch_assoc($categories_query)){

    $chart_labels[] = $row['category'];

    $chart_data[] = $row['total_ads'];

}

mysqli_data_seek($categories_query, 0);

?>