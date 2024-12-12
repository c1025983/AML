<?php
// Include the controller file from the backend folder
require_once('../backend/controller.php');  // Correct path to backend/controller.php

// Create an instance of the LibraryController
$controller = new LibraryController();

// Get the total number of members
$totalMembers = $controller->displayTotalMembers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Dashboard</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Correct path to styles.css -->
</head>
<body>

    <div class="dashboard">
        <h1>Librarian Dashboard</h1>
        
        <!-- Total Members Card -->
        <div class="card">
            <h3>Total Members</h3>
            <p><?php echo $totalMembers; ?></p>
        </div>

    </div>

</body>
</html>
