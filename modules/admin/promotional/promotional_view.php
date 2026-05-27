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
                    <th>Product</th>
                    <th>Seller</th>
                    <th>Plan</th>
                    <th>Status</th>
                </tr>

            </thead>

            <tbody>

                <?php while($ad = mysqli_fetch_assoc($promotional_query)): ?>

                <tr>

                    <td>
                        #AD<?php echo $ad['id']; ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($ad['title']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($ad['seller_name']); ?>
                    </td>

                    <td>
                        <?php echo ucfirst($ad['boost_type']); ?>
                    </td>

                    <td>
                        <?php echo ucfirst($ad['status']); ?>
                    </td>

                </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div> 