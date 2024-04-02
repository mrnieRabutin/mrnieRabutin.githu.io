<?php
require_once "config.php";
session_start();
include 'connect.php';

$username = $comment = $rating = "";
$comment_err = $rating_err = "";

// Check if the product_id is passed in the URL
if(isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
  
    // Fetch product details from the database
    $select_product = $conn_abarzosa->prepare("SELECT * FROM `product` WHERE id = ?");
    $select_product->bind_param("i", $product_id); // Assuming $product_id is an integer
    $select_product->execute();
    $product_result = $select_product->get_result();
    $product = $product_result->fetch_assoc();
    
} else {
    header("Location: all_posts.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate comment
    if(empty(trim($_POST["comment"]))){
        $comment_err = "Please enter your comment.";
    } else {
        $comment = trim($_POST["comment"]);
    }
  
    // Validate rating
    if(empty(trim($_POST["rating"]))){
        $rating_err = "Please select a rating.";
    } else {
        $rating = trim($_POST["rating"]);
    }

    // Fetch the username from the session
    if(isset($_SESSION['email'])) {
        $username = $_SESSION['email'];
    } else {
        echo "Username not found in session."; // Debugging statement
    }

    // If there are no errors, proceed to insert the review into the database
    if(empty($comment_err) && empty($rating_err)){
        $insert_review = $conn_abarzosa->prepare("INSERT INTO `reviews` (product_id, username, comment, rating) VALUES (?, ?, ?, ?)");
        if ($insert_review) {
            $insert_review->bind_param("isss", $product_id, $username, $comment, $rating);
            if ($insert_review->execute()) {
                // Redirect back to view_product.php after adding the review
                header("Location: view_product.php?product_id=$product_id");
                exit();
            } else {
                echo "Error executing the prepared statement: " . $insert_review->error; // Error handling
            }
        } else {
            echo "Error preparing the statement: " . $conn_abarzosa->error; // Error handling
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Review for Product ID <?= $product_id; ?></title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   <!-- Add your CSS stylesheets here -->
   <style>
      /* CSS styles */
      body {
         font-family: Arial, sans-serif;
         margin: 0;
         padding: 0;
      }

      .container {
         max-width: 600px;
         margin: 20px auto;
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

      button[type="submit"] {
         background-color: #007bff;
         color: #fff;
         border: none;
         padding: 10px 20px;
         border-radius: 4px;
         cursor: pointer;
         font-size: 16px;
      }

      button[type="submit"]:hover {
         background-color: #0056b3;
      }

      .error {
         color: red;
      }
   </style>
</head>
<body>

<div class="container">
   <h2>Post Your Review <?= $product_id; ?></h2>
    
   <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?product_id=' . $product_id; ?>">
      <div>
         <label for="comment">Review description:</label>
         <textarea id="comment" name="comment" rows="4" required></textarea>
         <span class="error"><?php echo $comment_err; ?></span>
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
         <span class="error"><?php echo $rating_err; ?></span>
      </div>
      <button type="submit">Add Review</button>
      <a href="all_posts.php">Go Back</a>
   </form>
</div>

</body>
</html>
