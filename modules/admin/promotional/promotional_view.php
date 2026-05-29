<?php require_once __DIR__ . '/promotional_data.php'; ?>

<style>
    /* ===== PROMOTIONAL PAGE – Blue/Teal UI (BikriBazaar) ===== */
    :root {
        --primary:      #1a3fc4;
        --teal:         #0ea5a0;
        --grad:         linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
        --surface:      #f4f7ff;
        --border:       #dde4f5;
        --text:         #1a1a2e;
        --muted:        #6b7280;
    }

    .table-card {
        background: #ffffff;
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
        color: var(--primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-header h3 i {
        color: var(--teal);
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

    tbody tr:last-child {
        border-bottom: none;
    }

    tbody td {
        padding: 10px 13px;
        color: var(--text);
        vertical-align: middle;
    }

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

    .ad-thumb {
        width: 42px;
        height: 42px;
        object-fit: cover;
        border-radius: 10px;
        border: 1.5px solid var(--border);
        display: block;
    }

    .ad-title {
        font-weight: 600;
        font-size: 0.84rem;
        color: var(--text);
    }

    .seller-text {
        color: var(--muted);
        font-size: 0.81rem;
    }

    .plan-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.73rem;
        font-weight: 700;
    }

    .premium-plan { background: #fef9c3; color: #a16207; }
    .special-plan  { background: #ede9fe; color: #6d28d9; }
    .basic-plan    { background: #e0f2fe; color: #0369a1; }

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

    .active-status { background: #dcfce7; color: #16a34a; }
    .active-status::before { background: #16a34a; }
    .sold-status   { background: #fee2e2; color: #dc2626; }
    .sold-status::before   { background: #dc2626; }

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

    @media (max-width: 640px) {
        .table-header { flex-direction: column; align-items: stretch; }
    }
</style>

<div class="table-card">
    <div class="table-header">
        <h3>
            <i class="fa-solid fa-bullhorn"></i>
            Promotional Advertisements
        </h3>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Seller</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($ad = mysqli_fetch_assoc($promotional_query)): ?>
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
                            $displayImage = !empty($ad['image'])
                                ? $ad['image']
                                : BASE_URL . 'assets/images/default-placeholder.png';
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
                        <span class="plan-badge <?php echo $plan_class; ?>">
                            <?php echo ucfirst($ad['boost_type']); ?>
                        </span>
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