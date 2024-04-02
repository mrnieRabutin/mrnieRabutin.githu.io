<?php
// Database credentials
define('DB_SERVER', 'sql6.freesqldatabase.com');
define('DB_USERNAME', 'sql6691333');
define('DB_PASSWORD', 'JcDgZUMWNU');
define('DB_NAME', 'sql6691333');

// Attempt to connect to the database
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
