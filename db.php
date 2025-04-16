<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library02";

// Since 3306 is the default MySQL port, we don't need to specify it explicitly
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
