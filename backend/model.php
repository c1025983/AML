<?php
require_once "config.php";

// Fetch total members
function getTotalMembers($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM LibraryMember");
    $result = $stmt->fetchColumn();
    return $result;
}

// Fetch new members this week
function getNewMembersThisWeek($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM LibraryMember WHERE registration_date > CURDATE() - INTERVAL 7 DAY");
    $result = $stmt->fetchColumn();
    return $result;
}

// Fetch books borrowed
function getBooksBorrowed($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM BorrowedBooks WHERE returned = 0");
    $result = $stmt->fetchColumn();
    return $result;
}

// Fetch books due (where the return date is today or in the past)
function getBooksDue($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM BorrowedBooks WHERE due_date <= CURDATE() AND returned = 0");
    $result = $stmt->fetchColumn();
    return $result;
}

// Fetch all items from the catalogue
function getCatalogueItems($pdo)
{
    $stmt = $pdo->query("SELECT * FROM MediaItem");
    return $stmt->fetchAll();
}

// Register a new user
function registerUser($pdo, $username, $email, $password)
{
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO LibraryMember (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword]);
}

// Authenticate a user
function loginUser($pdo, $email, $password)
{
    $stmt = $pdo->prepare("SELECT * FROM LibraryMember WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        return $user; // Login successful
    }
    return false; // Login failed
}
?>
