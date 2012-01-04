<?php
/**
 * Database Connection Class
 *
 * This class is a singleton - it is instantiated once at the beginning of our 
 * script and shared among all instances of Combination. It wraps a MySQL 
 * connection and handles loading all of our configuration variables from a 
 * file.
 */

class DBConnection {
	public $config;
}
?>
