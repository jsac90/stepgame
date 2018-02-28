<html>
<head>

<link rel="stylesheet" type="text/css" href="styles/stepgame.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login Page</title>

<?php
include('login.php'); // Includes Login Script


//this section will automatically redirect the user to their profile if they are logged in. :-)
if(isset($_SESSION['login_user'])){
header("location: profile.php");
}
?>

</head>
<body>
<center>

<img id="logo" src = "/images/stepgamelogo.png"/>

<h1> Please Log In </h1>

<form action="" method="post">
<label>Email Address: </label><br>
<input type="text" name="username">
<br><br>
<label>Password: </label><br>
<input type="password" name="password">
<br /> <br />
<input name="submit" type="submit" value="Log In">
<br /> <br />
<input name="create" type="submit" value="Create Account">
<br /><br />
<font color="red"><?php echo $error; ?></font>
</form>

</center>
</body>
</html>