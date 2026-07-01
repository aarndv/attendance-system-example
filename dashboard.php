<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
require_once 'config/db.php';

// Fetch all rows for display
$stmt = $conn->query("SELECT * FROM attendance ORDER BY log_date DESC");
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Attendance Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <span>Logged in as: <b><?php echo $_SESSION['user']; ?></b></span>
        <a href="logout.php" class="btn-danger">Logout</a>
    </header>

    <main>
        <section class="form-section">
            <h3>Log New Attendance</h3>
            <form method="POST" action="add_attendance.php">
                <input type="text" name="student_id" placeholder="Student ID (e.g. 2026-0001)" required>
                <input type="text" name="student_name" placeholder="Full Name" required>
                <select name="status">
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                    <option value="Tardy">Tardy</option>
                </select>
                <input type="date" name="log_date" required>
                <button type="submit" class="btn-pixel">Save Record</button>
            </form>
        </section>

        <section class="table-section">
            <h3>Attendance Records</h3>
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($row['status']); ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td><?php echo $row['log_date']; ?></td>
                        <td>
                            <a href="edit_attendance.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                            <a href="delete_attendance.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Delete this record?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>