<?php

require_once __DIR__ . '/reported_ads_data.php';

?>

<div class="table-card">

    <div class="table-header">

        <h3>

            🚨 Reported Ads

        </h3>

    </div>

    <table class="data-table">

        <thead>

            <tr>

                <th>Ad Name</th>

                <th>Price</th>

                <th>Category</th>

                <th>Reason</th>

                <th>Action</th>

            </tr>

        </thead>

        <tbody>

            <?php while($report = mysqli_fetch_assoc($reports_query)): ?>

                <tr>
                    <td>
                        <?php 
                            $displayImage = !empty($report['image']) ? $report['image'] : BASE_URL . 'assets/images/default-placeholder.png';
                        ?>
                        <img src="<?php echo htmlspecialchars($displayImage); ?>" 
                             alt="Ad Image" 
                             style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                    </td>

                    <td>

                        <?php echo htmlspecialchars($report['title']); ?>

                    </td>

                    <td>

                        ₹ <?php echo number_format($report['price']); ?>

                    </td>

                    <td>

                        <?php echo htmlspecialchars($report['category']); ?>

                    </td>

                    <td>

                        <?php echo htmlspecialchars($report['reason']); ?>

                    </td>

                    <td>

                        <a href="admin_page.php?page=delete_reported_ad&id=<?php echo $report['product_id']; ?>"
   class="delete-btn">

    Delete

</a>

                    </td>

                </tr>

            <?php endwhile; ?>

        </tbody>

    </table>

</div>