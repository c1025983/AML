<?php
require_once "model.php";

// Determine the requested action
$action = $_GET['action'] ?? 'home';

if ($action === 'librarian') {
    // Fetch necessary data for the librarian dashboard
    $totalMembers = getTotalMembers($pdo);

    // Load the librarian management page
    require "../views/librarian/index.php";
} elseif ($action === 'catalogue') {
    // Load the catalogue page
    $catalogueItems = getCatalogueItems($pdo);
    require "../views/catalogue.php";
} elseif ($action === 'join' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle user registration
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    registerUser($pdo, $username, $email, $password);
    header("Location: ../public/index.php?action=home");
    exit;
} elseif ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle user login
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $user = loginUser($pdo, $email, $password);
    
    if ($user) {
        session_start();
        $_SESSION['user'] = $user;
        header("Location: ../public/index.php?action=home");
        exit;
    } else {
        $error = "Invalid credentials!";
        require "../views/login.php";
    }
} elseif ($action === 'join') {
    // Load the registration page
    require "../views/join.php";
} elseif ($action === 'login') {
    // Load the login page
    require "../views/login.php";
} else {
    // Default to homepage
    require "../views/index.php";
}
?>
