<?php
require_once __DIR__ . '/../../../shared/db.php';

// Bring in the Cloudinary Namespace
use Cloudinary\Api\Upload\UploadApi;

if(isset($_GET['id'])){
    $user_id = intval($_GET['id']);

    // 1. HELPER FUNCTION TO EXTRACT CLOUDINARY PUBLIC ID
    if (!function_exists('extractCloudinaryPublicId')) {
        function extractCloudinaryPublicId($url) {
            $parts = explode('/upload/', $url);
            if(isset($parts[1])) {
                $pathWithoutVersion = preg_replace('/^v\d+\//', '', $parts[1]);
                $pathInfo = pathinfo($pathWithoutVersion);
                $dir = $pathInfo['dirname'] === '.' ? '' : $pathInfo['dirname'] . '/';
                return $dir . $pathInfo['filename'];
            }
            return null;
        }
    }

    // 2. FETCH ALL PRODUCT IMAGES FOR THIS USER BEFORE DELETING ROWS
    $img_query = mysqli_query($conn, "
        SELECT pi.image_path 
        FROM product_images pi
        JOIN products p ON pi.product_id = p.id
        WHERE p.user_id = $user_id
    ");

    // 3. DESTROY THEM ON CLOUDINARY
    if (mysqli_num_rows($img_query) > 0) {
        while($img = mysqli_fetch_assoc($img_query)) {
            $publicId = extractCloudinaryPublicId($img['image_path']);
            if($publicId) {
                try {
                    (new UploadApi())->destroy($publicId);
                } catch (Exception $e) {
                    error_log("User Deletion Cloudinary Error: " . $e->getMessage());
                }
            }
        }
    }

    /* 4. DELETE USER */
    mysqli_query(
        $conn,
        "DELETE FROM users WHERE id = $user_id"
    );

    /* 5. DELETE USER PRODUCTS */
    mysqli_query(
        $conn,
        "DELETE FROM products WHERE user_id = $user_id"
    );
}

/* REDIRECT BACK */
echo "
<script>
window.location.href = 'admin_page.php?page=users';
</script>
";
?>