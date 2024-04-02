<?php
session_start();
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Bookings and User Requests</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://startbootstrap.com/template/simple-sidebar">


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
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 10;
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

        .all-items {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: flex-start;
            padding: 0px;
            position: relative;
            top: 76px;
            left: 200px;
        }

        .box {
            width: 300px;
            /* Adjust the width as needed */
            margin-top: 70px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 20px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }


        .box:hover {
            transform: translateY(-5px);
        }

        .box img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .title {
            font-size: 20px;
            margin: 15px 0;
            color: #333;
        }

        .total-items {
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
            position: fixed;
            top: 76px;
            left: 0;
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

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .box .rating {
            color: #ffd700;
            /* Change star color to yellow */
        }

        .empty {
            text-align: center;
            color: #FF0000;
            /* Red color */
            font-weight: bold;
            margin-top: 20px;
        }

        /* Modal styles */
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
            width: 400px;
            height: 300px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* CSS styles for the logout button */
        .logout-btn {
            display: block;
            margin-top: 20px;
            background-color: #c0392b;
            /* Red color */
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            padding: 10px 20px;
            /* Adjust padding as needed */
            font-size: 16px;
            width: 100px;
        }

        .logout-btn:hover {
            background-color: #d35400;
            /* Darker red on hover */
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Restaurant Review System</h1>
        <div>
            <a href="#" id="openProfileModal" class="profile-icon"><i class="fas fa-user-circle"></i></a>
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

    <section class="all-items">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" id="Bookings" href="#"
                    onclick="openTable('Bookings', 'Bookings');">Bookings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="User_Requests" href="#"
                    onclick="openTable('User_Requests', 'User_Requests');">User Requests</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="Products" href="#" onclick="openTable('Products', 'Products');">Products</a>
            </li>
            <div class="tables">
    <?php
    $select_bookings = $conn->prepare("SELECT DISTINCT b.*, v.name AS venue_name, v.image AS venue_image, r.rating AS user_rating FROM bookings b INNER JOIN venues v ON b.venue_id = v.id LEFT JOIN reviews r ON b.id = r.booking_id WHERE b.email = ? ORDER BY b.id DESC");
    $select_bookings->bind_param("s", $_SESSION["email"]);
    $select_bookings->execute();
    $result_bookings = $select_bookings->get_result(); // Get result set
    if ($result_bookings->num_rows > 0) {
        while ($fetch_booking = $result_bookings->fetch_assoc()) { // Fetch data
            $booking_id = $fetch_booking['id'];
            $venue_name = $fetch_booking['venue_name'];
            $venue_image = "images/" . $fetch_booking['venue_image'];
            $username = $fetch_booking['username'];
            $user_rating = $fetch_booking['user_rating']; // Rating of the user
            
            ?>
            <div class="box">
                <h3 class="title">Booking ID:
                    <?= $booking_id; ?>
                </h3>
                <p class="total-items">Venue:
                    <?= $venue_name; ?>
                </p>
                <img src="images/Italian_R.jpg" alt="<?= htmlspecialchars($venue_name); ?>" class="box-img">
                <p class="total-items">Username:
                    <?= $username; ?>
                </p>
                <div class="rating">
                    <?php
                    // Display rating stars
                    if (!empty($user_rating)) {
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $user_rating) {
                                echo '<i class="fas fa-star"></i>'; // Full star
                            } else {
                                echo '<i class="far fa-star"></i>'; // Empty star
                            }
                        }
                        // Calculate the percentage of users who left a review
                        $total_bookings = $result_bookings->num_rows;
                        $percentage_reviews = ($total_bookings > 0) ? ($total_bookings / 100) : 0;
                        echo "<div class='percentage-reviews'>" . number_format($percentage_reviews, 1) . "% - " . number_format($percentage_reviews * 2, 1) . "%</div>";
                    } else {
                        echo "No reviews available";
                    }
                    ?>
                </div>
                <a href="view_post.php?booking_id=<?= $booking_id; ?>" class="inline-btn">View Booking</a>
            </div>
            <?php
        }
    } else {
        echo '<p class="empty">No bookings added yet!</p>';
    }
    ?>
</div>
    </section>
    <!-- Profile modal -->
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <!-- Profile content -->
            <div class="profile-info">
                <div class="profile-picture">
                    <!-- Display user's profile picture -->
                    <?php
                    // Check if $_SESSION["pic"] is set and not empty
                    if (isset ($_SESSION["pic"]) && !empty ($_SESSION["pic"])) {
                        // If it's set and not empty, use it to display the profile picture
                        ?>
                        <!-- Display user's profile picture -->
                        <img src="../images/<?= $_SESSION["pic"]; ?>" alt="Profile Picture">
                        <?php
                    } else {
                        // If $_SESSION["pic"] is not set or empty, display a placeholder image or a message
                        ?>
                        <div style="display: flex; justify-content: center; align-items: center;">
                            <img src="images/bg.png" alt="" style="width: 100px; height: 100px; border-radius: 50%;">
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="profile-details">
                    <p>
                        <?= $_SESSION["username"]; ?>
                    </p>
                    <p>
                        <?= $_SESSION["email"]; ?>
                    </p>
                </div>
            </div>
            <!-- Logout button -->
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        // Get the profile modal
        var profileModal = document.getElementById("profileModal");

        // Get the profile icon button
        var profileBtn = document.getElementById("openProfileModal");

        // Get the <span> element that closes the profile modal
        var closeBtn = profileModal.getElementsByClassName("close")[0];

        // When the user clicks the profile icon button, open the profile modal 
        profileBtn.onclick = function () {
            profileModal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the profile modal
        closeBtn.onclick = function () {
            profileModal.style.display = "none";
        }

        // When the user clicks anywhere outside of the profile modal, close it
        window.onclick = function (event) {
            if (event.target == profileModal) {
                profileModal.style.display = "none";
            }
        }
    </script>


    <script>
        function openTable(tabId, tabLinkId) {
            var tableType = tabId;

            var tabs = document.querySelectorAll('.nav-link');
            tabs.forEach(function (tab) {
                tab.classList.remove('active');
            });

            // Add "active" class to the clicked tab
            var activeTab = document.getElementById(tabLinkId);
            activeTab.classList.add('active');

            $.ajax({
                type: 'POST',
                url: 'rate_tables.php', // Create a separate PHP file for processing job upload
                data: { tableType: tableType },

                success: function (response) {
                    // Handle success, update the UI or close the modal if needed
                    $('.tables').html(response);
                },
                error: function (error) {
                    // Handle error, show an alert or update the UI
                    console.error('Ajax Error:', error);
                }
            });
        }
    </script>