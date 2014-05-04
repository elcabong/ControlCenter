<?php
// this needs to be updated to current version of db.
$DBVERSION = "1.0.1";

require_once "functions.php";
require "startsession.php";

if(substr($path2, 0, 3) == "../") { $folderlevel = "../"; } else { $folderlevel = "./"; };

$servercheckloc = $folderlevel . "servercheck.php";
$thepath = $folderlevel;

$ADDONDIR = $folderlevel . "addons/";

$missing = folderRequirements($folderlevel);
if (!file_exists($sessionsloc . "/config.db") || $missing > 0) { header('Location: ' . $servercheckloc);exit; }
$configdb = new PDO('sqlite:'.$sessionsloc.'/config.db');

	try {
		$sql = "SELECT dbversion FROM controlcenter ORDER BY dbversion DESC LIMIT 1";
		foreach ($configdb->query($sql) as $row)
		{
			if(isset($row['dbversion'])) {
			$thedbversion = $row['dbversion'];
			}
		}
		if($thedbversion < $DBVERSION) { header('Location: ' . $servercheckloc . '?newdbversion=' . $DBVERSION);exit; }
	} catch(PDOException $e)
		{
		echo $e->getMessage();
		}

	try {
		$sql = "SELECT * FROM users";
		$userid = 0;
		$USERNAMES = array("none");
		$HOWMANYUSERS = 0;
		foreach ($configdb->query($sql) as $row)
			{
			$userid = $row['userid'];
			$HOWMANYUSERS++;
			$USERNAME = "USERNAME$userid";
			${$USERNAME} = $row['username'];
			$USERNAMES[$userid] = ${$USERNAME};
			}
	} catch(PDOException $e)
		{
		echo $e->getMessage();
		}

if ($HOWMANYUSERS == 0) { header('Location: ' . $servercheckloc);exit; }	

		try {
		$sql = "SELECT * FROM rooms";
			$TOTALROOMS=0;
			foreach ($configdb->query($sql) as $row) {
			$TOTALROOMS++;
			$theroomid = $row['roomid'];
			$theperm = "USRPR$theroomid";
			${$theperm} = "0";
			$ROOMname = "ROOM$theroomid"."N";
			${$ROOMname} = $row['roomname'];
			}
		} catch(PDOException $e)
			{
			echo $e->getMessage();
			}
			
	if($HOWMANYUSERS > 0) {
			if (!isset($_SESSION['usernumber']) || $_SESSION['usernumber'] == "choose") {
				if(isset($_POST['usernumber'])) { $_SESSION['usernumber'] = $_POST['usernumber']; }
				elseif (!$_GET['user']) {
					header("Location: $thepath");
						exit;
				} else {
					$_SESSION['usernumber'] = $_GET['user']; 
				}			   
			   $usernumber = $_SESSION['usernumber'];
			} elseif (!isset($_SESSION['usernumber']) && isset($_GET['user']) && $_GET['user']!="choose") {
				$_SESSION['usernumber'] = $_GET['user']; 
			}
			$usernumber = $_SESSION['usernumber'];
	}

if($usernumber != "choose") {
		try {
		$sql = "SELECT * FROM users WHERE userid = $usernumber LIMIT 1";
			foreach ($configdb->query($sql) as $row) {
				$SETTINGSACCESS = $row['settingsaccess'];
				$authusername           = $AUTH_USERNAME          = $row['username'];
				 if(isset($row['password'])) { $authpassword           = $AUTH_PASS              = $row['password']; }
				 if($AUTH_PASS) { $AUTH_ON = 1; } else { $AUTH_ON = 0; }
				 $authsecured            = $AUTH_ON;
				 if(isset($row['navgroupaccess'])) { $NAVGROUPS              = $row['navgroupaccess']; }
				 $userwanenabled = $row['wanenabled'];
			}	 
		} catch(PDOException $e)
			{
			echo $e->getMessage();
			}

}

$WANCONNECTION = 0;
if(substr($_SERVER['REMOTE_ADDR'],0,6) != substr($_SERVER['SERVER_ADDR'],0,6)) { 
	$WANCONNECTION = 1; 
}
?>