<?php
include 'components/connect.php';

// Check if restaurant_id is provided and it's a valid number
if(isset($_GET['restaurant_id']) && is_numeric($_GET['restaurant_id'])) {
    $restaurant_id = $_GET['restaurant_id'];

    // Fetch restaurant details from the database
    $select_restaurant = $conn->prepare("SELECT * FROM `restaurants` WHERE id = ?");
    $select_restaurant->execute([$restaurant_id]);
    
    // Check if the restaurant exists
    if($select_restaurant->rowCount() > 0) {
        $restaurant = $select_restaurant->fetch(PDO::FETCH_ASSOC);
    } else {
        // Redirect to some error page if restaurant doesn't exist
        header("Location: error_page.php");
        exit();
    }
} else {
    // Redirect to some error page if restaurant_id is not provided or invalid
    header("Location: error_page.php");
    exit();
}

// If form is submitted
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $location = $_POST['location'];
    $cuisine = $_POST['cuisine'];
    $description = $_POST['description'];

    // Check if a new image file is uploaded
    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_type = $_FILES['image']['type'];
        $image_size = $_FILES['image']['size'];

        // Check if uploaded file is an image
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        $file_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        if(in_array($file_extension, $allowed_extensions)) {
            // Move uploaded image to desired directory
            move_uploaded_file($image_tmp_name, 'uploaded_files/' . $image_name);

            // Update restaurant details in the database
            $update_restaurant = $conn->prepare("UPDATE `restaurants` SET name = ?, location = ?, cuisine = ?, description = ?, image = ? WHERE id = ?");
            $update_restaurant->execute([$name, $location, $cuisine, $description, $image_name, $restaurant_id]);
        }
    } else {
        // Update restaurant details in the database without changing the image
        $update_restaurant = $conn->prepare("UPDATE `restaurants` SET name = ?, location = ?, cuisine = ?, description = ? WHERE id = ?");
        $update_restaurant->execute([$name, $location, $cuisine, $description, $restaurant_id]);
    }

    // Redirect back to all_posts.php after update
    header("Location: all_posts.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Restaurant</title>
    <style>
        /* Your CSS styles here */
       
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
            height: 100px; /* Adjust as needed */
        }

        .btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Restaurant</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Restaurant Name:</label>
                <input type="text" id="name" name="name" value="<?= $restaurant['name'] ?>" required>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" value="<?= $restaurant['location'] ?>" required>
            </div>
            <div class="form-group">
                <label for="cuisine">Cuisine Type:</label>
                <input type="text" id="cuisine" name="cuisine" value="<?= $restaurant['cuisine'] ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?= $restaurant['description'] ?></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>
</body>
</html>
