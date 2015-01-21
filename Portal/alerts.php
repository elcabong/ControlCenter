<?php
$grouplevels = Array();
$grouplevels[1] = "All Users";
$grouplevels[3] = "Admins";
$grouplevels[5] = "Super Admins";
require_once "startsession.php";
if(isset($_SESSION['usernumber'])) {
	$usernumber = $_SESSION['usernumber'];
} else {
	$log->LogWarn("User Redirected to Login Page, No usernumber in SESSION info. From " . basename(__FILE__) . " from " . $_SERVER['SCRIPT_FILENAME']);
	header("Location: login.php");
    exit;
}
$log->LogDebug("User " . $_SESSION['username'] . " loaded " . basename(__FILE__) . " from " . $_SERVER['SCRIPT_FILENAME']);

$getalerts = 0;
if(isset($_GET['getalerts'])) {
	$getalerts = 1;
}
$submitAlert = 0;
if(isset($_GET['submitAlert'])) {
	$submitAlert = 1;
}
$alertexists = 0;
require_once "$INCLUDES/includes/functions.php";
try {
	$configdb = new PDO("sqlite:$INCLUDES/sessions/config.db");
} catch(PDOException $e)
	{
	$log->LogError("$e->getMessage()" . basename(__FILE__));
	}
if(!empty($_POST["markread"])) {
	$alertid = $_POST["markread"];
	$configdb->exec("INSERT INTO alerts_viewed (userid,alert_id) VALUES ($usernumber,$alertid)");
	exit;
}
try {
	$sql = "SELECT * FROM users";
	$userid = 0;
	$USERIDS = array();
	$USERNAMES = array("none");
	$USERLEVEL = array("none");
	foreach ($configdb->query($sql) as $row) {
		$userid = $row['userid'];
		$USERIDS[] = "$userid";
		$USERNAME = "USERNAME$userid";
		${$USERNAME} = $row['username'];
		$USERNAMES[$userid] = ${$USERNAME};
		$USERLEVEL[$userid] = $row['userlevel'];
	}
} catch(PDOException $e) {
	$log->LogFatal("Fatal: User could not open DB: $e->getMessage().  from " . basename(__FILE__));
}
	
if($submitAlert == 1) {
	if(isset($_GET['message'])) {
		$message = urlencode($_GET['message']);
	}
	if(isset($_GET['toUser'])) {
		$toUser = $_GET['toUser'];
	}	
	if(isset($_GET['fromUser'])) {
		$fromUser = $_GET['fromUser'];
	}
	$toUser = explode(":",$toUser);
	
	// get UTC timestamp here and insert into db instead of using sqlite timestamp.
	// this is needed to it is consistent independant of server settings for sqlite/php
	// echo gmdate("Y-m-d H:i:s"); <<  this is in UTC
	
	if($toUser[0] == 'user') {
		$configdb->exec("INSERT INTO alerts (userid,message,from_userid) VALUES ($toUser[1],\"$message\",$fromUser)");
	} elseif($toUser[0] == 'userlevel') {
		$configdb->exec("INSERT INTO alerts (userlevel,message,from_userid) VALUES ($toUser[1],\"$message\",$fromUser)");
	}
	$submitAlert = 2;
}
$viewedalerts = array();
$viewedalertsdate = array();
$thisviewedalert = '';
try {
	$sql = "SELECT alert_id,viewed FROM alerts_viewed WHERE userid = '$usernumber'";
	foreach ($configdb->query($sql) as $thisviewedalert) {
		$viewedalerts[] = $thisviewedalert['alert_id'];
		$viewedalertsdate[$thisviewedalert['alert_id']] = $thisviewedalert['viewed'];
	}
} catch(PDOException $e)
	{
		$log->LogError("$e->getMessage()" . basename(__FILE__));
	}
if($getalerts == 0) {
	try {
		$sql = "SELECT * FROM alerts WHERE (userid = '$usernumber' OR userlevel <= '$USERLEVEL[$usernumber]') ORDER BY alert_id DESC";
		foreach ($configdb->query($sql) as $recentalerts) {
			if(!in_array($recentalerts['alert_id'], $viewedalerts)) {
				$alertexists = $recentalerts['alert_id'];
				break;
			}
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
		$sql = "SELECT * FROM alerts WHERE userid = '$usernumber' OR userlevel <= '$USERLEVEL[$usernumber]' ORDER BY alert_id DESC";
		foreach ($configdb->query($sql) as $thisalert) {
			if(!in_array($thisalert['alert_id'], $viewedalerts)) {
				$alertarray['unread'][$thisalert['alert_id']]['message'] = $thisalert['message'];
				if($thisalert['from_userid'] == 0) {
					$alertarray['unread'][$thisalert['alert_id']]['from_userid'] = "System";
				} else {
					$alertarray['unread'][$thisalert['alert_id']]['from_userid'] = $USERNAMES[$thisalert['from_userid']];
				}
				$alertarray['unread'][$thisalert['alert_id']]['created'] = $thisalert['created'];
				if(isset($thisalert['userlevel']) && $thisalert['userlevel'] != '') {
					$alertarray['unread'][$thisalert['alert_id']]['togroup'] = $grouplevels[$thisalert['userlevel']];
				} else {
					$alertarray['unread'][$thisalert['alert_id']]['togroup'] = "me";
				}
			} else {
				$alertarray['read'][$thisalert['alert_id']]['message'] = $thisalert['message'];
				if($thisalert['from_userid'] == 0) {
					$alertarray['read'][$thisalert['alert_id']]['from_userid'] = "System";
				} else {
					$alertarray['read'][$thisalert['alert_id']]['from_userid'] = $USERNAMES[$thisalert['from_userid']];
				}
				$alertarray['read'][$thisalert['alert_id']]['viewed'] = $viewedalertsdate[$thisalert['alert_id']];
				$alertarray['read'][$thisalert['alert_id']]['created'] = $thisalert['created'];
				if(isset($thisalert['userlevel']) && $thisalert['userlevel'] != '') {
					$alertarray['read'][$thisalert['alert_id']]['togroup'] = $grouplevels[$thisalert['userlevel']];
				} else {
					$alertarray['read'][$thisalert['alert_id']]['togroup'] = "me";
				}
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
			<link type='text/css' href='../css/modal.css?2' rel='stylesheet' />
		</head>
		<body>
			<div id='alertcontainer'>
				<div id='logo'>
					<h1>Alerts:</h1>
				</div>
				<div id="content"><br><br>
					<div id='SendNew'>
						<h1>Create New Alert: <span><input id="AlertMessage" name="AlertMessage" type="text" size="35" placeholder="Alert Message"></span>
							<span>For User:
								<select id="AlertUser">
									<option value="">Choose</option>
									<?php	foreach($USERIDS as $thisuserid) {
										echo "<option value=\"user:$thisuserid\">$USERNAMES[$thisuserid]</option>";
									}?>
									<option value="" disabled>======</option>
									<option value="userlevel:1"><?php echo "$grouplevels[1]";?></option>
									<option value="userlevel:3"><?php echo "$grouplevels[3]";?></option>
									<option value="userlevel:5"><?php echo "$grouplevels[5]";?></option>
								</select>
							</span>
							<span>
								<input name="submit" value="Submit" class="ui-button ui-widget ui-state-default ui-corner-all" id="newalertsubmit" type="button">
							</span>
						</h1>
					</div><br>
					<?php if($submitAlert == 2) { echo "<span>alert created</span>"; } ?>
					<hr><br>
				<?php
					// unread content
					if(!empty($alertarray['unread'])) {
						foreach($alertarray['unread'] as $thisid => $unread) {
							$createdtime = date_convert($unread['created'], 'UTC', 'Y-m-d H:i:s', 'America/Los_Angeles', 'Y-m-d H:i:s');
					
							echo "<div class='alert' id='$thisid'>";
								echo "<span class='from'>".$unread['from_userid']." Alerted ".$unread['togroup']."</span>";
								echo "<span class='dates'>Created:".$createdtime."<br /><span class='markread'>Mark Viewed</span></span>";
								echo "<span class='message clearl'>".$unread['message']."</span>";
								echo "<br class='clear' />";
							echo "</div>";
						}
					}
					echo "<br><br>";
						
					// read content
					if(!empty($alertarray['read'])) {
						foreach($alertarray['read'] as $read) {
							//date_convert('20131028000000', 'UTC', 'YmdHis', 'Europe/Athens', 'd/m/Y H:i:s');
							//$testtime = date_convert('2013-10-28 00:00:00', 'UTC', 'Y-m-d H:i:s', 'America/Los_Angeles', 'd/m/Y H:i:s');
							//echo $testtime;
							//$createdtime = $read['created'];
							//America/Los_Angeles needs to be a variable, that gets pulled from user_preferences table
							$createdtime = date_convert($read['created'], 'UTC', 'Y-m-d H:i:s', 'America/Los_Angeles', 'Y-m-d H:i:s');
							$readtime = date_convert($read['viewed'], 'UTC', 'Y-m-d H:i:s', 'America/Los_Angeles', 'Y-m-d H:i:s');
						
							echo "<div class='alert read'>";
								echo "<span class='from'>".$read['from_userid']." Alerted ".$read['togroup']."</span>";
								echo "<span class='dates'>Created:".$createdtime."<br />Viewed:".$readtime."</span>";
								echo "<span class='message clearl'>".$read['message']."</span>";
								echo "<br class='clear' />";
							echo "</div>";
						}						
					}
					echo "<br><br><br>";
					//print_r(timezone_identifiers_list());
				?>
				</div>
				<script>
					$(".markread").click(function() {
						$(this).parent().parent().addClass('read');
						$(this).text('Viewed: Just Now');
						var thisid = $(this).parent().parent().attr('id');
						$.post( "./alerts.php", { markread: thisid } );
					});
					$("#newalertsubmit").click(function() {
						var message = $("#AlertMessage").val();
						var user = $("#AlertUser option:selected").val();
						if(user == '') {
							alert("Please Select User"); 
						} else if( message == '') {
							alert("Alert message is blank");
						} else {
							safemessage = encodeURIComponent(message);
							safetouser = user;
							$('#modal').load('alerts.php?submitAlert=1&message='+safemessage+'&toUser='+safetouser+'&fromUser=<?php echo $usernumber; ?>&getalerts=1').modal({
								opacity: 75,
								overlayClose: true
							});
							return false;
						}
					});
				</script>
			</div>
		</body>
	</html>
	<?php
}
exit;
?>