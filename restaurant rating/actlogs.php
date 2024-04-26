<?php
// Include database connection
include ('connect.php');

// Function to delete all login logs
function deleteAllLogs($conn)
{
    $sql = "DELETE FROM logs";
    if ($conn->query($sql) !== false) { 
        echo "All login logs deleted successfully";
    } else {
        echo "Error deleting login logs: " . $conn->errorInfo()[2];
    }
}

// Check if the delete button is clicked
if (isset($_POST['delete_logs'])) {
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <style>
    
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1B1212;
        }
        .logo {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }

        .logo img {
            width: 50px;
            height: auto;
        }

        .header {
            background: linear-gradient(70deg, #cc1b1b, #c1853b);
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }

        .profile-container {
            display: flex;
            align-items: center;
        }

        .profile-icon,
        .logout-icon {
            color: #000;
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
            background-color: #000000;
            color: #fff;
            padding: 15px;
        }

        .sidebar-wrapper ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-wrapper ul li {
            margin-bottom: 10px;
        }

        .sidebar-wrapper ul li a {
            display: block;
            padding: 10px;
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
            margin-top: 50px auto;
            width: 150vh;
            margin-left: 10%;

        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 12px;
            margin-left: 50px;
        }

        th {
            background-color: #ffffff;
            font-size: 16px;
        }

        td {
            font-size: 14px;
            color:white;
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

        button[name="delete_logs"]:hover {
            background-color: #ff4444;
            /* Lighter shade of red */
        }

        a {
            color: white;
            background-color: transparent;
        }
    </style>

</head>

<body>

    <!-- Header section -->
    <div class="header">
        <h1>RateMeister</h1>
        <div>
            <a href="profile.php" class="profile-icon"><i class="fas fa-user-circle"></i></a>
            <a href="logout.php" class="logout-icon"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>

    <div class="sidebar-wrapper">
        <h2>Activity Logs</h2>
        <ul>
        <li><a href="all_posts.php"><i class="fas fa-home"></i>Home</a></li>
        <li><a href="actlogs.php"><i class="fas fa-history"></i>Activity Logs</a></li>
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
                    <th>Email</th>
                    <th>Activity</th>

                </tr>
            </thead>
            <tbody>
                <?php
                if (count($rows) > 0) {
                    foreach ($rows as $row) {
                        echo "<tr>";
                        echo "<td>" . $row["timestamp"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
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