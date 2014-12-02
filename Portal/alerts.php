<?php
require_once "startsession.php";
$log->LogDebug("User " . $_SESSION['username'] . " loaded " . basename(__FILE__) . " from " . $_SERVER['SCRIPT_FILENAME']);

$getalerts = 0;
if(isset($_GET['getalerts'])) {
	$getalerts = 1;
}
$alertexists = 0;
$usernumber = $_SESSION['usernumber'];
try {
	$configdb = new PDO("sqlite:$INCLUDES/sessions/config.db");
} catch(PDOException $e)
	{
	$log->LogError("$e->getMessage()" . basename(__FILE__));
	}

if($getalerts == 0) {
	try {
		$sql = "SELECT alert_id FROM alerts WHERE userid = '$usernumber' AND viewed = '0' ORDER BY alert_id DESC LIMIT 1";
		foreach ($configdb->query($sql) as $lastalert) {
			$alertexists = $lastalert['alert_id'];
		}
	} catch(PDOException $e)
		{
			$log->LogError("$e->getMessage()" . basename(__FILE__));
		}
	if($alertexists != 0) {
		echo "newalert";
	} else {
		echo "none";
	}
	exit;
} else {
	$log->LogInfo("User " . $_SESSION['username'] . " checked alerts from " . $_SERVER['SCRIPT_FILENAME']);
	$alertarray = array();
	try {
		$sql = "SELECT * FROM alerts WHERE userid = '$usernumber' ORDER BY alert_id DESC";
		foreach ($configdb->query($sql) as $thisalert) {
			if($thisalert['viewed']==0) {
				$alertarray['unread'][$thisalert['alert_id']]['message'] = $thisalert['message'];
				$alertarray['unread'][$thisalert['alert_id']]['from_userid'] = $thisalert['from_userid'];
			} else {
				$alertarray['read'][$thisalert['alert_id']]['message'] = $thisalert['message'];
				$alertarray['read'][$thisalert['alert_id']]['from_userid'] = $thisalert['from_userid'];
				$alertarray['read'][$thisalert['alert_id']]['viewed'] = $thisalert['viewed'];
			}
		}
	} catch(PDOException $e)
		{
			$log->LogError("$e->getMessage()" . basename(__FILE__));
		}
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Alerts</title>
			<link type='text/css' href='../css/modal.css' rel='stylesheet' media='screen' />
		</head>
		<body>
			<div id='alertcontainer'>
				<div id='logo'>
					<h1>Alerts:</h1>
				</div>
				<div id="content">
				<?php
					// unread content
					if(!empty($alertarray['unread'])) {
						print_r($alertarray['unread']);
					}
					echo "<br><br><br>";
						
						
						
						
					// read content
					if(!empty($alertarray['read'])) {
						print_r($alertarray['read']);
					}
					echo "<br><br><br>";
				?>
				</div>
			</div>
		</body>
	</html>
	<?php	print_r($alertarray);
}
exit;
?>