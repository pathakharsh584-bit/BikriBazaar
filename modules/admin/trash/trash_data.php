<?php
require_once __DIR__ . '/../../../shared/db.php';
use Cloudinary\Api\Upload\UploadApi;

// 1. Helper function to get Cloudinary ID
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

// 2. Find ads deleted more than 30 days ago
$expired_ads_query = mysqli_query($conn, "
    SELECT id FROM products 
    WHERE is_deleted = 1 
    AND deleted_at < NOW() - INTERVAL 30 DAY
    LIMIT 10
");

if(mysqli_num_rows($expired_ads_query) > 0) {
    while($expired = mysqli_fetch_assoc($expired_ads_query)) {
        $expired_id = $expired['id'];

        // 3. Fetch associated images and destroy them on Cloudinary
        $img_query = mysqli_query($conn, "SELECT image_path FROM product_images WHERE product_id = $expired_id");
        while($img = mysqli_fetch_assoc($img_query)) {
            $publicId = extractCloudinaryPublicId($img['image_path']);
            if($publicId) {
                try {
                    (new UploadApi())->destroy($publicId);
                } catch (Exception $e) {
                    error_log("Cleanup Cloudinary Error: " . $e->getMessage());
                }
            }
        }

        // 4. Hard delete the product from the database
        mysqli_query($conn, "DELETE FROM products WHERE id = $expired_id");
    }
}

/* PAGINATION */
$limit = 10;
$page_number = max(1, intval($_GET['pageno'] ?? 1));
$offset = ($page_number - 1) * $limit;

/* DELETED PRODUCTS (With Image & Report Status) */
$deleted_ads_query = mysqli_query(
    $conn,
    "SELECT
        products.id,
        products.title,
        products.category,
        products.price,
        products.status,
        products.boost_type,
        products.deleted_at,
        users.name AS seller_name,
        reported_ads.id AS was_reported,
        (SELECT image_path FROM product_images WHERE product_images.product_id = products.id LIMIT 1) AS image
     FROM products
     JOIN users ON products.user_id = users.id
     LEFT JOIN reported_ads ON products.id = reported_ads.product_id
     WHERE products.is_deleted = 1
     ORDER BY products.deleted_at DESC
     LIMIT $limit OFFSET $offset"
);

/* TOTAL DELETED ADS */
$total_deleted_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM products
     WHERE is_deleted = 1"
);

$total_deleted_ads = mysqli_fetch_assoc($total_deleted_result)['total'];
$total_pages = ceil($total_deleted_ads / $limit);
?>