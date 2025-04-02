<?php
session_start();
if (!isset($_SESSION['enrollmentno'])) {
    header("Location: login.php");
    exit();
}
?>
