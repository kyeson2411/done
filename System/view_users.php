<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$users = $user->getAllUsers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Users and Credits</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Users and Credits</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Credits</th>
                    <th>Expires At</th>
                    <th>Time Remaining</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $users->fetch_assoc()): ?>
                    <?php
                    $expires_at = strtotime($row['expires_at']);
                    $remaining_time = $expires_at - time();
                    ?>
                    <tr id="user-<?php echo htmlspecialchars($row['id']); ?>" data-expiration="<?php echo $expires_at; ?>" class="<?php echo $remaining_time <= 0 ? 'hidden' : ''; ?>">
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['credits']); ?></td>
                        <td><?php echo htmlspecialchars($row['expires_at']); ?></td>
                        <td id="timer-<?php echo htmlspecialchars($row['id']); ?>"><?php echo gmdate("H:i:s", $remaining_time); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary">Back to Credit Loading</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function updateTimer() {
            document.querySelectorAll('tr[id^="user-"]').forEach(function(row) {
                var id = row.id.split('-')[1];
                var expirationTime = row.getAttribute('data-expiration') * 1000; // Convert to milliseconds
                var now = new Date().getTime();
                var distance = expirationTime - now;

                if (distance <= 0) {
                    row.classList.add('hidden');
                } else {
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    hours = hours < 10 ? '0' + hours : hours;
                    minutes = minutes < 10 ? '0' + minutes : minutes;
                    seconds = seconds < 10 ? '0' + seconds : seconds;

                    document.getElementById('timer-' + id).innerText = hours + ":" + minutes + ":" + seconds;
                }
            });
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    </script>
</body>
</html>
