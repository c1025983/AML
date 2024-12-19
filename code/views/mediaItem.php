<?php
require_once '..\backend/config.php';

// Initialize variables
$message = '';
$dueDate = '';

if (isset($_GET['id'])) {
    $mediaId = $_GET['id'];

    // Fetch the media item details
    $query = "SELECT * FROM mediaitem WHERE media_id = :media_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':media_id' => $mediaId]);

    $mediaItem = $stmt->fetch();
    if ($mediaItem) {
        $title = htmlspecialchars($mediaItem['title']);
        $author = htmlspecialchars($mediaItem['author']);
        $genre = htmlspecialchars($mediaItem['genre']);
        $availability = htmlspecialchars($mediaItem['availability']);
        $type = htmlspecialchars($mediaItem['type']);
    } else {
        die("Media item not found.");
    }
} else {
    die("Invalid media ID.");
}

// Handle the borrow button
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrow'])) {
    $memberId = 1; // Use the fixed library member ID
    $borrowDate = date('Y-m-d');
    $dueDate = date('Y-m-d', strtotime('+14 days')); // 14-day borrowing period

    try {
        $pdo->beginTransaction();

        // Insert into borrowrecord table
        $borrowQuery = "INSERT INTO borrowrecord (borrow_date, return_due, status, member_id, media_id) 
                        VALUES (:borrow_date, :return_due, 'borrowed', :member_id, :media_id)";
        $stmt = $pdo->prepare($borrowQuery);
        $stmt->execute([
            ':borrow_date' => $borrowDate,
            ':return_due' => $dueDate,
            ':member_id' => $memberId,
            ':media_id' => $mediaId,
        ]);

        // Update the availability in the mediaitem table
        $updateQuery = "UPDATE mediaitem SET availability = availability - 1 WHERE media_id = :media_id";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute([':media_id' => $mediaId]);

        $pdo->commit();

        $message = "Media borrowed successfully, due for: $dueDate";

        // Update the availability status in the UI
        $availability = 0;
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Item</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
        // Display the success message as an alert
        document.addEventListener("DOMContentLoaded", function () {
            const message = "<?php echo addslashes($message); ?>";
            if (message) {
                alert(message);
            }
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 text-center">
                <!-- Display the media image -->
                <img src="/AML/images/<?php echo strtolower($type); ?>photo.jpg" class="img-fluid" alt="<?php echo $type; ?> Image">
            </div>
            <div class="col-md-6">
                <!-- Display media item details -->
                <h1><?php echo $title; ?></h1>
                <p><strong>Author:</strong> <?php echo $author; ?></p>
                <p><strong>Genre:</strong> <?php echo $genre; ?></p>
                <p><strong>Type:</strong> <?php echo $type; ?></p>
                <p><strong>Availability:</strong> <span id="availability"><?php echo $availability < 0 ? 'Available' : 'Not Available'; ?></span></p>

                <!-- Borrow button -->
                <?php if ($availability > 0): ?>
                    <form method="POST">
                        <button type="submit" name="borrow" class="btn btn-primary">Borrow</button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>Not Available</button>
                <?php endif; ?>
            </div>
        </div>
        <a href="catalogue.php" class="btn btn-secondary mt-3">Back to Catalog</a>
    </div>
</body>
</html>
