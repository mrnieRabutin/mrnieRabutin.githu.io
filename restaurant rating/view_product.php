<?php
include 'connect.php';

function getProductDetails($productId)
{
    global $conn;
    $stmt = $conn->prepare("SELECT product_name, image FROM product WHERE id = ?");
    $stmt->execute([$productId]);
    return $stmt->get_result()->fetch_assoc();

}

if (isset ($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $select_product = $conn_abarzosa->prepare("SELECT id, product_name, product_type, description, price FROM `product` WHERE id = ?");
    $select_product->execute([$product_id]);
    $product_result = $select_product->get_result();
    $product = $product_result->fetch_assoc();


    // Fetch reviews for the product
    $select_reviews = $conn_abarzosa->prepare("SELECT * FROM `reviews` WHERE product_id = ?");
    $select_reviews->bind_param("i", $product_id); // Assuming product_id is an integer
    $select_reviews->execute();
    $reviews_result = $select_reviews->get_result();

    // Fetch all rows as an associative array
    $reviews = array();
    while ($row = $reviews_result->fetch_assoc()) {
        $reviews[] = $row;
    }


    // Calculate average rating
    $total_reviews = count($reviews);
    $total_rating = 0;
    foreach ($reviews as $review) {
        $total_rating += $review['rating'];
    }
    $average_rating = ($total_reviews > 0) ? round($total_rating / $total_reviews, 1) : 0;

    if (isset ($_POST['delete_review'])) {
        $review_id = $_POST['delete_review'];
        $delete_review = $conn_abarzosa->prepare("DELETE FROM `reviews` WHERE id = ?");
        $delete_review->execute([$review_id]);
        header("Location: {$_SERVER['PHP_SELF']}?product_id=$product_id");
        exit();
    }

    // Fetch product details
    $productDetails = getProductDetails($product['id']);
} else {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Add your CSS styles here */
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
            max-width: calc(100% - 440px);
            padding: 20px;
            margin-left: 30%;
            margin-right: 50px;
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

        table {
            border-collapse: collapse;
            width: 90%;
            margin: auto;
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

        .product-details {
            padding: 20px 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .section-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .product-info {
            display: flex;
            align-items: center;
        }

        .product-image {
            width: 200px;
            margin-right: 20px;
        }

        .product-image img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .product-description {
            flex: 1;
        }

        .product-description h4 {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
        }

        .product-type {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }

        .price {
            font-size: 18px;
            color: #007bff;
            margin-bottom: 10px;
        }

        .description {
            font-size: 16px;
            color: #555;
            line-height: 1.5;
        }
    </style>
</head>

<body>

    <!-- Header section -->
    <div class="header">
        <h1>Product Details</h1>

        <div>
            <!-- Add your profile and logout links here -->
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

    <!-- Product details section -->
    <section class="product-details">
    <div class="container">
        <h3 class="section-title">Product Details</h3>
        <div class="product-info">
            <div class="product-image">
                <img src="images/burgers.jpg" alt="<?= $product['product_name']; ?>">
            </div>
            <div class="product-description">
                <h4><?= $product['product_name']; ?></h4>
                <p class="product-type"><?= $product['product_type']; ?></p>
                <p class="price">$<?= $product['price']; ?></p>
                <p class="description"><?= $product['description']; ?></p>
            </div>
        </div>
    </div>
</section>

    <!-- Reviews section -->
    <section class="user_requests">
        <div class="container">
            <div class="request-review">
                <!-- Font Awesome Library -->
                <link rel="stylesheet"
                    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
                <a href="product_review.php?product_id=<?= $product_id; ?>" class="inline-btn"><i
                        class="fas fa-plus"></i> Add Review</a>
            </div>
            <h2>User's Reviews</h2>
            <?php if (!empty ($reviews)): ?>
                <ul>
                    <?php foreach ($reviews as $review): ?>
                        <h2>
                            <?= $review['username']; ?>
                        </h2>
                        <li>

                            <img src="images/<?php echo $userDetails['image']; ?>" alt="" width="50">

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