<?php
// modules/chat/inbox_data.php
session_start();
require_once __DIR__ . '/../../shared/db.php';
require_once __DIR__ . '/inbox_functions.php'; 

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'unauthorized']);
    exit();
}

$current_user = intval($_SESSION['user_id']);

// Call our single shared source of truth function
$result = getLatestUserChats($conn, $current_user); 

$chats = [];
while ($chat = mysqli_fetch_assoc($result)) {
    $isUnread = ($chat['is_seen'] == 0 && $chat['receiver_id'] == $current_user);
    
    // Evaluate if the logged-in user wrote the last message item
    $isMine = ($chat['sender_id'] == $current_user);
    
    $chats[] = [
        'product_id'   => $chat['product_id'],
        'partner_id'   => $chat['partner_id'],
        'partner_name' => htmlspecialchars($chat['partner_name']),
        'product_title'=> htmlspecialchars($chat['product_title']),
        'message'      => htmlspecialchars($chat['message']),
        'is_unread'    => $isUnread,
        'is_mine'      => $isMine
    ];
}

echo json_encode(['status' => 'success', 'chats' => $chats]);
exit();
?>