<?php
// Autoload Composer dependencies
require_once __DIR__ . '/../../vendor/autoload.php';  // Go up two levels to access vendor folder

// Load .env variables from the root directory
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Error Reporting Turn On
ini_set('error_reporting', E_ALL);

// Setting up the time zone from .env
date_default_timezone_set($_ENV['TIMEZONE']);

// Host Name
$dbhost = $_ENV['DB_HOST'];

// Database Name
$dbname = $_ENV['DB_NAME'];

// Database Username
$dbuser = $_ENV['DB_USER'];

// Database Password
$dbpass = $_ENV['DB_PASS'];

// Defining base url from .env
define("BASE_URL", $_ENV['BASE_URL']);

// Getting Admin url
define("ADMIN_URL", BASE_URL . "admin" . "/");

try {
    $pdo = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo "Connection error :" . $exception->getMessage();
}
