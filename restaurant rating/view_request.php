<?php
include 'connect.php';

if (isset($_GET['requests_id'])) {
    $requests_id = $_GET['requests_id'];

    $select_requests = $conn->prepare("SELECT id, event_type, venue, budget, ambiance, special_requests FROM `user_requests` WHERE id = ?");
    $select_requests->bind_param("i", $requests_id); // Assuming $requests_id is an integer
    $select_requests->execute();
    $result_requests = $select_requests->get_result(); // Get the result set
    $requests = $result_requests->fetch_assoc(); // Fetch the data as an associative array

    $select_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE requests_id = ?");
    $select_reviews->bind_param("i", $requests_id); // Bind parameter
    $select_reviews->execute();
    $result_reviews = $select_reviews->get_result(); // Get the result set

    // Fetch reviews as an associative array
    $reviews = [];
    while ($row = $result_reviews->fetch_assoc()) {
        $reviews[] = $row;
    }

    $total_reviews = count($reviews);
    $total_rating = 0;
    foreach ($reviews as $review) {
        $total_rating += $review['rating'];
    }
    $average_rating = ($total_reviews > 0) ? round($total_rating / $total_reviews, 1) : 0;

    if (isset($_POST['delete_review'])) {
        $review_id = $_POST['delete_review'];
        $delete_review = $conn->prepare("DELETE FROM `reviews` WHERE id = ?");
        $delete_review->bind_param("i", $review_id); // Bind parameter
        $delete_review->execute();
        header("Location: {$_SERVER['PHP_SELF']}?requests_id=$requests_id");
        exit();
    }

    // Fetch user details
    $stmt_user = $conn->prepare("SELECT username, pic FROM users WHERE id = ?");
    $stmt_user->bind_param("i", $requests['id']); // Assuming user_id is used here
    $stmt_user->execute();
    $result_user = $stmt_user->get_result(); // Get the result set
    $userDetails = $result_user->fetch_assoc(); // Fetch the data as an associative array
} else {
    header("Location: add_review.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS and other meta tags -->
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
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
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .all-bookings {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: flex-start;
            padding: 0px;
            margin-left: 220px;
        }

        .box-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: center;
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
            display: block;
            margin: 0 auto;
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

        .sidebar-button {
            display: block;
            width: 100px;
            padding: 20px;
            margin-top: 20px;
            color: #fff;
            border: none;
            border-radius: 5px;
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
            margin-left: 240px;
            padding: 20px;
        }

        /* CSS styles */
        .rating {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fa {
            font-size: 24px;
            color: #FFD700;
        }

        .checked {
            color: #FFD700;
        }

        h3 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }

        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>

<body>

    <!-- Header section -->
    <div class="header">
        <h1>Users Requests</h1>

        <div>
            <a href="profile.php" class="profile-icon"><i class="fas fa-user-circle"></i></a>
            <a href="logout.php" class="logout-icon"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>

    <div class="sidebar-wrapper">
        <h2>Dashboard</h2>
        <ul>
            <a href="all_posts.php" class="sidebar-button">Home</a>
            <a href="actlogs.php" class="sidebar-button">Activity Logs</a>
        </ul>
    </div>

    <!-- Reviews section -->
    <section class="user_requests">
        <div class="container">
            <div class="request-review">
                <!-- Font Awesome Library -->
                <link rel="stylesheet"
                    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                <a href="request_review.php?requests_id=<?= $requests_id; ?>" class="inline-btn"><i
                        class="fas fa-plus"></i> Add Review</a>
            </div>
            <h2>User's Reviews</h2>
            <?php if (!empty($reviews)): ?>
                <ul>
                    <?php foreach ($reviews as $review): ?>
                        <h2>
                            <?= $review['username']; ?>
                        </h2>
                        <li>

                            <?php if (!empty($userDetails) && !empty($userDetails['pic'])): ?>
                                <img src="<?= $userDetails['pic']; ?>" alt="Profile Picture" width="50">
                            <?php endif; ?>
                            <form method="post">
                                <input type="hidden" name="delete_review" value="<?= $review['id']; ?>">
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                            <p>
                                <?= $review['comment']; ?>
                            </p>
                            <p>Rating:
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <?php if ($i <= $review['rating']): ?>
                                        <i class="fas fa-star checked"></i>
                                    <?php else: ?>
                                        <i class="fas fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </p>
                            <p>Date:
                                <?= $review['date']; ?>
                            </p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No reviews added yet!</p>
            <?php endif; ?>
        </div>
    </section>

</body>

</html>