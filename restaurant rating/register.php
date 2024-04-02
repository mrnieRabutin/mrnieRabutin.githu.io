<?php

session_start(); // Start the session

include 'connect.php'; // Include your database connection script

$warning_msg = [];
$success_msg = [];

function create_unique_id()
{
    return uniqid(); // Using uniqid() to generate a unique identifier
}

if (isset ($_POST['submit'])) {
    $id = create_unique_id();
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $c_pass = $_POST['c_pass'];
    $image = $_FILES['image']['name'];

    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $pass = password_hash($pass, PASSWORD_DEFAULT);
    $image = filter_var($image, FILTER_SANITIZE_STRING);

    if ($pass !== password_hash($c_pass, PASSWORD_DEFAULT)) {
        $warning_msg[] = 'Confirm password not matched!';
    }

    if (!empty ($image)) {
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = create_unique_id() . '.' . $ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'images/' . $rename;

        if ($image_size > 2000000) {
            $warning_msg[] = 'Image size is too large!';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
        }
    } else {
        $rename = '';
    }

    // Check if email already exists
    $verify_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $verify_email->bind_param("s", $email);
    $verify_email->execute();
    $result = $verify_email->get_result();

    if ($result->num_rows > 0) {
        $warning_msg[] = 'Email already taken!';
    } else {
        // Insert new user if email is not already taken
        $insert_user = $conn->prepare("INSERT INTO `users` (id, username, email, password, pic) VALUES (?, ?, ?, ?, ?)");
        $insert_user->bind_param("ssssi", $id, $name, $email, $pass, $rename);
        $insert_user->execute();
        $insert_user->close(); // Close the prepared statement after execution
        $success_msg[] = 'Registered successfully!';

        $_SESSION['user_id'] = $id;

        header("Location: index.php");
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
    <title>Register</title>
    <style>
        /* Add your custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            background-color: #f4f4f4;
            background-image: url('images/bg.png');
            /* Specify the path to your image */
            background-size: cover;
            /* Cover the entire background */
            background-repeat: no-repeat;
            /* Prevent the image from repeating */
            margin: 0;
            padding: 0;

        }

        .account-form {
            width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            margin-top: 0;
            text-align: center;
            color: #333;
        }

        .box {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .btn {
            width: 100%;
            padding: 10px;
            background-color: #FF5F1F;
            border: none;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .warning {
            color: red;
        }

        .success {
            color: green;
        }

        .link {
            text-align: center;
            margin-top: 10px;
        }

        .link a {
            color: #007bff;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }

        .success-message {
            color: #28a745;
            /* Soft green color */
            text-align: center;
            margin-top: 10px;
        }

        /* Your CSS styles */
    </style>
</head>

<body>
    <section class="account-form">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>Make Your Account!</h3>
            <?php
            if (!empty ($warning_msg)) {
                foreach ($warning_msg as $msg) {
                    echo "<p class='warning'>$msg</p>";
                }
            }
            if (!empty ($success_msg)) {
                foreach ($success_msg as $msg) {
                    echo "<p class='success'>$msg</p>";
                }
            }
            ?>
            <p class="placeholder">Your Name <span>*</span></p>
            <input type="text" name="name" required maxlength="50" placeholder="Enter your name" class="box">
            <p class="placeholder">Your Email <span>*</span></p>
            <input type="email" name="email" required maxlength="50" placeholder="Enter your email" class="box">
            <p class="placeholder">Your Password <span>*</span></p>
            <input type="password" name="pass" required maxlength="50" placeholder="Enter your password" class="box">
            <p class="placeholder">Confirm Password <span>*</span></p>
            <input type="password" name="c_pass" required maxlength="50" placeholder="Confirm your password"
                class="box">
            <p class="placeholder">Profile Picture</p>
            <input type="file" name="image" class="box" accept="image/*">
            <p class="link">Already have an account? <a href="index.php">Login now</a></p>
            <input type="submit" value="Register Now" name="submit" class="btn">
        </form>
    </section>
</body>

</html>