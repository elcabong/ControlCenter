<?php
// this needs to be updated to current version of db.
// gets called in servercheck.php
$DBVERSION = "1.1.8";
$CCVERSION = "1.0.1";
if(isset($getversion) && $getversion == "yes") {
	return $DBVERSION;
} else {

	require_once "$INCLUDES/includes/functions.php";

	if(!isset($folderlevel)) {
		$found = false;
		$path = './Portal';
		while(!$found){
			if(file_exists($path)){ 
				$found = true;
			}
			else{ $path = '../'.$path; }
		}
	}
	if(substr($path, 0, 3) == "../") { $folderlevel = "../"; } else { $folderlevel = "./"; };

	$servercheckloc = $folderlevel . "servercheck.php";
	$thepath = $folderlevel;
	$ADDONDIR = $folderlevel . "addons/";

	$missing = folderRequirements($folderlevel);
	if (!file_exists($INCLUDES . "/sessions/config.db") || $missing > 0) { header('Location: ' . $servercheckloc);exit; }

	try {
		$configdb = new PDO('sqlite:' . $INCLUDES . '/sessions/config.db');
	} catch (PDOException $e) {
		$log->LogFatal("Fatal: User could not open DB: $e->getMessage().  from " . basename(__FILE__));
	}

	try {
		$sql = "SELECT * FROM controlcenter";
		foreach ($configdb->query($sql) as $row)
		{
			$thesetting = $row['CCsetting'];
			${$thesetting} = $row['CCvalue'];
		}
	} catch(PDOException $e) {
		$log->LogFatal("Fatal: User could not open DB: $e->getMessage().  from " . basename(__FILE__));
	}

	//check cc/db version for updates
	//will have to run check against mainserver for new version
	//will need db area for last update check date, if longer than x, check for updates, if x too far away (longer than 24 hours) check for udpates and reset value
	if(!isset(${'ccversion'}) || ${'ccversion'} < $CCVERSION) { header('Location: ' . $servercheckloc);exit; }
	if(!isset(${'dbversion'}) || ${'dbversion'} < $DBVERSION) { header('Location: ' . $servercheckloc . '?newdbversion=' . $DBVERSION);exit; }

	try {
		$sql = "SELECT * FROM users";
		$userid = 0;
		$USERNAMES = array("none");
		$HOWMANYUSERS = 0;
		foreach ($configdb->query($sql) as $row) {
			$userid = $row['userid'];
			$HOWMANYUSERS++;
			$USERNAME = "USERNAME$userid";
			${$USERNAME} = $row['username'];
			$USERNAMES[$userid] = ${$USERNAME};
		}
	} catch(PDOException $e) {
		$log->LogFatal("Fatal: User could not open DB: $e->getMessage().  from " . basename(__FILE__));
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
	} catch(PDOException $e) {
		$log->LogFatal("Fatal: User could not open DB: $e->getMessage().  from " . basename(__FILE__));
	}
				
	if($HOWMANYUSERS > 0) {
		if (!isset($_SESSION['usernumber']) || $_SESSION['usernumber'] == "choose") {
			if(isset($_POST['usernumber'])) {
				$_SESSION['usernumber'] = $_POST['usernumber']; 
			} elseif (!isset($_GET['user'])) {
				$log->LogWarn("User NOUSER redirected to login screen from " . $_SERVER["REQUEST_URI"]);
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
				 if(isset($row['passwordreset']) && $row['passwordreset'] == '1') {
					$log->LogInfo("User $authusername had password reset.  Auto Logout from " . $_SERVER['SCRIPT_FILENAME']);
					$configdb->exec("UPDATE users SET passwordreset='0' WHERE userid='".$usernumber."'");
					header('Location: ./logout.php');exit;
				 }
			}
		} catch(PDOException $e) {
			$log->LogFatal("Fatal: User could not open DB: $e->getMessage().  from " . basename(__FILE__));
		}
	}

	$WANCONNECTION = 0;
	if(substr($_SERVER['REMOTE_ADDR'],0,6) != substr($_SERVER['SERVER_ADDR'],0,6)) { 
		$WANCONNECTION = 1; 
	}

	$settingsarray = array();
	try {
		$sql = "SELECT * FROM settings";
		foreach ($configdb->query($sql) as $row) {
			$settingname = $row['setting'];
			$settingsarray["$settingname"]["value1"] = $row['settingvalue1'];
			if(isset($row['settingvalue2'])){
				$settingsarray["$settingname"]["value2"] = $row['settingvalue2'];
			}
		}
	} catch(PDOException $e) {
		$log->LogFatal("Fatal: User could not open DB: $e->getMessage().  from " . basename(__FILE__));
	}

	if($settingsarray['LogLevel']['value1'] !== "INFO") {
		$thisloglevel = $settingsarray['LogLevel']['value1'];
		$loglevel = "KLogger::$thisloglevel";
		$log = new KLogger ( $INCLUDES."/logs/log-$date.log" , $loglevel );
	}


	if(isset($authusername)) {
		$log->LogDebug("User $authusername loaded " . basename(__FILE__) . " from " . $_SERVER['SCRIPT_FILENAME']);
	} else {
		$log->LogDebug("User loaded " . basename(__FILE__) . " from " . $_SERVER['SCRIPT_FILENAME']);
	}
}
?>