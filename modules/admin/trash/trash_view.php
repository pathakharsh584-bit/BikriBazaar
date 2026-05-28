<?php require_once __DIR__ . '/trash_data.php'; ?>

<div class="table-card">
    <div class="table-header">
        <h3>Deleted Advertisements</h3>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th> <th>Product</th>
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
                    #AD<?php echo $ad['id']; ?>
                </td>

                <td>
                    <?php 
                        $displayImage = !empty($ad['image']) ? $ad['image'] : BASE_URL . 'assets/images/default-placeholder.png';
                    ?>
                    <img src="<?php echo htmlspecialchars($displayImage); ?>" 
                         alt="Ad Image" 
                         style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                </td>

                <td>
                    <?php echo htmlspecialchars($ad['title']); ?>
                    
                    <?php if($ad['was_reported']): ?>
                        <span style="color: #dc2626; font-size: 0.75rem; margin-left: 6px; font-weight: bold; background: #fee2e2; padding: 2px 6px; border-radius: 4px;">
                            🚨 Banned
                        </span>
                    <?php endif; ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($ad['seller_name']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($ad['category']); ?>
                </td>

                <td>
                    ₹<?php echo number_format($ad['price']); ?>
                </td>

                <td>
                    <?php
                    if (!empty($ad['deleted_at'])) {
                        echo date('d M Y, h:i A', strtotime($ad['deleted_at']));
                    } else {
                        echo "<span style='color: #9ca3af;'>Unknown Date</span>";
                    }
                    ?>
                </td>

                <td>
                    <div class="action-buttons">
                        <a href="admin_page.php?page=restore_ad&id=<?php echo $ad['id']; ?>" 
                           class="restore-btn" 
                           style="background: #d1fae5; color: #059669; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block;"
                           onclick="event.stopImmediatePropagation(); return confirm('Are you sure you want to restore this advertisement?');">
                            <i class="fa-solid fa-rotate-left"></i> 
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
            <a href="?page=trash&pageno=<?php echo $page_number - 1; ?>" class="page-btn">Prev</a>
        <?php endif; ?>

        <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=trash&pageno=<?php echo $i; ?>" class="page-btn <?php echo $i == $page_number ? 'active-page' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if($page_number < $total_pages): ?>
            <a href="?page=trash&pageno=<?php echo $page_number + 1; ?>" class="page-btn">Next</a>
        <?php endif; ?>
    </div>
</div>