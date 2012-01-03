combine.php
-----------
-----------

A simple PHP script to serve as an introductory learning lesson for implementing JS/CSS file combination caching in a MySQL database. 

The script gathers file names from the end of a user inputted URL query and extracts the text of those files from the URL location or from the location on disk. It then stores the data along with the concatenated string of the file names into a row in a MySQL database. Then, whenever the contents of those file combinations are needed in an HTML document, the script simply queries the db first to see if those files are located in the db. If they are, they are retrieved and used in the HTML document. If not, the contents and file names are stored in the db.
