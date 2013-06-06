<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Log In - BugTracker</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	
	<body>	
		<div id="header">
			<h1>BugTracker</h1>
		</div>
		<div id="navigation">
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="login.php">Log In</a></li>
			</ul>
		</div>
		<?php
			setcookie("username", null);
		?>
		<p>You are not logged in!</p>
		<form action="" method="post">
			Username:
			<input type="text" name="username" /><br />
			Password:
			<input type="password" name="password"  /><br />
			<input type="submit" value="Log In!" /><br />
		</form>

		<?php
			if(isset($_POST["username"])&& $_POST["username"] != "")
			{
				require_once("classes.php");

				$username = $_POST["username"];
				$passwordMd5 = md5($_POST["password"]);
				
				$query = "SELECT * FROM testers WHERE Username = '$username' AND PasswordMd5 = '$passwordMd5'";
				$result = mysql_query($query, $GLOBALS["conn"]);
				
				if(mysql_num_rows($result) === 0)
				{
					echo "Wrong username or password!";
				}
				else
				{
					setcookie("username", $username);
					header("Location: index.php");
				}
			}
		?>
	</body>
</html>