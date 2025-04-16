<?php
include 'checkindex.php';
include 'db.php';




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = $_POST['book_id'];
    $enrollmentno = $_POST['enrollmentno'];

    // Get current date
    $issue_date = date('Y-m-d');
    $return_date = $_POST['return_date'];

    if (strtotime($return_date) <= strtotime($issue_date)) {
        echo "<script>alert('Error: Return date must be a future date!');</script>";
    } else {
        $checkUserQuery = "SELECT enrollmentno FROM users WHERE enrollmentno = ?";
        $stmt = $conn->prepare($checkUserQuery);
        $stmt->bind_param("s", $enrollmentno);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            echo "<script>alert('Error: Enrollment number does not exist!');</script>";
        } else {
            $stmt->close();

            $checkBookQuery = "SELECT book_name, quantity FROM books WHERE book_id = ?";
            $stmt = $conn->prepare($checkBookQuery);
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                echo "<script>alert('Error: Book ID does not exist!');</script>";
            } else {
                $bookData = $result->fetch_assoc();
                $book_name = $bookData['book_name'];
                $quantity = $bookData['quantity'];
                $stmt->close();

                if ($quantity > 0) {
                    $issue_id = uniqid('ISSUE_');

                    $insertQuery = "INSERT INTO issuedbook (issue_id, enrollmentno, book_id, book_name, issue_date, return_date, fine_amount, status) 
                                    VALUES (?, ?, ?, ?, ?, ?, 0, 'Issued')";
                    $stmt = $conn->prepare($insertQuery);
                    $stmt->bind_param("ssssss", $issue_id, $enrollmentno, $book_id, $book_name, $issue_date, $return_date);

                    if ($stmt->execute()) {
                        $updateQuantityQuery = "UPDATE books SET quantity = quantity - 1 WHERE book_id = ?";
                        $stmt = $conn->prepare($updateQuantityQuery);
                        $stmt->bind_param("s", $book_id);
                        $stmt->execute();

                        echo "<script>
                                alert('Book Issued Successfully!');
                                window.location.href = 'Totalbook.php'; // Redirect to avoid resubmission
                              </script>";
                        exit();
                    } else {
                        echo "<script>alert('Error issuing book!');</script>";
                    }
                    $stmt->close();
                } else {
                    echo "<script>alert('Error: No copies available!');</script>";
                }
            }
        }
    }
}

$query = "SELECT book_id, book_name, book_author, quantity, publish_year FROM books";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management</title>
    <link rel="stylesheet" href="styles.css">
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
            background: #f4f4f4;
        }

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

        .sidebar ul li a {
            color: white;
            text-decoration: none;
        }

        .logout {
            color: #e74c3c;
        }

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

        #search {
            padding: 10px;
            width: 30%;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #2c3e50;
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .issue-btn {
            background: #27ae60;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .sidebar h2 a {
            text-decoration: none;
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 30%;
            text-align: center;
        }

        .close {
            float: right;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const today = new Date().toISOString().split("T")[0];
            document.querySelector("input[name='issue_date']").value = today;
            document.querySelector("input[name='return_date']").setAttribute("min", today);
        });

        function openModal(bookId) {
            document.getElementById("book_id").value = bookId;
            document.getElementById("issueModal").style.display = "block";
            alert("Book ID: " + bookId);
        }

        function closeModal() {
            document.getElementById("issueModal").style.display = "none";
        }
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
            <h2>Total Books</h2>
            <input type="text" id="search" onkeyup="searchBooks()" placeholder="Search by book name or author...">
        </div>
        <table id="bookTable">
            <thead>
                <tr>
                    <!-- <th>Book ID</th> -->
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Quantity</th>
                    <th>Publish Year</th>
                    <th>Issue</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <!-- <td><?php echo htmlspecialchars($row['book_id']); ?></td> -->
                        <td><?php echo htmlspecialchars($row['book_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['book_author']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['publish_year']); ?></td>
                        <td><button class="issue-btn" onclick="openModal('<?php echo $row['book_id']; ?>')">Issue</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div id="issueModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form action="" method="POST">
                <input type="hidden" id="book_id" name="book_id">
                <label>Enrollment No:</label><input type="text" name="enrollmentno" required><br>
                <label>Issue Date:</label><input type="date" name="issue_date" readonly required><br>
                <label>Return Date:</label><input type="date" name="return_date" required><br>
                <button type="submit">Issue</button>
            </form>
        </div>
    </div>
</body>

</html>