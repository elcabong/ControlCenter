<?php
$found2 = false;
$path2 = './sessions';
while(!$found2){
	if(file_exists($path2)){ 
		$found2 = true;
		$sessionsloc = $path2;
	}
	else{ $path2= '../'.$path2; }
}
ini_set('display_errors', 'Off');
ini_set('session.gc_maxlifetime', 86400);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100	);
ini_set('session.save_path', "$sessionsloc");
ini_set('session.cookie_lifetime', 86400);
if(!isset($_SESSION)){session_start();}

$thepath = dirname(dirname($_SERVER['PHP_SELF']));
if (!file_exists($sessionsloc . "/config.db")) { header('Location: ' . $thepath . '/servercheck.php');exit; }
$configdb = new PDO('sqlite:'.$sessionsloc.'/config.db');

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
			array_push($USERNAMES,${$USERNAME});
			}
	} catch(PDOException $e)
		{
		echo $e->getMessage();
		}

if ($HOWMANYUSERS == 0) { header('Location: ' . $thepath . '/servercheck.php');exit; }		

		try {
		$sql = "SELECT * FROM rooms";
			$TOTALROOMS=0;
			foreach ($configdb->query($sql) as $row) {
			$TOTALROOMS++;
			$ROOMXBMC = "ROOM$TOTALROOMS"."XBMC";
			$ROOMXBMC2 = "$ROOMXBMC"."2";
			$ROOMXBMCM = "$ROOMXBMC"."M";
			$ROOMname = "ROOM$TOTALROOMS"."N";
			
			${$ROOMname} = $row['roomname'];
			${$ROOMXBMC} = $row['ip1'];
			if(isset($row['ip2']) && $row['ip2'] != '') { ${$ROOMXBMC2} = $row['ip2']; } else { ${$ROOMXBMC2} = 0; }
			if(isset($row['mac']) && $row['mac'] != '') { ${$ROOMXBMCM} = $row['mac']; } else { ${$ROOMXBMCM} = 0; }
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
			   $_SESSION['usernumber'] = $_GET['user']; }
			   $usernumber = $_SESSION['usernumber'];
			} else {
				if (isset($_GET['user']) && $_GET['user']!="choose") {
					$_SESSION['usernumber'] = $_GET['user']; }
					$usernumber = $_SESSION['usernumber'];
			}
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
			}	 
		} catch(PDOException $e)
			{
			echo $e->getMessage();
			}
			 
}
?>