<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // Check if all form fields are set
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $mobileno = isset($_POST['mobile_no']) ? $_POST['mobile_no'] : null;
    $enrollmentno = isset($_POST['enroll_no']) ? $_POST['enroll_no'] : null;
    $semester = isset($_POST['semester']) ? $_POST['semester'] : null;
    $password = isset($_POST['pass']) ? $_POST['pass'] : null;

    // Check if fields are empty
    if (!$name || !$email || !$mobileno || !$enrollmentno || !$semester || !$password) {
        die("Error: All fields are required!");
    }

    // Check if user already exists (by email or enrollment number)
    $check_sql = "SELECT * FROM users WHERE email = ? OR enrollmentno = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $email, $enrollmentno);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) { ?>
        <script>
            alert("User already exists!");
        </script>
        <?php

    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert new user into the database
        $sql = "INSERT INTO users (name, email, mobileno, enrollmentno, semester, password) 
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssis", $name, $email, $mobileno, $enrollmentno, $semester, $hashed_password);

        if ($stmt->execute()) { ?>
            <script>
                alert("Registration successful!");
            </script>
            <?php
            header("Location: login.php");

        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statements
        $check_stmt->close();
        $stmt->close();
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #0056b3;
        }

        .Login {
            display: inline-block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }

        .Login:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="content">
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Document</title>
                <link rel="stylesheet" href="style/style.css">
            </head>

            <body>
                <!-- <div class="container"> -->
                    <div class="content">
                        <div class="S_form">
                            <form class="form" action="" method="post">
                                <input required type="text" name="name" placeholder="Enter your name">
                                <input required type="email" name="email" placeholder="Enter your email">
                                <input required type="mobile" name="mobile_no" placeholder="Enter your mobile No">
                                <input required type="text" name="enroll_no" placeholder="Enter your Enrolment No">
                                <input required type="text" name="semester" placeholder="Enter your semester">
                                <input required type="text" name="pass" placeholder="Enter your password">
                                <p> <a class="Login" href="login.php">alredy have a account</a></p>
                                <button type="submit">Submit</button>
                            </form>
                        </div>


                    </div>
                <!-- </div> -->
        </div>

        <script>

        </script>

</body>

</html>

</div>
</div>
</div>

<script>

</script>

</body>

</html>