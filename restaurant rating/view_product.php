<?php
session_start();
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

include 'connect.php';

function getProductDetails($productId)
{
    global $conn;
    $stmt = $conn->prepare("SELECT product_name, product_type ,description, price ,image FROM product WHERE id = ?");
    $stmt->execute([$productId]);
    return $stmt->get_result()->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'], $_POST['rating'], $_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $comment = $_POST['comment'];
    $rating = $_POST['rating'];
    $username = $email;
    $date = date("Y-m-d H:i:s");

    // Insert review into database
    $insert_review = $conn->prepare("INSERT INTO `reviews` (product_id, username, comment, rating, date) VALUES (?, ?, ?, ?, ?)");
    $insert_review->bind_param("issis", $product_id, $username, $comment, $rating, $date);
    $insert_review->execute();

    // Redirect to same page after form submission to prevent form resubmission
    header("Location: {$_SERVER['PHP_SELF']}?product_id=$product_id");
    exit();
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch product details
    $productDetails = getProductDetails($product_id);

    // Fetch reviews for the product
    $select_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE product_id = ?");
    $select_reviews->bind_param("i", $product_id);
    $select_reviews->execute();
    $reviews_result = $select_reviews->get_result();

    // Fetch all rows as an associative array
    $reviews = [];
    while ($row = $reviews_result->fetch_assoc()) {
        $reviews[] = $row;
    }
} else {
    // Redirect if product ID is not provided
    header("Location: add_review.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        body {
            font-family: Poppins, sans-serif;
            background-color: #e6e9f0;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #000;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-info i {
            margin-right: 7px;
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
            margin: 20px auto;
            /* Center the container */
            padding: 20px;
        }

        .rating .fa {
            color: #FFD700;
            font-size: 24px;
        }

        .checked {
            color: #FFD700;
        }

        .review-container {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 10px;
        }

        .delete-btn {
            cursor: pointer;
        }

        .product-details-container {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            align-items: center;
            background-color: #fff;

        }


        .product-details-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .product-details img {
            max-width: 200px;
            /* Limit image width */
            height: auto;
            /* Maintain aspect ratio */
            border-radius: 5px;
            /* Add border radius */
        }

        .product-details {
            text-align: left;
            margin-left: 20px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>RateMeister</h1>
        <div>
            <a href="profile.php" class="profile-icon"><i class="fas fa-user-circle"></i></a>
            <a href="logout.php" class="logout-icon"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>

    <a href="all_posts.php" class="btn btn-secondary ml-3 mb-3">Back</a>

    <div class="container">
        <div class="product-details-container">
            <!-- Product Image -->
            <img src="images/<?= $productDetails['image']; ?>" alt="Product Image" class="mr-3"
                style="max-width: 200px;">

            <!-- Product Details -->
            <div class="product-details">
                <h2 class="product-details-header">Product</h2>
                <h2><?= $productDetails['product_name']; ?></h2>
                <p>Product Type: <?= $productDetails['product_type']; ?></p>
                <p>Description: <?= $productDetails['description']; ?></p>
                <p>Price: $<?= $productDetails['price']; ?></p>
            </div>
        </div>

        <!-- Add Review Button -->
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModal">
            Add Review
        </button>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Review</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="comment">Comment:</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="rating">Rating:</label>
                                <select class="form-control" id="rating" name="rating">
                                    <option value="1">1 Star</option>
                                    <option value="2">2 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="5">5 Stars</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <h2>Product Reviews:</h2>
        <div id="reviewsContainer">
            <?php if (!empty($reviews)): ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="review-container">
                        <div class="user-info">
                            <i class="fas fa-user"></i>
                            <h4><?= $review['username']; ?></h4>
                        </div>
                        <p><?= $review['comment']; ?></p>
                        <p class="rating">Rating:
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= $review['rating']): ?>
                                    <i class="fas fa-star checked"></i>
                                <?php else: ?>
                                    <i class="fas fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </p>
                        <p>Date: <?= $review['date']; ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No reviews added yet!</p>
            <?php endif; ?>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>