<?php
require_once "startsession.php";
$usernumber = -1;
if(isset($_SESSION['usernumber'])) {
	$usernumber = $_SESSION['usernumber'];
} else {
	$log->LogWarn("User Redirected to Login Page, No usernumber in SESSION info. From " . basename(__FILE__) . " from " . $_SERVER['SCRIPT_FILENAME']);
	header("Location: login.php");
    exit;
}
$log->LogInfo("User " . $_SESSION['username'] . " loaded " . basename(__FILE__) . " from " . $_SERVER['SCRIPT_FILENAME']);
// need to check dependency for auth
//require_once "$INCLUDES/includes/auth.php";

$usernum = 0;
if(isset($_GET['user'])) {
	$usernum = $_GET['user'];
}
if(isset($_POST['usernum'])) {
	$usernum = $_POST['usernum'];
}

try {
	$configdb = new PDO("sqlite:$INCLUDES/sessions/config.db");
} catch(PDOException $e)
	{
	$log->LogError("$e->getMessage()" . basename(__FILE__));
	}
try {
	$sql = "SELECT * FROM users WHERE userid = $usernum";
	foreach ($configdb->query($sql) as $row) {
		$username = $row['username'];
		if(isset($row['password'])) { $password = $row['password']; } else { $password = ''; }
	}
} catch(PDOException $e) {
	$log->LogFatal("Fatal: User could not open DB: $e->getMessage().  from " . basename(__FILE__));
}
$useraccess = 1;
if($usernumber != $usernum){
	try {
		$sql = "SELECT userlevel FROM users WHERE userid = $usernumber";
		foreach ($configdb->query($sql) as $row) {
			$useraccess = $row['userlevel'];
		}
	} catch(PDOException $e) {
		$log->LogFatal("Fatal: User could not open DB: $e->getMessage().  from " . basename(__FILE__));
	}	
}

if(isset($_POST['newpass']) && isset($_POST['currentpass']) && isset($_POST['usernum'])) {
	require "$INCLUDES/includes/PasswordHash.php";
	$hasher = new PasswordHash(8, false);
	$check=false;
	$currentpassword = $_POST['currentpass'];
	if($currentpassword != "set-to-none" && $password != '') {
		if(0 === strpos($password,"$2a")) {
			if (strlen($currentpassword) > 72) { $currentpassword = substr($password,0,72); }
			$stored_hash = "*";
			$stored_hash = "$password";
			$check = $hasher->CheckPassword($currentpassword, $stored_hash);
		} else {
			if($currentpassword == $password) {
				$check=true;
			}
		}
		if($check == false) {
			echo "Current Password Could Not Be Confirmed, Please re-enter and try again.";
			exit;
		}
	}
	
	if($_POST['newpass'] == "set-to-blank") {
		$configdb->exec("UPDATE users SET password='' WHERE userid=$usernum");
	} else if($_POST['newpass'] != '') {
		$newpass = $_POST['newpass'];
		if (strlen($newpass) > 72) { $newpass = substr($newpass,0,72); }
		$hash = $hasher->HashPassword($newpass);
		try {
			if($usernumber == $usernum) {
				$configdb->exec("UPDATE users SET password='".$hash."' WHERE userid='".$usernum."'");
			} else {
				$configdb->exec("UPDATE users SET password='".$hash."', passwordreset='1' WHERE userid='".$usernum."'");
			}
		} catch(PDOException $e) {
			$log->LogFatal("Fatal: User could not open DB: $e->getMessage().  from " . basename(__FILE__));
		}
	}
	echo "Password has been Updated";
	exit;
}
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
				<h1>Password Update for user: <?php echo $username; ?></h1>
			</div>
			<div id="content"><br><br>
				<div id='UpdatePassword'>
					<?php
					if($usernumber != $usernum && $useraccess < 3) { ?>
						You Do Not have access to update this password
					<?php exit; } ?>
					<table>
					<?php
						if($usernumber == $usernum && $password != '') { ?>
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
			</div>
			<script>
				$("#SetPassword").click(function() {
					if($("#CurrentPassword").length > 0) {
						var cp = $("#CurrentPassword").val();
					} else {
						var cp = 'set-to-none';
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
						if (confirm("Do you want to set a blank password?") == true) {
							np = 'set-to-blank';
						} else {
							return;
						}
					}
					$.post( "./changepassword.php", {
						newpass: np, currentpass: cp, usernum: <?php echo $usernum; ?> 
					}).done(function(data){
						if (data != "err"){
							$("span#error").text(data);
							$("#SetPassword").hide();
						}
					});
				});
			</script>
		</div>
	</body>
</html>