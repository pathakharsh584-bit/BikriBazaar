<?php
session_start();
require_once __DIR__ . '/../../shared/db.php';

// Fail silently if unauthorized (AJAX will just receive empty data)
if(!isset($_SESSION['user_id'])) {
    exit(); 
}

$current_user = $_SESSION['user_id'];

// Determine if we are fetching or sending
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// ==========================================
// HANDLE FETCHING MESSAGES (GET)
// ==========================================
if ($action === 'fetch' && isset($_GET['product_id']) && isset($_GET['other_user_id'])) {
    
    $product_id = mysqli_real_escape_string($conn, $_GET['product_id']);
    $other_user = mysqli_real_escape_string($conn, $_GET['other_user_id']);

    // Mark messages as seen
    mysqli_query($conn, "UPDATE messages SET is_seen = 1 
                         WHERE product_id = '$product_id' AND sender_id = '$other_user' AND receiver_id = '$current_user'");

    // Grab conversation
    $sql = "SELECT * FROM messages 
            WHERE product_id = '$product_id' 
            AND ((sender_id = '$current_user' AND receiver_id = '$other_user') 
              OR (sender_id = '$other_user' AND receiver_id = '$current_user')) 
            ORDER BY created_at ASC";

    $result = mysqli_query($conn, $sql);

    // Generate HTML bubbles
    while($msg = mysqli_fetch_assoc($result)) {
        $isMine = ($msg['sender_id'] == $current_user);
        $wrapperClass = $isMine ? 'my-wrapper' : 'other-wrapper';
        $msgClass = $isMine ? 'my-message' : 'other-message';
        $time = date("h:i A", strtotime($msg['created_at']));
        
        echo "
        <div class='message-wrapper {$wrapperClass}'>
            <div class='message {$msgClass}'>" . nl2br(htmlspecialchars($msg['message'])) . "</div>
            <div class='message-meta'>{$time}</div>
        </div>";
    }
}

// ==========================================
// HANDLE SENDING MESSAGES (POST)
// ==========================================
if ($action === 'send' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $receiver_id = mysqli_real_escape_string($conn, $_POST['receiver_id']);
    $message = trim(mysqli_real_escape_string($conn, $_POST['message']));
    
    if(!empty($message)){
        $sql = "INSERT INTO messages (product_id, sender_id, receiver_id, message) 
                VALUES ('$product_id', '$current_user', '$receiver_id', '$message')";
        mysqli_query($conn, $sql);
    }
}
?>