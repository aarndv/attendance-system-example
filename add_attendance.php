<?php
session_start();
if (!isset($_SESSION['user'])) { die("Unauthorized access."); }
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $student_name = trim($_POST['student_name'] ?? '');
    $status = $_POST['status'] ?? '';
    $log_date = $_POST['log_date'] ?? '';

    // Data eligibility validation (walang kalokohan)
    if (empty($student_id) || empty($student_name) || empty($status) || empty($log_date)) {
        die("Error: Invalid form entry. All fields are mandatory.");
    }

    $stmt = $conn->prepare("INSERT INTO attendance (student_id, student_name, status, log_date) VALUES (:sid, :sname, :status, :ldate)");
    $stmt->execute([
        'sid' => $student_id,
        'sname' => $student_name,
        'status' => $status,
        'ldate' => $log_date
    ]);

    header("Location: dashboard.php");
    exit;
}
?>