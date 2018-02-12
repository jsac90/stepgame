<?php
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
require 'setup/dbconnect.php';
// Selecting Database
session_start();// Starting Session

$loginsession = $_SESSION["login_user"];

//one perfect query to rule them all
$totalcustquery = mysqli_query($db,"
select 
e.player_id, e.created as account_created, e.last_login, 
c.emailaddr, 
g.level, g.remaining_steps, g.max_hp, g.current_hp, g.player_exp,
g.weapon_power, g.has_weapon,
g.armor_power, g.has_armor
from
entrance e 
inner join cust_data c on e.player_id = c.player_id
inner join game_character g on e.player_id = g.player_id
where e.player_id = $loginsession
;
");

$row_total = mysqli_fetch_assoc($totalcustquery); //gets data from query

//old queries - keep until I know the other thing is working 

//$query = mysqli_query($db,"select * from entrance where player_id = '$loginsession'");
//$row = mysqli_fetch_assoc($query); //gets data from query

//$charquery = mysqli_query($db,"select * from game_character where player_id = '$loginsession'");
//$row_char = mysqli_fetch_assoc($charquery); //gets data from query

//$custquery = mysqli_query($db,"select * from cust_data where player_id = '$loginsession'");
//$row_cust = mysqli_fetch_assoc($custquery); //gets data from query

?>