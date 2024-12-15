<!DOCTYPE html>
<html>
<?php
include_once('../../backend/config.php');

// Fetch the total number of members
$sql_count = "SELECT COUNT(*) AS total_members FROM librarymember";
$result_count = $pdo->query($sql_count);
$total_members = $result_count ? $result_count->fetch(PDO::FETCH_ASSOC)['total_members'] : 0;

// Fetch all members
$sql = "SELECT * FROM librarymember";
$members = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC) ?: [];

// Fetch the total number of books borrowed
$sql_borrowed = "SELECT COUNT(*) AS total_borrowed FROM borrowrecord";
$result_borrowed = $pdo->query($sql_borrowed);
$total_borrowed = $result_borrowed ? $result_borrowed->fetch(PDO::FETCH_ASSOC)['total_borrowed'] : 0;

// Fetch members who joined in the last week
$sql_last_week = "SELECT COUNT(*) AS members_last_week FROM librarymember WHERE registration_date >= NOW() - INTERVAL 7 DAY";
$result_last_week = $pdo->query($sql_last_week);
$members_last_week = $result_last_week ? $result_last_week->fetch(PDO::FETCH_ASSOC)['members_last_week'] : 0;

// Fetch media items
$sql_media_items = "SELECT * FROM mediaitem";
$media_items = $pdo->query($sql_media_items)->fetchAll(PDO::FETCH_ASSOC) ?: [];

// Handle media deletion
if (isset($_POST['delete_media'])) {
    $media_id = $_POST['media_id'];

    $sql_delete = "DELETE FROM mediaitem WHERE media_id = :media_id";
    $stmt = $pdo->prepare($sql_delete);
    $stmt->bindParam(':media_id', $media_id, PDO::PARAM_INT);
    $stmt->execute();

    // Refresh the media items list
    $media_items = $pdo->query($sql_media_items)->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

// Handle media creation
if (isset($_POST['create_media'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $branch_id = $_POST['branch_id'];

    $sql_insert = "INSERT INTO mediaitem (title, author, genre, branch_id) VALUES (:title, :author, :genre, :branch_id)";
    $stmt = $pdo->prepare($sql_insert);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':author', $author, PDO::PARAM_STR);
    $stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
    $stmt->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
    $stmt->execute();

    // Refresh the media items list
    $media_items = $pdo->query($sql_media_items)->fetchAll(PDO::FETCH_ASSOC) ?: [];
}
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Page</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
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
                        data-bs-target="#media" aria-expanded="false" aria-controls="media">
                        <i class="lni lni-library"></i>
                        <span>Media</span>
                    </a>
                    <ul id="media" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" data-bs-toggle="modal" data-bs-target="#deleteMediaModal">Delete Media</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" data-bs-toggle="modal" data-bs-target="#createMediaModal">Create Media</a>
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
                <!-- Navbar content here -->
            </nav>
            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <h3 class="fw-bold fs-4 mb-3">Librarian Dashboard</h3>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-0">
                                <div class="card-body">
                                    <h5>Total Members</h5>
                                    <p class="fw-bold"><?php echo $total_members; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0">
                                <div class="card-body">
                                    <h5>Total Books Borrowed</h5>
                                    <p class="fw-bold"><?php echo $total_borrowed; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0">
                                <div class="card-body">
                                    <h5>New Members (Last Week)</h5>
                                    <p class="fw-bold"><?php echo $members_last_week; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3 class="fw-bold fs-4">Library Members</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($members)) : ?>
                                    <?php foreach ($members as $member) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($member['member_id']); ?></td>
                                            <td><?php echo htmlspecialchars($member['name']); ?></td>
                                            <td><?php echo htmlspecialchars($member['email']); ?></td>
                                            <td><?php echo htmlspecialchars($member['address']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan="4">No members found</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <h3 class="fw-bold fs-4 my-4">Media Items</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Genre</th>
                                    <th>Branch ID</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($media_items)) : ?>
                                    <?php foreach ($media_items as $media) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($media['media_id']); ?></td>
                                            <td><?php echo htmlspecialchars($media['title']); ?></td>
                                            <td><?php echo htmlspecialchars($media['author']); ?></td>
                                            <td><?php echo htmlspecialchars($media['genre']); ?></td>
                                            <td><?php echo htmlspecialchars($media['branch_id']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan="5">No media items found</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Delete Media Modal -->
    <div class="modal fade" id="deleteMediaModal" tabindex="-1" aria-labelledby="deleteMediaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteMediaModalLabel">Delete Media</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="media_id" class="form-label">Enter Media ID to Delete</label>
                        <input type="number" class="form-control" id="media_id" name="media_id" placeholder="Media ID" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="delete_media" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Media Modal -->
    <div class="modal fade" id="createMediaModal" tabindex="-1" aria-labelledby="createMediaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createMediaModalLabel">Create Media</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" required>
                        
                        <label for="author" class="form-label mt-3">Author</label>
                        <input type="text" class="form-control" id="author" name="author" placeholder="Enter Author" required>
                        
                        <label for="genre" class="form-label mt-3">Genre</label>
                        <input type="text" class="form-control" id="genre" name="genre" placeholder="Enter Genre" required>
                        
                        <label for="branch_id" class="form-label mt-3">Branch ID</label>
                        <input type="number" class="form-control" id="branch_id" name="branch_id" placeholder="Enter Branch ID" value="1" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="create_media" class="btn btn-primary">Create</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>
