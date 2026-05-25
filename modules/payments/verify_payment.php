<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../../shared/config.php';
require_once __DIR__ . '/../../shared/db.php';

require_once __DIR__ . '/../../vendor/autoload.php';

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

if (!isset($_SESSION['user_id'])) {

    die("Please login first.");
}

$user_id = intval($_SESSION['user_id']);

$product_id = intval($_GET['product_id'] ?? 0);

$plan_key = trim($_GET['plan'] ?? '');

$razorpay_payment_id =
    trim($_GET['razorpay_payment_id'] ?? '');

$razorpay_order_id =
    trim($_GET['razorpay_order_id'] ?? '');

$razorpay_signature =
    trim($_GET['razorpay_signature'] ?? '');

// =========================
// VALIDATIONS
// =========================

if (
    $product_id <= 0 ||
    empty($plan_key) ||
    empty($razorpay_payment_id) ||
    empty($razorpay_order_id) ||
    empty($razorpay_signature)
) {

    die("Invalid payment request.");
}

// =========================
// LOAD PLANS
// =========================

$plans = require __DIR__ . '/../../shared/plans.php';

if (!isset($plans[$plan_key])) {

    die("Invalid plan selected.");
}

$plan = $plans[$plan_key];

$plan_name = $plan['name'];

$amount = $plan['price'];

$duration = intval($plan['duration']);

// =========================
// RAZORPAY INIT
// =========================

$api = new Api(
    $_ENV['RAZORPAY_KEY_ID'],
    $_ENV['RAZORPAY_KEY_SECRET']
);

// =========================
// VERIFY SIGNATURE
// =========================

try {

    $attributes = [

        'razorpay_order_id' => $razorpay_order_id,

        'razorpay_payment_id' => $razorpay_payment_id,

        'razorpay_signature' => $razorpay_signature

    ];

    $api->utility->verifyPaymentSignature($attributes);

} catch (SignatureVerificationError $e) {

    die("Payment verification failed.");
}

// =========================
// PLAN SETTINGS
// =========================

$is_boosted = 1;

$is_featured = 0;

$is_urgent = 0;

$boost_type = $plan_key;

// =========================
// PLAN BASED FEATURES
// =========================

if ($plan_key === "special") {

    $is_featured = 1;
}

if ($plan_key === "premium") {

    $is_featured = 1;

    $is_urgent = 1;
}

// =========================
// DATES
// =========================

$start_date = date('Y-m-d H:i:s');

$boost_expiry = date(
    'Y-m-d H:i:s',
    strtotime("+$duration days")
);

// =========================
// TRANSACTION START
// =========================

$conn->begin_transaction();

try {

    // =========================
    // UPDATE PRODUCT
    // =========================

    $update_sql = "

        UPDATE products
        SET

            boost_type = ?,
            boost_plan = ?,
            is_boosted = ?,
            is_featured = ?,
            is_urgent = ?,
            boost_expiry = ?

        WHERE id = ?

    ";

    $update_stmt = $conn->prepare($update_sql);

    $update_stmt->bind_param(

        "ssiissi",

        $boost_type,
        $plan_name,
        $is_boosted,
        $is_featured,
        $is_urgent,
        $boost_expiry,
        $product_id

    );

    $update_stmt->execute();

    // =========================
    // INSERT PAYMENT RECORD
    // =========================

    $payment_status = "success";

    $insert_sql = "

        INSERT INTO payment (

            user_id,
            product_id,
            plan_name,
            amount,
            razorpay_order_id,
            razorpay_payment_id,
            razorpay_signature,
            payment_status,
            start_date,
            expiry_date

        )

        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)

    ";

    $insert_stmt = $conn->prepare($insert_sql);

    $insert_stmt->bind_param(

        "iisdssssss",

        $user_id,
        $product_id,
        $plan_name,
        $amount,
        $razorpay_order_id,
        $razorpay_payment_id,
        $razorpay_signature,
        $payment_status,
        $start_date,
        $boost_expiry

    );

    $insert_stmt->execute();

    // =========================
    // COMMIT
    // =========================

    $conn->commit();

} catch (Exception $e) {

    $conn->rollback();

    die($e->getMessage());
}

// =========================
// MAILS
// =========================

require_once __DIR__ . '/send_purchase_mail.php';

// Demo plan:
// instantly trigger expiry mail too

if ($plan_key === "demo") {

    require_once __DIR__ . '/send_expiry_mail.php';
}

// =========================
// SUCCESS REDIRECT
// =========================

header(

    "Location: " .

    BASE_URL .

    "payment_success.php"

);

exit;