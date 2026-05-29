<?php
$current_page = $_GET['page'] ?? 'dashboard';
?>
<style>
    /* ===== SIDEBAR – brand colours (blue & teal) ===== */
    .sidebar {
        background: #ffffff;
        box-shadow: 2px 0 10px #445bab;
        border-right: 1px solid #e2e8f0;
        transition: all 0.3s;
    }
    .logo-section {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 1.5rem 1rem;
        border-bottom: 1px solid #eef2ff;
        margin-bottom: 1rem;
    }
    .logo-img {
        width: 54px;
        height: 42px;
        border-radius: 12px;
        object-fit: cover;
        background: #fff;
        padding: 4px;
        border: 1px solid #dde4f5;
    }
    .logo-text h2 {
        font-size: 1.2rem;
        font-weight: 800;
        color: #1a3fc4;       /* primary blue */
        margin: 0;
        line-height: 1.2;
    }
    .logo-text p {
        font-size: 0.9rem;
        color: #0ea5a0;       /* teal */
        margin: 0;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    .menu-title {
        font-size: 0.7rem;
        letter-spacing: 1px;
        color: #6b7280;
        padding: 0.8rem 1rem 0.4rem;
        margin-top: 0.5rem;
        font-weight: 600;
    }
    .menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0.7rem 1rem;
        margin: 0.2rem 0.8rem;
        border-radius: 12px;
        color: #1a1a2e;
        transition: all 0.2s;
        font-weight: 500;
    }
    .menu-item i {
        width: 22px;
        font-size: 1.1rem;
        text-align: center;
        color: #6b7280;
    }
    .menu-item:hover {
        background: #f4f7ff;
        color: #1a3fc4;
    }
    .menu-item:hover i {
        color: #1a3fc4;
    }
    .menu-item.active {
        background: linear-gradient(95deg, #1a3fc4, #0ea5a0);
        color: white;
        box-shadow: 0 4px 12px rgba(26,63,196,0.2);
    }
    .menu-item.active i {
        color: white;
    }
    .sidebar-bottom {
        margin-top: auto;
        padding: 1rem;
        border-top: 1px solid #eef2ff;
    }
    .admin-profile {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 1rem;
        padding: 0.5rem;
        border-radius: 12px;
        background: #f8fafc;
    }
    .profile-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1a3fc4, #0ea5a0);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: white;
    }
    .admin-info h4 {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }
    .admin-info p {
        font-size: 0.7rem;
        color: #6b7280;
        margin: 0;
    }
    .logout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #fee2e2;
        color: #b91c1c;
        padding: 0.6rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.2s;
    }
    .logout-btn:hover {
        background: #ef4444;
        color: white;
    }
    @media (max-width: 768px) {
        .sidebar { width: 80px; }
        .logo-text, .menu-item span, .admin-info, .logout-btn span, .menu-title { display: none; }
        .logo-section { justify-content: center; }
        .menu-item { justify-content: center; }
        .admin-profile { justify-content: center; }
    }
</style>

<div class="sidebar">

    <div class="sidebar-top">

        <div class="logo-section">
            <!-- Added actual logo image -->
            <img src="/BikriBazaar/public/assets/images/logo.png" alt="BikriBazaar Logo" class="logo-img"
                 onerror="this.src='https://via.placeholder.com/42?text=BB'">
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

<a href="admin_page.php?page=reported_ads"
   class="menu-item <?php echo $current_page === 'reported_ads' ? 'active' : ''; ?>"
   style="display: flex; align-items: center; gap: 12px; padding: 0.7rem 1rem; margin: 0.2rem 0.8rem; border-radius: 12px; color: #1a1a2e; transition: all 0.2s;">

    <i class="fa-solid fa-triangle-exclamation"></i>
    <span>Reported Ads</span>

</a>

        <p class="menu-title system-title">SYSTEM</p>

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
            <div class="profile-avatar">
                <i class="fa-solid fa-user-shield"></i>
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