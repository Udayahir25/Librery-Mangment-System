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
// Connection details
$host     = "dpg-cvvk9peuk2gs73dd2a3g-a.virginia-postgres.render.com"; // External host
$port     = "5432";
$dbname   = "final_lms";
$username = "final_lms_user";
$password = "vAmYB08eB4jtAQGkgwG77GwcBbT9qa4T";

// DSN (Data Source Name) for PostgreSQL with SSL mode required
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";

try {
    // Create a PDO instance
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    echo "Connected successfully to the PostgreSQL database on Render!";
    
    // Example query: Fetch PostgreSQL version
    $stmt = $pdo->query("SELECT version()");
    $version = $stmt->fetch();
    echo "<br>PostgreSQL version: " . $version['version'];
    
} catch (PDOException $e) {
    // Handle connection errors
    echo "Connection failed: " . $e->getMessage();
}
?>



