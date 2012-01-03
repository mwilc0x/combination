<?php

  /*
   * Author: Mike Wilcox
   * JS/CSS Combo Cache PHP Script
   * 
   * This program extracts JS/CSS files from a query string,
   * combining the file names into one string, and the text data
   * into one text field. It then places these two variables
   * into a database. The user can then retrieve the data and
   * display the data in their browser. This script is a work
   * in progress!
   *
   * TODO: Seperate functions into different files, started doing
   * it and broke the script. 
  */
  

  //extract variables from the query and calls func insertToDB() to insert data
  function insert() {

    $i = 0;
    $concat = "";
    $text = "";


    //grab each variable from $_GET array    
    foreach ($_GET['files'] as $key => $i) {

      $html = "http://";

      //see if it's a html page and if not, grab the file
      if(substr($i, 0, 7) == $html)
      {
         $text = $text. file_get_contents($i);
         $concat = $concat. $i;
         //echo $text;
      }
      else 
      {
        $text = $text. file_get_contents($i);
        //echo $text;
        $concat = $concat. $i;
      }
    }

    //$concat = mysql_real_escape_string($concat);
    $text = base64_encode($text); //encode the html data
    $con = connect();
    insertToDB($concat, $text, $con);
    return $concat;
   }
    

    //helper function to connect to DB
    function connect() {
      /* Connect and setup table in database. 
       * Tested with MySQL, insert appropriate variables
      */
      
      $con = mysql_connect("localhost","#my_user","#my_pass");
      if (!$con) 
      {
        die("<p>\nCould not connect: </p>" . mysql_error(). "<p>\n\n</p>");
      }
      
      return $con;
    }
    

    //sets up the table in DB and inserts appropriate data
    function insertToDB($files, $text, $con)
    {

      // Create table
      mysql_select_db("files", $con);
      
      //check if table is already created, if not, create a new table file_data
      $sql = "CREATE TABLE IF NOT EXISTS file_data 
              (
              fileId mediumint NOT NULL PRIMARY KEY AUTO_INCREMENT,
              fileNames varchar(255) NOT NULL,
			  fileData TEXT NOT NULL,
			  contentType VARCHAR(255) NOT NULL DEFAULT 'text/plain',
			  createdAt DATETIME DEFAULT NOW()
              )";

      // Execute query
      mysql_query($sql,$con);

      //SQL injection prevention
      $files = mysql_real_escape_string($files);
      $text = mysql_real_escape_string($text);

      //check if the row containing the fileNames is already in the table
      $query = "SELECT * FROM file_data WHERE fileNames = '$files'";
      $result = mysql_query($query);
      $user_data = mysql_fetch_row($result);
      
      if(empty($user_data)) {
      
        $sql="INSERT INTO file_data (fileNames, fileData) VALUES ('$files', '$text')";

        if (!mysql_query($sql,$con))
        {
          die("Error: " . mysql_error(). "<p>\n\n</p>");
        }

        echo "<p>SUCCESSFULLY ADDED RECORD TO DB\n\n</p>";
      }
      mysql_close($con);
    } 

    //called when we want the data back from the DB
    function retrieve($table_name, $fileName) {
      //echo $fileName;
      $con = connect();
      mysql_select_db("files", $con);
      

      //prevent SQL injection
      //$table_name = mysql_real_escape_string($table_name);
      $fileName = mysql_real_escape_string($fileName);      

      //procedure to query DB to retrieve row of data that we are looking for
      $sql = "SELECT * FROM $table_name WHERE fileNames = $fileName";
      $result = mysql_query($sql);
      //$result = mysql_real_escape_string($result);
      
      if (!$result) {
        echo "Could not successfully run query ($sql) from DB: " . mysql_error();
        exit;
      }

      if (mysql_num_rows($result) == 0) {
        echo "No rows found, nothing to print so am exiting";
        //exit;
      }

      $row = mysql_fetch_assoc($result);
      echo base64_decode($row["fileData"]);
      return base64_decode($row["fileData"]);

      //mysql_free_result($result);
      //mysql_close($con);
    } 


	function run() {
		//run script which right now returns the file text
		$table_name = 'file_data';
		$fileNamesString = insert();
		$fileNamesString = "'". $fileNamesString. "'";
		$fileText = retrieve($table_name, $fileNamesString);
		return $fileText;
	}

	echo run();
?>
