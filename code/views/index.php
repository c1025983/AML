<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg" style="background-color: #293b5f;">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">AML</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link text-white" href="login_register/login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="login_register/join.php">Register</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#">About</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Welcome to AML</h1>
        <div class="row justify-content-center">
            <?php
            // Database connection
            $dsn = 'mysql:host=localhost;dbname=aml_database;charset=utf8mb4';
            $username = 'root';
            $password = '';

            try {
                $pdo = new PDO($dsn, $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Fetch 3 random media items
                $query = "SELECT * FROM mediaitem ORDER BY RAND() LIMIT 3";
                $stmt = $pdo->query($query);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($results as $row) {
                    // Determine the correct image based on the media type
                    if ($row['type'] == 'Book') {
                        $imagePath = '/AML/images/bookphoto.jpg';
                    } elseif ($row['type'] == 'DVD') {
                        $imagePath = '/AML/images/dvdphoto.jpg';
                    } elseif ($row['type'] == 'Magazine') {
                        $imagePath = '/AML/images/magazinephoto.jpg';
                    } else {
                        $imagePath = '/AML/images/defaultphoto.jpg';
                    }

                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card h-100">';
                    echo '<img src="' . $imagePath . '" class="card-img-top" alt="' . htmlspecialchars($row['type']) . ' Photo">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
                    echo '<h6 class="card-subtitle mb-2 text-muted">' . htmlspecialchars($row['author']) . '</h6>';
                    echo '<p class="card-text">Genre: ' . htmlspecialchars($row['genre']) . '</p>';
                    echo '<p class="card-text">Available Copies: ' . htmlspecialchars($row['availability']) . '</p>';
                    echo '<a href="details.php?id=' . urlencode($row['media_id']) . '" class="btn btn-info">Details</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } catch (PDOException $e) {
                echo '<p class="text-danger text-center">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
            }
            ?>
        </div>

        <!-- Carousel -->
        <div id="carouselExampleIndicators" class="carousel slide mt-5" data-bs-ride="carousel" data-bs-interval="2000">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/AML/images/bookphoto.jpg" class="d-block w-100" alt="Book Photo">
                </div>
                <div class="carousel-item">
                    <img src="/AML/images/dvdphoto.jpg" class="d-block w-100" alt="DVD Photo">
                </div>
                <div class="carousel-item">
                    <img src="/AML/images/magazinephoto.jpg" class="d-block w-100" alt="Magazine Photo">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-white text-center py-4 mt-5" style="background-color: #3c4f6d;">
        <p>&copy; 2024 Library. All rights reserved.</p>
        <p>Address: 123 Library Lane, Booktown</p>
        <p>Email: contact@library.com | Phone: (123) 456-7890</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
