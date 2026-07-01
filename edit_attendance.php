<?php
session_start();
if (!isset($_SESSION['user'])) { die("Unauthorized access."); }
require_once 'config/db.php';

$id = $_GET['id'] ?? $_POST['id'] ?? null;
if (!$id) { die("Record ID mismatch."); }

// Handle the Form Update Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'] ?? '';
    
    $stmt = $conn->prepare("UPDATE attendance SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $id]);
    
    header("Location: dashboard.php");
    exit;
}

// Fetch current details for the editing UI
$stmt = $conn->prepare("SELECT * FROM attendance WHERE id = :id");
$stmt->execute(['id' => $id]);
$record = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$record) { die("Record not found."); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Record</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-card">
        <h3>Modify Status for <?php echo htmlspecialchars($record['student_name']); ?></h3>
        <form method="POST" action="edit_attendance.php">
            <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
            <select name="status">
                <option value="Present" <?php if($record['status']=='Present') echo 'selected'; ?>>Present</option>
                <option value="Absent" <?php if($record['status']=='Absent') echo 'selected'; ?>>Absent</option>
                <option value="Tardy" <?php if($record['status']=='Tardy') echo 'selected'; ?>>Tardy</option>
            </select><br><br>
            <button type="submit" class="btn-pixel">Update Status</button>
            <a href="dashboard.php">Cancel</a>
        </form>
    </div>
</body>
</html>