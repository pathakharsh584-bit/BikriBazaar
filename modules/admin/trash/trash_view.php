<?php require_once __DIR__ . '/trash_data.php'; ?>

<style>
    /* ===== TRASH PAGE – Blue/Teal UI (BikriBazaar) ===== */
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
        color: var(--primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-header h3 i { color: var(--teal); }

    .table-wrapper { overflow-x: auto; }

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

    tbody tr:hover { background: var(--surface); }
    tbody tr:last-child { border-bottom: none; }

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
        opacity: 0.75;
    }

    .ad-title {
        font-weight: 600;
        font-size: 0.84rem;
        color: var(--text);
    }

    .banned-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        background: #fee2e2;
        color: #b91c1c;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        margin-left: 6px;
        vertical-align: middle;
    }

    .seller-text {
        color: var(--muted);
        font-size: 0.81rem;
    }

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

    .price-text {
        font-weight: 700;
        color: var(--primary);
        font-size: 0.86rem;
    }

    .date-text {
        color: var(--muted);
        font-size: 0.79rem;
    }

    .unknown-date {
        color: #9ca3af;
        font-size: 0.79rem;
    }

    .action-buttons { display: flex; gap: 0.5rem; }

    .restore-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 5px 12px;
        background: #d1fae5;
        color: #059669;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.78rem;
        font-weight: 700;
        transition: all 0.2s;
    }

    .restore-btn:hover {
        background: #059669;
        color: #fff;
        transform: translateY(-2px);
    }

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
        .table-header { flex-direction: column; align-items: stretch; }
        .page-btn { padding: 4px 9px; min-width: 30px; font-size: 0.76rem; }
    }
</style>

<div class="table-card">
    <div class="table-header">
        <h3>
            <i class="fa-solid fa-trash-can"></i>
            Deleted Advertisements
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
                    <th>Category</th>
                    <th>Price</th>
                    <th>Deleted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($ad = mysqli_fetch_assoc($deleted_ads_query)): ?>
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
                    <?php if($ad['was_reported']): ?>
                        <span class="banned-badge">
                            <i class="fa-solid fa-ban" style="font-size:0.65rem;"></i> Banned
                        </span>
                    <?php endif; ?>
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
    <?php
    if (!empty($ad['deleted_at']) && $ad['deleted_at'] !== '0000-00-00 00:00:00') {
        echo '<span class="date-text">' .
             date('d M Y, h:i A', strtotime($ad['deleted_at'])) .
             '</span>';
    } else {
        echo '<span class="date-text">Not Available</span>';
    }
    ?>
</td>

                <td>
                    <div class="action-buttons">
                        <a href="#"
   class="restore-btn"
   data-id="<?php echo $ad['id']; ?>"
                           onclick="event.stopImmediatePropagation(); return confirm('Are you sure you want to restore this advertisement?');">
                            <i class="fa-solid fa-rotate-left"></i> Restore
                        </a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <?php if($page_number > 1): ?>
            <a href="?page=trash&pageno=<?php echo $page_number - 1; ?>" class="page-btn">
                <i class="fa-solid fa-chevron-left"></i> Prev
            </a>
        <?php endif; ?>

        <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=trash&pageno=<?php echo $i; ?>"
               class="page-btn <?php echo $i == $page_number ? 'active-page' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if($page_number < $total_pages): ?>
            <a href="?page=trash&pageno=<?php echo $page_number + 1; ?>" class="page-btn">
                Next <i class="fa-solid fa-chevron-right"></i>
            </a>
        <?php endif; ?>
    </div>
</div>