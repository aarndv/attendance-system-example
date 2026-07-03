<?php
if (!defined('MYSQL_ASSOC')) define('MYSQL_ASSOC', 1);
if (!defined('MYSQL_NUM')) define('MYSQL_NUM', 2);
if (!defined('MYSQL_BOTH')) define('MYSQL_BOTH', 3);

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

    function mysql_fetch_array($result, $result_type = MYSQL_BOTH) {
        return mysqli_fetch_array($result, $result_type);
    }

    function mysql_result($result, $row, $field = 0) {
        if ($result) {
            if (mysqli_data_seek($result, $row)) {
                $data = mysqli_fetch_array($result, MYSQL_BOTH);
                if (isset($data[$field])) {
                    return $data[$field];
                }
            }
        }
        return false;
    }

    function mysql_affected_rows($link = null) {
        global $mysql_connection_link;
        $active_link = $link ? $link : $mysql_connection_link;
        return mysqli_affected_rows($active_link);
    }

    function mysql_num_rows($result) {
        return mysqli_num_rows($result);
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