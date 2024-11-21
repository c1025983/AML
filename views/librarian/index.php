<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Link to your Custom Stylesheet -->
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <header>
        <h1>Librarian Dashboard</h1>
    </header>
    
    <!-- Navigation -->
    <nav>
        <a href="../public/index.php?action=home">Home</a>
        <a href="../public/index.php?action=catalogue">Catalogue</a>
        <a href="../public/index.php?action=join">Join</a>
        <a href="../public/index.php?action=login">Login</a>
    </nav>

    <!-- Main Dashboard Container -->
    <div class="container dashboard-container">
        <!-- Card for Total Members -->
        <div class="card">
            <div class="card-title">Total Members</div>
            <div class="card-number"><?php echo $totalMembers; ?></div>
        </div>
        
        <!-- Card for New Members This Week -->
<div class="card">
    <div class="card-title">New Members (Past Week)</div>
    <div class="card-number"><?php echo $newMembersThisWeek; ?></div>
</div>

        
        <!-- Card for Books Borrowed -->
        <div class="card">
            <div class="card-title">Books Currently Borrowed</div>
            <div class="card-number"><?php echo $booksBorrowed; ?></div>
        </div>
        
        <!-- Card for Books Due -->
        <div class="card">
            <div class="card-title">Books Due</div>
            <div class="card-number"><?php echo $booksDue; ?></div>
        </div>
    </div>


    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
