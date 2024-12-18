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
} elseif ($action === 'librarian'){
    require "../views/librarian/index.php";
}


//-------------------------------------------------librarian logic-----------------------------------------------------------

// Total members count
function getTotalMembers($pdo) {
    return fetchScalar($pdo, "SELECT COUNT(*) FROM librarymember");
}

// Get all members
function getAllMembers($pdo) {
    return fetchAll($pdo, "SELECT * FROM librarymember");
}

// Total books borrowed
function getTotalBooksBorrowed($pdo) {
    return fetchScalar($pdo, "SELECT COUNT(*) FROM borrowrecord");
}

// Members joined in the last week
function getNewMembersLastWeek($pdo) {
    return fetchScalar($pdo, "SELECT COUNT(*) FROM librarymember WHERE registration_date >= NOW() - INTERVAL 7 DAY");
}

// Fetch all media items
function getAllMediaItems($pdo) {
    return fetchAll($pdo, "SELECT * FROM mediaitem");
}

// Delete media item
function deleteMedia($pdo, $media_id) {
    return executeUpdate($pdo, "DELETE FROM mediaitem WHERE media_id = :media_id", ['media_id' => $media_id]);
}

// Create new media item
function createMedia($pdo, $title, $author, $genre, $type, $branch_id) {
    return executeUpdate($pdo, "INSERT INTO mediaitem (title, author, genre, type, branch_id) 
                                VALUES (:title, :author, :genre, :type, :branch_id)", 
                                ['title' => $title, 'author' => $author, 'genre' => $genre, 'type' => $type, 'branch_id' => $branch_id]);
}

// Edit media item
function editMedia($pdo, $media_id, $title, $author, $genre, $type, $branch_id) {
    return executeUpdate($pdo, "UPDATE mediaitem 
                                SET title = :title, author = :author, genre = :genre, type = :type, branch_id = :branch_id 
                                WHERE media_id = :media_id", 
                                ['media_id' => $media_id, 'title' => $title, 'author' => $author, 'genre' => $genre, 'type' => $type, 'branch_id' => $branch_id]);
}
// Edit member details
function editMember($pdo, $member_id, $name, $email, $address) {
    return executeUpdate($pdo, 
        "UPDATE librarymember 
         SET name = :name, email = :email, address = :address 
         WHERE member_id = :member_id", 
        ['name' => $name, 'email' => $email, 'address' => $address, 'member_id' => $member_id]
    );
}

// Delete a member
function deleteMember($pdo, $member_id) {
    return executeUpdate($pdo, 
        "DELETE FROM librarymember WHERE member_id = :member_id", 
        ['member_id' => $member_id]
    );
}

?>
