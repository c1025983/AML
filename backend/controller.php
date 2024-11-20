<?php
require_once "model.php";

// Determine the requested action
$action = $_GET['action'] ?? 'home';

if ($action === 'librarian') {
    // Fetch necessary data for the librarian dashboard
    $totalMembers = getTotalMembers($pdo);
    $newMembersThisWeek = getNewMembersThisWeek($pdo);
    $booksBorrowed = getBooksBorrowed($pdo);
    $booksDue = getBooksDue($pdo);
    
    // Pass the data to the view
    require "../views/librarian/index.php";
} elseif ($action === 'catalogue') {
    $catalogueItems = getCatalogueItems($pdo);
    require "../views/catalogue.php";
} elseif ($action === 'join' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    registerUser($pdo, $_POST['username'], $_POST['email'], $_POST['password']);
    header("Location: ../public/index.php?action=home");
    exit;
} elseif ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = loginUser($pdo, $_POST['email'], $_POST['password']);
    if ($user) {
        session_start();
        $_SESSION['user'] = $user;
        header("Location: ../public/index.php?action=home");
        exit;
    } else {
        $error = "Invalid credentials!";
    }
    require "../views/login.php";
} elseif ($action === 'join') {
    require "../views/join.php";
} elseif ($action === 'login') {
    require "../views/login.php";
} else {
    require "../views/index.php"; // Default to homepage
}
?>
