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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        body {
            font-family: Arial, sans-serif;
            background-color: #1B1212;
            margin: 0;
            padding: 0;
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

        .header h1 {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }


        .header {
            background: linear-gradient(70deg, #cc1b1b, #c1853b);
            color: whitesmoke;
            padding: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 10;
        }

        .profile-icon,
        .logout-icon {
            color: whitesmoke;
            text-decoration: none;
            font-size: 20px;
            margin-right: 10px;
        }

        .logout-icon:hover,
        .profile-icon:hover {
            cursor: pointer;
        }

        .sidebar-wrapper {
            float: left;
            width: 200px;
            height: 100vh;
            background-color: #000000;
            color: #fff;
            padding: 80px 60px;
            position: fixed;
            margin-top: 5%;
            left: 0;
            z-index: 9;
        }

        .sidebar-wrapper h2 {
            margin-bottom: 20px;
            font-size: 18px;
        }

        .sidebar-wrapper ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-wrapper ul li {
            margin-bottom: 30px;
        }

        .sidebar-button {
            display: block;
            padding: 20px;
            background-color: transparent;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .sidebar-button:hover {
            background-color: #45a049;
        }

        .content {
            margin-left: 250px;
            padding-top: 80px;
        }

        .all-items {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            padding: 30px;
            margin-top: 60px;
            font-size: 18px;

        }

        .venue-name {
            color: #000000;
            font-weight: bold;
            font-size: 18px;
        }


        .box {
            width: 400px;
            margin-right: 20px;
            margin-bottom: 50px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease;
            text-align: center;
            background-color: whitesmoke;

        }

        .box img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .box-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .title {
            font-size: 20px;
            margin: 15px 0;
            color: #333;
        }

        .total-items {
            margin-top: 10px;
            margin-bottom: 5px;
            font-size: 16px;
            line-height: 1.5;
        }

        .inline-btn {
            display: block;
            width: 90px;
            padding: 5px;
            background: linear-gradient(70deg, #cc1b1b, #c1853b);
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            transition: background-color 0.3s;
            font-size: 13px;
        }

        .inline-btn:hover {
            background-color: #ffffff;
        }

        a {
            color: white;
            background-color: transparent;
        }

        .rating {
            color: #ffd700;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>
            <i class="fas fa-crown"></i>
            RateMeister
            <a href="https://www.flaticon.com/free-icons/headhunter"></a>
        </h1>
        <div>
            <a href="profile.php" class="profile-icon"><i class="fas fa-user-circle"></i></a>
            <a href="logout.php" class="logout-icon"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>



    <div class="sidebar-wrapper">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="all_posts.php"><i class="fas fa-home"></i>Home</a></li>
            </li><a href="actlogs.php"><i class="fas fa-history"></i>Activity Logs</a></li>
        </ul>
    </div>

    <div class="content">
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
            </ul>
            <div class="tables">
                <?php
                $sql_bookings = $conn->prepare("SELECT * FROM `bookings` ");
                $sql_bookings->execute();
                $result = $sql_bookings->get_result();
                if ($result->num_rows > 0) {
                    while ($fetch_booking = $result->fetch_assoc()) {
                        $booking_id = $fetch_booking['id'];
                        $venue_name = $fetch_booking['venue_name'];
                        $venue_image = $fetch_booking['venue_image'];
                        $description = $fetch_booking['description'];
                        $bookingStatus = $fetch_booking['bookingStatus'];

                        $query = "SELECT AVG(rating) AS average_rating FROM reviews WHERE booking_id = '$booking_id'";
                        $result1 = mysqli_query($conn, $query);
                        $average_rating_row = $result1->fetch_assoc();
                        $average_rating = number_format($average_rating_row['average_rating'], 1);

                        ?>
                        <div class="box">
                            <img src="venue/<?= $venue_image; ?>" alt="<?= $venue_name; ?>">
                            <div class="box-content">
                                <h3 class="total-items venue-name">
                                    <?= $venue_name; ?>
                                </h3>
                                <h3 class="total-items description">
                                    Description: <?= $description; ?>
                                </h3>
                                <h3 class="total-items status">
                                    Status: <?= $bookingStatus; ?>
                                </h3>
                                <div class="average-rating white">
                                    <?php echo $average_rating; ?> out of 5
                                </div>
                                <?php
                                $stars = '';
                                $rating = round($average_rating);
                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $rating) {
                                        $stars .= '<i class="fas fa-star" style="color: #FFD700;"></i>';
                                    } else {
                                        $stars .= '<i class="far fa-star" style="color: #FFD700;"></i>';
                                    }
                                }
                                echo '<div class="rating">' . $stars . '</div>';
                                ?>

                            </div>
                            <a href="view_post.php?booking_id=<?= $booking_id; ?>" class="inline-btn">Rate Booking</a>
                        </div>
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
                    if (isset($_SESSION["pic"]) && !empty($_SESSION["pic"])) {
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
        // JavaScript for sidebar buttons
        document.addEventListener('DOMContentLoaded', function () {
            // Get all sidebar buttons
            var sidebarButtons = document.querySelectorAll('.sidebar-button');

            // Add click event listener to each sidebar button
            sidebarButtons.forEach(function (button) {
                button.addEventListener('click', function (event) {
                    // Prevent default behavior of anchor tag
                    event.preventDefault();

                    // Get the href attribute of the clicked button
                    var href = button.getAttribute('href');

                    // Redirect to the specified page
                    window.location.href = href;
                });
            });
        });
    </script>



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