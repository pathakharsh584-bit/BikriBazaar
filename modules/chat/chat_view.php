<?php
// chat_view.php – frontend only, original AJAX logic preserved, improved styling with visible borders
if (!isset($product_id) || !isset($receiver_id)) {
    die("Invalid chat request.");
}

// Fetch product title and receiver name for header
$product_title = '';
$receiver_name = '';
if (!empty($product_id)) {
    $prod_sql = "SELECT title FROM products WHERE id = " . intval($product_id);
    $prod_res = mysqli_query($conn, $prod_sql);
    if ($prod_res && mysqli_num_rows($prod_res) > 0) {
        $prod_row = mysqli_fetch_assoc($prod_res);
        $product_title = htmlspecialchars($prod_row['title']);
    }
}
if (!empty($receiver_id)) {
    $rec_sql = "SELECT name FROM users WHERE id = " . intval($receiver_id);
    $rec_res = mysqli_query($conn, $rec_sql);
    if ($rec_res && mysqli_num_rows($rec_res) > 0) {
        $rec_row = mysqli_fetch_assoc($rec_res);
        $receiver_name = htmlspecialchars($rec_row['name']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo $receiver_name ?: 'Seller'; ?> - BikriBazaar</title>
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

        /* ===== CHAT PAGE STYLES ===== */
        .chat-wrapper {
            max-width: 1000px;
            margin: 1.5rem auto;
            padding: 0 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            height: calc(100vh - 90px);
            width:28em;
        }
        .chat-header {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 0.75rem 1.2rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            border: 1px solid #909295;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .chat-header h2 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
        }
        .chat-header .product-link {
            background: var(--surface);
            padding: 0.3rem 0.8rem;
            border-radius: 30px;
            font-size: 0.8rem;
            color: var(--primary);
            border: 1px solid #cbd5e1;
        }
        .messages-container {
            flex: 1;
            background: var(--card-bg);
            border-radius: 24px;
            border: 1px solid #909295;
            overflow-y: auto;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 14px 40px #a4afd5;
        }
        /* Message bubbles */
        .message-wrapper {
            display: flex;
            flex-direction: column;
            max-width: 75%;
        }
        .my-wrapper {
            align-self: flex-end;
            align-items: flex-end;
        }
        .other-wrapper {
            align-self: flex-start;
            align-items: flex-start;
        }
        .message {
            padding: 0.65rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            line-height: 1.4;
            word-break: break-word;
        }
        .my-message {
            background: var(--grad);
            color: white;
            border-bottom-right-radius: 4px;
        }
        .other-message {
            background: #f8fafc;
            color: var(--text);
            border: 1.5px solid #94a3b8;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .message-meta {
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: 0.2rem;
            margin-left: 0.5rem;
        }
        /* Input area */
        .input-area {
            background: var(--card-bg);
            border-radius: 60px;
            border: 1px solid #909295;
            display: flex;
            align-items: center;
            padding: 0.3rem 0.3rem 0.3rem 1.2rem;
            gap: 0.5rem;
        }
        .input-area input {
            flex: 1;
            border: none;
            outline: none;
            padding: 0.75rem 0;
            font-size: 0.9rem;
            background: transparent;
        }
        .input-area button {
            background: var(--grad);
            border: none;
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 40px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .input-area button:hover {
            opacity: 0.9;
            transform: scale(0.97);
        }
        .empty-chat {
            text-align: center;
            padding: 2rem;
            color: var(--muted);
        }
        @media (max-width: 640px) {
            .chat-wrapper { height: calc(100vh - 70px); }
            .message-wrapper { max-width: 85%; }
        }
    </style>
</head>
<body>

<!-- SHARED NAVBAR -->
<?php include __DIR__ . '/../../shared/components/navbar.php'; ?>

<div class="chat-wrapper">
    <div class="chat-header">
        <h2><i class="fa-regular fa-comment-dots"></i> Chat with <?php echo $receiver_name ?: 'Seller'; ?></h2>
        <?php if (!empty($product_title)): ?>
            <a href="<?php echo BASE_URL; ?>product.php?id=<?php echo $product_id; ?>" class="product-link">
                <i class="fa-solid fa-box"></i> <?php echo $product_title; ?>
            </a>
        <?php endif; ?>
    </div>

    <div class="messages-container" id="messagesContainer">
        <div class="empty-chat" id="loadingMessages">
            <i class="fa-regular fa-spinner fa-pulse"></i> Loading messages...
        </div>
    </div>

    <div class="input-area">
        <input type="text" id="messageInput" placeholder="Type your message..." autocomplete="off">
        <button id="sendBtn"><i class="fa-regular fa-paper-plane"></i> Send</button>
    </div>
</div>

<script>
    const productId = <?php echo json_encode($product_id); ?>;
    const receiverId = <?php echo json_encode($receiver_id); ?>;
    const apiUrl = '../modules/chat/chat_actions.php';

    const messagesContainer = document.getElementById('messagesContainer');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');

    function fetchMessages() {
        fetch(`${apiUrl}?action=fetch&product_id=${productId}&other_user_id=${receiverId}`)
            .then(response => response.text())
            .then(html => {
                if (messagesContainer.innerHTML !== html) {
                    messagesContainer.innerHTML = html;
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            })
            .catch(err => console.error('Fetch error:', err));
    }

    function sendMessage() {
        const message = messageInput.value.trim();
        if (message === '') return;

        const formData = new FormData();
        formData.append('action', 'send');
        formData.append('product_id', productId);
        formData.append('receiver_id', receiverId);
        formData.append('message', message);

        fetch(apiUrl, {
            method: 'POST',
            body: formData
        })
        .then(() => {
            messageInput.value = '';
            fetchMessages();
        })
        .catch(err => console.error('Send error:', err));
    }

    sendBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            sendMessage();
        }
    });

    setInterval(fetchMessages, 2000);
    fetchMessages();
</script>

</body>
</html>