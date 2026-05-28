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
                    <th>Product</th>
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
                    <?php echo htmlspecialchars($ad['title']); ?>
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

                    echo date(
                        'd M Y, h:i A',
                        strtotime($ad['deleted_at'])
                    );

                    ?>

                </td>

                <td>

                    <div class="action-buttons">

                        <button 
                            class="restore-btn"
                            data-id="<?php echo $ad['id']; ?>"
                        >
                            <i class="fa-solid fa-rotate-left"></i>
                        </button>

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
                href="?page=trash&pageno=<?php echo $page_number - 1; ?>"
                class="page-btn"
            >
                Prev
            </a>

        <?php endif; ?>

        <?php for($i = 1; $i <= $total_pages; $i++): ?>

            <a 
                href="?page=trash&pageno=<?php echo $i; ?>"
                class="page-btn <?php echo $i == $page_number ? 'active-page' : ''; ?>"
            >
                <?php echo $i; ?>
            </a>

        <?php endfor; ?>

        <?php if($page_number < $total_pages): ?>

            <a 
                href="?page=trash&pageno=<?php echo $page_number + 1; ?>"
                class="page-btn"
            >
                Next
            </a>

        <?php endif; ?>

    </div>

</div>