<?php
include 'checklogin.php';
include 'db.php';

$query = "SELECT name, enrollmentno, semester, email, mobileno FROM users";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
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

        .sidebar h2 a {
            text-decoration: none;
            color: white;
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
        <h2><a href="admindash.php">Admin panel</a></h2>
        <ul>
            <li><a href="Totalbook.php">📚 Total Books</a></li>
            <li><a href="userdash.php">👨‍🎓 Student List</a></li>
            <li><a href="issuedbooks.php">📖 Issued Books</a></li>
            <li><a href="addbook.php">➕ Add Books</a></li>
            <li><a href="logout.php" class="logout">🚪 Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Student List</h2>
        </div>
        <table>
            <tr>
                <th>Name</th>
                <th>Enrollment No</th>
                <th>Semester</th>
                <th>Email</th>
                <th>Mobile No</th>
            </tr>
            <?php
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['enrollmentno']); ?></td>
                        <td><?php echo htmlspecialchars($row['semester']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['mobileno']); ?></td>
                    </tr>
                <?php endwhile;
            else:
                echo "<tr><td colspan='5'>No students found</td></tr>";
            endif;
            ?>
        </table>
    </div>
</body>

</html>

<?php
$conn->close();
?>