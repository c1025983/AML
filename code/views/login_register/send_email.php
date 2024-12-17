<?php
// Composer autoload
require __DIR__ . '/../../../vendor/autoload.php';

// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "aml_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// PHPMailer kullanımı
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($email, $token) {
    $mail = new PHPMailer(true);  // Exception handling enabled

    try {
        // Sunucu ayarları (Gmail SMTP örneği)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'gulecerbedirhan1@gmail.com';  // Gmail adresinizi girin
        $mail->Password = 'Fener1907';  // Gmail şifrenizi girin
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Gönderici bilgileri
        $mail->setFrom('your-email@gmail.com', 'AML System');
        $mail->addAddress($email);

        // Email içeriği
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification for AML System';

        // URL'de token ile doğrulama linki oluşturuluyor
        $verificationUrl = "http://localhost/AML/code/views/login_register/verify.php?token=" . $token;

        $bodyContent = "
        <h2>Welcome to AML System</h2>
        <p>Click the link below to verify your account:</p>
        <a href='$verificationUrl'>Verify Your Email</a>
        ";

        $mail->Body = $bodyContent;

        // Email gönderme
        $mail->send();
        // E-posta gönderildikten sonra kullanıcıyı yönlendir
        echo "<script>window.location.href = 'verify.php?token=$token';</script>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

$conn->close();
?>
