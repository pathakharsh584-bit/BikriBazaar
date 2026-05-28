<?php require_once __DIR__ . '/promotional_data.php'; ?>

<div class="table-card">
    <div class="table-header">
        <h3>Promotional Advertisements</h3>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th> <th>Product</th>
                    <th>Seller</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Actions</th> </tr>
            </thead>
            <tbody>
                <?php while($ad = mysqli_fetch_assoc($promotional_query)): ?>
                <?php
                    // Set up badge classes for styling
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
                    </td>

                    <td>
                        <?php echo htmlspecialchars($ad['seller_name']); ?>
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