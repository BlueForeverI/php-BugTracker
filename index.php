<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>BugTracker</title>
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
			else if($_COOKIE["username"] === "admin")
			{
				header("Location: admin.php");
			}
			else
			{
				echo "<h2>Welcome, " . $_COOKIE["username"] . "</h2>";
				require_once("classes.php");
				TesterActions :: setLastVisit($_COOKIE["username"]);
			}
		?>
		<p>Choose one of the following actions:</p>
		<ul>
			<li><a href="viewBugs.php?mode=project">View bugs by project</a></li>
			<li><a href="viewBugs.php?mode=all">View all bugs</a></li>
			<li><a href="addBug.php">Add Bug</a></li>
			<li><a href="editBug.php">Edit Bug</a></li>
			<li><a href="deleteBug.php">Delete Bug</a></li>
		</ul>
	</body>
</html>