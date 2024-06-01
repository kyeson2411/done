<?php
include 'db.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $credits = floatval($_POST['credits']);

    // Convert credits to minutes (1 credit = 6 minutes)
    $minutes = $credits * 6;

    $userInfo = $user->getUserInfo($username);
    $current_expires_at = strtotime($userInfo['expires_at']);

    // If current expires_at is in the past, start from now
    if ($current_expires_at <= time()) {
        $new_expires_at = time() + ($minutes * 60);
    } else {
        // Otherwise, extend the current expires_at
        $new_expires_at = $current_expires_at + ($minutes * 60);
    }

    $new_expires_at_formatted = date('Y-m-d H:i:s', $new_expires_at);

    $stmt = $conn->prepare("UPDATE users SET credits = credits + ?, expires_at = ? WHERE username = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("dss", $credits, $new_expires_at_formatted, $username);
    if ($stmt->execute()) {
        // Log the credit addition in the credit_history table
        $userId = $userInfo['id'];
        $operation = 'add';
        $stmt = $conn->prepare("INSERT INTO credit_history (user_id, credits, operation) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param("ids", $userId, $credits, $operation);
        if ($stmt->execute()) {
            echo "Credits added and logged successfully.";
        } else {
            echo "Error logging credit addition: " . htmlspecialchars($stmt->error);
        }
    } else {
        echo "Error adding credits: " . htmlspecialchars($stmt->error);
    }
}
?>
