<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../../shared/config.php';
require_once __DIR__ . '/../../shared/db.php';

require_once __DIR__ . '/../../vendor/autoload.php';

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

header('Content-Type: application/json');

// =========================
// VALIDATE USER
// =========================

if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        'success' => false,
        'message' => 'Please login first'
    ]);

    exit;
}

$user_id = intval($_SESSION['user_id']);

// =========================
// RAZORPAY INIT
// =========================

try {

    $razorpay = new Api(
        RAZORPAY_KEY_ID,
        RAZORPAY_KEY_SECRET
    );

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Razorpay initialization failed'
    ]);

    exit;
}

// =========================
// GET POST DATA
// =========================

$product_id = isset($_POST['product_id'])
    ? intval($_POST['product_id'])
    : 0;

$plan_id = isset($_POST['plan_id'])
    ? intval($_POST['plan_id'])
    : 0;

$plan_name = trim($_POST['plan_name'] ?? '');

$amount = isset($_POST['amount'])
    ? floatval($_POST['amount'])
    : 0;

$payment_mode = $_POST['payment_mode'] ?? 'razorpay';

// =========================
// VALIDATIONS
// =========================

if ($product_id <= 0) {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid product'
    ]);

    exit;
}

// =========================
// FREE PLAN HANDLER
// =========================

if ($payment_mode === 'free') {

    try {

        $update_sql = "
            UPDATE products
            SET
                boost_type = 'free',
                is_boosted = 0,
                is_featured = 0,
                is_urgent = 0,
                boost_expiry = NULL
            WHERE id = ?
        ";

        $stmt = $conn->prepare($update_sql);

        $stmt->bind_param("i", $product_id);

        $stmt->execute();

        $payment_status = 'free';

        $insert_sql = "
            INSERT INTO payment
            (
                user_id,
                product_id,
                plan_name,
                amount,
                payment_status
            )
            VALUES (?, ?, ?, ?, ?)
        ";

        $insert_stmt = $conn->prepare($insert_sql);

        $insert_stmt->bind_param(
            "iisds",
            $user_id,
            $product_id,
            $plan_name,
            $amount,
            $payment_status
        );

        $insert_stmt->execute();

        echo json_encode([
            'success' => true
        ]);

    } catch (Exception $e) {

        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }

    exit;
}

// =========================
// RAZORPAY VARIABLES
// =========================

$razorpay_payment_id =
    trim($_POST['razorpay_payment_id'] ?? '');

$razorpay_order_id =
    trim($_POST['razorpay_order_id'] ?? '');

$razorpay_signature =
    trim($_POST['razorpay_signature'] ?? '');

// =========================
// VALIDATIONS
// =========================

if (
    empty($razorpay_payment_id) ||
    empty($razorpay_order_id) ||
    empty($razorpay_signature)
) {

    echo json_encode([
        'success' => false,
        'message' => 'Missing payment credentials'
    ]);

    exit;
}

// =========================
// VERIFY SIGNATURE
// =========================

try {

    $attributes = [

        'razorpay_order_id' => $razorpay_order_id,

        'razorpay_payment_id' => $razorpay_payment_id,

        'razorpay_signature' => $razorpay_signature
    ];

    $razorpay->utility->verifyPaymentSignature($attributes);

} catch (SignatureVerificationError $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid payment signature'
    ]);

    exit;
}

// =========================
// PLAN SETTINGS
// =========================

$boost_type = 'basic';

$is_boosted = 1;

$is_featured = 0;

$is_urgent = 0;

$boost_days = 7;

// =========================
// PLAN-WISE LOGIC
// =========================

switch ($plan_id) {

    case 2:

        $boost_type = 'basic';
        $boost_days = 7;

        break;

    case 3:

        $boost_type = 'premium';
        $boost_days = 15;

        break;

    case 4:

        $boost_type = 'featured';
        $is_featured = 1;
        $boost_days = 15;

        break;

    case 5:

        $boost_type = 'urgent';
        $is_urgent = 1;
        $boost_days = 5;

        break;

    case 6:

        $boost_type = 'mega';
        $is_featured = 1;
        $boost_days = 30;

        break;

    case 7:

        $boost_type = 'seller_subscription';
        $is_featured = 1;
        $boost_days = 30;

        break;

    case 8:

        $boost_type = 'shop_plan';
        $is_featured = 1;
        $boost_days = 30;

        break;
}

// =========================
// BOOST EXPIRY
// =========================

$boost_expiry = date(
    'Y-m-d H:i:s',
    strtotime("+$boost_days days")
);

// =========================
// DATABASE TRANSACTION
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
            is_boosted = ?,
            is_featured = ?,
            is_urgent = ?,
            boost_expiry = ?
        WHERE id = ?
    ";

    $update_stmt = $conn->prepare($update_sql);

    $update_stmt->bind_param(
        "siiisi",
        $boost_type,
        $is_boosted,
        $is_featured,
        $is_urgent,
        $boost_expiry,
        $product_id
    );

    $update_stmt->execute();

    // =========================
    // UPDATE PAYMENT RECORD
    // =========================

    $payment_status = 'paid';

    $payment_sql = "
        UPDATE payment
        SET
            razorpay_payment_id = ?,
            razorpay_signature = ?,
            payment_status = ?
        WHERE razorpay_order_id = ?
    ";

    $payment_stmt = $conn->prepare($payment_sql);

    $payment_stmt->bind_param(
        "ssss",
        $razorpay_payment_id,
        $razorpay_signature,
        $payment_status,
        $razorpay_order_id
    );

    $payment_stmt->execute();

    // =========================
    // COMMIT
    // =========================

    $conn->commit();

    echo json_encode([

        'success' => true,

        'message' => 'Payment verified successfully'
    ]);

} catch (Exception $e) {

    $conn->rollback();

    echo json_encode([

        'success' => false,

        'message' => $e->getMessage()
    ]);
}

?>