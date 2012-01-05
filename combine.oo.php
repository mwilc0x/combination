<?php
/**
 * Combine.php, Object Oriented Edition
 */

// Load our class dependencies
//
// Some info about includes:
// require_once :: loads a file and exits if it fails. it will not load the same file twice
// require      :: exits on failure, but can load the same file multiple times
// include      :: will load a file many times and doesn't care if it fails
require_once("classes/db_connection.php");
require_once("classes/file_combiner.php");
require_once("classes/combination.php");

// Shared database connection abstraction. Loads values from an INI file, 
// establishes a connection, and selects the right database.
$conn = new DBConnection("config.ini");

// Initialize our combination abstraction.
$combination = new Combination($conn);
?>
