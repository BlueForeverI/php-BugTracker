<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Delete Tester - BugTracker</title>
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
			Choose tester to delete:
			<select name="username">
				<?php
					require_once("classes.php");
					
					foreach(AdminActions :: getTesterUsernames() as $username)
					{
						echo "<option value='$username'>$username</option>";
					}
				?>
				
			</select><br />
			<input type="submit" value="Delete" />
		</form>
		
		<?php
			function getInputErrors()
			{
				$errors = "";
				
				if(empty($_POST["username"]))
				{
					$errors .= "You should specify the tester's username<br />";
				}
				
				return $errors;
			}
		
			require_once("classes.php");
			
			if(!empty($_POST))
			{
				$errors = getInputErrors();
				if(empty($errors))
				{
					if(AdminActions :: deleteTester($_POST["username"]))
					{
						echo "Tester deleted successfully!";
					}
					else
					{
						echo "Cannot delete tester!";
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