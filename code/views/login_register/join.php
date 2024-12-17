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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['exampleInputEmail1'] ?? '';
    $password = $_POST['exampleInputPassword1'] ?? '';
    $address = $_POST['address'] ?? '';

    // Generate random authentication token
    $authToken = rand(99999, 1000000);

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO librarymember (name, email, password, address, account_activation_hash) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $fullName, $email, $hashedPassword, $address, $authToken);

    // Execute statement
    if ($stmt->execute()) {
        // Include send_email.php to send verification email
        include('send_email.php');
        sendVerificationEmail($email, $authToken); // Send the email with the verification link
        
        // Redirect to a page or show a message that the email is sent
        echo "<div class='alert alert-success'>Registration successful. Please check your email to verify your account.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="my-5">User Registration</h2>
        <form method="POST" action="join.php">
            <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" required>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" name="exampleInputEmail1" required>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="exampleInputPassword1" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</body>
</html>
