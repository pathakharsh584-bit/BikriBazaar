<?php
session_start();
require_once __DIR__ . '/../../shared/db.php';

header('Content-Type: application/json');

// If not logged in, return 0 safely
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['count' => 0]);
    exit();
}

$current_uid = intval($_SESSION['user_id']);
$sql = "SELECT COUNT(*) as total FROM messages WHERE receiver_id = $current_uid AND is_seen = 0";
$res = mysqli_query($conn, $sql);

$count = 0;
if ($res) {
    $row = mysqli_fetch_assoc($res);
    $count = intval($row['total']);
}

echo json_encode(['count' => $count]);
exit();
?>