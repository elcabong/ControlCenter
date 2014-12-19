<?php
require_once "startsession.php";
if(isset($_SESSION['usernumber'])) {
	$usernumber = $_SESSION['usernumber'];
} else {
	$log->LogWarn("User Redirected to Login Page, No usernumber in SESSION info. From " . basename(__FILE__) . " from " . $_SERVER['SCRIPT_FILENAME']);
	header("Location: login.php");
    exit;
}
$log->LogDebug("User " . $_SESSION['username'] . " loaded " . basename(__FILE__) . " from " . $_SERVER['SCRIPT_FILENAME']);
require_once "$INCLUDES/includes/auth.php";

$usernum = 0;
if(isset($_GET['user'])) {
	$usernum = $_GET['user'];
}
//require("$INCLUDES/includes/config.php");



// check if usernumber == usernum or if user is superadmin else exit;
//if the user, give current pass check, and 2 new fields for new password
//if superadmin, just give the 2 new fields for new password


$getalerts = 1;

$submitAlert = 0;
if(isset($_GET['submitAlert'])) {
	$submitAlert = 1;
}
$alertexists = 0;
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
	$HOWMANYUSERS = 0;
	foreach ($configdb->query($sql) as $row) {
		$userid = $row['userid'];
		$USERIDS[] = "$userid";
		$USERNAME = "USERNAME$userid";
		${$USERNAME} = $row['username'];
		$USERNAMES[$userid] = ${$USERNAME};
		$USERLEVEL[$userid] = $row['userlevel'];
		$HOWMANYUSERS++;
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
/*
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
		}*/
?>
	
	
	
	
	
	
	
	
	
	<!DOCTYPE html>
	<html>
		<head>
			<title>Change Password</title>
			<link type='text/css' href='../css/modal.css?2' rel='stylesheet' />
		</head>
		<body>
			<div id='passwordcontainer'>
				<div id='logo'>
					<h1>Password Update for user: <?php echo $USERNAMES[$usernum]; ?></h1>
				</div>
				<div id="content"><br><br>
					<div id='UpdatePassword'>
						<?php // if($usernumber != $usernum && $usernumber not admin) { ?>
							<!--  message that they do not have access to update this item             
							-->
						<?php // exit; } ?>
						<table>
						<?php // if usernumber = usernum and has password set in db { 
							if($usernumber == $usernum) { ?>
							<tr><td><h1>Current Password:</h1> </td><td><input id="CurrentPassword" name="CurrentPassword" type="password" size="35" placeholder="Current Password"></td></tr>
							<tr><td>&nbsp;</td><td></td></tr>
						<?php } ?>
							<tr><td><h1>New Password:</h1> </td><td><input id="NewPassword" name="NewPassword" type="password" size="35" placeholder="New Password"></td></tr>
							<tr><td><h1>Confirm Password:</h1> </td><td><input id="ConfirmPassword" name="ConfirmPassword" type="password" size="35" placeholder="Confirm Password"></td></tr>
							<tr><td>&nbsp;</td><td></td></tr>
							<tr><td>&nbsp;</td><td><input name="submit" value="Set Password" class="ui-button ui-widget ui-state-default ui-corner-all" id="SetPassword" type="button"></td></tr>
						</table>					
					</div><br>
					<span id="error"></span>
					<?php if($submitAlert == 2) { echo "<span>alert created</span>"; } ?>
					<?php if($submitError == 1) { echo "<span>Current Password Did Not Match</span>"; } ?>
				</div>
				<script>
					$("#SetPassword").click(function() {
						if($("#CurrentPassword").length > 0) {
							var cp = $("#CurrentPassword").val();
						} else {
							var cp = 'none';
						}
						if(cp == '') {
							$("span#error").text("Please enter your current password");
							return;
						}						
						var np = $("#NewPassword").val();
						var cnp = $("#ConfirmPassword").val();
						if(np != cnp) {
							$("span#error").text("New Password does not match Confirm Password");
							return;
						} else if(np == '') {
							// if keeping this check, then will need a way to remove passwords for a user
							$("span#error").text("New Password cannot be blank");
							return;
						} else {
							// else post current (if set) and new password to this page for submitError checking
							// $.post( "./changepassword.php", { newpass: np, currentpass: cp } );
						}
						//alert(cp + " -- " + np + " -- " + cnp);
						
						//var thisid = $(this).parent().parent().attr('id');
						//$.post( "./alerts.php", { markread: thisid } );
					});
					/*
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
					});*/
				</script>
			</div>
		</body>
	</html>
	<?php
}
exit;
?>