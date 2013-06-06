<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Admin Panel - BugTracker</title>
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
		<h2>Welcome, admin!</h2>
		<p>Choose one of the following actions:</p>
		<ul>
			<li><a href="addTester.php">Add tester</a></li>
			<li><a href="editTester.php">Edit tester</a></li>
			<li><a href="deleteTester.php">Delete tester</a></li>
			<li><a href="addProject.php">Add project</a></li>
			<li><a href="editProject.php">Edit project</a></li>
			<li><a href="deleteProject.php">Delete project</a></li>
			<li><a href="viewAllTesters.php">View all testers</a></li>
			<li><a href="viewBugs.php?mode=all">View all bugs</a></li>
			<li><a href="viewBugs.php?mode=project">View bugs by project</a></li>
		</ul>
	</body>
</html>