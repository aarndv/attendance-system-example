<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'auth_check.php';
require_once 'config/db.php';

// Establish selected date (defaults to today, cannot be in the future)
$selected_date = isset($_GET['filter_date']) ? $_GET['filter_date'] : date('Y-m-d');
if ($selected_date > date('Y-m-d')) {
    $selected_date = date('Y-m-d');
}

// 1. Instant Status Toggle (cycling Present -> Absent -> Tardy -> Present)
if (isset($_GET['toggle_id'])) {
    $toggle_id = $_GET['toggle_id'];
    $toggle_query = "SELECT status FROM attendance WHERE id = '$toggle_id'";
    $toggle_res = mysql_query($toggle_query, $conn);
    if ($toggle_res && $toggle_row = mysql_fetch_array($toggle_res)) {
        $current_status = $toggle_row['status'];
        $next_status = 'Present';
        if ($current_status === 'Present') {
            $next_status = 'Absent';
        } elseif ($current_status === 'Absent') {
            $next_status = 'Tardy';
        }
        
        $update_sql = "UPDATE attendance SET status = '$next_status' WHERE id = '$toggle_id'";
        mysql_query($update_sql, $conn);
    }
    
    // Redirect back to preserve search/filter parameters
    $redirect_url = "dashboard.php";
    $params = array();
    $params[] = "filter_date=" . urlencode($selected_date);
    if (isset($_GET['filter_class'])) $params[] = "filter_class=" . urlencode($_GET['filter_class']);
    if (isset($_GET['search_name'])) $params[] = "search_name=" . urlencode($_GET['search_name']);
    if (isset($_GET['filter_status'])) $params[] = "filter_status=" . urlencode($_GET['filter_status']);
    header("Location: " . $redirect_url . "?" . implode("&", $params));
    exit;
}

// 2. Auto-generate two classes of 30 students each for the selected date if they don't exist yet
$check_date_query = "SELECT COUNT(*) as count FROM attendance WHERE log_date = '$selected_date'";
$check_date_res = mysql_query($check_date_query, $conn);
$date_count = 0;
if ($check_date_res && $row_dc = mysql_fetch_array($check_date_res)) {
    $date_count = $row_dc['count'];
}

if ($date_count == 0) {
    $class_a_names = array(
        "Alice Smith", "Aaron Johnson", "Abigail Williams", "Alexander Brown", "Amelia Jones",
        "Andrew Garcia", "Anna Miller", "Anthony Davis", "Aria Rodriguez", "Asher Martinez",
        "Audrey Hernandez", "Austin Lopez", "Ava Gonzalez", "Avery Wilson", "Benjamin Anderson",
        "Brooklyn Thomas", "Caleb Taylor", "Chloe Moore", "Christian Jackson", "Christopher Martin",
        "Claire Lee", "Connor Perez", "Daniel Thompson", "David White", "Delilah Harris",
        "Dominic Sanchez", "Dylan Clark", "Eli Ramirez", "Elizabeth Lewis", "Ella Robinson"
    );
    
    $class_b_names = array(
        "Emma Walker", "Ethan Young", "Evelyn Allen", "Ezra King", "Grace Wright",
        "Hailey Scott", "Hannah Torres", "Henry Nguyen", "Hudson Hill", "Ian Flores",
        "Isaac Green", "Isabella Adams", "Jack Nelson", "Jackson Baker", "Jacob Hall",
        "James Rivera", "Jaxon Campbell", "Jeremiah Mitchell", "John Carter", "Jonathan Roberts",
        "Joseph Gomez", "Joshua Phillips", "Josiah Evans", "Julian Turner", "Julianna Diaz",
        "Kaylee Parker", "Kennedy Cruz", "Landon Edwards", "Layla Collins", "Leo Reyes"
    );
    
    $statuses = array("Present", "Present", "Present", "Present", "Present", "Absent", "Tardy");
    
    // Seed Class A
    for ($i = 0; $i < 30; $i++) {
        $student_id = "A-" . sprintf("%02d", $i + 1);
        $student_name = $class_a_names[$i];
        $class_section = "Class A";
        $status = $statuses[($i + strlen($student_name) + strtotime($selected_date)) % count($statuses)];
        
        $insert_sql = "INSERT INTO attendance (student_id, student_name, class_section, status, log_date) 
                       VALUES ('$student_id', '$student_name', '$class_section', '$status', '$selected_date')";
        mysql_query($insert_sql, $conn);
    }
    
    // Seed Class B
    for ($i = 0; $i < 30; $i++) {
        $student_id = "B-" . sprintf("%02d", $i + 1);
        $student_name = $class_b_names[$i];
        $class_section = "Class B";
        $status = $statuses[($i + strlen($student_name) + 3 + strtotime($selected_date)) % count($statuses)];
        
        $insert_sql = "INSERT INTO attendance (student_id, student_name, class_section, status, log_date) 
                       VALUES ('$student_id', '$student_name', '$class_section', '$status', '$selected_date')";
        mysql_query($insert_sql, $conn);
    }
}

// 3. Global Analytics Counters (counts overall database totals)
$res_present = mysql_query("SELECT COUNT(*) as total FROM attendance WHERE status = 'Present'", $conn);
$row_present = mysql_fetch_array($res_present);
$present_count = $row_present ? $row_present['total'] : 0;

$res_absent = mysql_query("SELECT COUNT(*) as total FROM attendance WHERE status = 'Absent'", $conn);
$row_absent = mysql_fetch_array($res_absent);
$absent_count = $row_absent ? $row_absent['total'] : 0;

$res_tardy = mysql_query("SELECT COUNT(*) as total FROM attendance WHERE status = 'Tardy'", $conn);
$row_tardy = mysql_fetch_array($res_tardy);
$tardy_count = $row_tardy ? $row_tardy['total'] : 0;

// 4. Search/Filter query construction
$query = "SELECT * FROM attendance WHERE log_date = '$selected_date'";

if (isset($_GET['filter_class']) && strlen($_GET['filter_class']) > 0) {
    $filter_class = $_GET['filter_class'];
    $query .= " AND class_section = '$filter_class'";
}

if (isset($_GET['search_name']) && strlen($_GET['search_name']) > 0) {
    $search_name = $_GET['search_name'];
    $query .= " AND student_name LIKE '%$search_name%'";
}

if (isset($_GET['filter_status']) && strlen($_GET['filter_status']) > 0) {
    $filter_status = $_GET['filter_status'];
    $query .= " AND status = '$filter_status'";
}

$query .= " ORDER BY class_section ASC, student_id ASC";
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
                        <label for="class_section">Class Section:</label>
                        <select id="class_section" name="class_section">
                            <option value="Class A">Class A</option>
                            <option value="Class B">Class B</option>
                        </select>
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
                        <input type="date" id="log_date" name="log_date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Save Record</button>
                    </div>
                </form>
            </div>

            <div>
                <h3>Search and Filters</h3>
                <form method="GET" action="dashboard.php" class="filter-form">
                    <div>
                        <label>Date Scope:</label>
                        <input type="date" name="filter_date" value="<?php echo $selected_date; ?>" max="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div>
                        <label>Class Section:</label>
                        <select name="filter_class">
                            <option value="">All Classes</option>
                            <option value="Class A" <?php if(isset($_GET['filter_class']) && $_GET['filter_class'] === 'Class A') echo 'selected'; ?>>Class A</option>
                            <option value="Class B" <?php if(isset($_GET['filter_class']) && $_GET['filter_class'] === 'Class B') echo 'selected'; ?>>Class B</option>
                        </select>
                    </div>
                    <div>
                        <label>Student Name:</label>
                        <input type="text" name="search_name" placeholder="Search Name" value="<?php echo isset($_GET['search_name']) ? $_GET['search_name'] : ''; ?>">
                    </div>
                    <div>
                        <label>Status:</label>
                        <select name="filter_status">
                            <option value="">All Statuses</option>
                            <option value="Present" <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] === 'Present') echo 'selected'; ?>>Present</option>
                            <option value="Absent" <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] === 'Absent') echo 'selected'; ?>>Absent</option>
                            <option value="Tardy" <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] === 'Tardy') echo 'selected'; ?>>Tardy</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Filter / Refresh</button>
                </form>

                <table class="attendance-table" style="margin-top: 20px;">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Status (Click to toggle)</th>
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
                                <td><?php echo $row['class_section']; ?></td>
                                <td class="status-<?php echo strtolower($row['status']); ?>">
                                    <a href="dashboard.php?toggle_id=<?php echo $row['id']; ?><?php 
                                        echo '&filter_date=' . urlencode($selected_date);
                                        if (isset($_GET['filter_class'])) echo '&filter_class=' . urlencode($_GET['filter_class']);
                                        if (isset($_GET['search_name'])) echo '&search_name=' . urlencode($_GET['search_name']);
                                        if (isset($_GET['filter_status'])) echo '&filter_status=' . urlencode($_GET['filter_status']);
                                    ?>" class="status-link"><?php echo $row['status']; ?></a>
                                </td>
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
