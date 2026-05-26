<?php
// modules/chat/inbox_functions.php

function getLatestUserChats($conn, $current_user) {
    $current_user = intval($current_user);
    
    $sql = "SELECT m.*, 
                   u.id AS partner_id, 
                   u.name AS partner_name,
                   p.title AS product_title
            FROM messages m
            INNER JOIN (
                SELECT 
                    MAX(inner_m.id) AS max_id
                FROM messages AS inner_m
                WHERE (inner_m.sender_id = $current_user OR inner_m.receiver_id = $current_user)
                  /* CHANGED: Don't look at messages this user has soft-deleted */
                  AND (inner_m.deleted_by IS NULL OR inner_m.deleted_by != $current_user)
                GROUP BY 
                    inner_m.product_id, 
                    LEAST(inner_m.sender_id, inner_m.receiver_id), 
                    GREATEST(inner_m.sender_id, inner_m.receiver_id)
            ) AS latest_msg ON m.id = latest_msg.max_id
            INNER JOIN users u 
                ON (u.id = m.sender_id OR u.id = m.receiver_id) 
               AND u.id != $current_user
            INNER JOIN products p ON p.id = m.product_id
            /* CHANGED: Final check to ensure the outer row isn't a soft-deleted message */
            WHERE (m.deleted_by IS NULL OR m.deleted_by != $current_user)
            ORDER BY m.id DESC";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        error_log("getLatestUserChats failed: " . mysqli_error($conn));
        return false;
    }

    return $result;
}
?>