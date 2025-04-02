<?php
include 'checklogin.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_name = trim($_POST['book_name']);
    $author = trim($_POST['author']);
    $quantity = intval($_POST['quantity']);
    $publish_year = intval($_POST['publish_year']);

    // Ensure connection is working
    if (!$conn) {
        die("<script>alert('❌ Database connection failed!');</script>");
    }

    // Check if book already exists (case-insensitive)
    $check_query = "SELECT * FROM books WHERE LOWER(book_name) = LOWER(?) AND LOWER(book_author) = LOWER(?)";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ss", $book_name, $author);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('❌ Book already exists in the database!'); window.location.href='addbook.php';</script>";
        exit();
    } else {
        // Generate unique book_id
        $short_book = strtoupper(substr($book_name, 0, 3));
        $short_author = strtoupper(substr($author, 0, 3));
        $year_suffix = substr($publish_year, -2);
        $random_number = rand(100, 999);
        $book_id = $short_book . $short_author . $year_suffix . $random_number;

        // Insert book into database
        $sql = "INSERT INTO books (book_id, book_name, book_author, quantity, publish_year) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $book_id, $book_name, $author, $quantity, $publish_year);

        if ($stmt->execute()) {
            echo "<script>alert('✅ Book added successfully! Book ID: $book_id'); window.location.href='addbook.php';</script>";
        } else {
            echo "<script>alert('⚠️ Error adding book: " . $conn->error . "');</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
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

        .sidebar h2 a {
            text-decoration: none;
            color: white;
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

        /* Form Styling */
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 60%;
            margin: 30px auto;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .submit-btn {
            width: 100%;
            padding: 10px;
            border: none;
            background: #2c3e50;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: #1a252f;
        }

        /* Dark Mode */
        .dark-mode {
            background: #2c3e50;
            color: white;
        }

        .dark-mode .header {
            background: #34495e;
        }

        .dark-mode .form-container {
            background: #34495e;
        }

        .dark-mode .form-group input {
            background: #2c3e50;
            color: white;
            border: 1px solid #fff;
        }
    </style>
    <script>
       
        document.addEventListener("DOMContentLoaded", function () {
            const darkModeToggle = document.getElementById("darkModeToggle");
            darkModeToggle.addEventListener("click", () => {
                document.body.classList.toggle("dark-mode");
                darkModeToggle.textContent = document.body.classList.contains("dark-mode") ? "☀" : "🌙";
            });
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
            <h2>Add New Book</h2>
            <button id="darkModeToggle">🌙</button>
        </div>

        <div class="form-container">
            <h2>Book Information</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Book Name</label>
                    <input type="text" name="book_name" required>
                </div>

                <div class="form-group">
                    <label>Author</label>
                    <input type="text" name="author" required>
                </div>

                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" required>
                </div>

                <div class="form-group">
                    <label>Publish Year</label>
                    <input type="number" name="publish_year" required>
                </div>

                <button type="submit" class="submit-btn">Add Book</button>
            </form>
        </div>
    </div>

</body>

</html>