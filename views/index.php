<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to AML</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <h1>Welcome to the Advanced Media Library</h1>
    <nav>
        <a href="index.php?action=catalogue">View Catalogue</a> |
        <a href="index.php?action=join">Join Us</a> |
        <a href="index.php?action=login">Login</a> |
        <a href="index.php?action=librarian">Librarian Admin</a>
    </nav>

    <?php
    // Check if the action is set in the URL and handle it
    $action = isset($_GET['action']) ? $_GET['action'] : null;

    switch ($action) {
        case 'catalogue':
            echo "<p>Welcome to the Catalogue page.</p>";
            break;
        case 'join':
            echo "<p>Join Us page.</p>";
            break;
        case 'login':
            echo "<p>Login page.</p>";
            break;
        case 'librarian':
            include('librarian/index.php');
            break;
        default:
            echo "<p>Welcome to the Advanced Media Library!</p>";
            break;
    }
    ?>
</body>
</html>
