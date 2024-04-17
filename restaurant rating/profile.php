<?php
session_start();

include 'connect.php';

function getUserDetails($userId, $conn)
{
    $user = array();

    try {
        $stmt = $conn->prepare("SELECT username, email, pic FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    return $user;
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$user = getUserDetails($_SESSION["id"], $conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Modal</title>
    <style>
        /* CSS styles for the modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <!-- Modal to display profile -->
    <div id="profileModal" class="modal" style="display: block;">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('profileModal').style.display = 'none';">&times;</span>
            <div class="profile-header">
                <div class="profile-picture">
                    <?php
                    // Display profile picture if available, otherwise show default image
                    if (!empty($user) && !empty($user['pic'])):
                        ?>
                        <!-- Display the profile picture -->
                        <img src="images/default.jpg<?php echo $user['pic']; ?>" alt="">
                    <?php else: ?>
                        <!-- Display default profile picture if user has no profile picture -->
                        <img src="images/default.jpg" alt="Default Profile Picture">
                    <?php endif; ?>
                </div>
                <div class="profile-name">
                    <?php echo !empty($user['username']) ? $user['username'] : ''; ?>
                </div>
                <div class="profile-email">
                    <?php echo !empty($user['email']) ? $user['email'] : ''; ?>
                </div>
            </div>
            <!-- Logout link -->
            <div class="logout-link" style="text-align: center; margin-top: 20px;">
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>


</body>

</html>