<?php
require_once 'auth_check.php';
require_once 'config/db.php';

$target_id = isset($_GET['id']) ? $_GET['id'] : '';

if (strlen($target_id) === 0) {
    die("Error: Missing target identifier.");
}

// Boundary Verification
$check_query = "SELECT id FROM attendance WHERE id = '$target_id'";
$check_result = mysql_query($check_query, $conn);

if (!$check_result) {
    die("Error: A database error occurred.");
}

$row = mysql_fetch_assoc($check_result);
if (!$row) {
    die("Error: Target record does not exist.");
}

// Execute deletion
$delete_query = "DELETE FROM attendance WHERE id = '$target_id'";
$delete_query_result = mysql_query($delete_query, $conn);

if (!$delete_query_result) {
    die("Error: Failed to delete record.");
}

header("Location: dashboard.php");
exit;
?>