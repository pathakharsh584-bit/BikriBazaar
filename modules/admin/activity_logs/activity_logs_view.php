<?php require_once __DIR__ . '/activity_logs_data.php'; ?>

<style>
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
        border-radius: 24px;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.2rem 1.5rem;
        border-bottom: 1px solid var(--border);
        background: #fafcff;
    }

    .table-header h3 {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-header h3 i {
        color: var(--teal);
    }

    .search-box {
        position: relative;
        display: flex;
        align-items: center;
        border: 1px solid #959595;
    }

    .search-box i {
        position: absolute;
        left: 12px;
        color: var(--muted);
        font-size: 0.85rem;
        pointer-events: none;
    }

    .search-box input {
        padding: 0.45rem 0.8rem 0.45rem 2rem;
        
        border-radius: 40px;
        font-size: 0.85rem;
        width: 220px;
        outline: none;
        transition: all 0.2s;
    }

    .search-box input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(26,63,196,0.1);
        width: 260px;
    }

    .table-wrapper {
        overflow-x: auto;
        padding: 0 0 0.5rem 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
    }

    thead th {
        text-align: left;
        padding: 12px 16px;
        background: var(--grad);
        color: #fff;
        font-weight: 600;
        font-size: 0.8rem;
        letter-spacing: 0.03em;
    }

    tbody tr {
        border-bottom: 1px solid var(--border);
        transition: background 0.15s;
    }

    tbody tr:hover {
        background: var(--surface);
    }

    tbody td {
        padding: 12px 16px;
        color: var(--text);
        vertical-align: middle;
    }

    /* Row number badge */
    .row-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: #eef2ff;
        color: var(--primary);
        border-radius: 50%;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .activity-message {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .activity-message i {
        width: 24px;
        color: var(--teal);
        font-size: 1rem;
    }

    .time-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        color: #475569;
    }

    .no-results {
        text-align: center;
        padding: 2rem;
        color: var(--muted);
    }

    @media (max-width: 640px) {
        .table-header {
            flex-direction: column;
            align-items: stretch;
        }
        .search-box input {
            width: 100%;
        }
        .search-box input:focus {
            width: 100%;
        }
        thead th, tbody td {
            padding: 8px 12px;
        }
    }
</style>

<div class="table-card">

    <div class="table-header">
        <h3>
            <i class="fa-solid fa-clock-rotate-left"></i>
            24 Hours Activity Logs
        </h3>
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="searchActivity" placeholder="Search activity...">
        </div>
    </div>

    <div class="table-wrapper">
        <table id="activityTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Activity</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = 1;
                while($log = mysqli_fetch_assoc($activity_logs_query)):
                ?>
                <tr class="activity-row">
                    <td><span class="row-num"><?php echo $count++; ?></span></td>
                    <td>
                        <div class="activity-message">
                            <i class="fa-solid fa-bell"></i>
                            <?php echo htmlspecialchars($log['activity_message']); ?>
                        </div>
                    </td>
                    <td>
                        <span class="time-badge">
                            <i class="fa-regular fa-calendar-alt"></i>
                            <?php echo date('d M Y h:i A', strtotime($log['created_at'])); ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    // Frontend search filtering – no backend changes
    const searchInput = document.getElementById('searchActivity');
    const rows = document.querySelectorAll('#activityTable tbody .activity-row');

    searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        rows.forEach(row => {
            const activityCell = row.cells[1]; // second column (activity message)
            if (activityCell) {
                const text = activityCell.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            }
        });
    });
</script>