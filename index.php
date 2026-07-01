<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

require_once 'config/db.php';

$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if (strlen($user) === 0 || strlen($pass) === 0) {
        $_SESSION['error'] = "Username and password are required.";
        header("Location: index.php");
        exit;
    } else {
        $query = "SELECT * FROM users WHERE username = '$user'";
        $result = mysql_query($query, $conn);

        if (!$result) {
            $_SESSION['error'] = "A database error occurred.";
            header("Location: index.php");
            exit;
        }

        $row = mysql_fetch_assoc($result);

        if ($row && $pass === $row['password']) {
            $_SESSION['user'] = $row['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: index.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h2>Admin Login</h2>
            <?php if ($error !== ''): ?>
                <div class="error-banner"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="index.php" method="POST">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
