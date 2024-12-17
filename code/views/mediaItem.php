<?php
require_once "config.php";
session_start();

//checks if the user has logged in already to determine whether the borrow button will be added
if (!isset($_SESSION['user'])) {
    die("You must be logged in to borrow media.");
}

//error message for if the media ID isn't found
if (!isset($_GET['id'])) {
    die("Media ID not specified.");
}

$mediaId = $_GET['id'];
$loggedInUserId = $_SESSION['user']['id']; //values to pass to query the user details

try {
    //The below code will get the details from the media table to add into the page
    $stmt = $pdo->prepare("SELECT * FROM mediaItem WHERE id = :id");
    $stmt->bindParam(':id', $mediaId, PDO::PARAM_INT);
    $stmt->execute();
    $mediaItem = $stmt->fetch();

    if (!$mediaItem) {
        die("Media item not found."); //Error message if fetching the media doesn't work for some reason
    }

    //Button functionality. Email functionality removed for now since it wasnt working.
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrow'])) {
        if ($mediaItem['availability'] == 1) {
            //insert into returnrecord after the borrow button is clicked, currently doesn't check for duplicates.
            $returndate = (new DateTime('+1 month'))->format('Y-m-d'); //I've made the due date 1 month away, will adjust if needed
            $stmt = $pdo->prepare("INSERT INTO returnrecord (memberID, mediaID, returndate, returncondition) VALUES (:memberID, :mediaID, :returndate, 'NotReturned')");
            $stmt->bindParam(':memberID', $loggedInUserId, PDO::PARAM_INT);
            $stmt->bindParam(':mediaID', $mediaId, PDO::PARAM_INT);
            $stmt->bindParam(':returndate', $returndate);
            $stmt->execute();

            //Updates the media table immediately so to reflect that its not available anymore.
            $stmt = $pdo->prepare("UPDATE mediaItem SET availability = 0 WHERE id = :id");
            $stmt->bindParam(':id', $mediaId, PDO::PARAM_INT);
            $stmt->execute();
            
            //This is a temporary message until the emailing works, will be replaced if time allows.
            echo "<script>alert('Media has been borrowed successfully. Return date is one month from now.');</script>";
            //Updates the availability text
            header("Location: mediaItem.php?id=$mediaId");
            exit;
        } else {
            //do nothing if the media is unavailable anyways. This branch ideally should not happen since the borrow button
            //usually won't be visible if this is the case
            echo "<script>alert('Media is unavailable.');</script>";
        }
    }
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($mediaItem['title']); ?></title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($mediaItem['title']); ?></h1>
    <p><strong>Author:</strong> <?php echo htmlspecialchars($mediaItem['author']); ?></p>
    <p><strong>Genre:</strong> <?php echo htmlspecialchars($mediaItem['genre']); ?></p>
    <p><strong>Availability:</strong> <?php echo $mediaItem['availability'] ? "Available" : "Unavailable"; ?></p>
    <?php if ($mediaItem['availability']): ?>
        <form method="POST">
            <button type="submit" name="borrow">Borrow</button>
        </form>
    <?php else: ?>
        <p>This media item is currently unavailable.</p> 
    <?php endif; ?>
    <a href="catalogue.php">Back to Catalogue</a>
</body>
</html>
