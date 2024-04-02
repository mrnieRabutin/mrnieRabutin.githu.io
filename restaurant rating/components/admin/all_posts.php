<?php
include 'components/connect.php';
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
          padding: 0px;
          margin-left: 220px;
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

      /* Sidebar styling */
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

/* Button styling */
.sidebar-button {
    display: block;
    width: 100%;
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
    transform: scale(1.05); /* Slight scale effect on hover */
}

      .content-wrapper {
          margin-left: 220px; /* Width of sidebar plus some space for margin */
          padding: 20px;
      }

      table {
        border-collapse: collapse;
        width: 90%;
        margin: .sidebar-wrapper {
    float: left;
    width: 200px;
    height: 100vh;
    background-color: #152238;
    color: #fff;
    padding: 20px;
}

.sidebar ul {
    list-style: none; /* Remove default list bullets */
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    margin-bottom: 10px;
}

.sidebar ul li a {
    display: block;
    padding: 10px 20px;
    color: #fff;
    text-decoration: none;
    transition: background-color 0.3s;
}

.sidebar ul li a:hover {
    background-color: rgba(255, 255, 255, 0.1);
}
0 auto;
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
    <h2>Dashboard</h2>
    <ul>
        <li><a href="all_posts.php" class="sidebar-button">Home</a></li>
        <li><a href="accounts.php" class="sidebar-button">Accounts</a></li>
        <li><a href="actlogs.php" class="sidebar-button">Activity Logs</a></li>
    </ul>
</div>


<!-- View all restaurants section -->
<section class="all-restaurants">
   <div class="container">
      <div class="heading">
         <h1>All Posts</h1>
         <a href="add_restaurant.php" class="inline-btn">Add</a>

      </div>
      <div class="box-container">

         <?php
            $select_restaurants = $conn->prepare("SELECT * FROM `restaurants` ORDER BY id DESC");
            $select_restaurants->execute();
            if($select_restaurants->rowCount() > 0){
               while($fetch_restaurant = $select_restaurants->fetch(PDO::FETCH_ASSOC)){
                  $restaurant_id = $fetch_restaurant['id'];
                  // Assuming you have a separate table for reviews and you want to count reviews for each restaurant
                  $count_reviews = $conn->prepare("SELECT COUNT(*) AS total_reviews FROM `reviews` WHERE restaurant_id= ?");
                  $count_reviews->execute([$restaurant_id]);
                  $total_reviews = $count_reviews->fetch(PDO::FETCH_ASSOC)['total_reviews'];
         ?>
         <div class="box">
            <!-- Assuming the image file path is stored in the 'image' column of the restaurants table -->
            <img src="../uploaded_files/<?= $fetch_restaurant['image']; ?>" alt="<?= $fetch_restaurant['name']; ?>" class="image">
            <h3 class="title"><?= $fetch_restaurant['name']; ?></h3>
            <p class="total-reviews"><i class="fas fa-star"></i> <span><?= $total_reviews; ?></span></p>
            <!-- Link to view more details about the restaurant -->
            <a href="view_post.php?restaurant_id=<?= $restaurant_id; ?>" class="inline-btn">View Post</a>
            <a href="edit_restaurant.php?restaurant_id=<?= $restaurant_id; ?>" class="inline-btn">Edit</a>
            <a href="delete_restaurant.php?restaurant_id=<?= $restaurant_id; ?>" class="inline-btn" onclick="return confirm('Are you sure you want to delete this restaurant?')">Delete</a>



         </div>
         <?php
               }
            } else {
               echo '<p class="empty">No restaurants added yet!</p>';
            }
         ?>
      </div>
   </div>
</section>


</div>
</body>
</html>
