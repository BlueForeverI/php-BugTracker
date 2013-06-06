<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Add Bug - BugTracker</title>
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
			Select a project:
			<select name="selectedProject">
				<?php
					require_once("classes.php");
					
					foreach(AdminActions :: getProjectNames() as $projectName)
					{
						echo "<option value='$projectName'>$projectName</option>";
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
			</select>
			<input type="submit" value="Add" />
		</form>
		
		<?php
			function getInputErrors()
			{
				$errors = "";
				
				if(empty($_POST["selectedProject"]))
				{
					$errors .= "You should select a project<br />";
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
				
				return $errors;
			}
			
			require_once("classes.php");
			
			if(!empty($_POST))
			{
				$errors = getInputErrors();
				if(empty($errors))
				{
					$bug = new Bug(date("d/M/Y H:i:s"), $_COOKIE["username"], $_POST["description"],
							$_POST["priority"], $_POST["selectedProject"], "New");
							
					if(TesterActions :: addBug($bug))
					{
						echo "Bug added successfully!";
						$project = $_POST["selectedProject"];
						TesterActions :: setLastAction($_COOKIE["username"], 
							"added a bug to project $project");
					}
					else
					{
						echo "Cannot add bug!";
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