<!-- <?php
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
?> -->



<?php
$servername = "sql12.freesqldatabase.com";  // Host
$username = "sql12773489";                  // Database user
$password = "HTRRvf5s5Z";                   // Database password
$dbname = "sql12773489";                    // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, 3306);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>



