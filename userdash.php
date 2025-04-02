<?php
include 'checklogin.php';
include 'db.php';


if (!isset($_SESSION['enrollmentno'])) {
    header("Location: login.php");
    exit();
}

$enrollmentno = $_SESSION['enrollmentno'];

$query = "SELECT issue_id, book_id, book_name, issue_date, return_date, fine_amount, status 
          FROM issuedbook 
          WHERE enrollmentno = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $enrollmentno);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Issued Books</title>
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
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>User Dashboard</h2>
        <ul>
            <li><a href="logout.php" class="logout">🚪 Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>My Issued Books</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Book Name</th>
                    <th>Issue Date</th>
                    <th>Return Date</th>
                    <th>Fine Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['book_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['issue_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['return_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['fine_amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No books issued yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php $conn->close(); ?>