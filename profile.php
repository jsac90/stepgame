<html>
<head>

<link rel="stylesheet" type="text/css" href="styles/stepgame.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
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
$missing_hp = $max_hp - $hp;
$tradereq = $missing_hp * 30;


//var_dump($row_total);

if (!isset($_SESSION['login_user']) || $_SESSION['login_user'] == ''){
	header("location: index.php");
};

if (isset($_POST['trade'])){
	
	if ($steps >= $tradereq && $hp < $max_hp ){
		$steps = $steps - $tradereq;
		$hp = $max_hp;
		if ($hp >= $max_hp){
			$hp = $max_hp;
		}
		mysqli_query($db,"update game_character set remaining_steps = $steps");
		mysqli_query($db,"update game_character set current_hp = $hp");
		$errormessage = "$tradereq steps traded. Your HP is now $hp / $max_hp. You have $steps steps remaining";
	} else if ($steps < $tradereq) {
		$errormessage = "NOT ENOUGH STEPS TO TRADE. Get more steps!";
	} else if ($hp == $max_hp) {
		$errormessage = "You are already at max health, you dummy!";
	} else {
		$errormessage = "UNKNOWN ERROR - NOTHING HAPPENED";
	}
};

if (isset($_POST['mantrade'])){
	$mansteps = $_POST['mansteps'];
	
	if ($mansteps <= $steps && (Ceil($mansteps / 30) <= ($max_hp - $hp))) {
		$steps = $steps - $mansteps;
		$hp = $hp + ceil($mansteps / 30);
		if ($hp >= $max_hp){
			$hp = $max_hp;
		}
		mysqli_query($db,"update game_character set remaining_steps = $steps");
		mysqli_query($db,"update game_character set current_hp = $hp");
		$errormessage = "$mansteps steps traded. Your HP is now $hp / $max_hp. You have $steps steps remaining";
	} else if ($steps < $mansteps) {
		$errormessage = "YOU DON'T HAVE THAT MANY STEPS TO TRADE! Try Again";
	}else if ((($mansteps / 30) > ($max_hp - $hp)) && ($mansteps >= $steps)) {
		$stepstopull = ($max_hp - $hp) * 30;
		$steps = $steps - $stepstopull;
		$hp = $max_hp;
		mysqli_query($db,"update game_character set remaining_steps = $steps");
		mysqli_query($db,"update game_character set current_hp = $max_hp");
		$errormessage = "You didn't need that much HP. Traded $stepstopull steps and topped you off. You're welcome.";
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
<img id="logo" src = "/images/stepgamelogo.png"/>
<br />
<h2><font color="red"><?php echo "$error"; ?></font></h2>
<br /> <br />
<b>Welcome Back!</b>
<br /> <br />

<form action="dungeon.php" method="post">
<input name="dungeon" type="submit" value="Enter the dungeon">
</form>
<br>

<form action="profile.php" method="post">
<?php 
if ($hp < $max_hp){ 
?> <input name="trade" type="submit" value="<?php echo "Exchange $tradereq steps for $missing_hp HP.";?>"> <?php } ?>
<BR><br><br>
</form>

<?php 
if ($hp < $max_hp){ 
?>
<form action="profile.php" method="post">
Manually trade steps for HP (cost: 30 steps for 1HP)<br><br>
How Many steps?   <input name="mansteps" type="number" > <br>
<input name="mantrade" type="submit" value="Trade Up!">
<br><br>
</form>
<?php } ?>

<br><br>

<div class="infobox">
<div id = "playerinfo" class="profilebox">
<h3>Player Info</h3>
<?php 

echo 
"
<br><br>
Player ID:  $login_session.
<br /><br />
Email Address: $email.
<br /><br />
Account Created: $createdate.
<br /><br />
Previous Session Start: $prevlogin. 
<br />
Current Session Start: $currentlogin.
<br /><br />
Current date: $date.
<br /><br />";

?>

</div>
<div id = "charinfo" class="profilebox">
<h3>Character Info</h3>
<?php
echo "
</br /> <br /> 
Character Level: $player_level
<br /><br />
Character HP: $hp / $max_hp
<br /><br />
Steps Available: $steps
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
";

?></div>

</div>

<form action="" method="post">
<?php
if($steps < 2000 || $steps < $tradereq){
?>
<BR><BR>
Enter Additional Steps: <input name="steps" type="number" > <br>
<input name="addsteps" type="submit" value="Add Steps to Profile">
<br><br>
<?php

}
?>
</form>
<br/> <br/>






<form action="logout.php" method="post">
<input name="logout" type="submit" value="Log Out">
</form>

</center>


</body>
</html>