<?php
session_start();
if (!isset($_SESSION['enrollmentno'])) {
    header("Location: index.php");
    exit();
}
?>
