<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Delete Project - BugTracker</title>
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
			Choose project to delete:
			<select name="projectName">
				<?php
					require_once("classes.php");
					
					foreach(AdminActions :: getProjectNames() as $projectName)
					{
						echo "<option value='$projectName'>$projectName</option>";
					}
				?>
				
			</select><br />
			<input type="submit" value="Delete" />
		</form>
		
		<?php
			function getInputErrors()
			{
				$errors = "";
				
				if(empty($_POST["projectName"]))
				{
					$errors .= "You should select the project to delete<br />";
				}
				
				return $errors;
			}
		
			require_once("classes.php");
			
			if(!empty($_POST))
			{
				$errors = getInputErrors();
				if(empty($errors))
				{
					if(AdminActions :: deleteProject($_POST["projectName"]))
					{
						echo "Project deleted successfully!";
					}
					else
					{
						echo "Cannot delete project!";
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