<?php
include 'checklogin.php';
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['issue_id'], $_POST['book_id'])) {
    $issue_id = $_POST['issue_id'];
    $book_id = $_POST['book_id'];


    // Delete issued book entry
    $deleteQuery = "DELETE FROM issuedbook WHERE issue_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("s", $issue_id);

    if ($stmt->execute()) {
        // Increase book quantity

        $updateBookQuery = "UPDATE books SET quantity = quantity + 1 WHERE book_id = ?";
        $stmt = $conn->prepare($updateBookQuery);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        echo "success";
        exit;
    } else {
        echo "Failed to remove book!";
        exit;
    }
}

$query = "SELECT i.issue_id, u.enrollmentno, u.name, i.book_id, i.book_name, i.issue_date, i.return_date, i.fine_amount, i.status 
          FROM issuedbook i
          JOIN users u ON i.enrollmentno = u.enrollmentno";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issued Books</title>
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
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #2c3e50;
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .remove-btn {
            background: #e74c3c;
            color: white;
            padding: 8px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .remove-btn:hover {
            background: #c0392b;
        }
    </style>
    <script>
        function searchIssuedBooks() {
            let input = document.getElementById("search").value.toLowerCase();
            let rows = document.querySelectorAll("#issuedTable tbody tr");
            rows.forEach(row => {
                let bookName = row.children[2].textContent.toLowerCase();
                let studentName = row.children[1].textContent.toLowerCase();
                row.style.display = bookName.includes(input) || studentName.includes(input) ? "" : "none";
            });
        }

        function removeBook(issueId, bookId) {
            if (confirm("Are you sure you want to remove this issued book?")) {
                let formData = new FormData();
                formData.append('issue_id', issueId);
                formData.append('book_id', bookId);

                fetch('issuedbooks.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() === "success") {
                            document.getElementById("row_" + issueId).remove();
                            alert("Book removed successfully!");
                        } else {
                            alert("Error: " + data);
                        }
                    });
            }
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
            <h2>Issued Books</h2>
            <input type="text" id="search" onkeyup="searchIssuedBooks()"
                placeholder="Search by book or student name...">
        </div>
        <table id="issuedTable">
            <thead>
                <tr>
                    <th>Enrollment No</th>
                    <th>Student Name</th>
                    <th>Book Name</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                    <th>Fine Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr id="row_<?php echo $row['issue_id']; ?>">
                            <td><?php echo htmlspecialchars($row['enrollmentno']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['book_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['issue_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['return_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['fine_amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><button class="remove-btn"
                                    onclick="removeBook('<?php echo $row['issue_id']; ?>', '<?php echo $row['book_id']; ?>')">Remove</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No issued books found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php $conn->close(); ?>