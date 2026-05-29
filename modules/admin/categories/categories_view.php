<?php require_once __DIR__ . '/categories_data.php'; ?>

<style>
    /* ===== CATEGORIES PAGE – Blue/Teal UI (BikriBazaar) ===== */
    :root {
        --primary:  #1a3fc4;
        --teal:     #0ea5a0;
        --grad:     linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
        --surface:  #f4f7ff;
        --border:   #dde4f5;
        --text:     #1a1a2e;
        --muted:    #6b7280;
    }

    .table-card {
        background: #fff;
        border-radius: 20px;
        border: 1.5px solid var(--border);
        overflow: hidden;
        margin-bottom: 2rem;
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
        font-size: 26px;
        font-weight: 800;
        color: var(--primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-header h3 i { color: var(--teal); }

    /* Chart card */
    .chart-card {
        margin: -1.6rem 1.4rem 0;
        background: var(--surface);
        border-radius: 16px;
        border: 1.5px solid var(--border);
        overflow: hidden;
    }

    .chart-card .card-header {
        padding: 0.9rem 1.2rem;
        border-bottom: 1.5px solid var(--border);
        background: #fafcff;
    }

    .chart-card .card-header h3 {
        font-size: 20.9px;
        font-weight: 700;
        color: var(--primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .chart-card .card-header h3 i { color: var(--teal); }

    .chart-body {
        padding: 1.2rem;
        height: 400px;
    }

    .table-wrapper {
        overflow-x: auto;
        padding-bottom: 1.4rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.83rem;
        margin-top: 1.4rem;
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

    tbody tr:hover { background: var(--surface); }
    tbody tr:last-child { border-bottom: none; }

    tbody td {
        padding: 10px 13px;
        color: var(--text);
        vertical-align: middle;
    }

    /* Row number badge */
    .row-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        background: #eef2ff;
        color: var(--primary);
        border-radius: 50%;
        font-size: 0.72rem;
        font-weight: 700;
    }

    /* Category name */
    .cat-name {
        font-weight: 600;
        font-size: 0.84rem;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cat-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    /* Total ads badge */
    .total-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        background: #eef2ff;
        color: var(--primary);
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* Active ads badge */
    .active-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        background: #dcfce7;
        color: #16a34a;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .active-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #16a34a;
        display: inline-block;
        flex-shrink: 0;
    }

    @media (max-width: 640px) {
        .chart-card { margin: 1rem 1rem 0; }
        table { margin-top: 1rem; }
    }
</style>

<div class="table-card">

    <div class="table-header">
        <h3>
            <i class="fa-solid fa-folder-open"></i>
            Categories Management
        </h3>
    </div>

    <div class="table-wrapper">

        <div class="chart-card">
            <div class="card-header">
                <h3>
                    <i class="fa-solid fa-chart-pie"></i>
                    Categories Distribution
                </h3>
            </div>
            <div class="chart-body">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category</th>
                    <th>Total Ads</th>
                    <th>Active Ads</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                while($category = mysqli_fetch_assoc($categories_query)):
                ?>
                <tr>
                    <td>
                        <span class="row-num"><?php echo $count++; ?></span>
                    </td>
                    <td>
                        <span class="cat-name">
                            <?php echo htmlspecialchars($category['category']); ?>
                        </span>
                    </td>
                    <td>
                        <span class="total-badge">
                            <i class="fa-solid fa-tag" style="font-size:0.65rem;"></i>
                            <?php echo $category['total_ads']; ?>
                        </span>
                    </td>
                    <td>
                        <span class="active-badge">
                            <?php echo $category['active_ads']; ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const categoryCtx = document.getElementById('categoryChart');

new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($chart_labels); ?>,
        datasets: [{
            data: <?php echo json_encode($chart_data); ?>,
            backgroundColor: [
                '#1a3fc4',
                '#0ea5a0',
                '#6366f1',
                '#8b5cf6',
                '#ec4899',
                '#f97316',
                '#22c55e'
            ],
            borderWidth: 3,
            borderColor: '#ffffff',
            hoverOffset: 15
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '55%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    pointStyleWidth: 10,
                    font: { size: 12, family: "'Segoe UI', sans-serif" },
                    color: '#1a1a2e'
                }
            }
        }
    }
});
</script>