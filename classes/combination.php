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
	public $files;
	public $content;
	public $content_type;

	public function __construct($conn) {
		$this->connection = $conn;
	}

	public function cache_and_combine($files=array())  {
		// Instantiate FileCombiner utility
		$combiner = new FileCombiner;

		if (empty($this->files) && empty($files)) {
			throw new Exception("No files to combine!");
			return false;
		} elseif (empty($this->files) && !empty($files)) {
			$this->files = $files;
		}

		// Pass the file array to FileCombiner
		$combiner->files = $this->files;
		$slug = $combiner->generate_slug();
		$existing = $this->get_cache_by_slug($slug);
	}

	public function get_cache_by_slug($slug) {
		$slug = mysql_real_escape_string($slug);
		$query = "SELECT * FROM file_data WHERE file_name = '$slug'";
		$result = mysql_query($query);

		if (!$result) {
			throw new Exception("Could not execute query: " . mysql_error());
		}

		// In PHP, 0 is falsy
		if (!mysql_num_rows($result)) {
			return null;
		} else {
			// Return all results as an array of associative arrays.
			$splat = array();

			while ($row = mysql_fetch_assoc($result)) {
				$splat[] = $row;
			}

			return $splat;
		}
	}
}
?>
