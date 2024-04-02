<?php
// Include database connection
include ('connect.php');

// Function to delete all login logs
function deleteAllLogs($conn)
{
    $sql = "DELETE FROM logs";
    if ($conn->query($sql) !== false) { // Changed exec() to query()
        echo "All login logs deleted successfully";
    } else {
        echo "Error deleting login logs: " . $conn->errorInfo()[2];
    }
}

// Check if the delete button is clicked
if (isset ($_POST['delete_logs'])) {
    deleteAllLogs($conn);
}

session_start();

// Fetch login logs for the current user
$sql = "SELECT logs.timestamp, CASE WHEN logs.activity = 'Login' THEN 'logged in' ELSE logs.activity END AS activity, users.email 
        FROM logs 
        INNER JOIN users ON logs.username = users.email 
        WHERE logs.username = ? 
        ORDER BY logs.timestamp DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION["email"]);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are rows returned
if ($result) {
    $rows = $result->fetch_all(MYSQLI_ASSOC); // Fetch all rows as associative array
} else {
    $rows = []; // Set empty array if no rows returned
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }

        .header {
            background-color: #152238;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .profile-container {
            display: flex;
            align-items: center;
        }

        .profile-icon,
        .logout-icon {
            color: #fff;
            text-decoration: none;
            font-size: 20px;
            margin-right: 10px;
        }

        .logout-icon:hover,
        .profile-icon:hover {
            cursor: pointer;
        }

        .title {
            font-size: 20px;
            margin: 15px 0;
            color: #333;
        }

        .total-reviews {
            font-size: 16px;
            color: #777;
        }

        .inline-btn {
            display: block;
            width: 100%;
            padding: 10px 0;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            text-align: center;
            border-radius: 0 0 8px 8px;
            transition: background-color 0.3s;
        }

        .inline-btn:hover {
            background-color: #0056b3;
        }

        .sidebar-wrapper {
            float: left;
            width: 200px;
            height: 100vh;
            background-color: #152238;
            color: #fff;
            padding: 20px;
        }

        .sidebar ul {
            list-style-type: none;
            /* Remove bullets */
            padding: 0;
            margin: 0;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
        }

        .sidebar-button {
            display: block;
            width: 100%;
            padding: 15px 0;
            margin-top: 20px;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        .content-wrapper {
            margin-left: 220px;
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            margin: 50px auto;
            /* Added margin */
            width: 150vh;

        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
        }

        th {
            background-color: #f2f2f2;
            font-size: 16px;
        }

        td {
            font-size: 14px;
        }

        .empty {
            text-align: center;
            color: red;
            /* Change color to red */
            font-weight: bold;
            margin-top: 20px;
        }

        form {
            /* Adjust position of the form */
            /* Example: center the form horizontally */
            text-align: center;
        }

        button[name="delete_logs"] {
            /* Adjust size of the button */
            width: 150px;
            height: 40px;

            /* Align to the right */
            float: right;
            margin-top: 20px;

            /* Background color */
            background-color: red;
            /* Add some padding */
            padding: 10px 20px;
            /* Add rounded corners */
            border-radius: 5px;
            /* Add box shadow for depth */
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
            /* Remove default button border */
            border: none;
            /* Add text color */
            color: white;
            /* Add hover effect */
            transition: background-color 0.3s ease;
        }

        /* Hover effect */
        button[name="delete_logs"]:hover {
            background-color: #ff4444;
            /* Lighter shade of red */
        }
    </style>

</head>

<body>

    <!-- Header section -->
    <div class="header">
        <h1>Restaurant Review System</h1>
        <div>
            <a href="profile.php" class="profile-icon"><i class="fas fa-user-circle"></i></a>
            <a href="logout.php" class="logout-icon"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>

    <div class="sidebar-wrapper">
        <h2>Activity Logs</h2>
        <ul>
            <a href="all_posts.php" class="sidebar-button">Home</a>
            <a href="actlogs.php" class="sidebar-button">Activity Logs</a>
        </ul>
    </div>

    <div class="container">
        <form method="post">
            <button type="submit" name="delete_logs" class="button is-danger">Delete All Logs</button>
        </form>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User ID</th>
                    <th>Activity</th>

                </tr>
            </thead>
            <tbody>
                <?php
                if (count($rows) > 0) {
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . $row["timestamp"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>"; // Displaying email from users table
                        echo "<td>" . $row["activity"] . "</td>";

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='empty' style='color: red;'>No login logs found</td></tr>";
                    // Apply 'empty' class here
                }
                ?>
            </tbody>
        </table>
    </div>


</body>

</html>