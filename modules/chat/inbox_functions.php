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
            INNER JOIN users u ON (u.id = m.sender_id OR u.id = m.receiver_id) AND u.id != $current_user
            INNER JOIN products p ON p.id = m.product_id
            WHERE m.id IN (
                SELECT MAX(sub_m.id) 
                FROM messages sub_m
                WHERE sub_m.sender_id = $current_user OR sub_m.receiver_id = $current_user 
                GROUP BY sub_m.product_id, LEAST(sub_m.sender_id, sub_m.receiver_id), GREATEST(sub_m.sender_id, sub_m.receiver_id)
            )
            ORDER BY m.id DESC";

    return mysqli_query($conn, $sql);
}
?>