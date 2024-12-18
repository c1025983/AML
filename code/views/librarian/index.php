<!DOCTYPE html>
<html>
<?php
session_start();
include_once('../../backend/controller.php');

// Fetch data for the view
$total_members = getTotalMembers($pdo);
$members = getAllMembers($pdo);
$total_borrowed = getTotalBooksBorrowed($pdo);
$members_last_week = getNewMembersLastWeek($pdo);
$media_items = getAllMediaItems($pdo);

// Handle POST requests
if (isset($_POST['delete_media'])) {
    deleteMedia($pdo, $_POST['media_id']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['create_media'])) {
    createMedia($pdo, $_POST['title'], $_POST['author'], $_POST['genre'], $_POST['type'], $_POST['branch_id']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['edit_media'])) {
    editMedia($pdo, $_POST['media_id'], $_POST['edit_title'], $_POST['edit_author'], $_POST['edit_genre'], $_POST['edit_type'], $_POST['edit_branch_id']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
if (isset($_POST['edit_member'])) {
    editMember($pdo, $_POST['member_id'], $_POST['edit_name'], $_POST['edit_email'], $_POST['edit_address']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['delete_member'])) {
    deleteMember($pdo, $_POST['member_id']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Page</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
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
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#members" aria-expanded="false" aria-controls="members">
                        <i class="lni lni-user"></i>
                        <span>Members</span>
                    </a>
                    <ul id="members" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" data-bs-toggle="modal"
                                data-bs-target="#deleteMemberModal">Delete Member</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="../login_register/join.php" class="sidebar-link">Create Member</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" data-bs-toggle="modal"
                                data-bs-target="#editMemberModal">Edit Member</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#media" aria-expanded="false" aria-controls="media">
                        <i class="lni lni-library"></i>
                        <span>Media</span>
                    </a>
                    <ul id="media" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" data-bs-toggle="modal"
                                data-bs-target="#deleteMediaModal">Delete Media</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" data-bs-toggle="modal"
                                data-bs-target="#createMediaModal">Create Media</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link" data-bs-toggle="modal"
                                data-bs-target="#editMediaModal">Edit Media</a>
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
                <a href="../index.php" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        <div class="main">
            <nav class="navbar navbar-expand px-4 py-3">
                <!--Display Cards -->
            </nav>
            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <h3 class="fw-bold fs-4 mb-3">Librarian Dashboard</h3>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card">
                                <h5>Total Members</h5>
                                <p class="fw-bold"><?php echo $total_members; ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <h5>Total Books Borrowed</h5>
                                <p class="fw-bold"><?php echo $total_borrowed; ?></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <h5>New Members (Last Week)</h5>
                                <p class="fw-bold"><?php echo $members_last_week; ?></p>
                            </div>
                        </div>
                    </div>
                    <h3 class="fw-bold fs-4 mb-3">Library Members</h3>
                    <div class="mb-3">
                        <input type="text" id="memberSearch" class="form-control" placeholder="Search for members...">
                    </div>
                    <div class="table-container">
                        <table class="table table-striped" id="membersTable">
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
                                <tr>
                                    <td colspan="4">No members found</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <h3 class="fw-bold fs-4 mb-3">Media Items</h3>
                    <div class="mb-3">
                        <input type="text" id="mediaSearch" class="form-control"
                            placeholder="Search for media items...">
                    </div>
                    <div class="table-container">
                        <table class="table table-striped" id="mediaTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Genre</th>
                                    <th>Type</th>
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
                                    <td><?php echo htmlspecialchars($media['type']); ?></td>
                                    <td><?php echo htmlspecialchars($media['branch_id']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else : ?>
                                <tr>
                                    <td colspan="5">No media items found</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>


    <!-- Edit Member Modal -->
    <div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMemberModalLabel">Edit Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="member_id" class="form-label">Member ID</label>
                        <input type="number" class="form-control" id="member_id" name="member_id" required>

                        <label for="edit_name" class="form-label mt-3">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="edit_name" required>

                        <label for="edit_email" class="form-label mt-3">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="edit_email" required>

                        <label for="edit_address" class="form-label mt-3">Address</label>
                        <input type="text" class="form-control" id="edit_address" name="edit_address" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="edit_member" class="btn btn-success">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Member Modal -->
    <div class="modal fade" id="deleteMemberModal" tabindex="-1" aria-labelledby="deleteMemberModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteMemberModalLabel">Delete Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="member_id" class="form-label">Enter Member ID</label>
                        <input type="number" class="form-control" id="member_id" name="member_id" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="delete_member" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Delete Media Modal -->
    <div class="modal fade" id="deleteMediaModal" tabindex="-1" aria-labelledby="deleteMediaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteMediaModalLabel">Delete Media</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="media_id" class="form-label">Enter Media ID to Delete</label>
                        <input type="number" class="form-control" id="media_id" name="media_id" placeholder="Media ID"
                            required>
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

                    <label for="type" class="form-label mt-3">Type</label>
                    <input type="text" class="form-control" id="type" name="type" placeholder="Enter Media Type" required>

                    <label for="branch_id" class="form-label mt-3">Branch ID</label>
                    <input type="number" class="form-control" id="branch_id" name="branch_id" placeholder="Enter Branch ID" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="create_media" class="btn btn-primary">Create</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <!-- Edit Media Modal -->
    <div class="modal fade" id="editMediaModal" tabindex="-1" aria-labelledby="editMediaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMediaModalLabel">Edit Media</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="media_id" class="form-label">Media ID</label>
                        <input type="number" class="form-control" id="media_id" name="media_id"
                            placeholder="Enter Media ID to Edit" required>

                        <label for="edit_title" class="form-label mt-3">Title</label>
                        <input type="text" class="form-control" id="edit_title" name="edit_title"
                            placeholder="Enter New Title" required>

                        <label for="edit_author" class="form-label mt-3">Author</label>
                        <input type="text" class="form-control" id="edit_author" name="edit_author"
                            placeholder="Enter New Author" required>

                        <label for="edit_genre" class="form-label mt-3">Genre</label>
                        <input type="text" class="form-control" id="edit_genre" name="edit_genre"
                            placeholder="Enter New Genre" required>

                        <label for="edit_type" class="form-label mt-3">Type</label>
                        <input type="text" class="form-control" id="edit_type" name="edit_type"
                            placeholder="Enter New Type" required>

                        <label for="edit_branch_id" class="form-label mt-3">Branch ID</label>
                        <input type="number" class="form-control" id="edit_branch_id" name="edit_branch_id"
                            placeholder="Enter New Branch ID" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="edit_media" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="script.js"></script>
</body>

</html>