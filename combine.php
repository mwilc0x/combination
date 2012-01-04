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
	$create_table = "CREATE TABLE IF NOT EXISTS file_data 
		  (
		  file_id mediumint NOT NULL PRIMARY KEY AUTO_INCREMENT,
		  file_name varchar(255) NOT NULL,
		  file_data TEXT NOT NULL,
		  content_type VARCHAR(255) NOT NULL DEFAULT 'text/plain',
		  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  INDEX fname_ind (file_name)
		  )";

	// Execute query
	mysql_query($create_table);
	$i = 0;
	$j = 0;
	$concat = "";
    	$text = "";
	$time = array();  	   
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
			//echo "$i was last modified:    ".  date ("Y-m-d h:i:s", filemtime($i));
			//echo "<p>\n\n</p>";
			$time[] = date ("Y-m-d H:i:s", filemtime($i));
      		}
    	}

        $concat = mysql_real_escape_string($concat);
	$text = mysql_real_escape_string($text);
	$text = base64_encode($text);
        $select = "SELECT * FROM file_data WHERE file_name = '$concat'";
        $result = mysql_query($select);

        if (!$result) {
                echo "Could not successfully run query ($select) from DB: " . mysql_error();
                exit;
        }

        if (mysql_num_rows($result) == 0) {
        	$insert="INSERT INTO file_data (file_name, file_data) VALUES ('$concat', '$text')";
        	
        	if (!mysql_query($insert)) {
          		die("Error: " . mysql_error(). "<p>\n\n</p>");
        	}
        	echo "<p>SUCCESSFULLY ADDED RECORD TO DB\n\n</p>";
      	}
	else {
		$row = mysql_fetch_assoc($result);
		foreach($time as $key => $date){
			/* if the date of modified file on disk is greater than the date when files were stored
			 * in the db, then we need to update the combo in the db
			*/
			if(strtotime($row["created_at"]) < strtotime($date)) {
				$insert = "UPDATE file_data SET file_data='$text' WHERE file_name='$concat'";
				if (!mysql_query($insert)) {
                        		die("Error: " . mysql_error(). "<p>\n\n</p>");
                		}
                		echo "<p>SUCCESSFULLY UPDATED RECORD TO DB\n\n</p>";
			}
		}
      		echo stripslashes(base64_decode($row["file_data"]));
	}
	mysql_close($my_conn); // don't close SQL connection until the end.
?>