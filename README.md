combine.php
-----------
-----------

A simple PHP script to serve as an introductory learning lesson for implementing a php script that caches js/css file combinations in a MySQL database. 

The script gathers file names from the end of a user inputted URL query and extracts the text of those files from the URL location or from the location on disk. It then stores the data along with the concatenated string of the file names into a row in a MySQL database. Then, whenever the contents of those files are needed in an html document, the script simply queries the db first to see if those files are located in the db. If they are, they are retrieved and used in the HTML document.
