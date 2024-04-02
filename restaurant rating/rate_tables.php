<?php
include ("connect.php");

if (isset ($_POST['tableType']) && $_POST["tableType"] === "User_Requests") {
    ?>
    <div class="box-container">
        <?php
        // Display user requests
        $select_requests = $conn->prepare("SELECT u.id, u.event_type, u.event_date, IFNULL(r.rating, 0) AS rating, GROUP_CONCAT(r.comment) AS comments
       FROM `user_requests` u 
       LEFT JOIN `reviews` r ON u.id = r.venue_id 
       GROUP BY u.id 
       ORDER BY u.id DESC");
        $select_requests->execute();

        // Bind variables to prepared statement
        $select_requests->bind_result($request_id, $event_type, $event_date, $rating, $comments);

        // Fetch results
        while ($select_requests->fetch()) {
            ?>
            <div class="box">
                <h3 class="title">Request ID:
                    <?= $request_id; ?>
                </h3>
                <p class="total-items">Type of Event:
                    <?= $event_type; ?>
                </p>
                <p class="total-items">Event Date:
                    <?= $event_date; ?>
                </p>
                <!-- Star Rating Display -->
                <div class="rating">
                    <?php
                    // Display star rating
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $rating) {
                            echo '<i class="fas fa-star checked"></i>'; // Full star
                        } else {
                            echo '<i class="far fa-star"></i>'; // Empty star
                        }
                    }
                    ?>
                </div>
                <!-- End of star rating section -->
                <!-- User Reviews Display -->
                <div class="user-reviews">
                    <?php
                    // Display user reviews if available
                    if (!empty ($comments)) {
                        $user_reviews = explode(",", $comments);
                        foreach ($user_reviews as $review) {
                            echo "<p>$review</p>";
                        }
                    } else {
                        echo "<p>No reviews available.</p>";
                    }
                    ?>
                </div>
                <!-- End of user reviews section -->
                <a href="view_request.php?requests_id=<?= $request_id; ?>" class="inline-btn">View Request</a>
            </div>
            <?php
        }

        // Check if no requests found
        if ($select_requests->num_rows === 0) {
            echo '<p class="empty">No approved user requests found!</p>';
        }
        ?>
    </div>
    <?php
}




if (isset ($_POST["tableType"]) && $_POST["tableType"] === "Products") {
    ?>

    <div class="box-container">
        <?php
        // Display user requests
        $select_requests = $conn_abarzosa->prepare("SELECT * FROM product");

        $select_requests->execute();
        if ($select_requests === false) {
            echo "Error executing the query: " . $conn_abarzosa->error;
        } else {
            $select_requests->execute();
            $result = $select_requests->get_result();

            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            // Fetch all rows as associative array
            if (!empty($rows)) {
                foreach ($rows as $fetch_request) {
                    $product_id = $fetch_request['id'];
                    $product_name = $fetch_request['product_name'];
                    $product_type = $fetch_request['product_type'];
                    $product_img = $fetch_request['image'];
                    $product_price = $fetch_request['price'];
                    $product_quantity = $fetch_request['quantity'];
                    
                    // Calculate average rating for the product
                    $query = "SELECT AVG(rating) AS average_rating FROM reviews WHERE product_id = '$product_id'";
                    $result1 = mysqli_query($conn_abarzosa, $query);
                    $average_rating_row = $result1->fetch_assoc();
                    $average_rating = number_format($average_rating_row['average_rating'], 1); // Format to one decimal place
                    
                    ?>
                    <div class="box">
                        <h3 class="title">Product ID: <?= $product_id; ?></h3>
                        <p class="total-items">Product Name: <?= $product_name; ?></p>
                        <div class="product-image">
                            <img src="images" alt="<?= $product_name; ?>">
                        </div>
                        <p class="total-items">Product Type: <?= $product_type; ?></p>
                        <p class="total-items">Price: <?= $product_price; ?></p>
                        <p class="total-items">Quantity: <?= $product_quantity; ?></p>
                        <div class="average-rating white">
                            <?php echo $average_rating; ?> out of 5
                        </div>
                        <?php
                        // Display stars based on the average rating
                        $stars = '';
                        $rating = round($average_rating); // Round the average rating
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $rating) {
                                $stars .= '<i class="fas fa-star" style="color: #FFD700;"></i>'; // Full star with soft yellow color
                            } else {
                                $stars .= '<i class="far fa-star" style="color: #FFD700;"></i>'; // Empty star with soft yellow color
                            }
                        }
                        echo '<div class="rating">' . $stars . '</div>';
                        ?>
                        <!-- Display "No reviews available" if rating is empty -->
                        <?php
                        if (empty($average_rating_row['average_rating'])) {
                            echo '<p>No reviews available</p>';
                        }
                        ?>
                        <!-- Add more details as needed -->
                        <a href="view_product.php?product_id=<?= $product_id; ?>" class="inline-btn">View Product</a>
                    </div>
                    <?php
                }
            }
            ?>
    <?php
}

if (isset ($_POST["tableType"]) && $_POST["tableType"] === "Bookings") {
    ?>
    <div class="box-container">
        <?php
        // Display bookings
        $select_bookings = $conn->prepare("SELECT b.*, v.name AS venue_name, v.image AS venue_image FROM bookings b INNER JOIN venues v ON b.venue_id = v.id WHERE b.email = ? ORDER BY b.id DESC");
        $select_bookings->bind_param("s", $_SESSION["email"]);
        $select_bookings->execute();
        $result_bookings = $select_bookings->get_result(); // Get result set
        if ($result_bookings->num_rows > 0) {
            while ($fetch_booking = $result_bookings->fetch_assoc()) {
                // Fetch data
                $booking_id = $fetch_booking['id'];
                $venue_name = $fetch_booking['venue_name'];
                $venue_image = $fetch_booking['venue_image'];
                $username = $fetch_booking['username'];
                $name = $fetch_booking['name'];
                $email = $fetch_booking['email'];
                $date = $fetch_booking['date'];
                $guest_count = $fetch_booking['guest_count'];
                $message = $fetch_booking['message'];
                $adminremark = $fetch_booking['adminremark'];
                $bookingStatus = $fetch_booking['bookingStatus'];
                // Assuming the rating is out of 5
                $rating = $fetch_booking['rating']; // Assuming this is where the rating is stored
                ?>
                <div class="box">
                    <h3 class="title">Booking ID:
                        <?= $booking_id; ?>
                    </h3>
                    <p class="total-items">Venue:
                        <?= $venue_name; ?>
                    </p>
                    <img src="../images/<?= $venue_image; ?>" alt="<?= $venue_name; ?>" class="box-img">
                    <p class="total-items">Username:
                        <?= $username; ?>
                    </p>
                    <div class="rating">
                        <?php
                        // Display star rating
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $rating) {
                                echo '<i class="fas fa-star"></i>'; // Full star
                            } else {
                                echo '<i class="far fa-star"></i>'; // Empty star
                            }
                        }
                        ?>
                    </div>
                    <?php
                    // Display "No reviews available" if rating is empty
                    if (empty ($rating)) {
                        echo '<p>No reviews available</p>';
                    }
                    ?>
                    <a href="view_post.php?booking_id=<?= $booking_id; ?>" class="inline-btn">View Booking</a>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">No bookings added yet!</p>';
        }
        ?>
    </div>
    <?php
}
}