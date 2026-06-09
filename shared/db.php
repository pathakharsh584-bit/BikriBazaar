<?php

require_once __DIR__ . '/config.php';

// 1. Your TiDB Cloud Credentials
$host = $_ENV['DB_HOST']; // Paste your long TiDB host URL here
$username = $_ENV['DB_USERNAME'];      // Paste your TiDB username here
$password = $_ENV['DB_PASSWORD'];        // Paste your TiDB password here
$database = $_ENV['DB_DATABASE'];                  // We used the 'test' database in the cloud!
$port = $_ENV['DB_PORT'];                        // Critical: TiDB Serverless uses port 4000

// 2. Initialize a secure MySQLi connection
$conn = mysqli_init();

// 3. Configure the SSL Certificate
// Note: Move the 'isrgrootx1.pem' file you downloaded earlier into the same folder as this db.php file!
mysqli_ssl_set($conn, NULL, NULL, __DIR__ . '/isrgrootx1.pem', NULL, NULL);

// 4. Establish the connection across the internet using the SSL flag
$success = mysqli_real_connect(
    $conn, 
    $host, 
    $username, 
    $password, 
    $database, 
    $port, 
    NULL, 
    MYSQLI_CLIENT_SSL
);

// 5. Catch any connection errors
if (!$success) {
    die("Cloud Database Connection Failed: " . mysqli_connect_error());
}

?>