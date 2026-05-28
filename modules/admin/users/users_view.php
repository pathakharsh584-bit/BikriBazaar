<?php require_once __DIR__ . '/users_data.php'; ?>

<div class="table-card">

    <div class="table-header">

        <h3>User Management</h3>

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

            <tr>

                <td>
                    #USR<?php echo $user['id']; ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($user['name']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($user['email']); ?>
                </td>

                <td>
                    <?php echo $user['total_ads']; ?>
                </td>

                <td>

                    <?php

                    echo date(
                        'd M Y',
                        strtotime($user['created_at'])
                    );

                    ?>

                </td>

                <td>

                    <div class="action-buttons">

    <a href="admin_page.php?page=delete_user&id=<?php echo $user['id']; ?>"
       class="delete-btn"
       onclick=" event.stopImmediatePropagation(); return confirm('Are you sure you want to delete this user and all of their ads? This action cannot be undone.');">

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

            <a 
                href="?page=users&pageno=<?php echo $page_number - 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>"
                class="page-btn"
            >
                Prev
            </a>

        <?php endif; ?>

        <?php for($i = 1; $i <= $total_pages; $i++): ?>

            <a 
                href="?page=users&pageno=<?php echo $i; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>"
                class="page-btn <?php echo $i == $page_number ? 'active-page' : ''; ?>"
            >
                <?php echo $i; ?>
            </a>

        <?php endfor; ?>

        <?php if($page_number < $total_pages): ?>

            <a 
                href="?page=users&pageno=<?php echo $page_number + 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>"
                class="page-btn"
            >
                Next
            </a>

        <?php endif; ?>

    </div>

</div>