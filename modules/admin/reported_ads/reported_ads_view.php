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