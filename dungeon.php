<html>
<head>
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


//checks steps from profile page and gives error if null or low or something.

if (isset($_POST['steps'])){
	if ($_POST['steps'] == "" && (stripos($icamefrom,'/profile.php')== TRUE)){
		$_SESSION['steperror'] = "ERROR: STEPS MUST NOT BE NULL";
		header("location: profile.php");
		exit();
	}else if ($_POST['steps'] > 30000 && (stripos($icamefrom,'/profile.php')== TRUE)){
		$_SESSION['steperror'] = "ERROR: DON'T CHEAT!";
		header("location: profile.php");
		exit();
	}else if ($_POST['steps'] < 1000 && (stripos($icamefrom,'/profile.php')== TRUE)){
		$_SESSION['steperror'] = "ERROR: YOU MUST HAVE AT LEAST 1000 STEPS TO ENTER THE DUNGEON!";
		header("location: profile.php");
		exit();
	}else{
		$steps = $_POST['steps'];
		$og_steps = $steps;
		$_SESSION['og_steps'] = $og_steps;
	}
} else if (isset($_SESSION['steps'])){
	$steps = $_SESSION['steps'];
	$og_steps = $_SESSION['og_steps'];
} else {
	$_SESSION['steperror'] = "ERROR: some step related problem. Figure it out, jerk. ";
	header("location: profile.php");
}

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
<h1> Welcome to THE DUNGEON! </h1>
<br><br>
<?php 


//set the stage...
echo "You are level $level <br>";
echo "You have $player_exp total exp. In $remaining_exp exp you will reach level $next_level <br><br>";
echo "You have $hp hit points <br>";
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
		$encounter = rand(1,2000);
		$steps = $steps - 1;
		$_SESSION['steps'] = $steps;
	}
	
	//encounter happens here
	if ($steps > 0 && $hp > 0) { 
		$steps = $steps - 1; //deducts the step
		$steps_taken = $og_steps - $steps; //calculates how many steps have been taken
		$_SESSION['steps'] = $steps; //saves steps taken to a session variable
		$button = 'Next Encounter'; //changes the button text.
		
		echo "you have $steps steps remaining.<br><br>";
		
		//calculate monster
		$monster_level = rand($level, ($level + 2));
		//eventually want to be able to hit weaker monsters but too lazy right now
		$monster_hp = ceil(10 + ($level));
		$monster_attack = rand($level, ($level + 2)); 
		//eventually want to be able to hit weaker monsters but too lazy right now
		$monster_defense = rand($level, ($level + 2));
		//eventually want to be able to have weaker monsters but too lazy right now
		
		//display monster
		echo "
			-----------------<br><br>
			After walking $steps_taken steps, a level $monster_level monster appeared. <br>
			You have $steps steps remaining. <br>
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
					$patk = ceil($player_attack * (pow(($player_attack / $monster_defense),.366)*.5));
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
					$matk = ceil($monster_attack * (pow(($monster_attack / $player_defense),.366)*.5));
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
			$button = 'Return Home';
			mysqli_query($db,"update game_character set current_hp = $hp");
			
		//you won the battle
		}elseif ($monster_hp <= 0){
			echo "Monster was Defeated! You have $hp / $max_hp hp remaining.<br><br>";
			$button = 'Next Encounter';
			
			//exp gain
			$gained_exp = $monster_level * (($monster_attack / $monster_level) + ($monster_defense / $monster_level)); 
			echo "You gained $gained_exp exp points! <br>";
			//calculates total player exp
			$player_exp = $player_exp + $gained_exp;
			//inserts to db
			mysqli_query($db,"update game_character set player_exp = $player_exp");
			//level up code
			if($player_exp >= $next_level_exp){
				echo "YOU LEVELED UP! <BR>";
				$level = $level++;
				//updates level in db
				mysqli_query($db,"update game_character set level = $level");
				//updates max health, refills health
				$max_hp = (10+(10*($level * 1.5)));
				mysqli_query($db,"update game_character set current_hp = $max_hp");
				mysqli_query($db,"update game_character set max_hp = $max_hp");
				echo "You are now level $level <br>";
				$next_level = $level + 1;
				$next_level_exp = (((37.5*(($next_level)**2))+(87.5*($next_level)))-124);
			}
			$remaining_exp = $next_level_exp - $player_exp;
			
			$_SESSION['player_exp'] = $player_exp;
			echo "$remaining_exp exp remaining until level $next_level <br>";
			echo "You have $hp hit points remaining<br><br>";
			
			//reward calculation
			$reward_chance = rand(1,100);
			if($reward_chance >= 80){ //20% chance to get something good
				echo "You get a reward!<br>";
				//pick the actual reward. if gear, will ALWAYS be better than what you have.
				$reward_select = rand (1,100);
				if ($reward_select <= 10){ //10% weapon. Randomly generates power level.
					echo "<font color='green'>You found a new weapon!</font><br>";
					$has_weapon = 1;
					mysqli_query($db,"update game_character set has_weapon = $has_weapon");
					$weapon_power = $weapon_power + $level + rand($weapon_power, ($weapon_Power + 5));
					mysqli_query($db,"update game_character set weapon_power = $weapon_power");
					echo "New Weapon has $weapon_power power.<br>";
					$player_attack = $level + $weapon_power ; //recalculates attack for moving forward
					//usually 50 or 75 for armor but changed this for testing
				} else if ($reward_select >10 && $reward_select <= 20){ //10% armor. Randomly generates power.
					echo "<font color='green'>You found new armor!</font><br>";
					$has_armor = 1;
					mysqli_query($db,"update game_character set has_armor = $has_armor");
					$armor_power = $armor_power + $level + rand($armor_power, ($armor_Power + 5));
					mysqli_query($db,"update game_character set armor_power = $armor_power");
					echo "New armor has $armor_power power.<br>";
					$player_defense = $level + $weapon_power ; //recalculates defense moving forward
				} else { //potion - randomly generates how much it restores
					echo "<font color='green'>You found a health potion!</font><br>";
					$restore_percent = rand(1,35);
					$restore_amt = ceil($max_hp * ($restore_percent/100));
					echo "Potion will restore up to $restore_percent percent of your health = $restore_amt pts <br>";
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
		echo "You are out of HP. <br><br>";
		$button = 'Return Home';
	
	//if player runs out of steps while rolling
	} else {
		$stepdiff = $og_steps - $steps;
		echo "You took $stepdiff steps. You are out of steps!<br><br>";
		$_SESSION['steps'] = $steps;
		$button = 'Return Home';
	}

//if player had no steps to begin with
} else if ($steps <= 0){
	echo "You took $og_steps steps. You are out of steps!<br><br>";
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
<input name="next" type="submit" value="<?php echo "$button";?>">
<input name="profile" type="submit" value="Back To Profile">
<input name="logout" type="submit" value="Log Out">
</form>



</center>


</body>
</html>