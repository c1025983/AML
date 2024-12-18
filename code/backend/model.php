<?php
require_once "config.php";

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

<<<<<<< Updated upstream
function getAllLibraryMembers($pdo) {
    // Query to get all members from the librarymember table
    $stmt = $pdo->query("SELECT * FROM librarymember");
    
    // Return the fetched results as an associative array
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

=======

//-----------------------------librarian---------------------

// Generic function to execute queries
function executeQuery($pdo, $sql, $params = []) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

// Fetch all rows
function fetchAll($pdo, $sql, $params = []) {
    return executeQuery($pdo, $sql, $params)->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch one row
function fetchOne($pdo, $sql, $params = []) {
    return executeQuery($pdo, $sql, $params)->fetch(PDO::FETCH_ASSOC);
}

// Fetch scalar value (e.g., counts)
function fetchScalar($pdo, $sql, $params = []) {
    return executeQuery($pdo, $sql, $params)->fetchColumn();
}

// Insert, Update, Delete operation
function executeUpdate($pdo, $sql, $params = []) {
    return executeQuery($pdo, $sql, $params);
}
>>>>>>> Stashed changes
?>
