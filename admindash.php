<?php
try {
    // Database connection
    $pdo = new PDO("mysql:host=localhost;dbname=library02", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get the count of rows in each table
    $query = "
        SELECT 
            (SELECT COUNT(*) FROM books) AS total_books,
            (SELECT COUNT(*) FROM users) AS total_users,
            (SELECT COUNT(*) FROM issuedbook) AS total_issued_books
    ";

    $stmt = $pdo->query($query);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Store data in an array for JavaScript
    $data = [
        "books" => $row['total_books'],
        "users" => $row['total_users'],
        "issued_books" => $row['total_issued_books']
    ];

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background: #f4f4f4;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar h2 a {
            text-decoration: none;
            color: white;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 15px;
            border-radius: 6px;
            transition: 0.3s;
        }

        .sidebar ul li:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logout {
            color: #e74c3c;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #darkModeToggle {
            background: transparent;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }

        /* Dashboard Cards */
        .dashboard {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex: 1;
            text-align: center;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        /* Dark Mode */
        .dark-mode {
            background: #2c3e50;
            color: white;
        }

        .dark-mode .header {
            background: #34495e;
        }

        .dark-mode .card {
            background: #34495e;
        }
    </style>
   <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Ensure data is properly passed from PHP
        let data = <?php echo json_encode($data); ?>;

        // Check if elements exist before updating
        let bookElement = document.getElementsByClassName("pbooks")[0];
        let userElement = document.getElementsByClassName("pstudents")[0];
        let issuedBookElement = document.getElementsByClassName("pibooks")[0];

        if (bookElement) bookElement.innerText = data.books;
        if (userElement) userElement.innerText = data.users;
        if (issuedBookElement) issuedBookElement.innerText = data.issued_books;

        // Dark mode toggle
        const darkModeToggle = document.getElementById("darkModeToggle");
        if (darkModeToggle) {
            darkModeToggle.addEventListener("click", () => {
                document.body.classList.toggle("dark-mode");
                darkModeToggle.textContent = document.body.classList.contains("dark-mode") ? "☀" : "🌙";
            });
        }
    });
</script>


</head>

<body>

    <div class="sidebar">
        <h2><a href="admindash.php">Admin panel</a></h2>
        <ul>
            <li><a href="Totalbook.php">📚 Total Books</a></li>
            <li><a href="studentlist.php">👨‍🎓 Student List</a></li>
            <li><a href="issuedbooks.php">📖 Issued Books</a></li>
            <li><a href="addbook.php">➕ Add Books</a></li>
            <li><a href="logout.php" class="logout">🚪 Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Welcome, Admin</h2>
            <button id="darkModeToggle">🌙</button>
        </div>
        <div class="dashboard">
            <div class="card">
                <h3>Total Books</h3>
                <p class="pbooks">500</p>
            </div>
            <div class="card">
                <h3>Students</h3>
                <p class="pstudents">120</p>
            </div>
            <div class="card">
                <h3>Issued Books</h3>
                <p class="pibooks">80</p>
            </div>
        </div>
    </div>

</body>

</html>