<?php require_once __DIR__ . '/reported_ads_data.php'; ?>

<style>
    /* ===== REPORTED ADS PAGE – Blue/Teal UI (BikriBazaar) ===== */
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
        font-size: 1rem;
        font-weight: 800;
        color: #b91c1c;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-header h3 i { color: #ef4444; }

    /* Alert strip under header */
    .report-alert {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 9px 1.4rem;
        background: #fff7ed;
        border-bottom: 1.5px solid #fed7aa;
        font-size: 0.8rem;
        color: #9a3412;
        font-weight: 500;
    }

    .report-alert i { color: #f97316; font-size: 0.85rem; }

    .table-wrapper { overflow-x: auto; }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.83rem;
    }

    .data-table thead th {
        text-align: left;
        padding: 11px 13px;
        background: var(--grad);
        color: #fff;
        font-weight: 600;
        font-size: 0.77rem;
        letter-spacing: 0.04em;
        white-space: nowrap;
    }

    .data-table tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.15s;
    }

    .data-table tbody tr:hover { background: #fff7f7; }
    .data-table tbody tr:last-child { border-bottom: none; }

    .data-table tbody td {
        padding: 10px 13px;
        color: var(--text);
        vertical-align: middle;
    }

    /* Ad image */
    .ad-thumb {
        width: 42px;
        height: 42px;
        object-fit: cover;
        border-radius: 10px;
        border: 1.5px solid var(--border);
        display: block;
    }

    /* Ad title */
    .ad-title {
        font-weight: 600;
        font-size: 0.84rem;
        color: var(--text);
    }

    /* Price */
    .price-text {
        font-weight: 700;
        color: var(--primary);
        font-size: 0.86rem;
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

    /* Reason badge */
    .reason-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        background: #fff7ed;
        color: #9a3412;
        border-radius: 20px;
        font-size: 0.73rem;
        font-weight: 600;
        border: 1px solid #fed7aa;
    }

    .reason-badge i { font-size: 0.68rem; }

    /* Delete button */
    .delete-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 5px 14px;
        background: #fee2e2;
        color: #b91c1c;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.78rem;
        font-weight: 700;
        transition: all 0.2s;
    }

    .delete-btn:hover {
        background: #ef4444;
        color: #fff;
        transform: translateY(-2px);
    }

    @media (max-width: 640px) {
        .table-header { flex-direction: column; align-items: stretch; }
    }
</style>

<div class="table-card">

    <div class="table-header">
        <h3>
            <i class="fa-solid fa-flag"></i>
            Reported Ads
        </h3>
    </div>

    <div class="report-alert">
        <i class="fa-solid fa-triangle-exclamation"></i>
        These ads have been flagged by users and require your review. Deleting will permanently remove the ad.
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Ad Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Reason</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($report = mysqli_fetch_assoc($reports_query)): ?>
                <tr>
                    <td>
                        <?php
                            $displayImage = !empty($report['image'])
                                ? $report['image']
                                : BASE_URL . 'assets/images/default-placeholder.png';
                        ?>
                        <img src="<?php echo htmlspecialchars($displayImage); ?>"
                             alt="Ad Image"
                             class="ad-thumb">
                    </td>

                    <td>
                        <span class="ad-title"><?php echo htmlspecialchars($report['title']); ?></span>
                    </td>

                    <td>
                        <span class="price-text">₹<?php echo number_format($report['price']); ?></span>
                    </td>

                    <td>
                        <span class="category-badge"><?php echo htmlspecialchars($report['category']); ?></span>
                    </td>

                    <td>
                        <span class="reason-badge">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <?php echo htmlspecialchars($report['reason']); ?>
                        </span>
                    </td>

                    <td>
                        <a href="admin_page.php?page=delete_reported_ad&id=<?php echo $report['product_id']; ?>"
                           class="delete-btn">
                            <i class="fa-solid fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>