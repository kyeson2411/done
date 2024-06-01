<?php
session_start();
if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_username']) && isset($_POST['add_credits'])) {
    $add_username = $_POST['add_username'];
    $add_credits = floatval($_POST['add_credits']);
    $user->addCredits($add_username, $add_credits);
    $error = 'Credits added successfully.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Add Credits</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Add Credits</h2>
        <?php if ($error): ?>
            <div class="alert alert-success"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="POST" class="form-inline mb-4">
            <div class="form-group mr-2">
                <label for="add_username" class="mr-2">Username:</label>
                <input type="text" id="add_username" name="add_username" class="form-control" required>
            </div>
            <div class="form-group mr-2">
                <label for="add_credits" class="mr-2">Credits:</label>
                <input type="number" step="0.01" id="add_credits" name="add_credits" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Credits</button>
        </form>

        <button id="viewNonAdminUsers" class="btn btn-secondary mb-4">View Non-Admin Users</button>
        
        <div id="nonAdminUsersSection" style="display: none;">
            <h4>Non-Admin Users</h4>
            <div id="nonAdminUsersTable"></div>
        </div>

        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
    <script>
        $(document).ready(function() {
            $('#viewNonAdminUsers').on('click', function() {
                $.ajax({
                    url: 'view_non_admin_users.php',
                    type: 'GET',
                    success: function(response) {
                        $('#nonAdminUsersTable').html(response);
                        $('#nonAdminUsersSection').show();
                    },
                    error: function() {
                        alert('Failed to load user data.');
                    }
                });
            });
        });
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
