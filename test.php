<html>
<head>
<?php

$og_steps = 10000;
$steps = $og_steps;
$steprange = $steps / 5;
$hp = 25;
$level = 1;
$next_level = $level + 1;
$player_attack = $level + 1;//test only
$player_defense = $level + 1;//test only
$player_exp = 0;
$next_level_exp = (((37.5*(($next_level)**2))+(87.5*($next_level)))-124);
$remaining_exp = $next_level_exp - $player_exp;

?>

</head>
<body>
<center>
<h1> TEST PAGE </h1>
<BR>
<?php 

echo "You begin the day with $steps steps! <br>";
echo "You are level $level <br>";
echo "You have $player_exp total exp. In $remaining_exp exp you will reach level $next_level <br><br>";
echo "You have $hp hit points <br>";
echo "You have $player_attack attack and $player_defense defense <br>";

echo "-----------------<br><br>";

while ($steps > 0 && $hp > 0) {
	
	//step range
	$rand = rand(1,50);
	// 3 is a monster battle
	if ($rand == 3){
		//calculate monster
		$monster_level = rand($level, ($level + 2));
		$monster_hp = ceil(10 + ($level * .5));
		$monster_attack = rand($level, ($level + 2));
		$monster_defense = rand($level, ($level + 2));
		
		//calculate steps used
		$used_steps = ($og_steps - $steps);
		
		//display monster
		echo "
			-----------------<br><br>
			After walking $used_steps steps, a level $monster_level monster appeared. <br>
			You have $steps steps remaining. <br>
			Monster has $monster_hp hit points. <br>
			Monster has $monster_attack attack and $monster_defense defense <br>
		";
		
		//determine who goes first 
		$whose_turn = rand(1,2);
		
		if ($whose_turn == 1){
			echo "You get to go first! <br><bR>";
		} else {
			echo "They went first... <br><br>";
		}
		
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
				
			}else {
				$hitrate = rand(1,10);
				if (hitrate == 1||hitrate == 5){ //monster only hits 80% of the time
					echo "monster MISSED! <br><br>";
					$whose_turn = 1;
				} else {
					$matk = ceil($monster_attack * (pow(($monster_attack / $player_defense),.366)*.5));
					echo "Monster attacks for $matk points of damage.<br>";
					$hp = $hp - $matk;
					echo "You have $hp hp remaining.<br><br>";
					$whose_turn = 1;
				}
			}
		}
		//win and lose statements.
		if ($hp <= 0){
			echo "Monster killed you!<br><br>";
		} else if ($monster_hp <= 0){
			echo "You killed monster<br>";
			$gained_exp = $monster_level * (($monster_attack / $monster_level) + ($monster_defense / $monster_level)); 
			echo "You gained $gained_exp exp points! <br>";
			$player_exp = $player_exp + $gained_exp;
			//this is where you need the if statement for levelups
			$remaining_exp = $next_level_exp - $player_exp;
			echo "$remaining_exp exp remaining until level $next_level <br><br>";
			
			
			
		} else {
			echo "someone died but we're not sure who...<br>";
			exit();
		}
		
		
		echo "-----------------<br><br>";

		
		
	}//end of encounter	
	//remove the used step :-)
	$steps = $steps - 1;

	}
//when out of steps or HP
if ($steps <= 0){
	echo "You're out of energy. Come back with more steps<br><br>";
} elseif ($hp <= 0) {
	echo "You're out of HP. Come back later when your HP has recharged<br><br>";
} else {
	echo "You're being booted from the dungeon for some reason. This developer sucks lel<br><br>";
}

echo "You have $player_exp total experience <br>
You will grow to level $next_level after gaining $remaining_exp to meet $next_level_exp exp <br><br>
You have $steps steps remaining. <br><br>";


?>
<br><br>
<a href="index.php">back to home </a>
<br><br><br><br>
</center>
</body>
</html>