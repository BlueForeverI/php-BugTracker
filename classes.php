<?php
	$conn = mysql_connect("localhost", "root", "") or die("Cannon connect to server");
	mysql_select_db("bug_tracker") or die("Cannon connect to database");

	class Tester
	{
		private $username = "";
		private $firstName = "";
		private $lastName = "";
		private $email = "";
		private $telephone = "";
		private $projectCount = 0;
		private $bugCount = 0;
		private $lastVisit = "";
		private $lastAction = "";
		
		public function __construct($username, $firstName = "", $lastName = "", 
			$email = "", $telephone = "")
		{
			$this -> username = $username;
			$this -> firstName = $firstName;
			$this -> lastName = $lastName;
			$this -> email = $email;
			$this -> telephone = $telephone;
		}
		
		public function getUsername()
		{
			return $this -> username;
		}
		
		public function getFirstName()
		{
			return $this -> firstName;
		}
		
		public function getLastName()
		{
			return $this -> lastName;
		}
		
		public function getEmail()
		{
			return $this -> email;
		}
		
		public function getTelephone()
		{
			return $this -> telephone;
		}
		
		public function setProjectCount($count)
		{
			$this -> projectCount = $count;
		}
		
		public function getProjectCount()
		{
			return $this -> projectCount;
		}
		
		public function setBugCount($count)
		{
			$this -> bugCount = $count;
		}
		
		public function getBugCount()
		{
			return $this -> bugCount;
		}
		
		public function setLastVisit($lastVisit)
		{
			$this -> lastVisit = $lastVisit;
		}
		
		public function getLastVisit()
		{
			return $this -> lastVisit;
		}
		
		public function setLastAction($lastAction)
		{
			$this -> lastAction = $lastAction;
		}
		
		public function getLastAction()
		{
			return $this -> lastAction;
		}
	}
	
	class Project
	{
		private $projectName = "";
		private $description = "";
		
		public function __construct($projectName, $description)
		{
			$this -> projectName = $projectName;
			$this -> description = $description;
		}
		
		public function getProjectName()
		{
			return $this -> projectName;
		}
		
		public function getDescription()
		{
			return $this -> description;
		}
		
		public function getBugs()
		{
			$bugs = array();
			$query = "SELECT * FROM bugs WHERE ProjectName = '$this -> projectName'";
			
			$result = mysql_query($query, $GLOBALS["conn"]);
			while($row = mysql_fetch_assoc($result))
			{
				$bugs[] = new Bug($row["TesterUsername"], $row["Description"], 
					$row["Priority"], $row["ProjectName"]);
			}
			
			return $bugs;
		}
	}
	
	class Bug
	{
		private $dateFound = null;
		private $testerUsername = null;
		private $description = "";
		private $priority = "";
		private $projectName = "";
		private $state = "";
		private $id = null;
		
		public function __construct($dateFound, $testerUsername, $description, 
			$priority, $projectName, $state, $id = null)
		{
			$this -> dateFound = $dateFound;
			$this -> description = $description;
			$this -> priority = $priority;
			$this -> state = $state;
			$this -> testerUsername = $testerUsername;
			$this -> projectName = $projectName;
			$this -> id = $id;
		}
		
		public function getDateFound()
		{
			return $this -> dateFound;
		}
		
		public function getTesterUsername()
		{
			return $this -> testerUsername;
		}
		
		public function getDescription()
		{
			return $this -> description;
		}
		
		public function getPriority()
		{
			return $this -> priority;
		}
		
		public function getProjectName()
		{
			return $this -> projectName;
		}
		
		public function getState()
		{
			return $this -> state;
		}
		
		public function getId()
		{
			return $this -> id;
		}
	}
	
	abstract class ManagementActions
	{
		public static function getAllTesters($sort = "Username", $page = 1)
		{		  
			$query = "SELECT * FROM testers WHERE Username != 'admin'";
			
			$result = mysql_query($query, $GLOBALS["conn"]);
			
			$testers = array();
			
			while($row = mysql_fetch_assoc($result))
			{
				$tester = new Tester($row["Username"], $row["FirstName"], $row["LastName"],
					$row["Email"], $row["Telephone"]);
				$tester -> setBugCount(count(TesterActions :: getSubmittedBugs($tester -> getUsername())));
				$tester -> setProjectCount(TesterActions :: getProjectCount($tester -> getUsername()));
				$tester -> setLastAction(TesterActions :: getLastAction($tester -> getUsername()));
				$tester -> setLastVisit(TesterActions :: getLastVisit($tester -> getUsername()));
				
				$testers[] = $tester;
			}
			
			$sortFunction = "";
			switch($sort)
			{
				case "Username":
					$sortFunction = "ManagementActions::sort_testers_names";
					break;
					
				case "Projects":
					$sortFunction = "ManagementActions::sort_testers_projects";
					break;
					
				case "Bugs":
					$sortFunction = "ManagementActions::sort_testers_bugs";
					break;
					
				case "Action":
					$sortFunction = "ManagementActions::sort_testers_actions";
					break;
					
				case "Visit":
					$sortFunction = "ManagementActions::sort_testers_visits";
					break;
					
				default:
					die("Wrong sort parameter!");
			}
			
			usort($testers, $sortFunction);
			
			$testersPerPage = 15;
			$pageCount = ceil(count($testers) / $testersPerPage);
			if($page < 0 || $page > $pageCount)
			{
				die("Invalid page number!");
			}
			
			echo "<table>";
			echo "<tr>";
			echo "<th>Username</th><th>Last Visit</th><th>Last Action</th>
				  <th>Projects</th><th>Bugs Found</th>";
			echo "<tr>";
			
			$startIndex = $page;
			
			for($i = ($startIndex - 1) * $testersPerPage; $i < ($startIndex * $testersPerPage); $i ++)
			{
				echo "<tr>";
				
				echo "<td>" . htmlspecialchars($testers[$i] -> getUsername()) . "</td>";
				echo "<td>" . htmlspecialchars($testers[$i] -> getLastVisit()) . "</td>";
				echo "<td>" . htmlspecialchars($testers[$i] -> getLastAction()) . "</td>";
				echo "<td>" . htmlspecialchars($testers[$i] -> getProjectCount()) . "</td>";
				echo "<td>" . htmlspecialchars($testers[$i] -> getBugCount()) . "</td>";
				
				echo "</tr>";
				
				if(count($testers) == $i + 1)
				{
					break;
				}
			}			
			
			echo "</table>";
			return $pageCount;
		}
		
		private static function sort_testers_names($a, $b)
		{
			if($a -> getUsername() === $b -> getUsername())
			{
				return 0;
			}
			
			return strcasecmp($a -> getUsername(), $b -> getUsername());
		}
		
		private static function sort_testers_projects($a, $b)
		{
			if($a -> getProjectCount() == $b -> getProjectCount())
			{
				return 0;
			}
			
			return ($a -> getProjectCount() > $b -> getProjectCount()) ? 1 : -1;
		}
		
		private static function sort_testers_bugs($a, $b)
		{
			if($a -> getBugCount() == $b -> getBugCount())
			{
				return 0;
			}
			
			return ($a -> getBugCount() > $b -> getBugCount()) ? 1 : -1;
		}
		
		private static function sort_testers_actions($a, $b)
		{
			if($a -> getLastAction() == $b -> getLastAction())
			{
				return 0;
			}
			
			return strcasecmp($a -> getLastAction(), $b -> getLastAction());
		}
		
		private static function sort_testers_visits($a, $b)
		{
			if($a -> getLastVisit() == $b -> getLastVisit())
			{
				return 0;
			}
			
			return (strtotime($a -> getLastVisit()) > strtotime($b -> getLastVisit())) ? 1 : -1;
		}
		
		public static function getActiveBugs($projectName = null, $sort = "Description", $page = 1)
		{	
			$query = "SELECT * FROM bugs";
			if($projectName != null)
			{
				$query = "SELECT * FROM bugs WHERE ProjectName = '$projectName'";
			}
			
			$result = mysql_query($query, $GLOBALS["conn"]);
			
			$bugs = array();
			while($row = mysql_fetch_assoc($result))
			{
				if($row["State"] != "Closed" && $row["State"] != "Deleted")
				{
					$bug = new Bug($row["DateFound"], $row["TesterUsername"], 
						$row["Description"], $row["Priority"], $row["ProjectName"],
						$row["State"], $row["ID"]);
					
					$bugs[] = $bug;
				}
			}

			$sortFunction = "";
			switch($sort)
			{
				case "Date":
					$sortFunction = "ManagementActions::sort_bugs_date";
					break;
					
				case "Owner":
					$sortFunction = "ManagementActions::sort_bugs_username";
					break;
				
				case "Description":
					$sortFunction = "ManagementActions::sort_bugs_description";
					break;
					
				case "Priority":
					$sortFunction = "ManagementActions::sort_bugs_priority";
					break;
					
				case "Project":
					$sortFunction = "ManagementActions::sort_bugs_project";
					break;
					
				case "State":
					$sortFunction = "ManagementActions::sort_bugs_state";
					break;
				
				default:
					die("Wrong sort parameter!");
			}
			
			usort($bugs, $sortFunction);
			
			$bugsPerPage = 20;
			$pageCount = ceil(count($bugs) / $bugsPerPage);
			if($page < 0 || $page > $pageCount)
			{
				die("Invalid page number!");
			}
			
			echo "<table>";
			echo "<tr>";
			echo "<th>Date Found</th><th>Description</th><th>Priority</th>
				  <th>Submitted By</th><th>Project</th><th>State</th>";
			echo "</tr>";
			
			$startIndex = $page;
			for($i = ($startIndex - 1) * $bugsPerPage; $i < ($startIndex * $bugsPerPage); $i ++)
			{
				echo "<tr>";
				echo "<td>" . htmlspecialchars($bugs[$i] -> getDateFound()) . "</td>";
				$description = substr($bugs[$i] -> getDescription(), 0, 50);
				echo "<td>" . htmlspecialchars($description) . "</td>";
				echo "<td>" . htmlspecialchars($bugs[$i] -> getPriority()) . "</td>";
				echo "<td>" . htmlspecialchars($bugs[$i] -> getTesterUsername()) . "</td>";
				echo "<td>" . htmlspecialchars($bugs[$i] -> getProjectName()) . "</td>";
				echo "<td>" . htmlspecialchars($bugs[$i] -> getState()) . "</td>";
				echo "</tr>";
				
				if(count($bugs) == $i + 1)
				{
					break;
				}
			}			
			
			echo "</table>";
			return $pageCount;
		}
		
		private static function sort_bugs_description($a, $b)
		{
			if($a -> getDescription() == $b -> getDescription())
			{
				return 0;
			}
			
			return strcasecmp($a -> getDescription(), $b -> getDescription());
		}
		
		private static function sort_bugs_username($a, $b)
		{
			if($a -> getTesterUsername() == $b -> getTesterUsername())
			{
				return 0;
			}
			
			return strcasecmp($a -> getTesterUsername(), $b -> getTesterUsername());
		}
		
		private static function sort_bugs_priority($a, $b)
		{
			if($a -> getPriority() == $b -> getPriority())
			{
				return 0;
			}
			
			return strcasecmp($a -> getPriority(), $b -> getPriority());
		}
		
		private static function sort_bugs_project($a, $b)
		{
			if($a -> getProjectName() == $b -> getProjectName())
			{
				return 0;
			}
			
			return strcasecmp($a -> getProjectName(), $b -> getProjectName());
		}
		
		private static function sort_bugs_state($a, $b)
		{
			if($a -> getState() == $b -> getState())
			{
				return 0;
			}
			
			return strcasecmp($a -> getState(), $b -> getState());
		}
		
		private static function sort_bugs_date($a, $b)
		{
			if($a -> getDateFound() == $b -> getDateFound())
			{
				return 0;
			}
			
			return (strtotime($a -> getDateFound()) > strtotime($b -> getDateFound())) ? 1 : -1;
		}
		
		public static function getProjectNames()
		{
			$names = array();
			$query = "SELECT ProjectName FROM projects";
			$result = mysql_query($query, $GLOBALS["conn"]);
			
			while($row = mysql_fetch_assoc($result))
			{
				$names[] = $row["ProjectName"];
			}
			
			return $names;
		}
	}
	
	class AdminActions extends ManagementActions
	{
		public static function addTester($tester, $passwordMd5)
		{
			$username = mysql_real_escape_string($tester -> getUsername());
			$firstName = mysql_real_escape_string($tester -> getFirstName());
			$lastName = mysql_real_escape_string($tester -> getLastName());
			$email = mysql_real_escape_string($tester -> getEmail());
			$telephone = mysql_real_escape_string($tester -> getTelephone());

			$query = "INSERT INTO testers (Username, PasswordMd5, 
				FirstName, LastName, Email, Telephone, LastVisit,
				LastAction) VALUES ('$username', '$passwordMd5',
				'$firstName', '$lastName', '$email', '$telephone', CURDATE(), ' ')";
				
			$result = mysql_query($query, $GLOBALS["conn"]);
			if($result !== true)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		public static function getTesterUsernames()
		{
			$usernames = array();
			$query = "SELECT Username FROM testers";
			$result = mysql_query($query, $GLOBALS["conn"]);
			while($row = mysql_fetch_assoc($result))
			{
				$usernames[] = $row["Username"];
			}
			
			return $usernames;
		}
		
		public static function editTester($tester, $password)
		{
			$username = $tester -> getUsername();
			$passMd5 = md5($password);
			$firstName = mysql_real_escape_string($tester -> getFirstName());
			$lastName = mysql_real_escape_string($tester -> getLastName());
			$email = mysql_real_escape_string($tester -> getEmail());
			$telephone = mysql_real_escape_string($tester -> getTelephone());
			
			$query = "UPDATE testers SET PasswordMd5 = '$passMd5', FirstName = '$firstName',
					  LastName = '$lastName', Email = '$email', Telephone = '$telephone'
					  WHERE Username = '$username'";
					  
			$result = mysql_query($query, $GLOBALS["conn"]);
			if($result !== true)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		public static function deleteTester($testerUsername)
		{
			if($testerUseame === "admin")
			{
				return false;
			}
		
			$query = "UPDATE bugs SET TesterUsername = '' WHERE TesterUsername = '$testerUsername'";
			$result = mysql_query($query, $GLOBALS["conn"]);
			
			$query = "DELETE FROM testers_projects WHERE TesterUsername = '$testerUsername'";
			$result = mysql_query($query, $GLOBALS["conn"]);

			$query = "DELETE FROM testers WHERE Username = '$testerUsername'";
			$result = mysql_query($query, $GLOBALS["conn"]);
			if($result !== true)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		public static function addProject($project)
		{
			$projectName = mysql_real_escape_string($project -> getProjectName());
			$description = mysql_real_escape_string($project -> getDescription());
			
			$query = "INSERT INTO projects (ProjectName, Description) VALUES (
					  '$projectName', '$description')";
					  
			$result = mysql_query($query, $GLOBALS["conn"]);
			if($result !== true)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		public static function editProject($projectName, $project)
		{
			$newName = mysql_real_escape_string($project -> getProjectName());
			$description = mysql_real_escape_string($project -> getDescription());
			
			$query = "UPDATE projects SET ProjectName = '$newName', Description = '$description'
					  WHERE ProjectName = '$projectName'";
			$result = mysql_query($query, $GLOBALS["conn"]);
			if($result !== true)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		public static function deleteProject($projectName)
		{
			$query = "DELETE FROM bugs WHERE ProjectName = '$projectName'";
			$result = mysql_query($query, $GLOBALS["conn"]);
			
			$query = "DELETE FROM testers_projects WHERE ProjectName = '$projectName'";
			$result = mysql_query($query, $GLOBALS["conn"]);
			
			$query = "DELETE FROM projects WHERE ProjectName = '$projectName'";
			$result = mysql_query($query, $GLOBALS["conn"]);
			
			if($result !== true)
			{
				return false;
			}
			else
			{	
				return true;
			}
		}
	}
	
	class TesterActions extends ManagementActions
	{
		public static function addBug($bug)
		{
			$dateFound = $bug -> getDateFound();
			$description = mysql_real_escape_string($bug -> getDescription());
			$testerUsername = mysql_real_escape_string($bug -> getTesterUsername());
			$priority = mysql_real_escape_string($bug -> getPriority());
			$projectName = mysql_real_escape_string($bug -> getProjectName());
			$state = mysql_real_escape_string($bug -> getState());
			
			$query = "INSERT INTO testers_projects (ProjectName, TesterUsername) 
				VALUES ('$projectName', '$testerUsername')";
			$result = mysql_query($query, $GLOBALS["conn"]);
			
			$query = "INSERT INTO bugs (DateFound, TesterUsername, Description, Priority,
					  ProjectName, State) VALUES ('$dateFound', '$testerUsername', '$description',
					  '$priority', '$projectName', '$state')";
					  
			$result = mysql_query($query, $GLOBALS["conn"]);
			if($result !== true)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		public static function getSubmittedBugs($testerUsername)
		{
			$query = "SELECT * FROM bugs WHERE TesterUsername = '$testerUsername'";
			$result = mysql_query($query, $GLOBALS["conn"]);
			
			$bugs = array();
			while($row = mysql_fetch_assoc($result))
			{
				if($row["State"] !== "Closed" && $row["State"] !== "Deleted")
				{
					$bugs[] = new Bug($row["DateFound"], $row["TesterUsername"], $row["Description"],
						$row["Priority"], $row["ProjectName"], $row["State"], $row["ID"]);
				}
			}
			
			return $bugs;
		}
		
		public static function getProjectCount($testerUsername)
		{
			$query = "SELECT * FROM testers_projects WHERE TesterUsername = '$testerUsername'";
			$result = mysql_query($query, $GLOBALS["conn"]);
			return mysql_num_rows($result);
		}
		
		public static function getLastAction($testerUsername)
		{
			$query = "SELECT LastAction FROM testers WHERE Username = '$testerUsername'";
			$result = mysql_query($query, $GLOBALS["conn"]);
			$row = mysql_fetch_assoc($result);
			
			return $row["LastAction"];
		}
		
		public static function getLastVisit($testerUsername)
		{
			$query = "SELECT LastVisit FROM testers WHERE Username = '$testerUsername'";
			$result = mysql_query($query, $GLOBALS["conn"]);
			$row = mysql_fetch_assoc($result);
			
			return $row["LastVisit"];
		}
		
		public static function editBug($id, $bug)
		{
			$description = mysql_real_escape_string($bug -> getDescription());
			$priority = $bug -> mysql_real_escape_string(getPriority());
			$state = mysql_real_escape_string($bug -> getState());
			
			$query = "UPDATE bugs SET Description = '$description', Priority = '$priority',
					  State = '$state' WHERE ID = $id";
			$result = mysql_query($query, $GLOBALS["conn"]);
			if($result !== true)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		public static function deleteBug($id)
		{
			$query = "UPDATE bugs SET State = 'Deleted' WHERE ID = $id";
			$result = mysql_query($query, $GLOBALS["conn"]);
			if($result !== true)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		public static function setLastAction($testerUsername, $action)
		{
			$query = "UPDATE testers SET LastAction = '$action' WHERE Username = '$testerUsername'";
			$result = mysql_query($query, $GLOBALS["conn"]);
			
			if($result !== true)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		public static function setLastVisit($testerUsername)
		{
			$date = date("d/M/Y H:i:s");
			$query = "UPDATE testers SET LastVisit = '$date' WHERE Username = '$testerUsername'";
			$result = mysql_query($query, $GLOBALS["conn"]);
			
			if($result !== true)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
	}