<?php

require_once __DIR__ . '/../../../shared/db.php';

$activity_query = mysqli_query(

    $conn,

    "SELECT *
     FROM activity_logs
     ORDER BY created_at DESC
     LIMIT 5"

);

while($activity = mysqli_fetch_assoc($activity_query)){

?>

<div class="activity-item">

    <div class="activity-dot"></div>

    <p>

        <?php echo htmlspecialchars(
            $activity['activity_message']
        ); ?>

    </p>

</div>

<?php

}
?>