<?php
// shared/components/navbar.php
// Make sure session is started before including this file.
?>
<style>
    :root {
        --primary: #1a3fc4;
        --primary-dark: #1530a0;
        --teal: #0ea5a0;
        --teal-dark: #0b8a86;
        --grad: linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
        --surface: #f4f7ff;
        --text: #1a1a2e;
        --muted: #6b7280;
        --border: #dde4f5;
    }

    /* ── NAVBAR ── */
    .navbar {
        background: #ffffff;
        box-shadow: 0 2px 12px rgba(26,63,196,0.10);
        display: flex;
        align-items: center;
        padding: 0 2rem;
        height: 66px;
        position: sticky;
        top: 0;
        z-index: 100;
        border: 1px solid #d3d6dd;
    }
    .logo {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary);
        flex-shrink: 0;
    }
    .logo span { color: var(--teal); }
    .logo-img {
        height: 44px;
        width: 44px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--primary);
    }
    .nav-links {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-left: auto;
    }
    /* Base styles for all navbar links */
    .nav-links a {
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text);
        padding: 0.4rem 0.7rem;
        border-radius: 6px;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        position: relative;
        text-decoration: none;
    }
    /* Animated underline for normal links (exclude .btn-sell / .btn-register) */
    .nav-links a:not(.btn-sell):not(.btn-register)::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -5px;
        width: 0;
        height: 3px;
        background: #4338ca;
        border-radius: 10px;
        transition: width 0.3s ease;
    }
    .nav-links a:not(.btn-sell):not(.btn-register):hover::after {
        width: 100%;
    }
    .nav-links a:not(.btn-sell):not(.btn-register):hover {
        background: var(--surface);
    }
    /* SELL & REGISTER buttons – gradient + hover effect (no underline) */
    .btn-sell, .btn-register {
        background: var(--grad) !important;
        color: #fff !important;
        font-weight: 700;
        border-radius: 8px;
        padding: 0.45rem 1.2rem !important;
        transition: opacity 0.2s, transform 0.15s !important;
    }
    .btn-sell:hover, .btn-register:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
    /* Profile avatar & dropdown */
    .profile-dropdown {
        position: relative;
    }
    /* Updated: support both text and image */
    .nav-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--grad);
        color: #fff;
        font-weight: 800;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 2px solid var(--border);
        transition: opacity 0.2s;
        overflow: hidden;
    }
    .nav-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .nav-avatar:hover {
        opacity: 0.9;
    }
    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: -4px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 24px rgba(0,0,0,0.10);
        min-width: 200px;
        border: 1px solid #dde4f5;
        overflow: hidden;
        z-index: 200;
    }
    .profile-dropdown:hover .dropdown-content {
        display: block;
    }
    /* Transparent bridge to keep dropdown open */
    .profile-dropdown::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 100%;
        height: 12px;
        background: transparent;
    }
    .dropdown-user-meta {
        padding: 0.75rem 1rem 0.45rem;
        font-size: 0.86rem;
        color: #6b7280;
    }
    .dropdown-content hr {
        border: none;
        border-top: 1px solid #dde4f5;
        margin: 3px 0;
    }
    .dropdown-content a {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.55rem 1rem;
        font-size: 0.86rem;
        color: #1a1a2e;
        transition: background 0.2s;
        position: relative;
    }
    .dropdown-content a i {
        width: 15px;
        color: var(--primary);
    }
    .dropdown-content a:hover {
        background: #f4f7ff;
    }
    .dropdown-content a::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -2px;
        width: 0;
        height: 2px;
        background: #4338ca;
        border-radius: 10px;
        transition: width 0.3s ease;
    }
    .dropdown-content a:hover::after {
        width: 100%;
    }
    .dropdown-badge {
        background: #ef4444;
        color: #fff;
        font-size: 0.66rem;
        font-weight: 700;
        padding: 0.1rem 0.4rem;
        border-radius: 20px;
        margin-left: auto;
    }
    /* Responsive */
    @media (max-width: 768px) {
        .navbar { padding: 0 1rem; }
        .logo { font-size: 1.2rem; }
        .logo-img { height: 36px; width: 36px; }
    }
</style>

<div class="navbar">
    <div class="logo">
        <img src="assets/images/logo.png" alt="BikriBazaar Logo" class="logo-img"
             onerror="this.style.display='none'">
        Bikri<span>Bazaar</span>
    </div>

    <div class="nav-links">
        <a href="index.php"><i class="fa-solid fa-house"></i> Home</a>

        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="post-ad.php" class="btn-sell">
                <i class="fa-solid fa-plus"></i> SELL
            </a>
            <div class="profile-dropdown">
                <div class="nav-avatar">
                    <?php 
                    // Show profile image if exists, otherwise show first letter
                    if (!empty($_SESSION['profile_image'])): 
                        // Build the correct image URL (assuming BASE_URL is defined)
                        $image_path = BASE_URL . 'uploads/profiles/' . $_SESSION['profile_image'];
                    ?>
                        <img src="<?php echo $image_path; ?>" alt="Profile" 
                             onerror="this.style.display='none'; this.parentElement.innerHTML='<?php echo isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 1)) : 'U'; ?>';">
                    <?php else: ?>
                        <?php echo isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 1)) : 'U'; ?>
                    <?php endif; ?>
                </div>
                <div class="dropdown-content">
                    <div class="dropdown-user-meta">
                        <strong>Hi, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User'; ?></strong>
                    </div>
                    <hr>
                    <a href="profile.php"><i class="fa-solid fa-user-circle"></i> Edit Profile</a>
                    <a href="my-ads.php"><i class="fa-solid fa-list"></i> My Ads</a>
                    <a href="favorites.php"><i class="fa-solid fa-heart"></i> Favorites</a>
                    <a href="inbox.php">
                        <i class="fa-solid fa-message"></i> Messages
                        <?php if (isset($unread_count) && $unread_count > 0): ?>
                            <span class="dropdown-badge"><?php echo $unread_count; ?></span>
                        <?php endif; ?>
                    </a>
                    <hr>
                    <a href="logout.php" style="color:#ef4444!important;">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                </div>
            </div>
        <?php else: ?>
            <a href="login.php"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
            <?php 
            if (!isset($hide_register) || $hide_register !== true):
            ?>
                <a href="register.php" class="btn-register">
                    <i class="fa-solid fa-user-plus"></i> Register
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>