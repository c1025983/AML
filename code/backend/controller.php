<?php
require_once "model.php";

// Determine the requested action
$action = $_GET['action'] ?? 'home';

if ($action === 'catalogue') {
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

<<<<<<< Updated upstream
$members = getAllLibraryMembers($pdo)
require_once 'views/librarian/index.php';
=======
// Handle "Order Media" POST request
if ($action === 'order_media' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    createPurchaseOrder($pdo, $_POST['media_id'], $_POST['vendor_id'], $_POST['quantity'], $_POST['delivery_date']);
    header("Location: ../public/index.php?action=librarian"); // Redirect after saving
    exit;
}


>>>>>>> Stashed changes
?>
