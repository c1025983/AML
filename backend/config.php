<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'aml_database';

// Create connection using mysqli
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session
session_start();

?>
