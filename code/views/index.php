<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Catalog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Library Catalog</h1>

        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form class="d-flex" method="GET" action="index.php">
                    <input type="text" class="form-control me-2" name="search" placeholder="Search by title or author" value="<?php echo htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES); ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            <div class="col-md-4">
                <form method="GET" action="index.php">
                    <select class="form-select" name="media_type" onchange="this.form.submit()">
                        <option value="">All Media Types</option>
                        <option value="Book" <?php echo (($_GET['media_type'] ?? '') === 'Book') ? 'selected' : ''; ?>>Book</option>
                        <option value="DVD" <?php echo (($_GET['media_type'] ?? '') === 'DVD') ? 'selected' : ''; ?>>DVD</option>
                        <option value="Magazine" <?php echo (($_GET['media_type'] ?? '') === 'Magazine') ? 'selected' : ''; ?>>Magazine</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Books Table -->
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Type</th>
                    <th>Available Copies</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection
                $dsn = 'mysql:host=localhost;dbname=aml_database;charset=utf8mb4';
                $username = 'root'; // Change this if necessary
                $password = ''; // Change this if necessary
                try {
                    $pdo = new PDO($dsn, $username, $password);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Query preparation
                    $search = $_GET['search'] ?? '';
                    $mediaType = $_GET['media_type'] ?? '';
                    $query = "SELECT * FROM mediaitem WHERE 1=1";
                    $params = [];

                    if (!empty($search)) {
                        $query .= " AND (title LIKE :search OR author LIKE :search)";
                        $params[':search'] = "%$search%";
                    }
                    if (!empty($mediaType)) {
                        $query .= " AND type = :media_type";
                        $params[':media_type'] = $mediaType;
                    }

                    $stmt = $pdo->prepare($query);
                    $stmt->execute($params);
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (empty($results)) {
                        echo '<tr><td colspan="6" class="text-center">No media items found</td></tr>';
                    } else {
                        foreach ($results as $index => $row) {
                            echo "<tr>
                                <td>" . ($index + 1) . "</td>
                                <td>" . htmlspecialchars($row['title']) . "</td>
                                <td>" . htmlspecialchars($row['author']) . "</td>
                                <td>" . htmlspecialchars($row['genre']) . "</td>
                                <td>" . htmlspecialchars($row['availability']) . "</td>
                                <td>
                                    <a href='details.php?id=" . urlencode($row['media_id']) . "' class='btn btn-info btn-sm'>Details</a>
                                </td>
                            </tr>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='6' class='text-danger text-center'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
