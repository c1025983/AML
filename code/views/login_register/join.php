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
    if (isset($_POST['verifyToken'])) {
        // Verification logic
        $verificationToken = $_POST['verificationToken'] ?? '';
        $stmt = $conn->prepare("UPDATE librarymember SET account_activation_hash = 0 WHERE account_activation_hash = ?");
        $stmt->bind_param("i", $verificationToken);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo "<div class='alert alert-success'>Authentication token verified and set to 0.</div>";
        } else {
            echo "<div class='alert alert-danger'>Invalid token or token already verified.</div>";
        }

        $stmt->close();
    } else {
        // Get form data
        $fullName = $_POST['fullName'] ?? '';
        $email = $_POST['exampleInputEmail1'] ?? '';
        $password = $_POST['exampleInputPassword1'] ?? '';
        $address = $_POST['address'] ?? '';

        // Generate random authentication token
        $authToken = rand(99999, 1000000);

        // Prepare SQL statement
        $stmt = $conn->prepare("INSERT INTO librarymember (name, email, password, address, account_activation_hash) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $fullName, $email, $password, $address, $authToken);

        // Execute statement
        if ($stmt->execute()) {
            echo "<script>window.location.href = 'verify.php?token=$authToken';</script>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
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
    <title>Join AML</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <a href="index.php">Back to Home</a>
        <form action="" method="post">
            <!-- Full Name Field -->
            <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Enter your full name">
            </div>

            <!-- Email Address Field -->
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" name="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="exampleInputPassword1" placeholder="Password">
            </div>

            <!-- Address Field -->
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Enter your address">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>

<!-- Verification Page (verify.php) -->
<?php if (isset($_GET['token'])): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Token</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h3>Verify Your Authentication Token</h3>
        <form action="" method="post">
            <input type="hidden" name="verifyToken" value="1">
            <div class="form-group">
                <label for="verificationToken">Authentication Token</label>
                <input type="number" class="form-control" id="verificationToken" name="verificationToken" placeholder="Enter your authentication token">
            </div>
            <button type="submit" class="btn btn-success">Verify</button>
        </form>
    </div>
</body>
</html>
<?php endif; ?>
