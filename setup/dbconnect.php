<?php

global $db;
$db = mysqli_connect('localhost','DATABASEUSER','DATABASEPW','DATABASESCHEMA');
if(mysqli_connect_errno($db)){
	$message = "Failed to connect to db";
}else{
	$message = "Connected to database successfully";
}
?>