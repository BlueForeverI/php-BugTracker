<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>View Bugs - BugTracker</title>
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
		
			if(empty($_GET["mode"]))
			{
				die("Cannot view bugs. Missing 'mode' parameter.<br />");
			}
			else if($_GET["mode"] == "project")
			{
				echo "Select Project:
				<form action='' method='post'>
				<select name='selectedProject'>";
				
				foreach(TesterActions :: getProjectNames() as $projectName)
				{
					echo "<option value='$projectName'>$projectName</option>";
				}
				echo "</select><br />";
				echo "<input type='submit' value='Select' /></form>";
				
				if(!empty($_POST["selectedProject"]))
				{
					if(!empty($_POST["sort"]) && !empty($_POST["page"]))
					{
						$pages = TesterActions :: getActiveBugs( $_POST["selectedProject"] ,$_POST["sort"], $_POST["page"]);
					}
					else
					{
						$pages = TesterActions :: getActiveBugs($_POST["selectedProject"]);
					}
					echo "
					<form action='' method='post'>
					Sort By:
					<select name='sort'>
						<option value='Description'>Description</option>
						<option value='Date'>Date</option>
						<option value='Owner'>Owner</option>
						<option value='Priority'>Priority</option>
						<option value='Project'>Project</option>
						<option value='State'>State</option>
					</select><br />
					Page:<select name='page'>";

						for($i = 1; $i <= $GLOBALS['pages']; $i ++)
						{
							echo '<option value=' .  $i . '>' . $i . '</option>';
						}
						
					echo "</select><br />
					<input type='text' name='selectedProject' value='" . $_POST["selectedProject"] . "' style='display: none' />
					<input type='submit' value='Go' /><br />";
				}
			}
			else if($_GET["mode"] == "all")
			{
				if(!empty($_POST["sort"]) && !empty($_POST["page"]))
					{
						$pages = TesterActions :: getActiveBugs(null, $_POST["sort"], $_POST["page"]);
					}
					else
					{
						$pages = TesterActions :: getActiveBugs();
					}
					
				echo "
					<form action='' method='post'>
					Sort By:
					<select name='sort'>
						<option value='Description'>Description</option>
						<option value='Date'>Date</option>
						<option value='Owner'>Owner</option>
						<option value='Priority'>Priority</option>
						<option value='Project'>Project</option>
						<option value='State'>State</option>
					</select><br />
					Page:<select name='page'>";

						for($i = 1; $i <= $GLOBALS['pages']; $i ++)
						{
							echo '<option value=' .  $i . '>' . $i . '</option>';
						}
						
					echo "</select><br />
					<input type='submit' value='Go' /><br />";
			}
			
		?>
	</body>
</html>