<?php require_once __DIR__ . '/settings_data.php'; ?>

<div class="table-card">

    <div class="table-header">

        <h3>Settings</h3>

    </div>

    <form id="settingsForm" action="../modules/admin/settings/update_settings.php" method="POST">

        <div class="form-grid">

            <div class="form-group">

                <label>Website Name</label>

                <input
                    type="text"
                    name="site_name"
                    value="<?php echo htmlspecialchars($settings['site_name']); ?>"
                >

            </div>

            <div class="form-group">

                <label>Support Email</label>

                <input
                    type="email"
                    name="support_email"
                    value="<?php echo htmlspecialchars($settings['support_email']); ?>"
                >

            </div>

            <div class="form-group">

                <label>Maintenance Mode</label>

                <select name="maintenance_mode">

                    <option value="off"
                        <?php echo ($settings['maintenance_mode'] === 'off') ? 'selected' : ''; ?>>
                        OFF
                    </option>

                    <option value="on"
                        <?php echo ($settings['maintenance_mode'] === 'on') ? 'selected' : ''; ?>>
                        ON
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label>Theme Mode</label>

                <select name="theme_mode">

                    <option value="light"
                        <?php echo ($settings['theme_mode'] === 'light') ? 'selected' : ''; ?>>
                        Light
                    </option>

                    <option value="dark"
                        <?php echo ($settings['theme_mode'] === 'dark') ? 'selected' : ''; ?>>
                        Dark
                    </option>

                </select>

            </div>

        </div>

        <button type="submit" class="save-btn">

            Save Settings

        </button>

    </form>

</div>
<script>

document
.getElementById('settingsForm')

.addEventListener(

    'submit',

    function(e){

        e.preventDefault();

        const formData = new FormData(this);

        fetch(

            '../modules/admin/settings/update_settings.php',

            {

                method: 'POST',

                body: formData

            }

        )

        .then(response => response.text())

        .then(data => {

            const themeMode =
                formData.get('theme_mode');

            if(themeMode === 'dark'){

                document.body.classList.add('dark');

                localStorage.setItem(
                    'admin_theme',
                    'dark'
                );

            }else{

                document.body.classList.remove('dark');

                localStorage.setItem(
                    'admin_theme',
                    'light'
                );

            }

            alert('Settings Saved');

        });

    }

);

// LOAD SAVED THEME

const savedTheme =
    localStorage.getItem('admin_theme');

if(savedTheme === 'dark'){

    document.body.classList.add('dark');

}

</script>
