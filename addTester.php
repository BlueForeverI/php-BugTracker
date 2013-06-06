<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Add Tester - BugTracker</title>
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
			if($_COOKIE["username"] !== "admin")
			{
				header("Location: index.php");
			}
		?>
		<form action="" method="post">
			Username:
			<input type="text" name="username" /><br />
			Password:
			<input type="password" name="password" /><br />
			First Name:
			<input type="text" name="firstName" /><br />
			Last Name:
			<input type="text" name="lastName" /><br />
			Email:
			<input type="text" name="email" /><br />
			Telephone:
			<input type="text" name="telephone" /><br />
			<input type="submit" value="Add" />
		</form>
		
		<?php
			require_once("classes.php");
			
			function getInputErrors()
			{
				$errors = "";
				
				if(empty($_POST["username"]))
				{
					$errors .= "You should specify the tester's username<br />";
				}
				else if(strlen($_POST["username"]) > 15)
				{
					$errors .= "The tester's username should not be longer than 15 characters<br />";
				}
				else if(!preg_match("/^[a-zA-Z0-9]+$/", $_POST["username"]))
				{
					$errors .= "Invalid name<br />";
				}
				
				if(empty($_POST["password"]))
				{
					$errors .= "You should specify the tester's password<br />";
				}
				
				if(empty($_POST["firstName"]))
				{
					$errors .= "You should specify the tester's first name<br />";
				}
				else if(strlen($_POST["firstName"]) > 15)
				{
					$errors .= "The tester's first name should not be longer than 15 characters<br />";
				}
				
				if(empty($_POST["lastName"]))
				{
					$errors .= "You should specify the tester's last name<br />";
				}
				else if(strlen($_POST["lastName"]) > 15)
				{
					$errors .= "The tester's last name should not be longer than 15 characters<br />";
				}
				
				$valid_mail = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/";
				if(empty($_POST["email"]))
				{
					$errors .= "You should specify the tester's email<br />";
				}
				else if(strlen($_POST["email"]) > 32)
				{
					$errors .= "The tester's email should not be longer than 32 characters<br />";
				}
				else if(!preg_match($valid_mail, $_POST["email"]))
				{
					$errors .= "Invalid email<br />";
				}
				
				if(empty($_POST["telephone"]))
				{
					$errors .= "You should specify the tester's telephone<br />";
				}
				else if(!preg_match("/^[0-9]+$/", $_POST["telephone"]))
				{
					$errors .= "Invalid telephone<br />";
				}
				else if(strlen($_POST["telephone"]) > 20)
				{
					$errors .= "The tester's telehpone number should not be longer than 20 digits<br />";
				}
				
				return $errors;
			}
		
			if(!empty($_POST))
			{
				$errors = getInputErrors();
				
				if(empty($errors))
				{
					$username = mysql_real_escape_string($_POST["username"]);
					$firstName = mysql_real_escape_string($_POST["firstName"]);
					$lastName = mysql_real_escape_string($_POST["lastName"]);
					$email = mysql_real_escape_string($_POST["email"]);
					$telephone = mysql_real_escape_string($_POST["telephone"]);
					$passMd5 = md5($_POST["password"]);
					
					$tester = new Tester($username, $firstName, $lastName, $email, $telephone);
					if(AdminActions :: addTester($tester, $passMd5))
					{
						echo "The tester was added successfully!<br />";
					}
					else
					{
						echo "Cannot add tester!<br />";
					}
				}
				else
				{
					echo $errors;
				}
			}
		?>
	</body>
</html>