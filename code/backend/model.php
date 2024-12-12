<?php
require_once "config.php";

// Fetch all items from the catalogue
function getCatalogueItems($pdo)
{
    $stmt = $pdo->query("SELECT * FROM MediaItem");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Register a new user
function registerUser($pdo, $username, $email, $password)
{
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO LibraryMember (username, email, password) VALUES (?, ?, ?)");
    return $stmt->execute([$username, $email, $hashedPassword]);
}

// Authenticate a user
function loginUser($pdo, $email, $password)
{
    $stmt = $pdo->prepare("SELECT * FROM LibraryMember WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        return $user; // Login successful
    }
    return false; // Login failed
}

// Fetch all library members
function getAllLibraryMembers($pdo)
{
    $stmt = $pdo->query("SELECT * FROM LibraryMember");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Add a new library member
function addLibraryMember($pdo, $name, $email, $password, $address)
{
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO LibraryMember (name, email, password, address) VALUES (:name, :email, :password, :address)";
    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':password' => $hashedPassword,
        ':address' => $address
    ]);
}
?>
