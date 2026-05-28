<?php

$current_page = $_GET['page'] ?? 'dashboard';

?>
<div class="sidebar">

    <div class="sidebar-top">

        <div class="logo-section">

            <div class="logo-icon">
                <i class="fa-solid fa-store"></i>
            </div>

            <div class="logo-text">
                <h2>Bikri Bazaar</h2>
                <p>Admin Panel</p>
            </div>

        </div>

    </div>

    <div class="sidebar-menu">

        <p class="menu-title">MAIN</p>

       <a 
    href="./admin_page.php" 
    class="menu-item <?php echo $current_page === 'dashboard' ? 'active' : ''; ?>"
>
            <i class="fa-solid fa-chart-line"></i>
            <span>Dashboard</span>
        </a>

        <a 
    href="./admin_page.php?page=users" 
    class="menu-item <?php echo $current_page === 'users' ? 'active' : ''; ?>"
>
    <i class="fa-solid fa-users"></i>
    <span>Users</span>
</a>

 <a 
    href="./admin_page.php?page=ads" 
    class="menu-item <?php echo $current_page === 'ads' ? 'active' : ''; ?>"
>
    <i class="fa-solid fa-bullhorn"></i>
    <span>Advertisements</span>
</a>

<a 
    href="./admin_page.php?page=promotional" 
    class="menu-item <?php echo $current_page === 'promotional' ? 'active' : ''; ?>"
>
    <i class="fa-solid fa-crown"></i>
    <span>Promotional Ads</span>
</a>


        <a 
    href="./admin_page.php?page=trash" 
    class="menu-item <?php echo $current_page === 'trash' ? 'active' : ''; ?>"
>
    <i class="fa-solid fa-trash"></i>
    <span>Deleted Ads</span>
</a>



        <a 
    href="./admin_page.php?page=categories" 
    class="menu-item <?php echo $current_page === 'categories' ? 'active' : ''; ?>"
>
    <i class="fa-solid fa-layer-group"></i>
    <span>Categories</span>
</a>

        <p class="menu-title system-title">SYSTEM</p>

        <a href="#" class="menu-item">
            <i class="fa-solid fa-bell"></i>
            <span>Notifications</span>
        </a>

        <a 
    href="./admin_page.php?page=settings"class="menu-item <?php echo $current_page === 'settings' ? 'active' : ''; ?>"
>
    <i class="fa-solid fa-gear"></i>
    <span>Settings</span>
</a>

        <a 
    href="./admin_page.php?page=activity_logs" 
    class="menu-item <?php echo $current_page === 'activity_logs' ? 'active' : ''; ?>"
>
    <i class="fa-solid fa-clock-rotate-left"></i>
    <span>Activity Logs</span>
</a>

    </div>

    <div class="sidebar-bottom">

        <div class="admin-profile">

            <div class="admin-avatar">
                H
            </div>

            <div class="admin-info">
                <h4>Super Admin</h4>
                <p>Administrator</p>
            </div>

        </div>

        <a href="<?php echo BASE_URL; ?>index.php"
   class="logout-btn">

    <i class="fa-solid fa-right-from-bracket"></i>

    <span>Logout</span>

</a>

    </div>

</div>