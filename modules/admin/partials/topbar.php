<div class="topbar">

    <div class="topbar-left">

        <button class="menu-toggle">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="search-box">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input type="text" placeholder="Search anything...">

        </div>

    </div>

    <div class="topbar-right">

        <div class="notification-wrapper">

    <button class="topbar-icon notification-btn">

        <i class="fa-regular fa-bell"></i>

        <span class="notification-dot"></span>

    </button>

    <div class="notification-dropdown">

        <h4>Notifications</h4>

        <?php

        require_once __DIR__ . '/../../../shared/db.php';

        $notification_query = mysqli_query(

            $conn,

            "SELECT *
             FROM activity_logs
             ORDER BY created_at DESC
             LIMIT 5"

        );

        while($notification = mysqli_fetch_assoc($notification_query)):

        ?>

            <div class="notification-item">

                <?php

                echo htmlspecialchars(

                    $notification['activity_message']

                );

                ?>

            </div>

        <?php endwhile; ?>

    </div>

</div>

        <button class="topbar-icon">
            <i class="fa-regular fa-envelope"></i>
        </button>

        <div class="topbar-profile">

            <div class="profile-avatar">
                H
            </div>

            <div class="profile-info">
                <h4>Super Admin</h4>
                <p>Administrator</p>
            </div>

            <i class="fa-solid fa-chevron-down"></i>

        </div>

    </div>

</div>