<?php
date_default_timezone_set('Asia/Kolkata');
require_once __DIR__ . '/../vendor/autoload.php';

// Load .env
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

use Razorpay\Api\Api;
use Cloudinary\Configuration\Configuration;

// ======================
// Razorpay Configuration
// ======================

define('RAZORPAY_KEY_ID', $_ENV['RAZORPAY_KEY_ID']);
define('RAZORPAY_KEY_SECRET', $_ENV['RAZORPAY_KEY_SECRET']);

$razorpay = new Api(RAZORPAY_KEY_ID, RAZORPAY_KEY_SECRET);

// ======================
// Cloudinary Configuration
// ======================

Configuration::instance([
  'cloud' => [
    'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
    'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
    'api_secret' => $_ENV['CLOUDINARY_API_SECRET']
  ],
  'url' => [
    'secure' => true
  ]
]);

// ======================
// BASE URL Configuration
// ======================

// Detect Protocol
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";

// Get Host
$host = $_SERVER['HTTP_HOST'];

// Detect Project Folder
$project_path = str_replace('\\', '/', dirname(__DIR__));
$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);

$folder_name = str_replace($doc_root, '', $project_path);

// Final BASE_URL
define('BASE_URL', $protocol . '://' . $host . $folder_name . '/public/');

?>