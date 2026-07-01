<?php
require_once 'auth_check.php';
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['student_id'];
    $name = $_POST['student_name'];
    $status = $_POST['status'];
    $date = $_POST['log_date'];

    if (strlen($id) === 0 || strlen($name) === 0 || strlen($status) === 0 || strlen($date) === 0) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: dashboard.php");
        exit;
    }

    // Duplication Check
    $check_query = "SELECT id FROM attendance WHERE student_id = '$id' AND log_date = '$date'";
    $check_result = mysql_query($check_query, $conn);
    
    if (!$check_result) {
        $_SESSION['error'] = "A database error occurred.";
        header("Location: dashboard.php");
        exit;
    }

    $existing_row = mysql_fetch_assoc($check_result);
    if ($existing_row) {
        $_SESSION['error'] = "Duplicate log: This student is already recorded for this date.";
        header("Location: dashboard.php");
        exit;
    }

    // Insert Data
    $insert_query = "INSERT INTO attendance (student_id, student_name, status, log_date) VALUES ('$id', '$name', '$status', '$date')";
    $insert_result = mysql_query($insert_query, $conn);

    if (!$insert_result) {
        $_SESSION['error'] = "A database error occurred.";
        header("Location: dashboard.php");
        exit;
    }

    header("Location: dashboard.php");
    exit;
}
?>