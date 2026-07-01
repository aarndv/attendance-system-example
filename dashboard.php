<?php
require_once 'auth_check.php';
require_once 'config/db.php';

// Analytics Counters
$presentCount = $conn->query("SELECT COUNT(*) FROM attendance WHERE status = 'Present'")->fetchColumn();
$absentCount = $conn->query("SELECT COUNT(*) FROM attendance WHERE status = 'Absent'")->fetchColumn();

// Search/Filter Logic
$query = "SELECT * FROM attendance WHERE 1=1";
$params = [];

if (!empty($_GET['search_name'])) {
    $query .= " AND student_name LIKE :name";
    $params['name'] = '%' . $_GET['search_name'] . '%';
}
if (!empty($_GET['filter_status'])) {
    $query .= " AND status = :status";
    $params['status'] = $_GET['filter_status'];
}

$query .= " ORDER BY log_date DESC";
$stmt = $conn->prepare($query);
$stmt->execute($params);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <span>User: <?php echo $_SESSION['user']; ?></span>
        <a href="logout.php">Logout</a>
    </header>

    <div class="stats">
        <div>Total Present: <?php echo $presentCount; ?></div>
        <div>Total Absent: <?php echo $absentCount; ?></div>
    </div>

    <?php 
    if (isset($_SESSION['error'])) {
        echo "<p class='error'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    ?>

    <div class="layout-grid">
        <form action="add_attendance.php" method="POST" class="add-form">
            <h3>Log Attendance</h3>
            <input type="text" name="student_id" placeholder="Student ID" required>
            <input type="text" name="student_name" placeholder="Student Name" required>
            <select name="status">
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="Tardy">Tardy</option>
            </select>
            <input type="date" name="log_date" required>
            <button type="submit">Save</button>
        </form>

        <div>
            <form method="GET" action="dashboard.php" class="filter-form">
                <input type="text" name="search_name" placeholder="Search Name">
                <select name="filter_status">
                    <option value="">All Statuses</option>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>
                <button type="submit">Filter</button>
            </form>

            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>ID</th><th>Name</th><th>Status</th><th>Date</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['student_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                        <td class="status-<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></td>
                        <td><?php echo $row['log_date']; ?></td>
                        <td>
                            <a href="edit_attendance.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit</a>
                            <a href="delete_attendance.php?id=<?php echo $row['id']; ?>" class="btn-delete">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
