<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Catalog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">AML</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Task</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Auth
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Login</a></li>
                            <li><a class="dropdown-item" href="#">Register</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Notifications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Settings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Library Catalog</h1>

        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form class="d-flex" method="GET" action="catalogue.php">
                    <input id="search" type="text" class="form-control me-2" name="search" placeholder="Search by title, author, or genre" value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
                <div id="autocomplete-results" class="list-group mt-1 position-absolute w-50"></div>
            </div>
            <div class="col-md-6">
                <form method="GET" action="catalogue.php">
                    <select class="form-select" name="media_type" onchange="this.form.submit()">
                        <option value="">All Media Types</option>
                        <option value="Book" <?php echo (($_GET['media_type'] ?? '') === 'Book') ? 'selected' : ''; ?>>Book</option>
                        <option value="DVD" <?php echo (($_GET['media_type'] ?? '') === 'DVD') ? 'selected' : ''; ?>>DVD</option>
                        <option value="Magazine" <?php echo (($_GET['media_type'] ?? '') === 'Magazine') ? 'selected' : ''; ?>>Magazine</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Sort Section -->
        <div class="row mb-3">
            <div class="col-md-12">
                <form method="GET" action="catalogue.php" class="d-flex justify-content-end">
                    <select class="form-select w-auto" name="sort" onchange="this.form.submit()">
                        <option value="">Sort by</option>
                        <option value="title" <?php echo (($_GET['sort'] ?? '') === 'title') ? 'selected' : ''; ?>>Title</option>
                        <option value="author" <?php echo (($_GET['sort'] ?? '') === 'author') ? 'selected' : ''; ?>>Author</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Books Section -->
        <div class="row">
            <?php
            // Database connection
            $dsn = 'mysql:host=localhost;dbname=aml_database;charset=utf8mb4';
            $username = 'root';
            $password = '';
            try {
                $pdo = new PDO($dsn, $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Query preparation
                $search = $_GET['search'] ?? '';
                $mediaType = $_GET['media_type'] ?? '';
                $sort = $_GET['sort'] ?? '';

                $query = "SELECT * FROM mediaitem WHERE 1=1";
                $params = [];

                if (!empty($search)) {
                    $query .= " AND (title LIKE :search OR author LIKE :search OR genre LIKE :search)";
                    $params[':search'] = "%$search%";
                }
                if (!empty($mediaType)) {
                    $query .= " AND type = :media_type";
                    $params[':media_type'] = $mediaType;
                }

                if (!empty($sort)) {
                    $allowedSortFields = ['title', 'author'];
                    if (in_array($sort, $allowedSortFields)) {
                        $query .= " ORDER BY $sort";
                    }
                }

                $stmt = $pdo->prepare($query);
                $stmt->execute($params);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (empty($results)) {
                    echo '<p class="text-center">No media items found</p>';
                } else {
                    foreach ($results as $row) {
                        // Determine the correct image based on the media type
                        if ($row['type'] == 'Book') {
                            $imagePath = '/AML/images/bookphoto.jpg';
                        } elseif ($row['type'] == 'DVD') {
                            $imagePath = '/AML/images/dvdphoto.jpg';
                        } elseif ($row['type'] == 'Magazine') {
                            $imagePath = '/AML/images/magazinephoto.jpg';
                        } else {
                            $imagePath = '/AML/images/defaultphoto.png'; // Fallback if the type is not recognized
                        }

                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card h-100">';
                        echo '<img src="' . $imagePath . '" class="card-img-top" alt="' . htmlspecialchars($row['type']) . ' Photo">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
                        echo '<h6 class="card-subtitle mb-2 text-muted">' . htmlspecialchars($row['author']) . '</h6>';
                        echo '<p class="card-text">Genre: ' . htmlspecialchars($row['genre']) . '</p>';
                        echo '<p class="card-text">Available Copies: ' . htmlspecialchars($row['availability']) . '</p>';
                        echo '<a href="mediaItem.php?id=' . urlencode($row['media_id']) . '" class="btn btn-info">Details</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
            } catch (PDOException $e) {
                echo '<p class="text-danger text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
            ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#search').on('input', function() {
                const query = $(this).val();
                if (query.length > 2) {
                    $.ajax({
                        url: 'autocomplete.php',
                        method: 'GET',
                        data: { query: query },
                        success: function(data) {
                            const results = JSON.parse(data);
                            let suggestions = '';
                            results.forEach(item => {
                                suggestions += `<a href="#" class="list-group-item list-group-item-action">${item}</a>`;
                            });
                            $('#autocomplete-results').html(suggestions).show();
                        }
                    });
                } else {
                    $('#autocomplete-results').hide();
                }
            });

            $(document).on('click', '#autocomplete-results a', function(e) {
                e.preventDefault();
                const text = $(this).text();
                $('#search').val(text);
                $('#autocomplete-results').hide();
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
