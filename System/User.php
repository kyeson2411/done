<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getUserInfo($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addCredits($username, $credits) {
        $minutes = $credits * 6;
        $userInfo = $this->getUserInfo($username);
        $current_expires_at = strtotime($userInfo['expires_at']);

        if ($current_expires_at <= time()) {
            $new_expires_at = time() + ($minutes * 60);
        } else {
            $new_expires_at = $current_expires_at + ($minutes * 60);
        }

        $new_expires_at_formatted = date('Y-m-d H:i:s', $new_expires_at);

        $stmt = $this->conn->prepare("UPDATE users SET credits = credits + ?, expires_at = ? WHERE username = ?");
        $stmt->bind_param("iss", $credits, $new_expires_at_formatted, $username);
        return $stmt->execute();
    }

    public function deductCredits($username, $amount) {
        $this->conn->begin_transaction();

        try {
            $stmt = $this->conn->prepare("SELECT credits FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (!$user || $user['credits'] < $amount) {
                throw new Exception("Not enough credits");
            }

            $newCredits = $user['credits'] - $amount;
            $stmt = $this->conn->prepare("UPDATE users SET credits = ? WHERE username = ?");
            $stmt->bind_param("ds", $newCredits, $username);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}
?>
