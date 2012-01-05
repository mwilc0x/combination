<?php
/**
 * Database Connection Class
 *
 * This class is a singleton - it is instantiated once at the beginning of our 
 * script and shared among all instances of Combination. It wraps a MySQL 
 * connection and handles loading all of our configuration variables from an 
 * INI formatted file.
 */

class DBConnection {
	private $config;
	public $connection;

	public __construct($file) {
		if ($conf = parse_ini_file($file)) {
			$this->config = parse_ini_file($file);
			$this->connection = mysql_connect($this->config['host'], $this->config['user'], $this->config['password']);
			mysql_select_db($this->config['database']);
		} else {
			throw new Exception("Could not load database configuration file: $file");
		}
	}
}
?>
