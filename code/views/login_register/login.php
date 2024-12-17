<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root"; // XAMPP varsayılan kullanıcı adı
$password = ""; // XAMPP varsayılan şifre
$dbname = "aml_database"; // Veritabanı adı

$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantı kontrolü
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Hata mesajlarını tutacak bir değişken oluştur
$error_message = "";

// Form verileri alındığında işleme
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    // Librarian tablosunda kullanıcıyı ara
    $sql = "SELECT * FROM librarian WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Librarian bulunursa
        $row = $result->fetch_assoc();
        // account_activation_hash kontrolü kaldırıldı
        if (password_verify($password, $row['password'])) {
            // Şifre doğruysa, librarian sayfasına yönlendir
            header("Location: /AML/code/views/librarian/index.php");
            exit();
        } else {
            $error_message = "<p style='color:red; text-align:center;'>Wrong Password!</p>";
        }
    } else {
        // Librarian bulunamazsa, member tablosunda ara
        $sql = "SELECT * FROM librarymember WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Member bulunduysa
            $row = $result->fetch_assoc();
            if ($row['account_activation_hash'] != 0) {
                $error_message = "<p style='color:red; text-align:center;'>Account not verified. Please check your email for verification instructions.</p>";
            } elseif (password_verify($password, $row['password'])) {
                // Şifre doğruysa, member dashboard sayfasına yönlendir
                header("Location: ../index.php");
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
    <header class="bg-primary text-white text-center py-3">
        <h1>Advanced Management Library</h1>
    </header>

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
                                <button type="submit" class="btn btn-primary">Sign In</button>
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
