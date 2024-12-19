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

$feedbackMessage = ''; // Default feedback message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['exampleInputEmail1'] ?? '';
    $password = $_POST['exampleInputPassword1'] ?? '';
    $address = $_POST['address'] ?? '';

    // Validation checks
    if (empty($fullName) || empty($email) || empty($password) || empty($address)) {
        $feedbackMessage = "Error: All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedbackMessage = "Error: Invalid email format.";
    } else {
        // Generate random authentication token
        $authToken = rand(99999, 1000000);

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO librarymember (name, email, password, address, account_activation_hash) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $fullName, $email, $hashedPassword, $address, $authToken);

        // Execute statement
        if ($stmt->execute()) {
            // Simulate email sending (replace with actual implementation)
            // include('send_email.php');
            // sendVerificationEmail($email, $authToken);

            $feedbackMessage = "Registration successful. Please check your email to verify your account.";
        } else {
            if ($stmt->errno === 1062) { // 1062: Duplicate entry for unique key
                $feedbackMessage = "Error: Duplicate email.";
            } else {
                $feedbackMessage = "Error: " . $stmt->error;
            }
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg" style="background-color: #293b5f;">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">AML</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page" href="..\index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">User Registration</h2>

        <!-- Display feedback messages if any -->
        <?php if (!empty($feedbackMessage)): ?>
            <div class="alert <?php echo strpos($feedbackMessage, 'successful') !== false ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo $feedbackMessage; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="join.php">
            <div class="mb-3">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" required>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" name="exampleInputEmail1" required>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="exampleInputPassword1" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <button type="submit" class="btn" style="background-color: #293b5f; color: white;">Register</button>
        </form>

        <!-- "Have an account? Login" link -->
        <div class="mt-3">
            <p>Have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
