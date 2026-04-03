<?php

	$host = "localhost";
	$dbname = "project";
	$user = "postgres";
	$password = "postgres123";
	

	$con=pg_connect("host=$host dbname=$dbname user=$user password=$password") or die("Could not Connect to  Database!");
	
?>
	
	
