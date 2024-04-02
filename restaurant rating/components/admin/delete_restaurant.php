<?php
include 'components/connect.php';

// Check if restaurant_id is provided and it's a valid number
if(isset($_GET['restaurant_id']) && is_numeric($_GET['restaurant_id'])) {
    $restaurant_id = $_GET['restaurant_id'];

    // Prepare and execute the delete query
    $delete_restaurant = $conn->prepare("DELETE FROM `restaurants` WHERE id = ?");
    $delete_restaurant->execute([$restaurant_id]);

    // Redirect back to all_posts.php after deletion
    header("Location: all_posts.php");
    exit();
} else {
    // Redirect to some error page if restaurant_id is not provided or invalid
    header("Location: error_page.php");
    exit();
}
?>
