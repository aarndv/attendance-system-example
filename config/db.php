<?php
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "attendance_db";

$conn = mysql_connect($db_host, $db_user, $db_pass);

if (!$conn) {
    die("Connection failed: " . mysql_error());
}

if (!mysql_select_db($db_name, $conn)) {
    die("Database selection failed: " . mysql_error());
}
?>