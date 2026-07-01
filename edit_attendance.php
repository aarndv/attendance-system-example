<?php
require_once 'auth_check.php';
require_once 'config/db.php';

// Processing Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE attendance SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $id]);
    
    header("Location: dashboard.php");
    exit;
}

// Display Logic
$target_id = $_GET['id'] ?? null;
$stmt = $conn->prepare("SELECT * FROM attendance WHERE id = :id");
$stmt->execute(['id' => $target_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) die("Error: Record not found.");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Record</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="edit-container">
        <h2>Modify Attendance</h2>
        <form action="edit_attendance.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            
            <label>Student ID</label>
            <input type="text" value="<?php echo $row['student_id']; ?>" readonly>
            
            <label>Student Name</label>
            <input type="text" value="<?php echo $row['student_name']; ?>" readonly>
            
            <label>Update Status</label>
            <select name="status">
                <option value="Present" <?php if($row['status'] == 'Present') echo 'selected'; ?>>Present</option>
                <option value="Absent" <?php if($row['status'] == 'Absent') echo 'selected'; ?>>Absent</option>
                <option value="Tardy" <?php if($row['status'] == 'Tardy') echo 'selected'; ?>>Tardy</option>
            </select>
            
            <button type="submit" class="btn-submit">Update Status</button>
            <a href="dashboard.php" class="btn-cancel">Cancel and Return</a>
        </form>
    </div>
</body>
</html>
