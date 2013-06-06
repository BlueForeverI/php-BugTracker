<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Delete Bug - BugTracker</title>
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
			<input type="submit" value="Delete" />
		</form>
		
		<?php
			function getInputErrors()
			{
				$errors = "";
				
				if(empty($_POST["bugId"]))
				{
					$errors .= "You should select a bug<br />";
				}
				
				return $errors;
			}
			
			require_once("classes.php");
			
			if(!empty($_POST))
			{
				$errors = getInputErrors();
				if(empty($errors))
				{
							
					if(TesterActions :: deleteBug($_POST["bugId"]))
					{
						echo "Bug deleted successfully!";
						$bugId = $_POST["bugId"];
						TesterActions :: setLastAction($_COOKIE["username"], "deleted bug #$bugId");
					}
					else
					{
						echo "Cannot delete bug!";
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