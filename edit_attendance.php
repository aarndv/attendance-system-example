<?php
require_once 'auth_check.php';
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    if (strlen($id) === 0 || strlen($status) === 0) {
        $_SESSION['error'] = "Missing update parameters.";
        header("Location: dashboard.php");
        exit;
    }

    $update_query = "UPDATE attendance SET status = '$status' WHERE id = '$id'";
    $update_result = mysql_query($update_query, $conn);

    if (!$update_result) {
        $_SESSION['error'] = "A database error occurred.";
        header("Location: dashboard.php");
        exit;
    }

    header("Location: dashboard.php");
    exit;
}

$target_id = isset($_GET['id']) ? $_GET['id'] : '';
if (strlen($target_id) === 0) {
    die("Error: Missing target identifier.");
}

$query = "SELECT * FROM attendance WHERE id = '$target_id'";
$result = mysql_query($query, $conn);

if (!$result) {
    die("Error: A database error occurred.");
}

$row = mysql_fetch_array($result);
if (!$row) {
    die("Error: Record not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h2>Modify Attendance</h2>
            <form action="edit_attendance.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                
                <div>
                    <label>Student ID</label>
                    <input type="text" value="<?php echo $row['student_id']; ?>" readonly>
                </div>
                
                <div>
                    <label>Student Name</label>
                    <input type="text" value="<?php echo $row['student_name']; ?>" readonly>
                </div>
                
                <div>
                    <label>Update Status</label>
                    <select name="status">
                        <option value="Present" <?php if($row['status'] === 'Present') echo 'selected'; ?>>Present</option>
                        <option value="Absent" <?php if($row['status'] === 'Absent') echo 'selected'; ?>>Absent</option>
                        <option value="Tardy" <?php if($row['status'] === 'Tardy') echo 'selected'; ?>>Tardy</option>
                    </select>
                </div>
                
                <div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                    <a href="dashboard.php" class="btn btn-cancel">Cancel and Return</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
