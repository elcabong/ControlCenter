<?php
require('config.php');
$log->LogDebug("User loaded " . basename(__FILE__));
if(isset($_GET['inputusername']) && $_GET['inputusername'] =='1') {
	if(isset($_POST['user'])) {
		$user = $_POST['user'];
		$configdb = new PDO("sqlite:$INCLUDES/sessions/config.db");
		try {
			$sql = "SELECT * FROM users WHERE username = '$user' LIMIT 1";
			foreach ($configdb->query($sql) as $row) {
				if(isset($row['userid']) && $row['userid'] != '' && $row['userid'] != 0) {
					$_SESSION['usernumber'] = $row['userid'];
				}
			}
		} catch(PDOException $e)
			{
			$log->LogFatal("Fatal: User $authusername could not open DB: $e->getMessage().  from " . basename(__FILE__));
			//echo $e->getMessage();
			}
	}
	if(!isset($_SESSION['usernumber']) || $_SESSION['usernumber'] == 'choose') { 
		header( "refresh: 0; url=./logout.php?loginerror=1" );
		exit;
	}
}
if(isset($_GET['user']) && $_GET['user'] =='choose') {
	header( "refresh: 0; url=../login.php?user=choose" );
	exit;
}
if($WANCONNECTION == 1 && $userwanenabled != 1) {
	$log->LogWarn("FAILED LOGIN user $authusername not allowed from WAN");
	header("refresh: 0; url=./logout.php?loginerror=1");
	exit;
}
If (!$authsecured) {
$_SESSION['username'] = $authusername;
$log->LogInfo("User $authusername LOGGED IN with no password");
header( "refresh: 0; url=index.php" );
    exit;
}
if(isset($_POST['user']) && isset($_POST['password'])) {
    if ($_POST['user']==$authusername && $_POST['password']==$authpassword) {
        $_SESSION["$authusername"] = $authusername;
		$_SESSION['username'] = $authusername;
		$_SESSION['loginerror'] = 0;
		$log->LogInfo("User $authusername LOGGED IN");
        header( "refresh: 0; url=index.php" );
        exit;
    } else {
		$log->LogWarn("FAILED LOGIN by $authusername");
		header( "refresh: 0; url=./logout.php?loginerror=1" );
		exit;
	}
}
?>