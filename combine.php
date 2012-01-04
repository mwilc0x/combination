<?php

  /*
   * Authors: Mike Green, Mike Wilcox
   * JS/CSS Combo Cache PHP Script
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
	$text = mysql_real_escape_string($text);
        $sql = "SELECT * FROM file_data WHERE file_name = '$concat'";
        $result = mysql_query($sql);

        if (!$result) {
                echo "Could not successfully run query ($sql) from DB: " . mysql_error();
                exit;
        }

        if (mysql_num_rows($result) == 0) {
    		$text = base64_encode($text); //encode the html data
        	$sql="INSERT INTO file_data (file_name, file_data) VALUES ('$concat', '$text')";
        	
        	if (!mysql_query($sql)) {
          		die("Error: " . mysql_error(). "<p>\n\n</p>");
        	}
        	echo "<p>SUCCESSFULLY ADDED RECORD TO DB\n\n</p>";
      	}
	else {
		//SQL injection prevention, run queries to find row of data  
      		$sql = "SELECT * FROM file_data WHERE file_name = '$concat'";
      		$result = mysql_query($sql);
      
      		if (!$result) {
        		echo "Could not successfully run query ($sql) from DB: " . mysql_error();
        		exit;
      		}

      		$row = mysql_fetch_assoc($result);
      		echo stripslashes(base64_decode($row["file_data"]));
	}
	mysql_close($my_conn); // don't close SQL connection until the end.
?>