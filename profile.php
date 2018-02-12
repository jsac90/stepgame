<html>
<head>
<?php 

include 'session.php';

session_start(); // Starting Session

$login_session = $_SESSION["login_user"];
$prevlogin = $_SESSION["prev_login"];
$email = $row_total['emailaddr'];
$createdate = $row_total['account_created']; 
$currentlogin = $row_total['last_login'];
$errormessage = $_SESSION['steperror'];
$player_level = $row_total['level'];
$error = "";
$date = date('Y-m-d H:i:s');
$weapon_name = $row_total['weapon_name'];
$weapon_power = $row_total['weapon_power'];
$armor_name = $row_total['armor_name'];
$armor_power = $row_total['armor_power'];
$player_attack = $row_total['level'] + $row_total['weapon_power'];
$player_defense = $row_total['level'] + $row_total['armor_power'];

//var_dump($row_total);

if (!isset($_SESSION['login_user']) || $_SESSION['login_user'] == ''){
	header("location: index.php");
};

if(isset($errormessage)){
	$error = $errormessage;
}else{
	$error = "";
};


mysqli_close($db); // Closing Connection

?>

<title>Your Home Page - <?php echo"$email" ?></title>
</head>
<body>
<center>

<img src="images/flip.png">
<br />
<br />
<h2><font color="red"><?php echo "$error"; ?></font></h2>
<br /> <br />
<b>Welcome!</b>
<br /> <br />

<?php 

echo 
"
Your player ID is $login_session.
<br /><br />
Your email address is $email.
<br /><br />
Your account was created on $createdate.
<br /><br />
Your previous session started at $prevlogin. 
<br />
Your current session started on $currentlogin.
<br /><br />
Current date is $date.
<br /><br />
----------------------
<br />
Player Data:
</br /> <br /> 
Character Level: $player_level
<br /><br />
Total Current Attack Power: $player_attack
<br /><br />
Total Current Defense Power: $player_defense
<br /><br />
----------------------
<br />
Player Gear:
</br /> <br />
Weapon: <b>$weapon_name</b>
<br />
Weapon Power: $weapon_power
</br /> <br />
Armor: <b>$armor_name</b>
<br />
Armor Power: $armor_power
<br>
----------------------
<br /><br />
<br /><br />
HAVE FUN!
";

?>

<br /><br /><br /><br />





<form action="dungeon.php" method="post">
Enter Today's Steps: <input name="steps" type="number" >
<input name="dungeon" type="submit" value="Enter the dungeon">
</form>
<br/> <br/><br/> <br/>
<form action="logout.php" method="post">
<input name="logout" type="submit" value="Log Out">
</form>

</center>


</body>
</html>