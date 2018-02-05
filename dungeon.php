<html>
<head>
<?php 

include 'session.php';

session_start(); // Starting Session

$icamefrom = $_SERVER['HTTP_REFERER'];

if (!isset($_SESSION['login_user']) || $_SESSION['login_user'] == ''){
	header("location: index.php");
	exit();
} elseif ($icamefrom != 'http://interwebs.xyz/profile.php'){
	header("location: profile.php");
	exit();
}

if ($_POST['steps'] == ""){
	$_SESSION['steperror'] = "ERROR: STEPS MUST NOT BE NULL";
	header("location: profile.php");
	exit();
}else if ($_POST['steps'] > 30000){
	$_SESSION['steperror'] = "ERROR: DON'T CHEAT!";
	header("location: profile.php");
	exit();
}else if ($_POST['steps'] < 1000){
	$_SESSION['steperror'] = "ERROR: YOU MUST HAVE AT LEAST 1000 STEPS TO ENTER THE DUNGEON!";
	header("location: profile.php");
	exit();
}else {
	$steps = $_POST['steps'];
	$_SESSION['steperror'] = "";
	unset($_SESSION['steperror']);
}

$login_session = $_SESSION["login_user"];

if(isset($_POST['logout'])){
	header("location: logout.php");
	exit();
}else if (isset($_POST['profile'])) {
	header("location: profile.php");
	exit();
};

//game code starts here
$steprange = ($steps / 2);



?>

<title>Stepgame - The Dungeon</title>
</head>
<body>
<center>

<h1><?php echo "You have walked $steps steps today. :-)"; ?></h1>


<br /><br /><br /><br />





<form action="" method="post">
<input name="profile" type="submit" value="Back To Profile">
<input name="logout" type="submit" value="Log Out">
</form>

</center>


</body>
</html>