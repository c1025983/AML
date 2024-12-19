<?php
session_start();

// Database connection code
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aml_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM librarian WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            header("Location: /AML/code/views/librarian/index.php");
            exit();
        } else {
            $error_message = "<p style='color:red; text-align:center;'>Wrong Password!</p>";
        }
    } else {
        $sql = "SELECT * FROM librarymember WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['account_activation_hash'] != 0) {
                $error_message = "<p style='color:red; text-align:center;'>Account not verified. Please check your email for verification instructions.</p>";
            } elseif (password_verify($password, $row['password'])) {
                // Store member ID in the session
                $_SESSION['member_id'] = $row['id'];

                // Redirect to the member dashboard
                header("Location: ../catalogue.php");
                exit();
            } else {
                $error_message = "<p style='color:red; text-align:center;'>Wrong Password!</p>";
            }
        } else {
            $error_message = "<p style='color:red; text-align:center;'>User Not Found!</p>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <!-- Navbar -->
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
                        <a class="nav-link text-white" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Login</h2>
                    </div>
                    <div class="card-body">
                        <!-- Giriş Formu -->
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn" style="background-color: #293b5f; color: white;">Sign In</button>
                            </div>
                        </form>

                        <!-- Hata mesajını burada gösterebilirsiniz -->
                        <?php if (!empty($error_message)) { echo $error_message; } ?>
                    </div>
                    <div class="card-footer text-center">
                        <small>Don't have an account? <a href="join.php">Create one now</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
