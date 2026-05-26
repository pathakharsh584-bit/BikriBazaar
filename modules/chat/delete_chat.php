<?php
session_start();

require_once __DIR__ . '/../../shared/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'unauthorized'
    ]);
    exit();
}

$current_user = intval($_SESSION['user_id']);

// Correctly pulling from $_POST since your JS uses FormData
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$partner_id = isset($_POST['partner_id']) ? intval($_POST['partner_id']) : 0;

if ($product_id <= 0 || $partner_id <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid data'
    ]);
    exit();
}

// STEP 1: OPTIMIZED HARD DELETE
// If the OTHER user already soft-deleted this chat, neither of you needs it anymore.
// We permanently delete it here to keep your database lean and optimized.
$sql_hard_delete = "
    DELETE FROM messages 
    WHERE product_id = ? 
    AND (
        (sender_id = ? AND receiver_id = ?) 
        OR 
        (sender_id = ? AND receiver_id = ?)
    )
    AND deleted_by IS NOT NULL 
    AND deleted_by != ?
";

$stmt1 = mysqli_prepare($conn, $sql_hard_delete);
mysqli_stmt_bind_param(
    $stmt1, 
    "iiiiii", 
    $product_id, 
    $current_user, 
    $partner_id, 
    $partner_id, 
    $current_user, 
    $current_user
);
mysqli_stmt_execute($stmt1);
mysqli_stmt_close($stmt1);


// STEP 2: SOFT DELETE
// For messages that haven't been deleted by anyone yet, mark them as deleted by the current user.
$sql_soft_delete = "
    UPDATE messages 
    SET deleted_by = ? 
    WHERE product_id = ? 
    AND (
        (sender_id = ? AND receiver_id = ?) 
        OR 
        (sender_id = ? AND receiver_id = ?)
    )
    AND deleted_by IS NULL
";

$stmt2 = mysqli_prepare($conn, $sql_soft_delete);
mysqli_stmt_bind_param(
    $stmt2, 
    "iiiiii", 
    $current_user, 
    $product_id, 
    $current_user, 
    $partner_id, 
    $partner_id, 
    $current_user
);

$success = mysqli_stmt_execute($stmt2);

if ($success) {
    echo json_encode([
        'status' => 'success'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error'
    ]);
}

mysqli_stmt_close($stmt2);
exit();
?>