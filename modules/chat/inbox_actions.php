<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../../shared/db.php';
require_once __DIR__ . '/inbox_functions.php'; 

$current_user = intval($_SESSION['user_id']);

// Fetch unread count for navbar badge
$unread_count = 0;
$unread_sql = "SELECT COUNT(*) as total FROM messages WHERE receiver_id = ? AND is_seen = 0";
$unread_stmt = mysqli_prepare($conn, $unread_sql);
mysqli_stmt_bind_param($unread_stmt, 'i', $current_user);
mysqli_stmt_execute($unread_stmt);
$unread_res = mysqli_stmt_get_result($unread_stmt);
if($unread_res){
    $unread_data = mysqli_fetch_assoc($unread_res);
    $unread_count = $unread_data['total'];
}
mysqli_stmt_close($unread_stmt);

$result = getLatestUserChats($conn, $current_user); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox - BikriBazaar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a3fc4;
            --primary-dark: #1530a0;
            --teal: #0ea5a0;
            --teal-dark: #0b8a86;
            --grad: linear-gradient(135deg, #1a3fc4 0%, #0ea5a0 100%);
            --surface: #f4f7ff;
            --card-bg: #ffffff;
            --text: #1a1a2e;
            --muted: #6b7280;
            --border: #dde4f5;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--surface);
            color: var(--text);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        a { text-decoration: none; color: inherit; }

        /* ===== INBOX PAGE STYLES ===== */
        .inbox-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            flex: 1;
        }
        .inbox-card {
            background: var(--card-bg);
            border-radius: 24px;
            box-shadow: 0 2px 11px #93a4e1;
            border: 1px solid var(--border);
            padding: 1.5rem;
            width: 100%;                 /* Fixed: remove fixed width, use full width */
        }
        .inbox-header {
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--teal);
            display: inline-block;
        }
        .inbox-header h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .chat-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .chat-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 16px;
            background: #fff;
            border: 1px solid var(--border);
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        .chat-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26,63,196,0.1);
            border-color: var(--teal);
        }
        .partner-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--grad);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.3rem;
            text-transform: uppercase;
            flex-shrink: 0;
        }
        .chat-info {
            flex: 1;
            min-width: 0;               /* Fixed: allows flex child to shrink below content size */
        }
        .chat-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.4rem;
        }
        .partner-name {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text);
        }
        .product-tag {
            font-size: 0.7rem;
            background: var(--surface);
            color: var(--primary);
            padding: 0.2rem 0.7rem;
            border-radius: 30px;
            font-weight: 600;
            max-width: 150px;           /* Fixed: prevent very long product names from breaking layout */
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
        }
        .last-message {
            font-size: 0.85rem;
            color: var(--muted);
            white-space: normal;        /* Fixed: allow wrapping instead of single line overflow */
            word-break: break-word;     /* Fixed: break long unbroken strings (e.g., "hhhhh...") */
            overflow-wrap: break-word;
            line-height: 1.4;
        }
        .my-prefix {
            color: #94a3b8;
            font-weight: 600;
        }
        .unread-dot {
            width: 10px;
            height: 10px;
            background: #ef4444;
            border-radius: 50%;
            flex-shrink: 0;
            margin-left: 0.5rem;
        }
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--muted);
        }
        .empty-state i {
            font-size: 3rem;
            color: var(--border);
            margin-bottom: 1rem;
            display: block;
        }
        .empty-state h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text);
        }
        @media (max-width: 640px) {
            .inbox-container { padding: 0 1rem; }
            .chat-item { padding: 0.75rem; }
            .partner-avatar { width: 45px; height: 45px; font-size: 1rem; }
            .product-tag { max-width: 120px; }
        }
    </style>
</head>
<body>

<!-- SHARED NAVBAR -->
<?php include __DIR__ . '/../../shared/components/navbar.php'; ?>

<div class="inbox-container">
    <div class="inbox-card">
        <div class="inbox-header">
            <h2><i class="fa-regular fa-message"></i> Your Chats</h2>
        </div>
        
        <div id="inbox-list-container" class="chat-list">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($chat = mysqli_fetch_assoc($result)): ?>
                    <?php 
                        $prefix = ($chat['sender_id'] == $current_user) ? '<span class="my-prefix">You: </span>' : '';
                    ?>
                    <a href="chat.php?product_id=<?php echo $chat['product_id']; ?>&receiver_id=<?php echo $chat['partner_id']; ?>" class="chat-item">
                        <div class="partner-avatar">
                            <?php echo substr(htmlspecialchars($chat['partner_name']), 0, 1); ?>
                        </div>
                        <div class="chat-info">
                            <div class="chat-meta">
                                <span class="partner-name"><?php echo htmlspecialchars($chat['partner_name']); ?></span>
                                <span class="product-tag"><?php echo htmlspecialchars($chat['product_title']); ?></span>
                            </div>
                            <div class="last-message"><?php echo $prefix . htmlspecialchars($chat['message']); ?></div>
                        </div>
                        <?php if($chat['is_seen'] == 0 && $chat['receiver_id'] == $current_user): ?>
                            <div class="unread-dot"></div>
                        <?php endif; ?>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fa-regular fa-inbox"></i>
                    <h3>No active chats found</h3>
                    <p style="margin-top: 0.5rem;">Conversations with other users will appear here.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    const inboxContainer = document.getElementById('inbox-list-container');

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function pollInboxUpdates() {
        fetch('../modules/chat/inbox_data.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.chats.length === 0) {
                        inboxContainer.innerHTML = `
                            <div class="empty-state">
                                <i class="fa-regular fa-inbox"></i>
                                <h3>No active chats found</h3>
                                <p style="margin-top: 0.5rem;">Conversations with other users will appear here.</p>
                            </div>`;
                        return;
                    }

                    let htmlBuilder = '';
                    data.chats.forEach(chat => {
                        const initial = chat.partner_name.charAt(0);
                        const unreadDot = chat.is_unread ? '<div class="unread-dot"></div>' : '';
                        const prefix = chat.is_mine ? '<span class="my-prefix">You: </span>' : '';

                        htmlBuilder += `
                            <a href="chat.php?product_id=${chat.product_id}&receiver_id=${chat.partner_id}" class="chat-item">
                                <div class="partner-avatar">${escapeHtml(initial)}</div>
                                <div class="chat-info">
                                    <div class="chat-meta">
                                        <span class="partner-name">${escapeHtml(chat.partner_name)}</span>
                                        <span class="product-tag">${escapeHtml(chat.product_title)}</span>
                                    </div>
                                    <div class="last-message">${prefix}${escapeHtml(chat.message)}</div>
                                </div>
                                ${unreadDot}
                            </a>`;
                    });
                    inboxContainer.innerHTML = htmlBuilder;
                }
            })
            .catch(error => console.error('Error fetching inbox updates:', error));
    }

    // Poll every 3 seconds
    setInterval(pollInboxUpdates, 3000);
</script>

</body>
</html>