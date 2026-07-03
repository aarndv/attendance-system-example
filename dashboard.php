<?php
require_once 'auth_check.php';
require_once 'config/db.php';

// Global Analytics Counters (ignoring filters)
$res_present = mysql_query("SELECT COUNT(*) as total FROM attendance WHERE status = 'Present'", $conn);
$row_present = mysql_fetch_array($res_present);
$present_count = $row_present ? $row_present['total'] : 0;

$res_absent = mysql_query("SELECT COUNT(*) as total FROM attendance WHERE status = 'Absent'", $conn);
$row_absent = mysql_fetch_array($res_absent);
$absent_count = $row_absent ? $row_absent['total'] : 0;

$res_tardy = mysql_query("SELECT COUNT(*) as total FROM attendance WHERE status = 'Tardy'", $conn);
$row_tardy = mysql_fetch_array($res_tardy);
$tardy_count = $row_tardy ? $row_tardy['total'] : 0;

// Search/Filter logic using 1=1 trick
$query = "SELECT * FROM attendance WHERE 1=1";

if (isset($_GET['search_name']) && strlen($_GET['search_name']) > 0) {
    $search_name = $_GET['search_name'];
    $query .= " AND student_name LIKE '%$search_name%'";
}

if (isset($_GET['filter_status']) && strlen($_GET['filter_status']) > 0) {
    $filter_status = $_GET['filter_status'];
    $query .= " AND status = '$filter_status'";
}

$query .= " ORDER BY log_date DESC";
$result = mysql_query($query, $conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="header-bar">
            <span>Welcome, <strong><?php echo $_SESSION['user']; ?></strong></span>
            <a href="logout.php" class="btn btn-cancel">Logout</a>
        </div>

        <div class="stats">
            <div class="stat-card">Total Present: <strong><?php echo $present_count; ?></strong></div>
            <div class="stat-card">Total Absent: <strong><?php echo $absent_count; ?></strong></div>
            <div class="stat-card">Total Tardy: <strong><?php echo $tardy_count; ?></strong></div>
        </div>

        <?php 
        if (isset($_SESSION['error'])) {
            echo "<div class='error-banner'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        ?>

        <div class="layout-grid">
            <div class="form-box">
                <h3>Log Attendance</h3>
                <form action="add_attendance.php" method="POST">
                    <div>
                        <label for="student_id">Student ID:</label>
                        <input type="text" id="student_id" name="student_id" required>
                    </div>
                    <div>
                        <label for="student_name">Student Name:</label>
                        <input type="text" id="student_name" name="student_name" required>
                    </div>
                    <div>
                        <label for="status">Status:</label>
                        <select id="status" name="status">
                            <option value="Present">Present</option>
                            <option value="Absent">Absent</option>
                            <option value="Tardy">Tardy</option>
                        </select>
                    </div>
                    <div>
                        <label for="log_date">Date:</label>
                        <input type="date" id="log_date" name="log_date" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Save Record</button>
                    </div>
                </form>
            </div>

            <div>
                <h3>Search and Filters</h3>
                <form method="GET" action="dashboard.php" class="filter-form">
                    <input type="text" name="search_name" placeholder="Search Name" value="<?php echo isset($_GET['search_name']) ? $_GET['search_name'] : ''; ?>">
                    <select name="filter_status">
                        <option value="">All Statuses</option>
                        <option value="Present" <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] === 'Present') echo 'selected'; ?>>Present</option>
                        <option value="Absent" <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] === 'Absent') echo 'selected'; ?>>Absent</option>
                        <option value="Tardy" <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] === 'Tardy') echo 'selected'; ?>>Tardy</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>

                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Status</th>
                            <th>Log Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result): ?>
                            <?php while ($row = mysql_fetch_array($result)): ?>
                            <tr>
                                <td><?php echo $row['student_id']; ?></td>
                                <td><?php echo $row['student_name']; ?></td>
                                <td class="status-<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></td>
                                <td><?php echo $row['log_date']; ?></td>
                                <td>
                                    <a href="edit_attendance.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
                                    <a href="delete_attendance.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
