<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librarian Admin Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bubble {
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 150px;
            width: 150px;
            font-size: 24px;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .bubble:hover {
            transform: scale(1.1);
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .bubble-info {
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row text-center">
            <div class="col">
                <div class="bubble" id="totalMembers" data-bs-toggle="modal" data-bs-target="#dataModal"><?php echo $totalMembers; ?></div>
                <p>Total Members</p>
            </div>
            <div class="col">
                <div class="bubble" id="newMembers" data-bs-toggle="modal" data-bs-target="#dataModal"><?php echo $newMembersThisWeek; ?></div>
                <p>New Members This Week</p>
            </div>
            <div class="col">
                <div class="bubble" id="booksBorrowed" data-bs-toggle="modal" data-bs-target="#dataModal"><?php echo $booksBorrowed; ?></div>
                <p>Books Borrowed</p>
            </div>
            <div class="col">
                <div class="bubble" id="booksDue" data-bs-toggle="modal" data-bs-target="#dataModal"><?php echo $booksDue; ?></div>
                <p>Books Due</p>
            </div>
        </div>
    </div>

    <!-- Modal for detailed view -->
    <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataModalLabel">Data Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalContent">Loading...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="app.js"></script>
</body>
</html>
