<?php require_once __DIR__ . '/users_data.php'; ?>

<style>
    /* ===== USERS PAGE – Blue/Teal UI (BikriBazaar) ===== */
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

    .table-header h3 i {
        color: var(--teal);
    }

    .search-input {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input i {
        position: absolute;
        left: 22px;
        color: var(--muted);
        font-size: 0.82rem;
        pointer-events: none;
    }

    .search-input input {
        padding: 8px 14px 8px 32px;
        border: 1.5px solid #696d79;
        border-radius: 40px;
        font-size: 0.83rem;
        width: 220px;
        outline: none;
        background: #fff;
        color: var(--text);
        transition: border-color 0.2s, box-shadow 0.2s, width 0.2s;
        font-family: inherit;
    }

    .search-input input:focus {
        border-color: var(--teal);
        box-shadow: 0 0 0 3px rgba(14, 165, 160, 0.10);
        width: 260px;
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
        padding: 11px 14px;
        background: var(--grad);
        color: #fff;
        font-weight: 600;
        font-size: 0.78rem;
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
        padding: 10px 14px;
        color: var(--text);
        vertical-align: middle;
    }

    /* User name cell with avatar initials */
    .user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--grad);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
        text-transform: uppercase;
    }

    .user-name-text {
        font-weight: 600;
        font-size: 0.84rem;
        color: var(--text);
    }

    /* ID badge */
    .id-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 9px;
        background: #eef2ff;
        color: var(--primary);
        border-radius: 20px;
        font-size: 0.74rem;
        font-weight: 700;
    }

    /* Ads count badge */
    .ads-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        background: rgba(14, 165, 160, 0.10);
        color: var(--teal);
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    /* Date text */
    .date-text {
        color: var(--muted);
        font-size: 0.8rem;
    }

    /* Email text */
    .email-text {
        color: var(--muted);
        font-size: 0.82rem;
    }

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
        transition: all 0.2s;
        text-decoration: none;
        font-size: 0.82rem;
    }

    .delete-btn:hover {
        background: #ef4444;
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
        .table-header {
            flex-direction: column;
            align-items: stretch;
        }
        .search-input input,
        .search-input input:focus {
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
        <h3><i class="fa-solid fa-users"></i> User Management</h3>
        <div class="table-actions">
            <form method="GET">
                <input type="hidden" name="page" value="users">
                <div class="search-input">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input
                        type="text"
                        name="search"
                        placeholder="Search users..."
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
                    <th>Name</th>
                    <th>Email</th>
                    <th>Total Ads</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($user = mysqli_fetch_assoc($all_users_query)): ?>
                <?php
                    // Generate initials for avatar
                    $nameParts = explode(' ', trim($user['name']));
                    $initials  = strtoupper(substr($nameParts[0], 0, 1));
                    if (isset($nameParts[1])) {
                        $initials .= strtoupper(substr($nameParts[1], 0, 1));
                    }
                ?>
                <tr>
                    <td>
                        <span class="id-badge">#USR<?php echo $user['id']; ?></span>
                    </td>
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar"><?php echo $initials; ?></div>
                            <span class="user-name-text"><?php echo htmlspecialchars($user['name']); ?></span>
                        </div>
                    </td>
                    <td>
                        <span class="email-text"><?php echo htmlspecialchars($user['email']); ?></span>
                    </td>
                    <td>
                        <span class="ads-badge">
                            <i class="fa-solid fa-tag" style="font-size:0.7rem;"></i>
                            <?php echo $user['total_ads']; ?>
                        </span>
                    </td>
                    <td>
                        <span class="date-text"><?php echo date('d M Y', strtotime($user['created_at'])); ?></span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="admin_page.php?page=delete_user&id=<?php echo $user['id']; ?>"
                               class="delete-btn"
                               onclick="event.stopImmediatePropagation(); return confirm('Are you sure you want to delete this user and all of their ads? This action cannot be undone.');">
                                <i class="fa-solid fa-user-slash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="pagination">
        <?php if($page_number > 1): ?>
            <a href="?page=users&pageno=<?php echo $page_number - 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>"
               class="page-btn">
                <i class="fa-solid fa-chevron-left"></i> Prev
            </a>
        <?php endif; ?>

        <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=users&pageno=<?php echo $i; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>"
               class="page-btn <?php echo $i == $page_number ? 'active-page' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if($page_number < $total_pages): ?>
            <a href="?page=users&pageno=<?php echo $page_number + 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>"
               class="page-btn">
                Next <i class="fa-solid fa-chevron-right"></i>
            </a>
        <?php endif; ?>
    </div>

</div>