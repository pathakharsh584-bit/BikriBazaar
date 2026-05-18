<?php

?>
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
                    <?php echo isset($_SESSION['user_name']) ? strtoupper(substr($_SESSION['user_name'], 0, 1)) : 'U'; ?>
                </div>
                <div class="dropdown-content">
                    <div class="dropdown-user-meta">
                        <strong>Hi, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'User'; ?></strong>
                    </div>
                    <hr>
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