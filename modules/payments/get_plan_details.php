<?php

session_start();

require_once __DIR__ . '/../../shared/config.php';
require_once __DIR__ . '/../../shared/db.php';

header('Content-Type: application/json');

// =========================
// GET PLAN ID
// =========================

$plan_id = isset($_GET['plan_id'])
    ? intval($_GET['plan_id'])
    : 0;

// =========================
// VALIDATION
// =========================

if ($plan_id <= 0) {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid Plan ID'
    ]);

    exit;
}

// =========================
// PLAN DATA
// =========================

$plans = [

    1 => [
        'id' => 1,
        'name' => 'Free Plan',
        'price' => 0,
        'duration' => 'Forever',
        'badge' => 'FREE',
        'description' =>
            'Basic product visibility for regular listings.',

        'features' => [
            'Normal listing',
            'Basic visibility',
            'Standard search placement',
            'Community support'
        ]
    ],

    2 => [
        'id' => 2,
        'name' => 'Basic Boost',
        'price' => 99,
        'duration' => '7 Days',
        'badge' => 'POPULAR',
        'description' =>
            'Boost your product visibility and reach more buyers.',

        'features' => [
            'Top placement for 7 days',
            'Better visibility',
            'Priority search listing',
            'More buyer reach'
        ]
    ],

    3 => [
        'id' => 3,
        'name' => 'Premium Boost',
        'price' => 199,
        'duration' => '15 Days',
        'badge' => 'RECOMMENDED',
        'description' =>
            'Premium homepage visibility with maximum engagement.',

        'features' => [
            'Homepage visibility',
            'Top search placement',
            'Premium exposure',
            'Higher buyer engagement'
        ]
    ],

    4 => [
        'id' => 4,
        'name' => 'Featured Ad',
        'price' => 299,
        'duration' => '15 Days',
        'badge' => 'FEATURED',
        'description' =>
            'Get featured across homepage and categories.',

        'features' => [
            'Highlighted premium badge',
            'Homepage featured section',
            'Category top placement',
            'Priority customer attention'
        ]
    ],

    5 => [
        'id' => 5,
        'name' => 'Urgent Sale',
        'price' => 49,
        'duration' => '5 Days',
        'badge' => 'URGENT',
        'description' =>
            'Sell faster with urgent sale visibility.',

        'features' => [
            'Urgent sale label',
            'Quick visibility',
            'Fast-selling priority',
            'Buyer attention boost'
        ]
    ],

    6 => [
        'id' => 6,
        'name' => 'Mega Boost',
        'price' => 499,
        'duration' => '30 Days',
        'badge' => 'MEGA',
        'description' =>
            'Maximum premium exposure across the platform.',

        'features' => [
            'Homepage premium visibility',
            'Category top priority',
            'Premium verified badge',
            'Maximum buyer exposure'
        ]
    ],

    7 => [
        'id' => 7,
        'name' => 'Seller Subscription',
        'price' => 999,
        'duration' => 'Monthly',
        'badge' => 'PRO',
        'description' =>
            'Professional seller tools with unlimited boosts.',

        'features' => [
            'Unlimited boosts',
            'Priority support',
            'Seller analytics',
            'Premium seller tools'
        ]
    ],

    8 => [
        'id' => 8,
        'name' => 'Shop Plan',
        'price' => 1999,
        'duration' => 'Monthly',
        'badge' => 'BUSINESS',
        'description' =>
            'Business storefront with advanced dashboard.',

        'features' => [
            'Business seller dashboard',
            'Unlimited listings',
            'Dedicated storefront',
            'Advanced analytics'
        ]
    ]

];

// =========================
// CHECK PLAN EXISTS
// =========================

if (!isset($plans[$plan_id])) {

    echo json_encode([
        'success' => false,
        'message' => 'Plan not found'
    ]);

    exit;
}

// =========================
// SUCCESS RESPONSE
// =========================

echo json_encode([

    'success' => true,

    'plan' => $plans[$plan_id]
]);

?>