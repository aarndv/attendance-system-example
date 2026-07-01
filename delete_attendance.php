<?php
require_once 'auth_check.php';
require_once 'config/db.php';

$target_id = $_GET['id'] ?? null;

// Boundary Verification
$check = $conn->prepare("SELECT id FROM attendance WHERE id = :id");
$check->execute(['id' => $target_id]);
if ($check->rowCount() === 0) {
    die("Error: Target record does not exist.");
}

$stmt = $conn->prepare("DELETE FROM attendance WHERE id = :id");
$stmt->execute(['id' => $target_id]);

header("Location: dashboard.php");
exit;
?>
