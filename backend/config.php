<?php
// Database configuration
$host = 'localhost';
$db = 'aml_database';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable error mode
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Use associative arrays by default
    PDO::ATTR_EMULATE_PREPARES => false, // Disable emulation for safety
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Generic error message
    die("Database connection failed. Please contact the administrator.");
}
?>
