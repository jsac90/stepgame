<html>
<head>
<title> CREATE USER </title>

</head>
<body>
<center>
<h1> Create A User Account </h1>
<form action="confirm_create.php" method="post">
Enter your email address: <input type="text" name="username" />
<br /> <br />
Enter a password (Case Sensitive): <input type="password" name="password" />
<br /> <br />
<input type="submit" name="create_user" value="Submit" />
</form>
<form action="index.php" method="post">
<input type="submit" name="cancel" value="Cancel" />
</form>
</center>
</body>
</html>