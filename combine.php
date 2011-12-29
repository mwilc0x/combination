<?php

  /*
   * Author: Mike Wilcox
   * JS/CSS Database Cache PHP Script
   * 
   * This program extracts JS/CSS files from a user input query
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
    echo $concat. ' is the concatenated string';


    //$concat = mysql_real_escape_string($concat);
    $text = base64_encode($text); //encode the html data
    $con = connect();
    insertToDB($concat, $text, $con);
  }
    

    //helper function to connect to DB
    function connect() {
      /* Connect and setup table in database. 
       * Tested with MySQL, insert appropriate variables
      */
      
      $con = mysql_connect("localhost","#my_username","#my_password");
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
      $sql = "CREATE TABLE file_data
              (
              fileId mediumint NOT NULL PRIMARY KEY AUTO_INCREMENT,
              fileNames varchar(255) NOT NULL,
              fileData TEXT NOT NULL
              )";

      // Execute query
      mysql_query($sql,$con);

      $concat = mysql_real_escape_string($concat);
      $sql="INSERT INTO file_data (fileNames, fileData) VALUES ('$concat', '$text')";

      if (!mysql_query($sql,$con))
      {
        die("Error: " . mysql_error(). "<p>\n\n</p>");
      }

      echo "<p>SUCCESSFULLY ADDED RECORD TO DB\n\n</p>";

      mysql_close($con);
  }

    //called when we want the data back from the DB
    function retrieve($table_name) {
      $con = connect();
      mysql_select_db("files", $con);

      $result = mysql_query("SELECT * FROM $table_name");

      while($row = mysql_fetch_array($result))
      {
        $fileData = base64_decode($row['fileData']); //decode the html data and display
        echo $row[$fileNames] . " " . $fileData;
        echo "<br />";
      }

      mysql_close($con);

    } 

  //run script
  insert();
  $table_name = 'file_data';
  retrieve($table_name);
?>
