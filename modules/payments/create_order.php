<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../../shared/config.php';
require_once __DIR__ . '/../../shared/db.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use Razorpay\Api\Api;

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
        'message' => 'Razorpay initialization failed',
        'error' => $e->getMessage()
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

if ($plan_id <= 0) {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid plan'
    ]);

    exit;
}

if ($amount <= 0) {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid amount'
    ]);

    exit;
}

// =========================
// CHECK PRODUCT EXISTS
// =========================

$product_sql = "
    SELECT id, user_id, title
    FROM products
    WHERE id = ?
    LIMIT 1
";

$stmt = $conn->prepare($product_sql);

if (!$stmt) {

    echo json_encode([
        'success' => false,
        'message' => 'Database prepare failed'
    ]);

    exit;
}

$stmt->bind_param("i", $product_id);

$stmt->execute();

$product_result = $stmt->get_result();

if ($product_result->num_rows === 0) {

    echo json_encode([
        'success' => false,
        'message' => 'Product not found'
    ]);

    exit;
}

$product = $product_result->fetch_assoc();

// =========================
// OWNER CHECK
// =========================

if ($product['user_id'] != $user_id) {

    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);

    exit;
}

// =========================
// CONVERT TO PAISE
// =========================

$amount_in_paise = intval($amount * 100);

// =========================
// CREATE RAZORPAY ORDER
// =========================

try {

    $receipt = 'BB_' . $product_id . '_' . time();

    $order = $razorpay->order->create([

        'receipt' => $receipt,

        'amount' => $amount_in_paise,

        'currency' => 'INR',

        'payment_capture' => 1,

        'notes' => [

            'user_id' => $user_id,

            'product_id' => $product_id,

            'plan_id' => $plan_id,

            'plan_name' => $plan_name
        ]
    ]);

    // =========================
    // SAVE PAYMENT
    // =========================

    $payment_status = 'pending';

    $insert_sql = "
        INSERT INTO payment
        (
            user_id,
            product_id,
            plan_name,
            amount,
            razorpay_order_id,
            payment_status
        )
        VALUES (?, ?, ?, ?, ?, ?)
    ";

    $insert_stmt = $conn->prepare($insert_sql);

    if (!$insert_stmt) {

        echo json_encode([
            'success' => false,
            'message' => 'Payment insert prepare failed'
        ]);

        exit;
    }

    $razorpay_order_id = $order['id'];

    $insert_stmt->bind_param(
        "iisdss",
        $user_id,
        $product_id,
        $plan_name,
        $amount,
        $razorpay_order_id,
        $payment_status
    );

    $insert_stmt->execute();

    // =========================
    // SUCCESS RESPONSE
    // =========================

    $response = [

        'success' => true,

        'key' => RAZORPAY_KEY_ID,

        'amount' => $amount_in_paise,

        'currency' => 'INR',

        'order_id' => $razorpay_order_id,

        'product_id' => $product_id,

        'plan_id' => $plan_id,

        'plan_name' => $plan_name
    ];

    echo json_encode($response);

    exit;

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'message' => 'Order creation failed',
        'error' => $e->getMessage()
    ]);

    exit;
}
?>