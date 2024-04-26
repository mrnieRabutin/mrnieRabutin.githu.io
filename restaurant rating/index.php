<?php
// Include database connection
include ('connect.php');

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check input errors before processing the database
    if (empty($username_err) && empty($password_err)) {
        // Prepare a select statement
        $sql = "SELECT id, email, password FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                // Check if username exists, then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $username;

                            // Record login activity
                            $activity = "Login";
                            $timestamp = date("Y-m-d H:i:s");
                            $sql_insert = "INSERT INTO logs (username, activity, timestamp) VALUES (?, ?, ?)";
                            $stmt_insert = $conn->prepare($sql_insert);
                            $stmt_insert->execute([$username, $activity, $timestamp]);

                            // Redirect user to the dashboard
                            header("location: all_posts.php");
                        } else {
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                background-image: url('images/slide.jpg');
                filter: brightness(90%);
                z-index: -1;
            }

            .login-container {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                z-index: -1;
            }

            .login-box {
                background: #000000;
                color: #f4f4f4;
                backdrop-filter: blur(10px);
                border-radius: 5px;
                padding: 50px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                width: 500px;
                max-width: 80%;

            }

            .login-image {
                flex: 0 0 auto;
                max-width: 400px;
            }

            .login-image img {
                width: 100%;
                height: 65vh;
                border-radius: 5px;
            }

            .btn-primary {
                color: #fff;
                width: 100%;
                padding: 10px;
                background-color: #e62e2e;
                color: #fff;
                border: none;
                border-radius: 3px;
                cursor: pointer;
            }

            .form-group {
                position: relative;
                margin-bottom: 20px;
            }

            .icon {
                position: absolute;
                top: 50%;
                right: 10px;
                transform: translateY(-50%);
                color: #000;

            }

            .form-control {
                padding-left: 30px;
            }
        </style>
</head>

<body>

    <div class="login-container">
        <div class="login-box">
            <h2>LOG IN</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <input type="text" name="username" placeholder="Email" class="form-control"
                        value="<?php echo $username; ?>">
                        <span class="icon">
                        <i class="fas fa-user"></i>
                    </span>
                    <span class="help-block">
                        <?php echo $username_err; ?>
                    </span>
                </div>
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <span class="icon">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" placeholder="Password" class="form-control">
                    <span class="help-block">
                        <?php echo $password_err; ?>
                    </span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Log in">
                </div>
                <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
            </form>
        </div>
    </div>

</body>

</html>