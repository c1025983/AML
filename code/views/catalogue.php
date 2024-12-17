<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Catalogue</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <h1>Media Catalogue</h1>
    <a href="index.php">Back to Home</a>
    <ul>
        <?php foreach ($catalogueItems as $item): ?>
            <li>
                <a href="mediaItem.php?id=<?php echo $item['id']; ?>">
                    <strong><?php echo htmlspecialchars($item['title']); ?></strong> by <?php echo htmlspecialchars($item['author']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
