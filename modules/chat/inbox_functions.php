<?php
// modules/chat/inbox_functions.php

/**
 * Fetches the single latest message preview for every unique user conversation thread.
 */
function getLatestUserChats($conn, $current_user) {
    $current_user = intval($current_user);
    
    $sql = "SELECT m.*, 
                   u.id as partner_id, u.name as partner_name,
                   p.title as product_title
            FROM messages m
            INNER JOIN (
                SELECT MAX(id) as max_id
                FROM messages 
                WHERE sender_id = $current_user OR receiver_id = $current_user 
                GROUP BY product_id, LEAST(sender_id, receiver_id), GREATEST(sender_id, receiver_id)
            ) latest_msg ON m.id = latest_msg.max_id
            INNER JOIN users u ON (u.id = m.sender_id OR u.id = m.receiver_id) AND u.id != $current_user
            INNER JOIN products p ON p.id = m.product_id
            ORDER BY m.id DESC";

    return mysqli_query($conn, $sql);
}
?>