<html>
<head>
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

<img src = "/images/shrug.jpg" />

<h1> Please Log In </h1>

<form action="" method="post">
<label>Email Address: </label>
<input type="text" name="username">
<label>Password: </label>
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