<?php
session_start();
if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$stmt = $conn->prepare("SELECT username, credits, expires_at FROM users WHERE is_admin = 0");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo '<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Total Credits Added</th>
                    <th>Remaining Time</th>
                </tr>
            </thead>
            <tbody>';

    while ($row = $result->fetch_assoc()) {
        $expires_at = strtotime($row['expires_at']);
        $remaining_time = $expires_at - time();
        $hours = floor($remaining_time / 3600);
        $minutes = floor(($remaining_time % 3600) / 60);
        $seconds = $remaining_time % 60;

        echo '<tr>
                <td>' . htmlspecialchars($row['username']) . '</td>
                <td>' . htmlspecialchars($row['credits']) . '</td>
                <td>' . gmdate("H:i:s", $remaining_time) . '</td>
              </tr>';
    }

    echo '</tbody></table>';
} else {
    echo '<div class="alert alert-info">No non-admin users found.</div>';
}

$stmt->close();
?>
