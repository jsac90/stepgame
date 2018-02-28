<html>
<head>

<link rel="stylesheet" type="text/css" href="styles/stepgame.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php 

include 'session.php';

session_start(); // Starting Session

$login_session = $_SESSION["login_user"];

//make sure only coming from an approved page. 
$icamefrom = $_SERVER['HTTP_REFERER'];

//Setup
//level and exp
$level = $row_total['level']; //pull from db
$next_level = $level + 1;

$player_exp = $row_total['player_exp'];
$next_level_exp = (((37.5*(($next_level)**2))+(87.5*($next_level)))-124);
$remaining_exp = $next_level_exp - $player_exp;
//weapons and armor
$weapon_power = $row_total['weapon_power'];
$armor_power = $row_total['armor_power'];
$has_weapon = $row_total['has_weapon'];
$has_armor = $row_total['has_armor'];
$player_attack = $level + $weapon_power ; //depends on gear
$player_defense = $level + $armor_power; //depends on gear
//HP stuff
$hp = $row_total['current_hp'];
$max_hp = $row_total['max_hp'];
$existing_steps = $row_total['remaining_steps']; 


$steps = $row_total['remaining_steps'];
$og_steps = $steps;
$_SESSION['og_steps'] = $og_steps;

if(isset($_POST['logout'])){
	header("location: logout.php");
	exit();
}else if (isset($_POST['profile'])) {
	header("location: profile.php");
	exit();
};

if (!isset($_SESSION['login_user']) || $_SESSION['login_user'] == ''){
	header("location: index.php");
	exit();
} elseif ((stripos($icamefrom,'/profile.php')== FALSE) && (stripos($icamefrom,'/dungeon.php')== FALSE)){
	//your URL here
	header("location: profile.php");
	exit();
}

//game code starts here

//encounter variable
$encounter = '';

//determines what button does
//if steps are positive, reloads the page
//if steps are zero or negative, destroys the session
//eventually want to send them back to the profile page
if(isset($_POST['next']) && $steps > 0 && $hp > 0 ){
	$_SESSION['steperror'] = "";
	header("location: dungeon.php");
	exit();
}else if (isset($_POST['next'])) {
	$_SESSION['steperror'] = "";
	header("location: profile.php");
	exit();
};

?>

<title>Stepgame - The Dungeon</title>
</head>
<body>
<center>
<img id="logo" src="images/stepgamelogo.png" >
<h1> Welcome to THE DUNGEON! </h1>
<br><br>
<?php 


//set the stage...
echo "You have $steps steps to start with...<br>";
echo "You are level $level <br>";
echo "You have $player_exp total exp. In $remaining_exp exp you will reach level $next_level ($next_level_exp exp total) <br><br>";
echo "You have $hp / $max_hp hit points <br>";
echo "You have $player_attack attack and $player_defense defense <br><br>";
if($has_weapon == 1){
	echo "You have a weapon. It has $weapon_power power <br>";
} else{
	echo "You do not have a weapon. Just your fists! <br>";
}
if($has_armor == 1){
	echo "You have some armor. It has $armor_power power <br>";
} else {
	echo "You do not have any armor. Just your clothes! <br>";
}


echo "<br>-----------------<br><br>";

if ($steps > 0 && $hp > 0){
	
	//rolls and deducts steps until encounter occurs. 
	while ($encounter != 3 && $steps > 0 && $hp > 0){
		$encounter = rand(1,200);
		$steps = $steps - 1;
	}
	
	//encounter happens here
	if ($steps > 0 && $hp > 0) { 
		$steps = $steps - 1; //deducts the step
		$steps_taken = $og_steps - $steps; //calculates how many steps have been taken
		mysqli_query($db,"update game_character set remaining_steps = $steps");
		$button = 'Next Encounter'; //changes the button text.
		
		echo "you have $steps steps remaining.<br><br>";
		
		//calculate monster
		
		//calculate actual level
		//calculate stats as a function of the monster's level
		//should also compliment player stats
		
		
		if ($level > 1){
			$monster_level = ceil(round(rand($level * .6, $level + 2)));
			$monster_hp = ceil(round(rand(($max_hp*.5),($max_hp*1.2)))) + $monster_level;

		}else{
			$monster_level = ceil(round(rand(1,3)));
			$monster_hp = ceil(round(rand(($max_hp*.2),($max_hp*1.2)))) + $monster_level;
		}
		
		$monster_attack = ceil(round(rand($monster_level, $monster_level*1.5)));
		$monster_defense = ceil(round(rand($monster_level, $monster_level*1.5)));
		
?>

		<img src="images/shittymonsters/<?php echo rand(1,10); ?>.png"><br><br>

<?php		
		//pick monster picture lol I have 10 monsters right now
		
		//display monster
		echo "
			-----------------<br><br>
			After walking $steps_taken steps, a level $monster_level monster appeared. <br>
			Monster has $monster_hp hit points. <br>
			Monster has $monster_attack attack and $monster_defense defense <br><br>
		";
		
		//pick the turn order
		$whose_turn = rand(1,2);
		
		if ($whose_turn == 1){
			echo "You get to go first! <br><bR>";
		} else {
			echo "They went first... <br><br>";
		}
		
		//here is the code for the actual battle
		while ($hp > 0 && $monster_hp > 0){
			if($whose_turn==1){
				$hitrate = rand(1,10);
				if (hitrate==3){
					echo "You MISSED! <br><br>";
					$whose_turn = 2;
				} else {
					$patk = ceil($player_attack * (pow(($player_attack / $monster_defense),.4)*.5));
					echo "You attack for $patk points of damage.<br>";
					$monster_hp = $monster_hp - $patk;
					echo "Monster has $monster_hp hp remaining.<br><br>";
					$whose_turn = 2;
				}
				
			}else{
				$hitrate = rand(1,10);
				if (hitrate == 1||hitrate == 5){ //monster only hits 80% of the time
					echo "monster MISSED! <br><br>";
					$whose_turn = 1;
				} else {
					$matk = ceil($monster_attack * (pow(($monster_attack / $player_defense),.4)*.5));
					echo "Monster attacks for $matk points of damage.<br>";
					$hp = $hp - $matk;
					mysqli_query($db,"update game_character set current_hp = $hp");
					echo "You have $hp hp remaining.<br><br>";
					$whose_turn = 1;
				}
			}
		}
		
		//post battle messages
		
		//you lost the battle
		if ($hp <= 0){
			$hp = 0; //sets to zero if neg value
			echo "You have $hp / $max_hp hp remaining. <br><br> you ded <br><br>";
			$loserexp = ceil(($monster_level * (($monster_attack / $monster_level) + ($monster_defense / $monster_level)))*.5+1);
			$ifonly_exp = ceil(2 * $monster_level * (($monster_attack / $monster_level) + ($monster_defense / $monster_level)));
			$player_exp = $player_exp + $loserexp;
			echo "In defeat, you learn more about battle. You gain  $loserexp experience. You now have $player_exp exp.<br><br>";
			echo "If you had won, you would have gained $ifonly_exp exp. Oh well. Next time.<br><Br>";
			$button = 'Return Home';
			mysqli_query($db,"update game_character set current_hp = $hp");
			mysqli_query($db,"update game_character set player_exp = $player_exp");
			
		//you won the battle
		}elseif ($monster_hp <= 0){
			echo "Monster was Defeated! You have $hp / $max_hp hp remaining.<br><br>";
			$button = 'Next Encounter';
			
			//exp gain
			$gained_exp = ceil(2 * $monster_level * (($monster_attack / $monster_level) + ($monster_defense / $monster_level))); 
			echo "You gained $gained_exp exp points! <br>";
			//calculates total player exp
			$player_exp = $player_exp + $gained_exp;
			//inserts to db
			mysqli_query($db,"update game_character set player_exp = $player_exp");
			//level up code
			if($player_exp >= $next_level_exp){
				echo "<h2>YOU LEVELED UP!</h2> <BR>";
				$level = $level + 1;
				$next_level = $next_level + 1;
				$max_hp = (10+(10*($level * 1.5)));
				$hp = $max_hp;
				$next_level_exp = (((37.5*(($next_level)**2))+(87.5*($next_level)))-124);
				//updates level in db
				mysqli_query($db,"update game_character set level = $level");
				//updates max health, refills health
				mysqli_query($db,"update game_character set current_hp = $max_hp");
				mysqli_query($db,"update game_character set max_hp = $max_hp");
				//words
				echo "You are now level $level <br>";
			}
			$remaining_exp = $next_level_exp - $player_exp;
			
			echo "$remaining_exp exp remaining until level $next_level <br>";
			echo "You have $hp hit points remaining<br><br>";
			
			//reward calculation
			$reward_chance = rand(1,100);
			if($reward_chance >= 90){ //10% chance to get something good
				echo "You get a reward!<br>";
				//pick the actual reward. if gear, will ALWAYS be better than what you have.
				$reward_select = rand (1,100);
				if ($reward_select <= 5){ //5% weapon. Randomly generates power level.
					echo "<font color='green'><h2>YOU FOUND A NEW WEAPON!</h2></font><br>";
					$has_weapon = 1;
					mysqli_query($db,"update game_character set has_weapon = $has_weapon");
					$weapon_power = rand($weapon_power+1, ceil($weapon_Power*1.05)+1);
					mysqli_query($db,"update game_character set weapon_power = $weapon_power");
					echo "<H3>New Weapon has $weapon_power power.</H3><br>";
					$player_attack = $level + $weapon_power ; //recalculates attack for moving forward
				} else if ($reward_select >5 && $reward_select <= 10){ //5% armor. Randomly generates power.
					echo "<font color='green'><h2>YOU FOUND NEW ARMOR!</h2></font><br>";
					$has_armor = 1;
					mysqli_query($db,"update game_character set has_armor = $has_armor");
					$armor_power = rand($armor_power+1, ceil($armor_Power*1.05)+1);
					mysqli_query($db,"update game_character set armor_power = $armor_power");
					echo "<H3>New armor has $armor_power power.</H3><br>";
					$player_defense = $level + $weapon_power ; //recalculates defense moving forward
				} else { //potion - randomly generates how much it restores
					echo "<font color='green'><h2>YOU FOUND A HEALTH POTION!<h2></font><br>";
					$restore_percent = rand(1,20);
					$restore_amt = ceil($max_hp * ($restore_percent/100));
					echo "<H3>Potion will restore up to $restore_percent percent of your health = $restore_amt pts </H3> <br>";
					$hp = $hp + $restore_amt;

					if ($hp > $max_hp){
						$hp = $max_hp; //make sure cant have more than max of your hp. 
					}
					mysqli_query($db,"update game_character set current_hp = $hp");
					echo "You now have $hp hit points!<br><br>";
					
				}
			}
			
			
		//only for testing. If this ever happens I'll be pissed. 
		} else {
			echo "Someone died but we're not sure who. lol. kicking you to homepage.";
			sleep(10);
			header("location: profile.php");
			
		}
		

	//if the player runs out of HP while rolling
	}else if ($hp < 0){
		$steps = $steps - 1;
		mysqli_query($db,"update game_character set remaining_steps = $steps");
		echo "You are out of HP. <br><br>";
		$button = 'Return Home';
	
	//if player runs out of steps while rolling
	} else {
		$stepdiff = $og_steps - $steps;
		echo "You took $stepdiff steps. You are out of steps!<br><br>";
		mysqli_query($db,"update game_character set remaining_steps = $steps");
		$button = 'Return Home';
	}

//if player had no steps to begin with
} else if ($steps <= 0){
	echo "You took $og_steps steps without finding anything. You are out of steps!<br><br>";
	$button = 'Return Home';
	
//if the player did not have HP coming in to this. 
} else if ($hp <= 0){
	echo "You have no HP. Go Home! <br><br>";
	$button = 'Return Home';
} else {
	echo "some dumb shit happened<br><br>";
	session_destroy(); //session destroy for testing only. 
	header("location: profile.php");
	$button = 'Return Home';
}




?>

<br><br>


<form action="" method="post">
<input name="next" type="submit" value="<?php echo "$button";?>"> <br><br>
<input name="profile" type="submit" value="Back To Profile"> <br><br>
<input name="logout" type="submit" value="Log Out"> <br><br>
</form>



</center>


</body>
</html>