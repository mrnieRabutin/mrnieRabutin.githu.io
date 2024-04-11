<?php
session_start();
include 'connect.php';

$username = $comment = $rating = "";
$comment_err = $rating_err = "";

// Check if the booking_id is passed in the URL
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Fetch booking details from the database
    $select_booking = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
    $select_booking->bind_param("i", $booking_id);
    $select_booking->execute();
    // Fetch the results
    $booking_details = $select_booking->get_result()->fetch_assoc();

    // Retrieve venue_id from booking details
    $venue_id = $booking_details['venue_id'];
} else {
    header("Location: all_posts.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate comment
    if (empty(trim($_POST["comment"]))) {
        $comment_err = "Please enter your comment.";
    } else {
        $comment = trim($_POST["comment"]);
    }

    // Validate rating
    if (empty(trim($_POST["rating"]))) {
        $rating_err = "Please select a rating.";
    } else {
        $rating = trim($_POST["rating"]);
    }

    if (empty($comment_err) && empty($rating_err)) {
        $username = isset($_SESSION['email']) ? $_SESSION['email'] : '';

        $insert_review = $conn->prepare("INSERT INTO reviews (booking_id, venue_id, username, comment, rating, date) VALUES (?,?,?,?,?,CURRENT_TIMESTAMP())");
        $insert_review->bind_param("iiiss", $booking_id, $venue_id, $username, $comment, $rating);
        $insert_review->execute();

        // Redirect to all_posts.php after successful review submission
        header("Location: all_posts.php");
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review for Booking ID <?= $booking_id; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Add your CSS stylesheets here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('images/review.jpg');
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

        .container {
            margin: 20px auto;
            max-width: 600px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        button[type="submit"],
        a {
            background-color: orange;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }

        button[type="submit"]:hover,
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="header">
        <!-- Your existing header content -->
    </div>

    <div class="container">
        <h2> Feedback</h2>
        <form method="post"
            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?booking_id=' . $booking_id; ?>">
            <div>
                <label for="comment">Comments</label>
                <textarea id="comment" name="comment" rows="4" required></textarea>
                <span class="help-block"><?php echo $comment_err; ?></span>
            </div>
            <div>
                <label for="rating">Rating:</label>
                <select id="rating" name="rating" required>
                    <option value="">Select Rating</option>
                    <option value="1">1 Star</option>
                    <option value="2">2 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="5">5 Stars</option>
                </select>
                <span class="help-block"><?php echo $rating_err; ?></span>
            </div>
            <button type="submit">Submit</button>
            <a href="view_post.php">Go Back</a>
        </form>
    </div>

</body>

</html>