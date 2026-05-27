<?php require_once __DIR__ . '/dashboard_data.php'; ?>

<div class="dashboard-wrapper">

    <div class="dashboard-header">

        <div>
            <h1>Dashboard Overview</h1>
            <p>Welcome back, Admin 👋</p>
        </div>

    </div>

    <!-- STATS -->

    <div class="stats-grid">

    <!-- TOTAL USERS -->

    <div class="stat-card">

        <div class="stat-icon users-icon">
            <i class="fa-solid fa-users"></i>
        </div>

        <div class="stat-info">
            <h2 class="counter" data-target="<?php echo $total_users; ?>">0</h2>
            <p>Total Users</p>
        </div>

    </div>

    <!-- TOTAL ADS -->

    <div class="stat-card">

        <div class="stat-icon ads-icon">
            <i class="fa-solid fa-bullhorn"></i>
        </div>

        <div class="stat-info">
            <h2 class="counter" data-target="<?php echo $total_ads; ?>">0</h2>
            <p>Total Ads</p>
        </div>

    </div>

    <!-- PREMIUM ADS -->

    <div class="stat-card">

        <div class="stat-icon premium-icon">
            <i class="fa-solid fa-crown"></i>
        </div>

        <div class="stat-info">
            <h2 class="counter" data-target="<?php echo $premium_ads; ?>">0</h2>
            <p>Premium Ads</p>
        </div>

    </div>

    <!-- TOTAL REVENUE -->

    <div class="stat-card">

        <div class="stat-icon revenue-icon">
            <i class="fa-solid fa-wallet"></i>
        </div>

        <div class="stat-info">
            <h2 class="counter revenue-counter" data-target="<?php echo (int)$total_revenue; ?>">0</h2>
            <p>Total Revenue</p>
        </div>

    </div>

    <!-- DELETED ADS -->

    <div class="stat-card">

        <div class="stat-icon deleted-icon">
            <i class="fa-solid fa-trash"></i>
        </div>

        <div class="stat-info">
            <h2 class="counter" data-target="<?php echo $deleted_ads; ?>">0</h2>
            <p>Deleted Ads</p>
        </div>

    </div>

</div>

    <!-- CHART + ACTIVITIES -->

    <div class="dashboard-grid">

        <div class="chart-card">

            <div class="card-header">
                <h3>Platform Overview</h3>
            </div>

            <div class="chart-container">
                <canvas id="overviewChart"></canvas>
            </div>

        </div>

        <div class="activity-card">

            <div class="card-header">

    <h3>Recent Activities</h3>

                                 <a href="./admin_page.php?page=activity_logs"
                                 class="view-all-btn">

                                 View All
                                 </a>

</div>

          <div class="activity-list"
                id="recentActivities"></div>

    <?php while($activity = mysqli_fetch_assoc($recent_activity_query)): ?>

        <div class="activity-item">

            <div class="activity-dot"></div>

            <p>

                <?php echo htmlspecialchars(
                    $activity['activity_message']
                ); ?>

            </p>

        </div>
        <div class="card-header">


</div>

    <?php endwhile; ?>

</div>

        </div>

    </div>

    <!-- QUICK ACTIONS -->

    <div class="quick-actions-card">

        <div class="card-header">
            <h3>Quick Actions</h3>
        </div>

        <div class="quick-actions-grid">

            <a href="./admin_page.php?page=users"class="quick-action-item">
              <div class="quick-icon users-bg">
                 <i class="fa-solid fa-users"></i>
               </div>
                 <h4>Manage Users</h4>
                 </a>

            <a href="./admin_page.php?page=promotional"class="quick-action-item">

    <div class="quick-icon premium-bg">

        <i class="fa-solid fa-crown"></i>

    </div>

    <h4>Promotional Ads</h4>

</a>

           <a href="./admin_page.php?page=promotional"class="quick-action-item">
               <div class="quick-icon premium-bg">
                <i class="fa-solid fa-crown"></i>
            </div>
               <h4>Promotional Ads</h4>
            </a>

            <a href="./admin_page.php?page=activity_logs"
   class="quick-action-item">

    <div class="quick-icon report-bg">

        <i class="fa-solid fa-clock-rotate-left"></i>

    </div>

    <h4>Recent Activities</h4>

</a>

           <a href="./admin_page.php?page=categories"class="quick-action-item">

    <div class="quick-icon category-bg">

        <i class="fa-solid fa-layer-group"></i>

    </div>

    <h4>Categories</h4>

</a>

            <div class="quick-action-item">
                <div class="quick-icon settings-bg">
                    <i class="fa-solid fa-gear"></i>
                </div>
                <h4>Settings</h4>
            </div>

        </div>

    </div>

    <!-- RECENT ADS TABLE -->

    <div class="table-card">

        <div class="table-header">

            <h3>Recent Advertisements</h3>

            <a href="./admin_page.php?page=ads" class="view-all-btn">
    View All
</a>

        </div>

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Product</th>
                        <th>Seller</th>
                        <th>Category</th>
                        <th>Plan</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>

                </thead>

                <tbody>

                    <?php while($ad = mysqli_fetch_assoc($recent_ads_query)): ?>

                    <?php

                    $plan_class = 'basic-plan';

                    if($ad['boost_type'] === 'premium'){
                        $plan_class = 'premium-plan';
                    }

                    elseif($ad['boost_type'] === 'special'){
                        $plan_class = 'special-plan';
                    }

                    $status_class = $ad['status'] === 'active'
                        ? 'active-status'
                        : 'sold-status';

                    ?>

                    <tr>

                        <td>
                            #AD<?php echo $ad['id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($ad['title']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($ad['seller_name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($ad['category']); ?>
                        </td>

                        <td>

    <?php if(!empty($ad['boost_type'])): ?>

        <span class="plan-badge <?php echo $plan_class; ?>">

            <?php echo ucfirst($ad['boost_type']); ?>

        </span>

    <?php else: ?>

        <span style="color:#9ca3af;">
            No Plan
        </span>

    <?php endif; ?>

</td>

                        <td>

                            <span class="status <?php echo $status_class; ?>">

                                <?php echo ucfirst($ad['status']); ?>

                            </span>

                        </td>
                        <td>

    <div class="action-buttons">

        <button class="edit-btn">
            <i class="fa-solid fa-pen"></i>
        </button>

        <button 
            class="delete-btn"
            data-id="<?php echo $ad['id']; ?>"
        >
            <i class="fa-solid fa-trash"></i>
        </button>

    </div>

</td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx =
    document.getElementById('overviewChart');

new Chart(ctx, {

    type: 'line',

    data: {

        labels: [

            'Users',
            'Ads',
            'Promotional ads',
            'Revenue',
            'Deleted'

        ],

        datasets: [

            {

                label: 'Platform Analytics',

                data: [

                    <?php echo $total_users; ?>,
                    <?php echo $total_ads; ?>,
                    <?php echo $premium_ads; ?>,
                    <?php echo (int)$total_revenue; ?>,
                    <?php echo $deleted_ads; ?>

                ],

                backgroundColor: [

    'rgba(234, 223, 16, 0.9)',

    'rgba(239,68,68,0.7)',

    'rgba(234,179,8,0.7)',

    'rgba(168,85,247,0.7)',

    'rgba(0,0,0,0.7)'

],

borderColor: [

    '#00d4fe',

    '#ef4444',

    '#eab308',

    '#a855f7',

    '#000000'

],

                fill: true,

                tension: 0.4,

                borderWidth: 3,

                pointBackgroundColor: '#0004fc',

                pointRadius: 5

            }

        ]

    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        plugins: {

            legend: {

                display: false

            }

        },

        scales: {

            y: {

                beginAtZero: true

            }

        }

    }

});

</script>
<script>

function loadRecentActivities(){

    fetch(

        '../modules/admin/activity_logs/fetch_recent_activities.php'

    )

    .then(response => response.text())

    .then(data => {

        document.getElementById(

            'recentActivities'

        ).innerHTML = data;

    });

}

/* AUTO REFRESH */

setInterval(

    loadRecentActivities,

    5000

);

</script>