<?php
$servername = "localhost";
$username = "root";
$password = "";
$DB = "nutritionx";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $DB);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
