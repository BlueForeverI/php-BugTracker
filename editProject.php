<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Edit Project - BugTracker</title>
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
			Select Project to Edit:
			<select name="selectedProject">
				<?php
					require_once("classes.php");
					
					foreach(AdminActions :: getProjectNames() as $projectName)
					{
						echo "<option value='$projectName'>$projectName</option>";
					}
				?>
			</select><br />
			Project Name:
			<input type="text" name="projectName" /><br />
			Description:<br />
			<textarea name="description" rows="10" cols="30"></textarea><br />
			<input type="submit" value="Edit" /><br />
		</form>
		
		<?php
			function getInputErrors()
			{
				$errors = "";
			
				if(empty($_POST["selectedProject"]))
				{
					$errors .= "You should select the project to edit<br />";
				}
			
				if(empty($_POST["projectName"]))
				{
					$errors .= "You should specify the project's name<br />";
				}
				else if(strlen($_POST["projectName"]) > 32)
				{
					$errors .= "The project's name should not be longer than 32 characters<br />";
				}
				
				if(empty($_POST["description"]))
				{
					$errors .= "You should specify the project's description<br />";
				}
				
				return $errors;
			}
			
			require_once("classes.php");
			
			if(!empty($_POST))
			{
				$errors = getInputErrors();
				if(empty($errors))
				{
					$project = new Project($_POST["projectName"], $_POST["description"]);
					if(AdminActions :: editProject($_POST["selectedProject"], $project))
					{
						echo "Project edited successfully!";
					}
					else
					{
						echo "Cannot edit project!";
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