<?php require_once __DIR__ . '/ads_data.php'; ?>

<style>
    /* ===== ADS PAGE – Blue/Teal UI (BikriBazaar) ===== */
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

    .table-header h3::before {
        content: '\f02b';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: var(--teal);
        font-size: 1rem;
    }

    .table-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-select {
        padding: 7px 14px;
        border: 1.5px solid var(--border);
        border-radius: 40px;
        font-size: 0.82rem;
        color: var(--text);
        background: #fff;
        outline: none;
        cursor: pointer;
        font-family: inherit;
        transition: border-color 0.2s, box-shadow 0.2s;
        appearance: none;
        -webkit-appearance: none;
        padding-right: 28px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b7280' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
    }

    .filter-select:focus {
        border-color: var(--teal);
        box-shadow: 0 0 0 3px rgba(14, 165, 160, 0.10);
    }

    .search-input {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input i {
        position: absolute;
        left: 11px;
        color: var(--muted);
        font-size: 0.82rem;
        pointer-events: none;
    }

    .search-input input {
        padding: 7px 14px 7px 32px;
        border: 1.5px solid var(--border);
        border-radius: 40px;
        font-size: 0.82rem;
        width: 200px;
        outline: none;
        background: #fff;
        color: var(--text);
        transition: border-color 0.2s, box-shadow 0.2s, width 0.2s;
        font-family: inherit;
    }

    .search-input input:focus {
        border-color: var(--teal);
        box-shadow: 0 0 0 3px rgba(14, 165, 160, 0.10);
        width: 240px;
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

    /* Price */
    .price-text {
        font-weight: 700;
        color: var(--primary);
        font-size: 0.86rem;
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

    /* Pagination */
    .pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 1rem 1.2rem;
        border-top: 1.5px solid var(--border);
        background: #fafcff;
        flex-wrap: wrap;
    }

    .page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        padding: 5px 12px;
        background: #fff;
        border: 1.5px solid var(--border);
        border-radius: 30px;
        color: var(--text);
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.18s;
        gap: 5px;
    }

    .page-btn:hover {
        background: var(--surface);
        border-color: var(--primary);
        color: var(--primary);
    }

    .active-page {
        background: var(--grad) !important;
        border-color: transparent !important;
        color: #fff !important;
    }

    @media (max-width: 640px) {
        .table-header {
            flex-direction: column;
            align-items: stretch;
        }
        .table-actions {
            flex-direction: column;
        }
        .search-input input,
        .search-input input:focus {
            width: 100%;
        }
        .filter-select {
            width: 100%;
        }
        .pagination {
            gap: 4px;
        }
        .page-btn {
            padding: 4px 9px;
            min-width: 30px;
            font-size: 0.76rem;
        }
    }
</style>

<div class="table-card">

    <div class="table-header">
        <h3>Manage Advertisements</h3>
        <div class="table-actions">

            <form method="GET">
                <input type="hidden" name="page" value="ads">
                <select name="filter" class="filter-select" onchange="this.form.submit()">
                    <option value="">All Ads</option>
                    <option value="premium" <?php echo ($_GET['filter'] ?? '') === 'premium' ? 'selected' : ''; ?>>Premium Ads</option>
                    <option value="special"  <?php echo ($_GET['filter'] ?? '') === 'special'  ? 'selected' : ''; ?>>Special Ads</option>
                    <option value="basic"    <?php echo ($_GET['filter'] ?? '') === 'basic'    ? 'selected' : ''; ?>>Basic Ads</option>
                    <option value="sold"     <?php echo ($_GET['filter'] ?? '') === 'sold'     ? 'selected' : ''; ?>>Sold Ads</option>
                </select>
            </form>

            <form method="GET">
                <input type="hidden" name="page" value="ads">
                <div class="search-input">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input
                        type="text"
                        name="search"
                        placeholder="Search products..."
                        value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                    >
                </div>
            </form>

        </div>
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
                    <th>Price</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($ad = mysqli_fetch_assoc($all_ads_query)): ?>

            <?php
                $plan_class = 'basic-plan';
                if ($ad['boost_type'] === 'premium') {
                    $plan_class = 'premium-plan';
                } elseif ($ad['boost_type'] === 'special') {
                    $plan_class = 'special-plan';
                }

                $status_class = $ad['status'] === 'active'
                    ? 'active-status'
                    : 'sold-status';
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
                    <span class="category-badge"><?php echo htmlspecialchars($ad['category']); ?></span>
                </td>

                <td>
                    <span class="price-text">₹<?php echo number_format($ad['price']); ?></span>
                </td>

                <td>
                    <?php if (!empty($ad['boost_type'])): ?>
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

        <div class="pagination">

            <?php if($page_number > 1): ?>
                <a href="?page=ads&pageno=<?php echo $page_number - 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&filter=<?php echo urlencode($_GET['filter'] ?? ''); ?>"
                   class="page-btn">
                    <i class="fa-solid fa-chevron-left"></i> Prev
                </a>
            <?php endif; ?>

            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=ads&pageno=<?php echo $i; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&filter=<?php echo urlencode($_GET['filter'] ?? ''); ?>"
                   class="page-btn <?php echo $i == $page_number ? 'active-page' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if($page_number < $total_pages): ?>
                <a href="?page=ads&pageno=<?php echo $page_number + 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&filter=<?php echo urlencode($_GET['filter'] ?? ''); ?>"
                   class="page-btn">
                    Next <i class="fa-solid fa-chevron-right"></i>
                </a>
            <?php endif; ?>

        </div>

    </div>

</div>