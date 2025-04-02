<!-- <?php
$servername = "localhost";
$username = "root";
$password = ""; // Leave empty if no password
$database = "library02"; // Change this if your database name is different

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?> -->
<?php
$servername = "sql102.infinityfree.com"; // Your database host from InfinityFree
$username = "if0_38654473";
$password = "wZ485oE4FcFA7";
$dbname = "if0_38654473_library02";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>