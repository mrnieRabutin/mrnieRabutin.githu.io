<?php
// Assuming you have included the connect.php file here
include 'components/connect.php';

// Check if the restaurant_id is passed in the URL
if(isset($_GET['restaurant_id'])) {
    $restaurant_id = $_GET['restaurant_id'];

    // Fetch restaurant details from the database
    $select_restaurant = $conn->prepare("SELECT * FROM `restaurants` WHERE id = ?");
    $select_restaurant->execute([$restaurant_id]);
    $restaurant = $select_restaurant->fetch(PDO::FETCH_ASSOC);

    // Fetch reviews for the restaurant
    $select_reviews = $conn->prepare("SELECT * FROM `reviews` WHERE restaurant_id = ?");
    $select_reviews->execute([$restaurant_id]);
    $reviews = $select_reviews->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Redirect to all_restaurants.php if restaurant_id is not provided
    header("Location: all_restaurants.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $restaurant['name']; ?></title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   <style>
      /* General styles */
      body {
          font-family: Arial, sans-serif;
          margin: 0;
          padding: 0;
          background-color: #f7f7f7;
      }

      .container {
          max-width: 800px;
          margin: 0 auto;
          padding: 20px;
      }

      /* Header styles */
      .header {
          background-color: #152238;
          color: #fff;
          padding: 10px;
          display: flex;
          justify-content: space-between;
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

      /* Restaurant details styles */
      .restaurant-details {
          margin-top: 20px;
      }

      .restaurant-details h2 {
          margin-bottom: 10px;
          color: #333;
      }

      .restaurant-image {
          width: 100%;
          max-width: 400px;
          height: auto;
          border-radius: 5px;
      }

      /* Reviews styles */
      .restaurant-reviews {
          margin-top: 20px;
      }

      .restaurant-reviews h2 {
          margin-bottom: 10px;
          color: #333;
      }

      .restaurant-reviews ul {
          list-style-type: none;
          padding: 0;
      }

      .restaurant-reviews li {
          margin-bottom: 10px;
          background-color: #fff;
          padding: 10px;
          border-radius: 5px;
          box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      }

      /* Responsive styles */
      @media only screen and (max-width: 600px) {
          .container {
              padding: 10px;
          }

          .header {
              padding: 5px;
              font-size: 14px;
          }

          .restaurant-image {
              max-width: 100%;
          }
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

<!-- Restaurant details section -->
<section class="restaurant-details">
   <div class="container">
      <h2><?= $restaurant['name']; ?></h2>
      <img src="<?= $restaurant['image']; ?>" alt="<?= $restaurant['name']; ?>" class="restaurant-image">
      <p><?= $restaurant['description']; ?></p>
   </div>
</section>

<!-- Reviews section -->
<section class="restaurant-reviews">
   <div class="container">
      <h2>Reviews</h2>
      <?php if(!empty($reviews)): ?>
         <ul>
            <?php foreach($reviews as $review): ?>
               <li>
                  <strong><?= $review['user']; ?></strong>: <?= $review['comment']; ?>
               </li>
            <?php endforeach; ?>
         </ul>
      <?php else: ?>
         <p>No reviews yet!</p>
      <?php endif; ?>
   </div>
</section>

</body>
</html>
