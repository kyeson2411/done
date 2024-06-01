<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

$username = $_SESSION['username'];
$is_admin = $_SESSION['is_admin'];

$userInfo = $user->getUserInfo($username);

if (!$is_admin) {
    $expires_at = strtotime($userInfo['expires_at']);
    if ($expires_at <= time()) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// Fetch all food items
$foodItems = $conn->query("SELECT id, name, price FROM food_items")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Account</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            function updateTime() {
                var expirationTime = <?php echo $expires_at * 1000; ?>;
                var currentTime = new Date().getTime();
                var timeRemaining = expirationTime - currentTime;

                if (timeRemaining > 0) {
                    var hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

                    $('#timeRemaining').text(hours + "h " + minutes + "m " + seconds + "s ");

                    setTimeout(updateTime, 1000);
                } else {
                    alert("Time has expired!");
                    window.location.href = 'logout.php';
                }
            }

            <?php if (!$is_admin): ?>
            updateTime();
            <?php endif; ?>

            $('#viewAccountDetails').on('click', function() {
                $('#accountDetails').toggle();
            });

            $('#orderFood').on('click', function() {
                $('#orderFoodForm').toggle();
            });

            $('#foodOrderForm').on('submit', function(e) {
                e.preventDefault();
                var foodItemId = $('#foodItemId').val();

                $.ajax({
                    url: 'order_food.php',
                    type: 'POST',
                    data: { foodItemId: foodItemId },
                    success: function(response) {
                        $('#orderResult').html(response);
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">User Account</h2>
        <button id="viewAccountDetails" class="btn btn-primary mb-3">View Account Details</button>
        <button id="orderFood" class="btn btn-success mb-3">Order Food</button>

        <div id="accountDetails" style="display: none;">
            <h4>Account Details</h4>
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
                    <?php
                    $remaining_time = $expires_at - time();
                    $hours = floor($remaining_time / 3600);
                    $minutes = floor(($remaining_time % 3600) / 60);
                    $seconds = $remaining_time % 60;
                    ?>
                    <tr>
                        <td><?php echo $username; ?></td>
                        <td><?php echo $userInfo['credits']; ?></td>
                        <td><?php echo $userInfo['expires_at']; ?></td>
                        <td><span id="timeRemaining"><?php echo "{$hours}h {$minutes}m {$seconds}s"; ?></span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="orderFoodForm" style="display: none;">
            <h4>Order Food</h4>
            <form id="foodOrderForm" method="POST">
                <div class="form-group">
                    <label for="foodItemId">Select Food Item:</label>
                    <select id="foodItemId" name="foodItemId" class="form-control" required>
                        <?php foreach ($foodItems as $item): ?>
                            <option value="<?php echo $item['id']; ?>"><?php echo $item['name'] . " - " . $item['price'] . " credits"; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Order</button>
            </form>
            <div id="orderResult" class="mt-3"></div>
        </div>

        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
