<?php require_once __DIR__ . '/categories_data.php'; ?>

<div class="table-card">

    <div class="table-header">

        <h3>Categories Management</h3>

    </div>

    <div class="table-wrapper">


    <div class="chart-card">

    <div class="card-header">

        <h3>Categories Distribution</h3>

    </div>

    <div style="height:400px;">

        <canvas id="categoryChart"></canvas>

    </div>

</div>

        <table>

            <thead>

                <tr>

                    <th>#</th>

                    <th>Category</th>

                    <th>Total Ads</th>

                    <th>Active Ads</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $count = 1;

                while($category = mysqli_fetch_assoc($categories_query)):

                ?>

                <tr>

                    <td>
                        <?php echo $count++; ?>
                    </td>

                    <td>

                        <?php echo htmlspecialchars($category['category']); ?>

                    </td>

                    <td>

                        <?php echo $category['total_ads']; ?>

                    </td>

                    <td>

                        <?php echo $category['active_ads']; ?>

                    </td>

                </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const categoryCtx =
    document.getElementById('categoryChart');

new Chart(categoryCtx, {

    type: 'doughnut',

    data: {

        labels: <?php echo json_encode($chart_labels); ?>,

        datasets: [{

            data: <?php echo json_encode($chart_data); ?>,

            backgroundColor: [

                '#6366f1',
                '#8b5cf6',
                '#ec4899',
                '#f97316',
                '#22c55e',
                '#06b6d4',
                '#eab308'

            ],

            borderWidth: 0,

            hoverOffset: 15

        }]

    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        cutout: '45%',

        plugins: {

            legend: {

                position: 'bottom'

            }

        }

    }

});
</script>