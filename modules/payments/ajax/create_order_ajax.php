<?php

session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../shared/config.php';

use Razorpay\Api\Api;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid request"
    ]);

    exit;
}

$planKey = trim($_POST['plan'] ?? '');

$plans = require __DIR__ . '/../../../shared/plans.php';

if (!isset($plans[$planKey])) {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid plan selected"
    ]);

    exit;
}

$plan = $plans[$planKey];

$amount = (int)$plan['price'] * 100;

$product_id = $_SESSION['boost_product_id'] ?? 1;

try {

    $api = new Api(
        $_ENV['RAZORPAY_KEY_ID'],
        $_ENV['RAZORPAY_KEY_SECRET']
    );

    $order = $api->order->create([

        'receipt' => 'receipt_' . time(),

        'amount' => $amount,

        'currency' => 'INR'

    ]);

    echo json_encode([

        "status" => "success",

        "key" => $_ENV['RAZORPAY_KEY_ID'],

        "amount" => $amount,

        "order_id" => $order['id'],

        "plan_name" => $plan['name'],

        "plan_key" => $planKey,

        "product_id" => $product_id

    ]);

} catch (Exception $e) {

    echo json_encode([

        "status" => "error",

        "message" => $e->getMessage()

    ]);
}