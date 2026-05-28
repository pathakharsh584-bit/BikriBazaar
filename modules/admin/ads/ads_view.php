<?php require_once __DIR__ . '/ads_data.php'; ?>

<div class="table-card">

    <div class="table-header">

    <h3>Manage Advertisements</h3>

    <div class="table-actions">
         <form method="GET">

    <input type="hidden" name="page" value="ads">

    <select name="filter" class="filter-select" onchange="this.form.submit()">

        <option value="">All Ads</option>

        <option 
            value="premium"
            <?php echo ($_GET['filter'] ?? '') === 'premium' ? 'selected' : ''; ?>
        >
            Premium Ads
        </option>

        <option 
            value="special"
            <?php echo ($_GET['filter'] ?? '') === 'special' ? 'selected' : ''; ?>
        >
            Special Ads
        </option>

        <option 
            value="basic"
            <?php echo ($_GET['filter'] ?? '') === 'basic' ? 'selected' : ''; ?>
        >
            Basic Ads
        </option>

        <option 
            value="sold"
            <?php echo ($_GET['filter'] ?? '') === 'sold' ? 'selected' : ''; ?>
        >
            Sold Ads
        </option>

    </select>

</form>
        <form method="GET">

            <input type="hidden" name="page" value="ads">

            <div class="search-input">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input 
                    type="text"
                    name="search"
                    placeholder="Search products..."
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
                    <th>Image</th>
                    <th>Product</th>
                    <th>Seller</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>

            </thead>

            <tbody>

            <?php while($ad = mysqli_fetch_assoc($all_ads_query)): ?>

            <?php

            $plan_class = 'basic-plan';

            if($ad['boost_type'] === 'premium'){
                $plan_class = 'premium-plan';
            }

            elseif($ad['boost_type'] === 'special'){
                $plan_class = 'special-plan';
            }

            $status_class = $ad['status'] === 'active'
                ? 'active-status'
                : 'sold-status';

            ?>

            <tr>

                <td>
                    #AD<?php echo $ad['id']; ?>
                </td>

                <td>
        <?php 
            // Fallback to placeholder if Cloudinary image is missing
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
                    <?php echo htmlspecialchars($ad['category']); ?>
                </td>

                <td>
                    ₹<?php echo number_format($ad['price']); ?>
                </td>

                <td>

    <?php if(!empty($ad['boost_type'])): ?>

        <span class="plan-badge <?php echo $plan_class; ?>">

            <?php echo ucfirst($ad['boost_type']); ?>

        </span>

    <?php else: ?>

        <span style="color:#9ca3af;">
            No Plan
        </span>

    <?php endif; ?>

</td>

                <td>

                    <span class="status <?php echo $status_class; ?>">

                        <?php echo ucfirst($ad['status']); ?>

                    </span>

                </td>

                <td>

                    <div class="action-buttons">


                        <button 
                            class="delete-btn"
                            data-id="<?php echo $ad['id']; ?>"
                        >
                            <i class="fa-solid fa-trash"></i>
                        </button>

                    </div>

                </td>

            </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

        <div class="pagination">

    <?php if($page_number > 1): ?>

        <a 
            href="?page=ads&pageno=<?php echo $page_number - 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&filter=<?php echo urlencode($_GET['filter'] ?? ''); ?>"
            class="page-btn"
        >
            Prev
        </a>

    <?php endif; ?>

    <?php for($i = 1; $i <= $total_pages; $i++): ?>

        <a 
            href="?page=ads&pageno=<?php echo $i; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&filter=<?php echo urlencode($_GET['filter'] ?? ''); ?>"
            class="page-btn <?php echo $i == $page_number ? 'active-page' : ''; ?>"
        >
            <?php echo $i; ?>
        </a>

    <?php endfor; ?>

    <?php if($page_number < $total_pages): ?>

        <a 
            href="?page=ads&pageno=<?php echo $page_number + 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>&filter=<?php echo urlencode($_GET['filter'] ?? ''); ?>"
            class="page-btn"
        >
            Next
        </a>

    <?php endif; ?>

</div>

    </div>

</div>
