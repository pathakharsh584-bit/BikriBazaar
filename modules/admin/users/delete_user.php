<?php

require_once __DIR__ . '/../../../shared/db.php';

if(isset($_GET['id'])){

    $user_id = intval($_GET['id']);

    /* DELETE USER */

    mysqli_query(

        $conn,

        "DELETE FROM users
         WHERE id = $user_id"

    );

    /* DELETE USER PRODUCTS */

    mysqli_query(

        $conn,

        "DELETE FROM products
         WHERE user_id = $user_id"

    );

}

/* REDIRECT BACK */

echo "

<script>

window.location.href =
'admin_page.php?page=users';

</script>

";