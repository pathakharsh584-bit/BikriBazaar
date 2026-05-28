<?php require_once __DIR__ . '/activity_logs_data.php'; ?>

<div class="table-card">

    <div class="table-header">

        <h3>24 Hours Activity Logs</h3>

    </div>

    <div class="table-wrapper">

        <table>

            <thead>

                <tr>

                    <th>#</th>

                    <th>Activity</th>

                    <th>Time</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $count = 1;

                while($log = mysqli_fetch_assoc($activity_logs_query)):

                ?>

                <tr>

                    <td>

                        <?php echo $count++; ?>

                    </td>

                    <td>

                        <?php echo htmlspecialchars(
                            $log['activity_message']
                        ); ?>

                    </td>

                    <td>

                        <?php

                        echo date(

                            'd M Y h:i A',

                            strtotime($log['created_at'])

                        );

                        ?>

                    </td>

                </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>