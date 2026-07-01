<?php
$host = "localhost";
$dbname = "attendance_db";
$username = "root";
$password = "";

try {
    // Crucial Update: Added ;charset=utf8mb4 to the connection string
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optional but highly recommended: Force clean string fetches
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
