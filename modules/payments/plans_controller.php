<?php

session_start();

require_once __DIR__ . '/../../shared/config.php';
require_once __DIR__ . '/../../shared/db.php';

// =========================
// VALIDATE PRODUCT ID
// =========================

$product_id = isset($_GET['product_id'])
    ? intval($_GET['product_id'])
    : 0;

if ($product_id <= 0) {
    die("Invalid Product ID");
}

// =========================
// FETCH PRODUCT
// =========================

$product_sql = "
    SELECT 
        products.*,

        (
            SELECT image_path 
            FROM product_images 
            WHERE product_id = products.id 
            ORDER BY id ASC 
            LIMIT 1
        ) AS image

    FROM products
    WHERE id = ?
    LIMIT 1
";

$stmt = $conn->prepare($product_sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();

$product_result = $stmt->get_result();

if ($product_result->num_rows === 0) {
    die("Product not found");
}

$product = $product_result->fetch_assoc();

// =========================
// PAYMENT PLANS
// =========================

$plans = [

    [
        'id' => 1,
        'name' => 'Free Plan',
        'price' => 0,
        'duration' => 'Forever',
        'badge' => 'FREE',
        'recommended' => false,
        'features' => [
            'Normal listing',
            'Basic visibility',
            'Standard search placement',
            'Community support'
        ]
    ],

    [
        'id' => 2,
        'name' => 'Basic Boost',
        'price' => 99,
        'duration' => '7 Days',
        'badge' => 'POPULAR',
        'recommended' => false,
        'features' => [
            'Top placement for 7 days',
            'Better visibility',
            'Priority search listing',
            'More buyer reach'
        ]
    ],

    [
        'id' => 3,
        'name' => 'Premium Boost',
        'price' => 199,
        'duration' => '15 Days',
        'badge' => 'RECOMMENDED',
        'recommended' => true,
        'features' => [
            'Homepage visibility',
            'Top search placement',
            'Premium exposure',
            'Higher buyer engagement'
        ]
    ],

    [
        'id' => 4,
        'name' => 'Featured Ad',
        'price' => 299,
        'duration' => '15 Days',
        'badge' => 'FEATURED',
        'recommended' => false,
        'features' => [
            'Highlighted premium badge',
            'Homepage featured section',
            'Category top placement',
            'Priority customer attention'
        ]
    ],

    [
        'id' => 5,
        'name' => 'Urgent Sale',
        'price' => 49,
        'duration' => '5 Days',
        'badge' => 'URGENT',
        'recommended' => false,
        'features' => [
            'Urgent sale label',
            'Quick visibility',
            'Fast-selling priority',
            'Buyer attention boost'
        ]
    ],

    [
        'id' => 6,
        'name' => 'Mega Boost',
        'price' => 499,
        'duration' => '30 Days',
        'badge' => 'MEGA',
        'recommended' => false,
        'features' => [
            'Homepage premium visibility',
            'Category top priority',
            'Premium verified badge',
            'Maximum buyer exposure'
        ]
    ],

    [
        'id' => 7,
        'name' => 'Seller Subscription',
        'price' => 999,
        'duration' => 'Monthly',
        'badge' => 'PRO',
        'recommended' => false,
        'features' => [
            'Unlimited boosts',
            'Priority support',
            'Seller analytics',
            'Premium seller tools'
        ]
    ],

    [
        'id' => 8,
        'name' => 'Shop Plan',
        'price' => 1999,
        'duration' => 'Monthly',
        'badge' => 'BUSINESS',
        'recommended' => false,
        'features' => [
            'Business seller dashboard',
            'Unlimited listings',
            'Dedicated storefront',
            'Advanced analytics'
        ]
    ]

];

// =========================
// LOAD VIEW
// =========================

require_once __DIR__ . '/plans_view.php';

?>