<?php
/**
 * Combine.php Database Configuration File
 *
 * MySQL Configuration variables are set, and a single shared connection is instantiated
 * here to avoid repeating ourselves later.
 */

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '#pass';
$db_name = 'files';

$my_conn = mysql_connect($db_host, $db_user, $db_pass);

if (!$my_conn) {
	die("MySQL Connection Error: " . mysql_error());
}

mysql_select_db($db_name, $my_conn);
?>
