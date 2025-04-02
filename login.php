<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enrollmentno = $_POST['enroll_no'];
    $password = $_POST['pass'];

    if ($enrollmentno == "230170132011" && $password == "123456") {
        $_SESSION['enrollmentno'] = $enrollmentno; // Set session for admin
        header("Location: admindash.php");
        exit();
    }

    $sql = "SELECT password FROM users WHERE enrollmentno = ?";
    $stmt = $conn->prepare($sql);   
    $stmt->bind_param("s", $enrollmentno);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['enrollmentno'] = $enrollmentno; // Set session for user
            header("Location: userdash.php");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "Enrollment number not found!";
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Reset some default styles */
        body,
        html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;  
        }

        .content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 320px;
            text-align: left;
        }

        .F_form {
            margin-top: 20px;
            margin-right: 10px;
        }

        .form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .form button:hover {
            background-color: #0056b3;
        }

        .Login {
            text-decoration: none;
            color: #007bff;
            font-size: 14px;
        }

        .Login:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="content">
            <div class="F_form">
                <form class="form" action="" method="post">
                    <input type="text" name="enroll_no" placeholder="Enter your Enrolment No">
                    <input type="text" name="pass" placeholder="Enter your password">
                    <p> <a class="Login" href="register.php">Register</a></p>
                    <button type="submit">Submit</button>
                </form>
            </div>


        </div>
    </div>
    </div>

    <script>

    </script>

</body>

</html>