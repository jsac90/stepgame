<?php
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
require 'setup/dbconnect.php';
// Selecting Database
session_start();// Starting Session

$loginsession = $_SESSION["login_user"];

$query = mysqli_query($db,"select * from entrance where player_id = '$loginsession'");
$row = mysqli_fetch_assoc($query); //gets data from query

$charquery = mysqli_query($db,"select * from game_character where player_id = '$loginsession'");
$row_char = mysqli_fetch_assoc($charquery); //gets data from query

$custquery = mysqli_query($db,"select * from cust_data where player_id = '$loginsession'");
$row_cust = mysqli_fetch_assoc($custquery); //gets data from query

?>