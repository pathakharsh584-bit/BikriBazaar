<?php

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