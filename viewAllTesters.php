<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>View Testers - BugTracker</title>
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
			if(!isset($_COOKIE["username"]))
			{
				header("Location: login.php");
			}
			
			require_once("classes.php");
		
			if(empty($_POST["sort"]) || empty($_POST["page"]))
			{
				$pages = ManagementActions :: getAllTesters();
			}
			else
			{
				$pages = ManagementActions :: getAllTesters($_POST["sort"], $_POST["page"]);
			}
		?>
		
		<form action="" method="post">
			Sort By:
			<select name="sort">
				<option value="Username">Username</option>
				<option value="Projects">Projects</option>
				<option value="Bugs">Bugs</option>
				<option value="Action">Last Action</option>
				<option value="Visit">Last Visit</option>
			</select><br />
			Page:
			<select name="page">
				<?php
					for($i = 1; $i <= $GLOBALS["pages"]; $i ++)
					{
						echo "<option value='$i'>$i</option>";
					}
				?>
			</select><br />
			<input type="submit" value="Go" /><br />
		</form>
	</body>
</html>