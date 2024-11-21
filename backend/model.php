<?php
require_once "config.php";

// Fetch total members
function getTotalMembers($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM librarymember");
    return $stmt->fetchColumn();
}

// Fetch new members this week
function getNewMembersThisWeek($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM LibraryMember WHERE registration_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
    $result = $stmt->fetchColumn();
    return $result;
}

// Fetch books currently borrowed
function getBooksBorrowed($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM borrowrecord WHERE status = 'borrowed'");
    return $stmt->fetchColumn();
}

// Fetch books due (where the return_due date is today or in the past)
function getBooksDue($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM borrowrecord WHERE return_due <= CURDATE() AND status = 'borrowed'");
    return $stmt->fetchColumn();
}

// Fetch all items from the catalogue
function getCatalogueItems($pdo) {
    $stmt = $pdo->query("SELECT * FROM MediaItem");
    return $stmt->fetchAll();
}

// Register a new user
function registerUser($pdo, $username, $email, $password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO librarymember (name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword]);
}

// Authenticate a user
function loginUser($pdo, $email, $password) {
    $stmt = $pdo->prepare("SELECT * FROM librarymember WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        return $user; // Login successful
    }
    return false; // Login failed
}
?>
