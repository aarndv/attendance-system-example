<?php
session_start();
if (!isset($_SESSION['user'])) { die("Unauthorized access."); }
require_once 'config/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM attendance WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header("Location: dashboard.php");
exit;
?>