<?php
session_start();
require_once 'config/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $user]);
    $foundUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($foundUser && password_verify($pass, $foundUser['password'])) {
        $_SESSION['user'] = $foundUser['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="login-page">
    <div class="login-card">
        <h2>System Login</h2>
        <?php if($error): ?> <p class="error"><?php echo $error; ?></p> <?php endif; ?>
        <form method="POST" action="index.php">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" class="btn-pixel">Login</button>
        </form>
    </div>
</body>
</html>