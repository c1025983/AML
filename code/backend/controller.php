<?php
require_once "model.php";

// Determine the requested action
$action = $_GET['action'] ?? 'home';

// Default error variable
$error = null;

// Route based on action
if ($action === 'catalogue') {
    $catalogueItems = getCatalogueItems($pdo);
    require "../views/catalogue.php";
} elseif ($action === 'join' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle user registration
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (registerUser($pdo, $username, $email, $password)) {
        header("Location: ../public/index.php?action=home");
        exit;
    } else {
        $error = "Registration failed! Please try again.";
        require "../views/join.php";
    }
} elseif ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle user login
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
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
    // Render join view
    require "../views/join.php";
} elseif ($action === 'login') {
    // Render login view
    require "../views/login.php";
} elseif ($action === 'librarian') {
    // Librarian dashboard logic
    $members = getAllLibraryMembers($pdo);
    $totalMembers = count($members);
    $newMembers = getNewMembersLastWeek($pdo);
    $totalBorrowed = getTotalBooksBorrowed($pdo);
    $mediaItems = getAllMediaItems($pdo);

    require "../views/librarian/index.php";
} else {
    // Render default homepage view
    require "../views/index.php";
}
?>
