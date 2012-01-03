<?php

  /*
   * Authors: Mike Green, Mike Wilcox
   * JS/CSS Combo Cache Script
   * 
   * This program extracts JS/CSS files from a query string,
   * combining the file names into one string, and the text data
   * into one text field. It then places these two variables
   * into a database. The user can then retrieve the data and
   * display the data in their browser. This script is a work
   * in progress!
   *
   */
  
// Load MySQL config and initialize connection
require_once('config.php');


function bootstrap() {
	//check if table is already created, if not, create a new table file_data
	$sql = "CREATE TABLE IF NOT EXISTS file_data 
		  (
		  file_id mediumint NOT NULL PRIMARY KEY AUTO_INCREMENT,
		  file_name varchar(255) NOT NULL,
		  file_data TEXT NOT NULL,
		  content_type VARCHAR(255) NOT NULL DEFAULT 'text/plain',
		  created_at DATETIME DEFAULT '0000-00-00 00:00:00'
		  )";

	// Execute query
	mysql_query($sql);
}

  //extract variables from the query and calls func insertToDB() to insert data
  function insert() {
	$i = 0;
	$concat = "";
    	$text = "";
    	   
    	/* Grab variables from the $_GET array
    	 * Check if variable starts with 'http://'
    	 * If so, it's a file from the web else 
    	 * it's a file stored on disk.
    	*/
    	foreach ($_GET['files'] as $key => $i) {
		$html = "http://";
      		if(substr($i, 0, 7) == $html) {
         		$text = $text. file_get_contents($i);
         		$concat = $concat. $i;
      		}
      		else {
        		$text = $text. file_get_contents($i);
        		$concat = $concat. $i;
      		}
    	}

        $concat = mysql_real_escape_string($concat);
        $sql = "SELECT * FROM file_data WHERE file_name = '$concat'";
        $result = mysql_query($sql);

        if (!$result) {
                echo "Could not successfully run query ($sql) from DB: " . mysql_error();
                exit;
        }

        if (mysql_num_rows($result) == 0) {
    		$text = base64_encode($text); //encode the html data
    		insertToDB($concat, $text);
    		return $concat;
	} else {
		return $concat;
	}
   }
    
  //sets up the table in DB and inserts appropriate data
  function insertToDB($files, $text) {
      	//SQL injection prevention, store data in db row
      	$files = mysql_real_escape_string($files);
      	$text = mysql_real_escape_string($text);
      	$query = "SELECT * FROM file_data WHERE file_name = '$files'";
      	$result = mysql_query($query);
      	$user_data = mysql_fetch_row($result);
      
      	if(empty($user_data)) {
        	$sql="INSERT INTO file_data (file_name, file_data) VALUES ('$files', '$text')";
        	
        	if (!mysql_query($sql)) {
          		die("Error: " . mysql_error(). "<p>\n\n</p>");
        	}
        	echo "<p>SUCCESSFULLY ADDED RECORD TO DB\n\n</p>";
      	}
  } 

  //called when we want the data back from the DB
  function retrieve($table_name, $fileName) {
      	//SQL injection prevention, run queries to find row of data
      	$table_name = mysql_real_escape_string($table_name);
      	$fileName = mysql_real_escape_string($fileName);  
      	$sql = "SELECT * FROM $table_name WHERE file_name = '$fileName'";
      	$result = mysql_query($sql);
      
      	if (!$result) {
        	echo "Could not successfully run query ($sql) from DB: " . mysql_error();
        	exit;
      	}

      	if (mysql_num_rows($result) == 0) {
        	echo "No rows found, nothing to print so am exiting";
      	}

      	$row = mysql_fetch_assoc($result);
      	return base64_decode($row["file_data"]);
  } 


	function run() {
		bootstrap();
		//run script which right now returns the file text
		$table_name = 'file_data';
		$fileNamesString = insert();
		$fileText = retrieve($table_name, $fileNamesString);
		return $fileText;
	}

	echo run();
	mysql_close($my_conn); // don't close SQL connection until the end.
?>
