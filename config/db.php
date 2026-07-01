<?php
if (!function_exists('mysql_connect')) {
    function mysql_connect($host, $user, $pass) {
        global $mysql_connection_link;
        $mysql_connection_link = mysqli_connect($host, $user, $pass);
        return $mysql_connection_link;
    }

    function mysql_select_db($db_name, $link = null) {
        global $mysql_connection_link;
        $active_link = $link ? $link : $mysql_connection_link;
        return mysqli_select_db($active_link, $db_name);
    }

    function mysql_query($query, $link = null) {
        global $mysql_connection_link;
        $active_link = $link ? $link : $mysql_connection_link;
        return mysqli_query($active_link, $query);
    }

    function mysql_fetch_assoc($result) {
        return mysqli_fetch_assoc($result);
    }

    function mysql_error($link = null) {
        global $mysql_connection_link;
        $active_link = $link ? $link : $mysql_connection_link;
        if ($active_link) {
            return mysqli_error($active_link);
        }
        return mysqli_connect_error();
    }
}

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