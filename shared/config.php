<?php

require_once __DIR__ . '/../vendor/autoload.php';

// This looks for your .env file in the root directory
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

use Cloudinary\Configuration\Configuration;

Configuration::instance([
  'cloud' => [
    'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'], 
    'api_key'    => $_ENV['CLOUDINARY_API_KEY'],  
    'api_secret' => $_ENV['CLOUDINARY_API_SECRET']
  ],
  'url' => [
    'secure' => true // Forces HTTPS
  ]
]);

//Detect Protocol (HTTP or HTTPS)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";

//Get the host 
$host = $_SERVER['HTTP_HOST'];

//Dynamically find the project folder name
// __DIR__ points to the 'shared' folder. dirname(__DIR__) points to the project root.
$project_path = str_replace('\\', '/', dirname(__DIR__));
$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);

$folder_name = str_replace($doc_root, '', $project_path);

//Create the universal BASE_URL
define('BASE_URL', $protocol . '://' . $host . $folder_name . '/public/');


?>