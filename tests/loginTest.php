<?php

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    private $dbConnection;

    protected function setUp(): void
    {
        // Veritabanı bağlantısı oluştur
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "aml_database";

        $this->dbConnection = new mysqli($servername, $username, $password, $dbname);

        if ($this->dbConnection->connect_error) {
            die("Connection failed: " . $this->dbConnection->connect_error);
        }

        $this->dbConnection->query("DELETE FROM librarymember WHERE email IN ('librarian@test.com', 'member@test.com', 'inactive@test.com')");
        // Test kullanıcılarını ekle (account_activation_hash = 0)
        $this->dbConnection->query("INSERT INTO librarymember (name,email, password, account_activation_hash) VALUES ('test 1', 'librarian@test.com', '" . password_hash('password123', PASSWORD_BCRYPT) . "', 0)");
        $this->dbConnection->query("INSERT INTO librarymember (name, email, password, account_activation_hash) VALUES ('test 2','member@test.com', '" . password_hash('password123', PASSWORD_BCRYPT) . "', 0)");
        $this->dbConnection->query("INSERT INTO librarymember (name, email, password, account_activation_hash) VALUES ('test 3','inactive@test.com', '" . password_hash('password123', PASSWORD_BCRYPT) . "', 1)");
    }

    protected function tearDown(): void
    {
        // Test kullanıcılarını kaldır
        $this->dbConnection->query("DELETE FROM librarian WHERE email IN ('librarian@test.com', 'inactive@test.com')");
        $this->dbConnection->query("DELETE FROM librarymember WHERE email = 'member@test.com'");

        $this->dbConnection->close();
    }

    public function testLibrarianLoginSuccess(): void
    {
        $_POST['email'] = 'librarian@test.com';
        $_POST['password'] = 'password123';
        
        ob_start(); // Çıktıyı yakalamak için
        include 'C:\xampp\htdocs\AML\code\views\login_register\login.php';
        $output = ob_get_clean();

        $this->assertStringNotContainsString('<p style=\'color:red;', $output);
    }

    public function testLibrarianLoginWrongPassword(): void
    {
        $_POST['email'] = 'librarian@test.com';
        $_POST['password'] = 'wrongpassword';
        
        ob_start();
        include 'C:\xampp\htdocs\AML\code\views\login_register\login.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Wrong Password!', $output);
    }

    public function testMemberLoginSuccess(): void
    {
        $_POST['email'] = 'member@test.com';
        $_POST['password'] = 'password123';
        
        ob_start();
        include 'C:\xampp\htdocs\AML\code\views\login_register\login.php';
        $output = ob_get_clean();

        $this->assertStringNotContainsString('<p style=\'color:red;', $output);
    }

    public function testInactiveAccount(): void
    {
        $_POST['email'] = 'inactive@test.com';
        $_POST['password'] = 'password123';
        
        ob_start();
        include 'C:\xampp\htdocs\AML\code\views\login_register\login.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Account not verified', $output);
    }

    public function testUserNotFound(): void
    {
        $_POST['email'] = 'notfound@test.com';
        $_POST['password'] = 'password123';
        
        ob_start();
        include 'C:\xampp\htdocs\AML\code\views\login_register\login.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('User Not Found!', $output);
    }
}

?>
