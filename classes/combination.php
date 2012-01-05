<?php
/**
 * Combination Class
 *
 * This is our ORM (Object Relational Mapper). One instance of Combination 
 * corresponds to one row in the database. We use this class to abstract our 
 * SQL operations, so you're never directly writing SQL queries. Instead, you 
 * instantiate a new Combination object, and either create and save, or search 
 * and return its contents.
 */

// This class depends on the FileCombiner utility class.
require_once("file_combiner.php");

class Combination {
	private $connection; // expects an instance of DBConnection
	private $file_combiner;
	public $files;
	public $content;
	public $content_type;

	public function __construct($conn) {
		$this->connection = $conn;
		$this->file_combiner = new FileCombiner;
	}

	public function cache_and_combine()  {
	}
}
?>
