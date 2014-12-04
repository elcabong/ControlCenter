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
	try {
		$sql = "SELECT * FROM users";
		$userid = 0;
		$USERNAMES = array("none");
		foreach ($configdb->query($sql) as $row) {
			$userid = $row['userid'];
			$USERNAME = "USERNAME$userid";
			${$USERNAME} = $row['username'];
			$USERNAMES[$userid] = ${$USERNAME};
		}
	} catch(PDOException $e) {
		$log->LogFatal("Fatal: User could not open DB: $e->getMessage().  from " . basename(__FILE__));
	}
	$alertarray = array();
	try {
		$sql = "SELECT * FROM alerts WHERE userid = '$usernumber' ORDER BY alert_id DESC";
		foreach ($configdb->query($sql) as $thisalert) {
			if($thisalert['viewed']==0) {
				$alertarray['unread'][$thisalert['alert_id']]['message'] = $thisalert['message'];
				if($thisalert['from_userid'] == 0) {
					$alertarray['unread'][$thisalert['alert_id']]['from_userid'] = "System Alert";
				} else {
					$alertarray['unread'][$thisalert['alert_id']]['from_userid'] = $USERNAMES[$thisalert['from_userid']];
				}
				$alertarray['unread'][$thisalert['alert_id']]['created'] = $thisalert['created'];
			} else {
				$alertarray['read'][$thisalert['alert_id']]['message'] = $thisalert['message'];
				if($thisalert['from_userid'] == 0) {
					$alertarray['read'][$thisalert['alert_id']]['from_userid'] = "System Alert";
				} else {
					$alertarray['read'][$thisalert['alert_id']]['from_userid'] = $USERNAMES[$thisalert['from_userid']];
				}
				$alertarray['read'][$thisalert['alert_id']]['viewed'] = $thisalert['viewed'];
				$alertarray['read'][$thisalert['alert_id']]['created'] = $thisalert['created'];
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
			<link type='text/css' href='../css/modal.css?1' rel='stylesheet' media='screen' />
		</head>
		<body>
			<div id='alertcontainer'>
				<div id='logo'>
					<h1>Alerts:</h1>
				</div>
				<div id="content"><br><br>
				<?php
					// unread content
					if(!empty($alertarray['unread'])) {
						//print_r($alertarray['unread']);
						
						foreach($alertarray['unread'] as $unread) {
							echo "<div class='alert'>";
								echo "<span class='from'>".$unread['from_userid']."</span>";
								
								echo "<span class='dates'>Created:".$unread['created']."<br />mark read</span>";
								
								echo "<span class='message clearl'>".$unread['message']."</span>";
								
								echo "<br class='clear' />";
							
							echo "</div>";
						}
						
						
						
					}
					echo "<br><br>";
						
						
						
						
					// read content
					if(!empty($alertarray['read'])) {
						//print_r($alertarray['read']);
						
						
						foreach($alertarray['read'] as $read) {
							echo "<div class='alert read'>";
								echo "<span class='from'>".$read['from_userid']."</span>";
								
								echo "<span class='dates'>Created:".$read['created']."<br />Viewed:".$read['viewed']."</span>";
								
								echo "<span class='message clearl'>".$read['message']."</span>";
								
								echo "<br class='clear' />";
							
							
							echo "</div>";
						}						
						
						
						
						
						
					}
					echo "<br><br><br>";
				?>
				</div>
			<script>
			// handler for mark read button
			// sets current timestamp to db and adds class read to this.parent
	
			</script>
			</div>
		</body>
	</html>
	<?php
}
exit;
?>