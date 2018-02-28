<html>
<head>
<title> CREATE USER </title>
<link rel="stylesheet" type="text/css" href="styles/stepgame.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<center>
<img id="logo" src = "/images/stepgamelogo.png"/>
<br>
<h1> Create A User Account </h1>
<form action="confirm_create.php" method="post">
Enter your email address: <br>
<input type="text" name="username" />
<br /> <br />
Enter a password (Case Sensitive): <br>
<input type="password" name="password" />
<br /> <br />
<input type="submit" name="create_user" value="Submit" />
</form>
<form action="index.php" method="post">
<input type="submit" name="cancel" value="Cancel" />
</form>
</center>
</body>
</html>