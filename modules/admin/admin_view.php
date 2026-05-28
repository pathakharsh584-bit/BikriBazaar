<?php
require_once __DIR__ . '/auth/auth_check.php';
require_once __DIR__ . '/../../shared/db.php';

$theme_query = mysqli_query(

    $conn,

    "SELECT theme_mode
     FROM settings
     LIMIT 1"

);

$theme = mysqli_fetch_assoc($theme_query);

$theme_mode = $theme['theme_mode'] ?? 'light';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BikriBazaar Admin Panel</title>


    <link rel="stylesheet" href="./../modules/admin/assets/css/style.css">

    <link rel="stylesheet" href="./../modules/admin/assets/css/sidebar.css">

    <link rel="stylesheet" href="./../modules/admin/assets/css/dashboard.css">

    <link rel="stylesheet" href="./../modules/admin/assets/css/responsive.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

</head>

<body id="adminBody">

    <div class="admin-container">

        <?php require_once __DIR__ . '/partials/sidebar.php'; ?>

        <div class="main-content">

            <?php require_once __DIR__ . '/partials/topbar.php'; ?>

            <div class="content-area">

               <?php

$page = $_GET['page'] ?? 'dashboard';

switch($page){

    case 'ads':
        require_once __DIR__ . '/ads/ads_view.php';
        break;

    case 'users':
        require_once __DIR__ . '/users/users_view.php';
        break;
        
        case 'trash':
    require_once __DIR__ . '/trash/trash_view.php';
    break;

    case 'promotional':
    require_once __DIR__ . '/promotional/promotional_view.php';
    break;

case 'categories':
    require_once __DIR__ . '/categories/categories_view.php';
    break;

    case 'activity_logs':
    require_once __DIR__ . '/activity_logs/activity_logs_view.php';
    break;

case 'settings':
    require_once __DIR__ . '/settings/settings_view.php';
    break;

    case 'reported_ads':
    require_once __DIR__ . '/reported_ads/reported_ads_view.php';
    break;

    default:
        require_once __DIR__ . '/dashboard/dashboard_view.php';
        break;

}

?>

            </div>

        </div>

    </div>

    <?php require_once __DIR__ . '/partials/modals.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="./../modules/admin/assets/js/app.js"></script>

    <script src="./../modules/admin/assets/js/sidebar.js"></script>

    <script src="./../modules/admin/assets/js/dashboard.js"></script>

    <script src="./../modules/admin/assets/js/ajax.js"></script>
    
<div class="toast-container" id="toastContainer"></div>

<script>

const savedTheme =
    localStorage.getItem('admin_theme');

if(savedTheme === 'dark'){

    document
    .getElementById('adminBody')
    .classList
    .add('dark');

}

</script>
</body>

</html>