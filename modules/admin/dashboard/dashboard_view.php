<?php require_once __DIR__ . '/dashboard_data.php'; ?>

<style>
    /* ===== DASHBOARD TABLE STYLES (same as ads_view.php) ===== */
    :root {
        --primary:      #1a3fc4;
        --primary-dark: #1530a0;
        --teal:         #0ea5a0;
        --grad:         linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
        --surface:      #f4f7ff;
        --border:       #dde4f5;
        --text:         #1a1a2e;
        --muted:        #6b7280;
    }

    .dashboard-wrapper {
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    /* HEADER */
    .dashboard-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dashboard-header h1 {
        font-size: 32px;
        color: #1f2937;
        margin-bottom: 6px;
    }
    .dashboard-header p {
        color: #8a8fa3;
        font-size: 15px;
    }

    /* STATS */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5,1fr);
        gap: 24px;
    }
    .stat-card {
        background: rgba(255,255,255,0.75);
        backdrop-filter: blur(18px);
        border-radius: 26px;
        padding: 24px;
        border: 1px solid rgba(230,230,230,0.8);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: 0.3s;
    }
    .stat-card:hover {
        transform: translateY(-6px) scale(1.01);
        box-shadow: 0 25px 40px rgba(91,124,255,0.12);
    }
    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }
    .users-icon { background: linear-gradient(135deg,#3b82f6,#2563eb); }
    .ads-icon   { background: linear-gradient(135deg,#f59e0b,#f97316); }
    .premium-icon { background: linear-gradient(135deg,#8b5cf6,#7c3aed); }
    .revenue-icon { background: linear-gradient(135deg,#10b981,#059669); }
    .deleted-icon { background: linear-gradient(135deg,#ef4444,#dc2626); }
    .stat-info h2 {
        font-size: 28px;
        color: #1f2937;
        margin-bottom: 6px;
    }
    .stat-info p {
        color: #8a8fa3;
        font-size: 15px;
    }

    /* DASHBOARD GRID */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }
    .chart-card, .activity-card {
        box-shadow: 0 15px 35px rgba(91,124,255,0.06);
        background: rgba(255,255,255,0.75);
        backdrop-filter: blur(18px);
        border-radius: 28px;
        padding: 24px;
        border: 1px solid rgba(230,230,230,0.8);
    }
    .card-header {
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .card-header h3 {
        font-size: 22px;
        color: #1f2937;
    }
    .view-all-btn {
    background: rgba(26,63,196,0.1);   /* light blue background (matches primary) */
    color: #1a3fc4;                    /* primary text */
    padding: 12px 18px;
    border-radius: 14px;
    font-weight: 600;
    transition: 0.3s;
    text-decoration: none;
}

.view-all-btn:hover {
    background: #1a3fc4;              /* solid primary blue */
    color: white;
}
    .chart-container {
        height: 350px;
        position: relative;
    }
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .activity-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px;
        border-radius: 16px;
        background: rgba(245,247,255,0.8);
        transition: 0.3s;
    }
    .activity-item:hover {
        transform: translateX(4px);
    }
    .activity-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: linear-gradient(135deg,#5b7cff,#7f5cff);
    }
    .activity-item p {
        color: #4b5563;
        font-size: 15px;
    }

    /* QUICK ACTIONS */
    .quick-actions-card {
        background: rgba(255,255,255,0.75);
        backdrop-filter: blur(18px);
        border-radius: 28px;
        padding: 24px;
        border: 1px solid rgba(230,230,230,0.8);
        box-shadow: 0 15px 35px rgba(91,124,255,0.06);
    }
    .quick-actions-grid {
        display: flex;
        flex-wrap: nowrap;
        gap: 22px;
        overflow-x: auto;
        margin-top: 24px;
        padding-bottom: 8px;
    }
    .quick-action-item {
        flex: 1 1 auto;
        min-width: 150px;
        background: rgba(245,247,255,0.8);
        border-radius: 22px;
        padding: 28px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 18px;
        transition: 0.3s;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
    }
    .quick-action-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 35px rgba(91,124,255,0.10);
    }
    .quick-icon {
        width: 72px;
        height: 72px;
        border-radius: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }
    .users-bg { background: linear-gradient(135deg,#3b82f6,#2563eb); }
    .premium-bg { background: linear-gradient(135deg,#8b5cf6,#7c3aed); }
    .report-bg { background: linear-gradient(135deg,#ef4444,#dc2626); }
    .category-bg { background: linear-gradient(135deg,#10b981,#059669); }
    .settings-bg { background: linear-gradient(135deg,#6366f1,#4f46e5); }
    .quick-action-item h4 {
        color: #374151;
        font-size: 16px;
    }

    /* ===== TABLE STYLES (matching ads_view.php) ===== */
    .table-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1.5px solid var(--border);
        overflow: hidden;
        margin-top: 0;
    }
    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.1rem 1.4rem;
        border-bottom: 1.5px solid var(--border);
        background: #fafcff;
    }
    .table-header h3 {
        font-size: 1rem;
        font-weight: 800;
        color: var(--primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .table-wrapper {
        overflow-x: auto;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.83rem;
    }
    thead th {
        text-align: left;
        padding: 11px 13px;
        background: var(--grad);
        color: #fff;
        font-weight: 600;
        font-size: 0.77rem;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }
    tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.15s;
    }
    tbody tr:hover {
        background: var(--surface);
    }
    tbody td {
        padding: 10px 13px;
        color: var(--text);
        vertical-align: middle;
    }
    /* ID badge */
    .id-badge-ad {
        display: inline-flex;
        align-items: center;
        padding: 3px 9px;
        background: #eef2ff;
        color: var(--primary);
        border-radius: 20px;
        font-size: 0.73rem;
        font-weight: 700;
    }
    /* Ad thumbnail */
    .ad-thumb {
        width: 42px;
        height: 42px;
        object-fit: cover;
        border-radius: 10px;
        border: 1.5px solid var(--border);
        display: block;
    }
    /* Product title */
    .ad-title {
        font-weight: 600;
        font-size: 0.84rem;
        color: var(--text);
    }
    /* Seller name */
    .seller-text {
        color: var(--muted);
        font-size: 0.81rem;
    }
    /* Category badge */
    .category-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 9px;
        background: #f0fdf4;
        color: #15803d;
        border-radius: 20px;
        font-size: 0.73rem;
        font-weight: 600;
    }
    /* Plan badges */
    .plan-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.73rem;
        font-weight: 700;
    }
    .premium-plan {
        background: #fef9c3;
        color: #a16207;
    }
    .special-plan {
        background: #ede9fe;
        color: #6d28d9;
    }
    .basic-plan {
        background: #e0f2fe;
        color: #0369a1;
    }
    /* Status badges */
    .status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.73rem;
        font-weight: 700;
    }
    .status::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }
    .active-status {
        background: #dcfce7;
        color: #16a34a;
    }
    .active-status::before {
        background: #16a34a;
    }
    .sold-status {
        background: #fee2e2;
        color: #dc2626;
    }
    .sold-status::before {
        background: #dc2626;
    }
    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    .delete-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        background: #fee2e2;
        color: #b91c1c;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        font-size: 0.82rem;
        transition: all 0.2s;
    }
    .delete-btn:hover {
        background: #ef4444;
        color: #fff;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 640px) {
        .stats-grid {
            grid-template-columns: repeat(2,1fr);
        }
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
        .quick-actions-grid {
            flex-wrap: wrap;
        }
        .table-header {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

<div class="dashboard-wrapper">

    <div class="dashboard-header">
        <div>
            <h1>Dashboard Overview</h1>
            <p>Welcome back, Admin 👋</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon users-icon">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-info">
                <h2 class="counter" data-target="<?php echo $total_users; ?>">0</h2>
                <p>Total Users</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon ads-icon">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <div class="stat-info">
                <h2 class="counter" data-target="<?php echo $total_ads; ?>">0</h2>
                <p>Total Ads</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon premium-icon">
                <i class="fa-solid fa-crown"></i>
            </div>
            <div class="stat-info">
                <h2 class="counter" data-target="<?php echo $premium_ads; ?>">0</h2>
                <p>Premium Ads</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon revenue-icon">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div class="stat-info">
                <h2 class="counter revenue-counter" data-target="<?php echo (int)$total_revenue; ?>">0</h2>
                <p>Total Revenue</p>
            </div>
        </div>

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
                <a href="./admin_page.php?page=activity_logs" class="view-all-btn">
                    View All
                </a>
            </div>
            <div class="activity-list" id="recentActivities">
                <!-- loaded dynamically -->
            </div>
        </div>
    </div>

    <div class="quick-actions-card">
        <div class="card-header">
            <h3>Quick Actions</h3>
        </div>
        <div class="quick-actions-grid">
            <a href="./admin_page.php?page=users" class="quick-action-item">
                <div class="quick-icon users-bg">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h4>Manage Users</h4>
            </a>
            <a href="./admin_page.php?page=promotional" class="quick-action-item">
                <div class="quick-icon premium-bg">
                    <i class="fa-solid fa-crown"></i>
                </div>
                <h4>Promotional Ads</h4>
            </a>
            <a href="./admin_page.php?page=activity_logs" class="quick-action-item">
                <div class="quick-icon report-bg">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <h4>Recent Activities</h4>
            </a>
            <a href="./admin_page.php?page=categories" class="quick-action-item">
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

    <!-- RECENT ADVERTISEMENTS TABLE (styling matches ads_view.php) -->
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
                        <th>Image</th>
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
                        } elseif($ad['boost_type'] === 'special'){
                            $plan_class = 'special-plan';
                        }
                        
                        $status_class = $ad['status'] === 'active' ? 'active-status' : 'sold-status';
                    ?>
                    <tr>
                        <td>
                            <span class="id-badge-ad">#AD<?php echo $ad['id']; ?></span>
                        </td>
                        
                        <td>
                            <?php 
                                $displayImage = !empty($ad['image']) ? $ad['image'] : BASE_URL . 'assets/images/default-placeholder.png';
                            ?>
                            <img src="<?php echo htmlspecialchars($displayImage); ?>" 
                                 alt="Ad Image" 
                                 class="ad-thumb">
                        </td>

                        <td>
                            <span class="ad-title"><?php echo htmlspecialchars($ad['title']); ?></span>
                        </td>
                        <td>
                            <span class="seller-text"><?php echo htmlspecialchars($ad['seller_name']); ?></span>
                        </td>
                        <td>
                            <span class="category-badge"><?php echo htmlspecialchars($ad['category']); ?></span>
                        </td>
                        <td>
                            <?php if(!empty($ad['boost_type'])): ?>
                                <span class="plan-badge <?php echo $plan_class; ?>">
                                    <?php echo ucfirst($ad['boost_type']); ?>
                                </span>
                            <?php else: ?>
                                <span style="color:#9ca3af;font-size:0.8rem;">No Plan</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status <?php echo $status_class; ?>">
                                <?php echo ucfirst($ad['status']); ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="delete-btn" data-id="<?php echo $ad['id']; ?>">
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
const ctx = document.getElementById('overviewChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Users', 'Ads', 'Promotional ads', 'Revenue', 'Deleted'],
        datasets: [{
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
        }]
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
    fetch('../modules/admin/activity_logs/fetch_recent_activities.php')
    .then(response => response.text())
    .then(data => {
        document.getElementById('recentActivities').innerHTML = data;
    });
}

/* AUTO REFRESH */
setInterval(loadRecentActivities, 5000);
</script>