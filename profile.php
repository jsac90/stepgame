<html>
<head>
<?php 

include 'session.php';

session_start(); // Starting Session

$errormessage = $_SESSION['steperror'];
$steps = $row_total['remaining_steps'];

if (isset($_POST['addsteps'])){
	$newsteps = $_POST['steps'];
	
	//sets negative and empty values to zero
	if ($newsteps <= '0' || !isset($_POST['addsteps']) ){
		$newsteps = 0;
	}
	
	$steps = $steps + $newsteps;
	mysqli_query($db,"update game_character set remaining_steps = $steps");
	$errormessage = "Added $newsteps to profile. Total steps = $steps";
};

$login_session = $_SESSION["login_user"];
$prevlogin = $_SESSION["prev_login"];
$email = $row_total['emailaddr'];
$createdate = $row_total['account_created']; 
$currentlogin = $row_total['last_login'];
$player_level = $row_total['level'];
$error = "";
$date = date('Y-m-d H:i:s');
$has_weapon = $row_total['has_weapon'];
$weapon_power = $row_total['weapon_power'];
$has_armor = $row_total['has_armor'];
$armor_power = $row_total['armor_power'];
$player_attack = $row_total['level'] + $row_total['weapon_power'];
$player_defense = $row_total['level'] + $row_total['armor_power'];
$hp = $row_total['current_hp'];
$max_hp = $row_total['max_hp'];
$player_exp = $row_total['player_exp'];


//var_dump($row_total);

if (!isset($_SESSION['login_user']) || $_SESSION['login_user'] == ''){
	header("location: index.php");
};

if (isset($_POST['trade'])){
	
	if ($steps >= 1000 && $hp < $max_hp ){
		$steps = $steps - 1000;
		$hp = $hp + 10;
		if ($hp >= $max_hp){
			$hp = $max_hp;
		}
		mysqli_query($db,"update game_character set remaining_steps = $steps");
		mysqli_query($db,"update game_character set current_hp = $hp");
		$errormessage = "1000 steps traded for 10 HP. Your HP is now $hp / $max_hp. You have $steps remaining";
	} else if ($steps < 1000) {
		$errormessage = "NOT ENOUGH STEPS TO TRADE. YOU NEED AT LEAST 1000 STEPS TO BE ABLE TO TRADE!";
	} else if ($hp == $max_hp) {
		$errormessage = "You are already at max health, you dummy!";
	} else {
		$errormessage = "UNKNOWN ERROR - NOTHING HAPPENED";
	}
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
Character HP: $hp / $max_hp
<br /><br />
You have $steps steps available.
<br /> <br />
Character EXP: $player_exp
<br /><br />
Total Current Attack Power: $player_attack
<br /><br />
Total Current Defense Power: $player_defense
<br /><br />
----------------------
<br />
Player Gear:
</br /> <br />
Weapon: <b>$has_weapon</b>
<br />
Weapon Power: $weapon_power
</br /> <br />
Armor: <b>$has_armor</b>
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




<form action="" method="post">
<?php
if($steps < 2000){

?>
Enter Additional Steps: <input name="steps" type="number" > 
<input name="addsteps" type="submit" value="Add Steps to Profile">
<br><br>
<?php

}
?>
</form>
<br/> <br/>

<form action="dungeon.php" method="post">
<input name="dungeon" type="submit" value="Enter the dungeon">
</form>

<form action="profile.php" method="post">
<input name="trade" type="submit" value="Exchange 1000 steps for 10 HP">
</form>

<br/> <br/>
<br/> <br/>

<form action="logout.php" method="post">
<input name="logout" type="submit" value="Log Out">
</form>

</center>


</body>
</html>