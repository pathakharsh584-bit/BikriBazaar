<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BikriBazaar Chat</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <style>
        /* Essential Chat UI Constraints */
        body { background: #f3f4f6; font-family: sans-serif; display: flex; justify-content: center; padding-top: 5vh; }
        .chat-container { width: 100%; max-width: 900px; height: 85vh; background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); display: flex; flex-direction: column; overflow: hidden; }
        .chat-header { background: #312e81; padding: 20px 30px; color: white; font-size: 20px; font-weight: bold; }
        .messages-box { flex: 1; padding: 30px; background: #f8fafc; overflow-y: auto; display: flex; flex-direction: column; gap: 15px; }
        .message-wrapper { max-width: 75%; display: flex; flex-direction: column; }
        .my-wrapper { align-self: flex-end; align-items: flex-end; }
        .other-wrapper { align-self: flex-start; align-items: flex-start; }
        .message { padding: 15px 20px; border-radius: 20px; font-size: 15px; line-height: 1.5; }
        .my-message { background: #312e81; color: white; border-bottom-right-radius: 5px; }
        .other-message { background: white; color: black; border: 1px solid #e2e8f0; border-bottom-left-radius: 5px; }
        .message-meta { font-size: 11px; color: #94a3b8; margin-top: 5px; }
        .send-area { padding: 20px; background: white; border-top: 1px solid #f1f5f9; display: flex; gap: 15px; }
        .send-area input { flex: 1; padding: 15px; border: 1px solid #e2e8f0; border-radius: 30px; outline: none; font-size: 15px; }
        .send-area button { padding: 0 30px; background: #312e81; color: white; border: none; border-radius: 30px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>

<div class="chat-container">
    <div class="chat-header">💬 Live Chat</div>

    <div class="messages-box" id="messages-box">
        </div>

    <form class="send-area" id="chat-form">
        <input type="text" id="message-input" placeholder="Type your message..." autocomplete="off" required>
        <button type="submit">Send</button>
    </form>
</div>

<script>
    // Variables passed seamlessly from chat.php
    const productId = <?php echo json_encode($product_id); ?>;
    const receiverId = <?php echo json_encode($receiver_id); ?>;
    const apiUrl = '../modules/chat/chat_actions.php';
    
    const messagesBox = document.getElementById('messages-box');
    const form = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');

    // 1. Fetch data from API
    function fetchMessages() {
        fetch(`${apiUrl}?action=fetch&product_id=${productId}&other_user_id=${receiverId}`)
            .then(response => response.text())
            .then(html => {
                if(messagesBox.innerHTML !== html) {
                    messagesBox.innerHTML = html;
                    messagesBox.scrollTop = messagesBox.scrollHeight;
                }
            });
    }

    // 2. Post data to API
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if(message === '') return;

        const formData = new FormData();
        formData.append('action', 'send');
        formData.append('product_id', productId);
        formData.append('receiver_id', receiverId);
        formData.append('message', message);

        fetch(apiUrl, {
            method: 'POST',
            body: formData
        }).then(() => {
            messageInput.value = ''; 
            fetchMessages(); 
        });
    });

    // 3. Keep chat live
    setInterval(fetchMessages, 2000);
    fetchMessages();
</script>

</body>
</html>