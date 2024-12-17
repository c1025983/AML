<?php
// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aml_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // SQL query to check if the token exists
    $stmt = $conn->prepare("SELECT * FROM librarymember WHERE account_activation_hash = ?");
    $stmt->bind_param("i", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token found, update the account_activation_hash to 0
        $updateStmt = $conn->prepare("UPDATE librarymember SET account_activation_hash = 0 WHERE account_activation_hash = ?");
        $updateStmt->bind_param("i", $token);
        $updateStmt->execute();

        echo "<div class='alert alert-success'>Your account has been verified successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Invalid or expired verification link.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>No token provided.</div>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="my-5">Account Verification</h2>
    </div>
</body>
</html>
