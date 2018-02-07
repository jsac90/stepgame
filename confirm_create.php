<html> 
<head>
<title> confirm creation </title>

<?php

require 'setup/dbconnect.php';

$username = strtoupper($_POST['username']);
$password = $_POST['password'];
$hashedun = hash('sha256',$username);
$hashpass = password_hash($password, PASSWORD_BCRYPT);

//insert data in to database
//check to make sure email address is valid. No use collecting some BS. 
if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
	$insertmessage = "Invalid email format. Please Try again.";
	 
//check to make sure password is long enough
}else if (strlen($password) < 6){
	$insertmessage = "Invalid password - must be at least 6 characters. Please Try Again.";

//check to make sure email hasn't already been used
}else if (mysqli_num_rows(mysqli_query($db,"select * from entrance where hashemail = '$hashedun'")) > 0){
	$insertmessage = "Email already in use. Please try an email that hasn't been used before.";

//if everything is good, create the credentials. 
}else{
	//create login credentials
	mysqli_query($db,"INSERT INTO entrance (hashemail,hashpass,created) VALUES 
	('$hashedun','$hashpass',NOW())");
	//create the character
	//get player ID that was just created
	$getid = mysqli_query($db,"select * from entrance where hashemail = '$hashedun'");
	$idrow = mysqli_fetch_assoc($getid); //gets data from query
	$id = $idrow["player_id"]; //gets data from query
	//insert character record
	mysqli_query($db,"insert into game_character(player_id, createdate) values ('$id',NOW())"); 
	mysqli_query($db,"insert into cust_data(player_id, emailaddr,addate) values ('$id','$username',NOW())"); 
	//confirm that the account was created
	$insertmessage = "Account Created.";
}
//close db connection
mysqli_close($db);

?>

</head>
<body>

<?php echo "$message"; ?>
<br /> <br />
<?php echo "$insertmessage"; ?>
<br /> <br />
<form action="index.php" method="post">
<input type="submit" name="gohome" value="Go Home" />
</form>
<form action="createuser.php" method="post">
<input type="submit" name="gocreate" value="Back To Create" />


</body>
</html>