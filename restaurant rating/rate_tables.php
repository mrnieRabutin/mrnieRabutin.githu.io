<?php
include ("connect.php");



if (isset($_POST['tableType']) && $_POST["tableType"] === "User_Requests") {
    ?>
    <div class="box-container">
        <style>
            .box {
                width: 500px;
                margin-top: 70px;
                padding: 40px;
                border: 1px solid #ddd;
                border-radius: 10px;
                overflow: hidden;
                transition: transform 0.3s ease;
                text-align: center;
            }

            .flexbox-container {
                display: flex;
                justify-content: space-between;
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
                color: #000000;
                font-weight: bold;
                font-size: 18px;
                margin-top: 10px;
                margin-bottom: 5px;
                font-size: 16px;
                line-height: 1.5;
            }

        </style>
        <?php

        $select_requests = $conn->prepare("SELECT u.id, venue, u.event_type, u.event_date, budget, start_time, end_time , guest_count, IFNULL(r.rating, 0) AS rating, GROUP_CONCAT(r.comment) AS comments
       FROM `user_requests` u 
       LEFT JOIN `reviews` r ON u.id = r.venue_id 
       GROUP BY u.id 
       ORDER BY u.id DESC");
        $select_requests->execute();
        $select_requests->bind_result($id, $venue, $event_type, $event_date, $budget, $start_time, $end_time, $guest_count, $rating, $comments);


        // Fetch results
        while ($select_requests->fetch()) {
            ?>
            <div class="box">

                <p class="total-items">
                    <?= $venue; ?>
                </p>
                <p class="total-items">Event:
                    <?= $event_type; ?>
                </p>
                <p class="total-items">Date:
                    <?= $event_date; ?>
                </p>
                <p class="total-items">Budget:
                    <?= $budget; ?>
                </p>
                <p class="total-items">Start Time:
                    <?= $start_time; ?>
                </p>
                <p class="total-items">End Time:
                    <?= $end_time; ?>
                </p>
                <p class="total-items">Guest Count:
                    <?= $guest_count; ?>
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
                    if (!empty($comments)) {
                        $user_reviews = explode(",", $comments);
                        foreach ($user_reviews as $review) {
                            echo "<p>$review</p>";
                        }
                    } else {
                        echo "<p>0 Review</p>";
                    }
                    ?>
                </div>
                <!-- End of user reviews section -->
                <a href="view_request.php?requests_id=<?= $id; ?>" class="inline-btn">Rate Request</a>
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




if (isset($_POST["tableType"]) && $_POST["tableType"] === "Products") {
    ?>

    <div class="box-container">
        <style>
            .box {
                width: 500px;
                margin-top: 70px;
                padding: 40px;
                border: 1px solid #ddd;
                border-radius: 10px;
                overflow: hidden;
                transition: transform 0.3s ease;
                text-align: center;
            }

            .flexbox-container {
                display: flex;
                justify-content: space-between;
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
                margin-top: 10px;
                margin-bottom: 5px;
                font-size: 16px;
                line-height: 1.5;
            }
        </style>
        <?php
        $select_requests = $conn_abarzosa->prepare("SELECT * FROM product");

        $select_requests->execute();
        if ($select_requests === false) {
            echo "Error executing the query: " . $conn->error;
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
                    $description = $fetch_request['description'];
                    $product_price = $fetch_request['price'];
                    $product_quantity = $fetch_request['quantity'];


                    $query = "SELECT AVG(rating) AS average_rating FROM reviews WHERE product_id = '$product_id'";
                    $result1 = mysqli_query($conn_abarzosa, $query);
                    $average_rating_row = $result1->fetch_assoc();
                    $average_rating = number_format($average_rating_row['average_rating'], 1); // Format to one decimal place
    
                    ?>
                    <div class="box">
                        <div class="product-image">
                            <img src="images/<?= $product_img; ?>" alt="<?= $product_name; ?>">
                        </div>
                        <p class="total-items">
                            <?= $product_name; ?>
                        </p>
                        <p class="total-items">Product Type:
                            <?= $product_type; ?>
                        </p>
                        <p class="total-items">Description:
                            <?= $description; ?>
                        </p>
                        <p class="total-items">Price:
                            <?= $product_price; ?>
                        </p>
                        <p class="total-items">Quantity:
                            <?= $product_quantity; ?>
                        </p>
                        <div class="average-rating white">
                            <?php echo $average_rating; ?> out of 5
                        </div>
                        <?php
                        // Display stars based on the average rating
                        $stars = '';
                        $rating = round($average_rating); // Round the average rating
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= $rating) {
                                $stars .= '<i class="fas fa-star" style="color: #FFD700;"></i>';
                            } else {
                                $stars .= '<i class="far fa-star" style="color: #FFD700;"></i>';
                            }
                        }
                        echo '<div class="rating">' . $stars . '</div>';
                        ?>

                        <a href="view_product.php?product_id=<?= $product_id; ?>" class="inline-btn">Rate Product</a>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
        }

}



if (isset($_POST["tableType"]) && $_POST["tableType"] === "Bookings") {
    ?>
    <div class="box-container">
        <style>
            .box {
                width: 500px;
                margin-top: 70px;
                padding: 40px;
                border: 1px solid #ddd;
                border-radius: 10px;
                overflow: hidden;
                transition: transform 0.3s ease;
                text-align: center;
            }

            .flexbox-container {
                display: flex;
                justify-content: space-between;
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
                margin-top: 10px;
                margin-bottom: 5px;
                font-size: 16px;
                line-height: 1.5;
            }
        </style>
        <?php
        // Display bookings
        $sql_bookings = $conn->prepare("SELECT *
        FROM `bookings` ");
        $sql_bookings->execute(); // Execute the query
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
                    <h3 class="total-items">
                        <?= $venue_name; ?>
                    </h3>
                    <h3 class="total-items">Description:
                        <?= $description; ?>
                    </h3>
                    <h3 class="total-items">Status:
                        <?= $bookingStatus; ?>
                    </h3>
                    <div class="average-rating white">
                        <?= $average_rating; ?> out of 5
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

                    <a href="view_post.php?booking_id=<?= $booking_id; ?>" class="inline-btn">Rate Booking</a>
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