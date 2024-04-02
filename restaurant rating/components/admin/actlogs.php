<?php
include 'components/connect.php';

// Function to insert activity logs
function insertActivityLog($admin_id, $activity) {
    global $conn;
    $sql = "INSERT INTO logs (admin_id, activity) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$admin_id, $activity]);
}

// Function to fetch activity logs
function getActivityLogs() {
    global $conn;
    $sql = "SELECT * FROM logs ORDER BY timestamp DESC"; // Assuming 'timestamp' is the column name for timestamp
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

      .container {
          max-width: calc(100% - 220px); /* Subtract sidebar width plus some space for margin */
          padding: 20px; /* Add padding for content */
          margin-left: 220px; /* Width of sidebar plus some space for margin */
      }

      .all-restaurants {
          display: flex;
          flex-wrap: wrap;
          justify-content: flex-start;
          align-items: flex-start;
          padding: 70px 20px;
      }

      .box-container {
          display: flex;
          flex-wrap: wrap;
          justify-content: flex-start;
          align-items: flex-start;
      }

      .box {
          width: calc(33.33% - 20px);
          margin-right: 20px;
          margin-bottom: 20px;
          border: 1px solid #ccc;
          border-radius: 5px;
          padding: 10px;
          box-sizing: border-box;
      }

      .heading {
          text-align: center;
          margin-bottom: 20px;
      }

      .box img {
          width: 100%;
          height: auto;
          border-radius: 5px;
          margin-bottom: 10px;
      }

      .title {
          font-size: 18px;
          margin-bottom: 5px;
      }

      .total-reviews {
          font-size: 14px;
          margin-bottom: 10px;
      }

      .inline-btn {
          display: inline-block;
          padding: 5px 10px;
          background-color: #007bff;
          color: #fff;
          text-decoration: none;
          border-radius: 5px;
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

      .sidebar ul li a {
          color: #fff;
          text-decoration: none;
      }

      .content-wrapper {
          margin-left: 220px; /* Width of sidebar plus some space for margin */
          padding: 20px;
      }

      table {
        border-collapse: collapse;
        width: 90%;
        margin: 0 auto;
    }

    th, td {
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
    </style>
</head>
<body>
   
<!-- Header section -->
<div class="header">
   <h1>Restaurant Rating</h1>
   <div>
      <a href="profile.php" class="profile-icon"><i class="fas fa-user-circle"></i></a>
      <a href="logout.php" class="logout-icon"><i class="fas fa-sign-out-alt"></i></a>
   </div>
</div>

<div class="sidebar-wrapper">
    <h2>Activity Logs</h2>
    <ul>
        <li><a href="all_posts.php">Home</a></li>
        <li><a href="accounts.php">Accounts</a></li>
        <li><a href="actlogs.php">Activity Logs</a></li>
    </ul>
</div>

<div class="content-wrapper">
    <?php
    // Fetch activity logs
    $logs = getActivityLogs();
    ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Admin ID</th>
                <th>Activity</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
            <tr>
                <td><?php echo $log['id']; ?></td>
                <td><?php echo $log['admin_id']; ?></td>
                <td><?php echo $log['activity']; ?></td>
                <td><?php echo $log['timestamp']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
