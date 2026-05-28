<?php

require_once __DIR__ . '/../../../shared/db.php';

$query = mysqli_query(

    $conn,

    "SELECT *
     FROM activity_logs
     ORDER BY created_at DESC
     LIMIT 5"

);

while($activity = mysqli_fetch_assoc($query)):

?>

<div class="activity-item">

    <span class="activity-dot"></span>

    <?php

    echo htmlspecialchars(
        $activity['activity_message']
    );

    ?>

</div>

<?php endwhile; ?>