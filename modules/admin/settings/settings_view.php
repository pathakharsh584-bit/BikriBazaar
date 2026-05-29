<?php require_once __DIR__ . '/settings_data.php'; ?>

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

    form {
        padding: 1.5rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.2rem;
        margin-bottom: 1.8rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
    }

    .form-group label {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text);
        letter-spacing: 0.3px;
    }

    .form-group label i {
        margin-right: 6px;
        color: var(--teal);
        width: 18px;
    }

    .form-group input,
    .form-group select {
        padding: 0.7rem 1rem;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        font-size: 0.9rem;
        font-family: inherit;
        background: #fff;
        transition: all 0.2s;
        outline: none;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(26,63,196,0.1);
    }

    .save-btn {
        background: var(--grad);
        color: white;
        border: none;
        border-radius: 40px;
        padding: 0.75rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: 23%;
        justify-content: center;
        text-align: center;
        left: 50px;
        position: relative;
        left: 29rem;

    }

    .save-btn:hover {
        opacity: 0.92;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(26,63,196,0.2);
    }

    /* Dark mode support (kept from original) */
    body.dark .table-card {
        background: #1e293b;
        border-color: #334155;
    }

    body.dark .table-header {
        background: #0f172a;
        border-color: #334155;
    }

    body.dark .table-header h3 {
        color: #94a3b8;
    }

    body.dark .form-group label {
        color: #cbd5e1;
    }

    body.dark .form-group input,
    body.dark .form-group select {
        background: #0f172a;
        border-color: #334155;
        color: #e2e8f0;
    }

    body.dark .form-group input:focus,
    body.dark .form-group select:focus {
        border-color: #0ea5a0;
    }

    @media (max-width: 640px) {
        form {
            padding: 1.2rem;
        }
        .form-grid {
            gap: 1rem;
        }
        .save-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="table-card">

    <div class="table-header">
        <h3>
            <i class="fa-solid fa-gear"></i>
            Settings
        </h3>
    </div>

    <form id="settingsForm" action="../modules/admin/settings/update_settings.php" method="POST">

        <div class="form-grid">

            <div class="form-group">
                <label><i class="fa-solid fa-globe"></i> Website Name</label>
                <input type="text" name="site_name"
                       value="<?php echo htmlspecialchars($settings['site_name']); ?>">
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-envelope"></i> Support Email</label>
                <input type="email" name="support_email"
                       value="<?php echo htmlspecialchars($settings['support_email']); ?>">
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-wrench"></i> Maintenance Mode</label>
                <select name="maintenance_mode">
                    <option value="off" <?php echo ($settings['maintenance_mode'] === 'off') ? 'selected' : ''; ?>>OFF</option>
                    <option value="on" <?php echo ($settings['maintenance_mode'] === 'on') ? 'selected' : ''; ?>>ON</option>
                </select>
            </div>

            <div class="form-group">
                <label><i class="fa-solid fa-moon"></i> Theme Mode</label>
                <select name="theme_mode">
                    <option value="light" <?php echo ($settings['theme_mode'] === 'light') ? 'selected' : ''; ?>>Light</option>
                    <option value="dark" <?php echo ($settings['theme_mode'] === 'dark') ? 'selected' : ''; ?>>Dark</option>
                </select>
            </div>

        </div>

        <button type="submit" class="save-btn">
            <i class="fa-solid fa-floppy-disk"></i> Save Settings
        </button>

    </form>

</div>

<script>
    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('../modules/admin/settings/update_settings.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            const themeMode = formData.get('theme_mode');
            if (themeMode === 'dark') {
                document.body.classList.add('dark');
                localStorage.setItem('admin_theme', 'dark');
            } else {
                document.body.classList.remove('dark');
                localStorage.setItem('admin_theme', 'light');
            }
            alert('Settings Saved');
        });
    });

    // Load saved theme
    const savedTheme = localStorage.getItem('admin_theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark');
    }
</script>