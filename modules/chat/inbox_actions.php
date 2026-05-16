<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Going up two levels to hit your shared database connection
require_once __DIR__ . '/../../shared/db.php';

$current_user = intval($_SESSION['user_id']);

// Heavy Query: Pulls the latest message row from every unique user conversation lane
$sql = "SELECT m.*, 
               u.id as partner_id, u.name as partner_name,
               p.title as product_title
        FROM messages m
        INNER JOIN users u ON (u.id = m.sender_id OR u.id = m.receiver_id) AND u.id != $current_user
        INNER JOIN products p ON p.id = m.product_id
        WHERE m.id IN (
            SELECT MAX(id) FROM messages 
            WHERE sender_id = $current_user OR receiver_id = $current_user 
            GROUP BY product_id, LEAST(sender_id, receiver_id), GREATEST(sender_id, receiver_id)
        )
        ORDER BY m.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox - BikriBazaar</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family: Arial, sans-serif; }
        body { background:#f5f5f5; color: #002f34; }
        .navbar { background:#002f34; padding:15px 40px; color:white; display:flex; justify-content:space-between; align-items:center; }
        .navbar a { color:white; text-decoration:none; font-weight:bold; margin-left:15px; }
        .container { max-width: 700px; margin: 40px auto; padding: 0 20px; }
        .inbox-card { background: white; border-radius: 10px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        h2 { font-size: 24px; margin-bottom: 20px; font-weight: 700; color: #002f34; }
        .chat-item { display: flex; justify-content: space-between; align-items: center; padding: 20px 15px; border-bottom: 1px solid #f1f5f9; text-decoration: none; color: inherit; transition: background 0.2s ease; border-radius: 6px; }
        .chat-item:hover { background: #f8fafc; }
        .partner-avatar { width: 45px; height: 45px; border-radius: 50%; background: #e0f2fe; color: #0369a1; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px; margin-right: 15px; text-transform: uppercase; }
        .chat-details { flex: 1; }
        .chat-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
        .partner-name { font-size: 16px; font-weight: bold; }
        .product-tag { font-size: 12px; background: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 12px; font-weight: 600; }
        .last-msg { font-size: 14px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 400px; }
        .unread-dot { width: 10px; height: 10px; background: #312e81; border-radius: 50%; margin-left: 10px; }
        .empty-state { text-align: center; padding: 40px; color: #64748b; }
    </style>
</head>
<body>

<div class="navbar">
    <div style="font-size: 20px; font-weight: bold;">BikriBazaar</div>
    <div>
        <a href="index.php">Home</a>
        <a href="my-ads.php">My Ads</a>
    </div>
</div>

<div class="container">
    <div class="inbox-card">
        <h2>📥 Your Chats</h2>
        
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($chat = mysqli_fetch_assoc($result)): ?>
                <a href="chat.php?product_id=<?php echo $chat['product_id']; ?>&receiver_id=<?php echo $chat['partner_id']; ?>" class="chat-item">
                    <div style="display: flex; align-items: center; width: 100%;">
                        <div class="partner-avatar">
                            <?php echo substr(htmlspecialchars($chat['partner_name']), 0, 1); ?>
                        </div>
                        <div class="chat-details">
                            <div class="chat-meta">
                                <span class="partner-name"><?php echo htmlspecialchars($chat['partner_name']); ?></span>
                                <span class="product-tag"><?php echo htmlspecialchars($chat['product_title']); ?></span>
                            </div>
                            <p class="last-msg"><?php echo htmlspecialchars($chat['message']); ?></p>
                        </div>
                        <?php if($chat['is_seen'] == 0 && $chat['receiver_id'] == $current_user): ?>
                            <div class="unread-dot"></div>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <h3>No active chats found</h3>
                <p style="font-size: 14px; margin-top: 5px;">Conversations with other marketplace users will pull together here.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>