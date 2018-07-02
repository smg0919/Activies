<?php
define(DB_HOST, 'localhost');  
define(DB_USER, 'root');  
define(DB_PASS, 'symiao');  
define(DB_DATABASENAME, 'jj_huodong4');   
$conn = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("connect failed" . mysql_error());
mysql_select_db(DB_DATABASENAME, $conn);
mysql_query("SET NAMES 'utf8'"); 
?>