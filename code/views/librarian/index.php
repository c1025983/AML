<!DOCTYPE html>
<html>
<?php
include_once('../../backend/config.php'); // Including the config file for the database connection

// SQL query to fetch the total number of members
$sql_count = "SELECT COUNT(*) AS total_members FROM librarymember";
$result_count = $pdo->query($sql_count);

// Check if there are any results for total members
$total_members = 0;
if ($result_count) {
    $row_count = $result_count->fetch(PDO::FETCH_ASSOC);
    $total_members = $row_count['total_members'];
}
// SQL query to fetch all members
$sql = "SELECT * FROM librarymember";
$result = $pdo->query($sql);

// Check if there are any results
$members = [];
if ($result) {
    $members = $result->fetchAll(PDO::FETCH_ASSOC);
}

// SQL query to fetch the total number of books borrowed
$sql_borrowed = "SELECT COUNT(*) AS total_borrowed FROM borrowrecord";
$result_borrowed = $pdo->query($sql_borrowed);

// Check if there are any results for total books borrowed
$total_borrowed = 0;
if ($result_borrowed) {
    $row_borrowed = $result_borrowed->fetch(PDO::FETCH_ASSOC);
    $total_borrowed = $row_borrowed['total_borrowed'];
}

$sql_last_week = "SELECT COUNT(*) AS members_last_week FROM librarymember WHERE registration_date >= NOW() - INTERVAL 7 DAY";
$result_last_week = $pdo->query($sql_last_week);

// Check if there are any results for members who joined in the last week
$members_last_week = 0;
if ($result_last_week) {
    $row_last_week = $result_last_week->fetch(PDO::FETCH_ASSOC);
    $members_last_week = $row_last_week['members_last_week'];
}


$sql_media_items = "SELECT * FROM mediaitem";
$result_media_items = $pdo->query($sql_media_items);

// Check if there are any results for media items
$media_items = [];
if ($result_media_items) {
    while ($row = $result_media_items->fetch(PDO::FETCH_ASSOC)) {
        $media_items[] = $row;
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Page</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">AML</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="lni lni-agenda"></i>
                        <span>Task</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                        <i class="lni lni-protection"></i>
                        <span>Auth</span>
                    </a>
                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">Login</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link">Register</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#multi" aria-expanded="false" aria-controls="multi">
                        <i class="lni lni-layout"></i>
                        <span>Multi Level</span>
                    </a>
                    <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse"
                                data-bs-target="#multi-two" aria-expanded="false" aria-controls="multi-two">
                                Two Links
                            </a>
                            <ul id="multi-two" class="sidebar-dropdown list-unstyled collapse">
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link">Link 1</a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link">Link 2</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="lni lni-popup"></i>
                        <span>Notification</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="lni lni-cog"></i>
                        <span>Setting</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="#" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        <div class="main">
            <nav class="navbar navbar-expand px-4 py-3">
                <form action="#" class="d-none d-sm-inline-block">

                </form>
                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                <img src="/account.png" class="avatar img-fluid" alt="">
                            </a>
                            <div class="dropdown-menu dropdown-menu-end rounded">

                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <div class="mb-3">
                        <h3 class="fw-bold fs-4 mb-3">Librarian Dashboard</h3>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="card border-0">
                                    <div class="card-body py-4">
                                        <h5 class="mb-2 fw-bold">
                                            Total Members
                                        </h5>
                                        <p class="mb-2 fw-bold">
                                            <?php echo $total_members; ?>
                                        </p>
                                        <div class="mb-0">
                                            <span class="fw-bold">
                                                Total members in the system
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="card border-0">
                                    <div class="card-body py-4">
                                        <h5 class="mb-2 fw-bold">
                                            Total Books Borrowed
                                        </h5>
                                        <p class="mb-2 fw-bold">
                                            <?php echo $total_borrowed; ?>
                                        </p>
                                        <div class="mb-0">
                                            <span class="fw-bold">
                                                Total books borrowed
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="card border-0">
                                    <div class="card-body py-4">
                                        <h5 class="mb-2 fw-bold">
                                            New Members
                                        </h5>
                                        <p class="mb-2 fw-bold">
                                            <?php echo $members_last_week; ?>
                                        </p>
                                        <div class="mb-0">
                                            <span class="fw-bold">
                                                In the past week
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h3 class="fw-bold fs-4 my-3">Library Members
                        </h3>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped">
                                    <thead>
                                        <tr class="highlight">
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Adress</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (count($members) > 0) {
                                            // Loop through the fetched members
                                            foreach ($members as $row) {
                                                echo "<tr>";
                                                echo "<th scope='row'>" . $row['member_id'] . "</th>"; 
                                                echo "<td>" . $row['name'] . "</td>";
                                                echo "<td>" . $row['email'] . "</td>"; 
                                                echo "<td>" . $row['address'] . "</td>"; 
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='4'>No members found</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive mt-4">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Author</th>
                                            <th scope="col">Genre</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($media_items as $media_item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($media_item['media_id']); ?></td>
                                            <td><?php echo htmlspecialchars($media_item['title']); ?></td>
                                            <td><?php echo htmlspecialchars($media_item['author']); ?></td>
                                            <td><?php echo htmlspecialchars($media_item['genre']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-body-secondary">
                        <div class="col-6 text-start ">
                            <a class="text-body-secondary" href=" #">
                                <strong>AML</strong>
                            </a>
                        </div>
                        <div class="col-6 text-end text-body-secondary d-none d-md-block">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a class="text-body-secondary" href="#">Contact</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-body-secondary" href="#">About Us</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-body-secondary" href="#">Terms & Conditions</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="script.js"></script>
</body>

</html>