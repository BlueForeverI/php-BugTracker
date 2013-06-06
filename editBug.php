<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Edit Bug - BugTracker</title>
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
		?>
		<form action="" method="post">
			Select a bug:
			<select name="bugId">
				<?php
					require_once("classes.php");
					
					foreach(TesterActions :: getSubmittedBugs($_COOKIE["username"]) as $bug)
					{
						$id = $bug -> getId();
						$shortDescription = substr($bug -> getDescription(), 0, 20) . "...";
						$projectName = $bug -> getProjectName();
						echo "<option value='$id'>$shortDescription in $projectName</option>";
					}
				?>
			</select><br />
			Description:<br />
			<textarea name="description" rows="10" cols="30"></textarea><br />
			Priority:
			<select name="priority">
				<option value="Critical">Critical</option>
				<option value="High">High</option>
				<option value="Normal">Normal</option>
				<option value="Low">Low</option>
			</select><br />
			State:
			<select name="state">
				<option value="New">New</option>
				<option value="In Progress">In Progress</option>
				<option value="Fixed">Fixed</option>
				<option value="Deleted">Deleted</option>
				<option value="Closed">Closed</option>
			</select>
			<input type="submit" value="Edit" />
		</form>
		
		<?php
			function getInputErrors()
			{
				$errors = "";
				
				if(empty($_POST["bugId"]))
				{
					$errors .= "You should select a bug<br />";
				}
				
				if(empty($_POST["description"]))
				{
					$errors .= "You should specify the bug's description<br />";
				}
				
				$priority_values = array("Critical", "High", "Normal", "Low");
				if(empty($_POST["priority"]))
				{
					$errors .= "You should specify the bug's priority<br />";
				}
				else if(!in_array($_POST["priority"], $priority_values))
				{
					$errors .= "Invalid priority<br />";
				}
				
				$state_values = array("New", "In Progress", "Fixed", "Deleted", "Closed");
				if(empty($_POST["state"]))
				{
					$errors .= "You should specify the bug's state<br />";
				}
				else if(!in_array($_POST["state"], $state_values))
				{
					$errors .= "Invalid state<br />";
				}
				
				return $errors;
			}
			
			require_once("classes.php");
			
			if(!empty($_POST))
			{
				$errors = getInputErrors();
				if(empty($errors))
				{
					$bug = new Bug(null, null, $_POST["description"],
							$_POST["priority"], null, $_POST["state"]);
							
					if(TesterActions :: editBug($_POST["bugId"], $bug))
					{
						echo "Bug edited successfully!";
						$bugId = $_POST["bugId"];
						TesterActions :: setLastAction($_COOKIE["username"], "edited bug #$bugId");
					}
					else
					{
						echo "Cannot edit bug!";
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