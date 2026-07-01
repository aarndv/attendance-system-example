<?php
require_once 'auth_check.php';
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['student_id']);
    $name = trim($_POST['student_name']);
    $status = $_POST['status'];
    $date = $_POST['log_date'];

    // Validation Check
    if (strlen($name) < 2 || empty($id)) {
        $_SESSION['error'] = "Invalid input data.";
        header("Location: dashboard.php");
        exit;
    }

    // Duplication Check
    $checkStmt = $conn->prepare("SELECT id FROM attendance WHERE student_id = :id AND log_date = :date");
    $checkStmt->execute(['id' => $id, 'date' => $date]);
    
    if ($checkStmt->rowCount() > 0) {
        $_SESSION['error'] = "Duplicate log: This student is already recorded for this date.";
        header("Location: dashboard.php");
        exit;
    }

    // Insert Data
    $stmt = $conn->prepare("INSERT INTO attendance (student_id, student_name, status, log_date) VALUES (:id, :name, :status, :date)");
    $stmt->execute(['id' => $id, 'name' => $name, 'status' => $status, 'date' => $date]);
    
    header("Location: dashboard.php");
    exit;
}
?>
