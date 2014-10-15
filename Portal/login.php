<?php
require_once('config.php');
if(isset($_GET['inputusername']) && $_GET['inputusername'] =='1') {
	if(isset($_POST['user'])) {
		$user = $_POST['user'];
		$configdb = new PDO('sqlite:../sessions/config.db');
		try {
			$sql = "SELECT * FROM users WHERE username = '$user' LIMIT 1";
			foreach ($configdb->query($sql) as $row) {
				if(isset($row['userid']) && $row['userid'] != '' && $row['userid'] != 0) {
					$_SESSION['usernumber'] = $row['userid'];
				}
			}
		} catch(PDOException $e)
			{
			$log->LogFatal("Fatal: User $authusername from $USERIP could not open DB: $e->getMessage().  from " . basename(__FILE__));
			echo $e->getMessage();
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
	$log->LogWarn("FAILED LOGIN user $authusername not allowed from WAN, $USERIP");
	header("refresh: 0; url=./logout.php?loginerror=1");
	exit;
}
If (!$authsecured) {
header( "refresh: 0; url=index.php" );
    exit;
}
if(isset($_POST['user']) && isset($_POST['password'])) {
    if ($_POST['user']==$authusername && $_POST['password']==$authpassword) {
        $_SESSION["$authusername"] = $authusername;
		$_SESSION['loginerror'] = 0;
		$log->LogInfo("User $authusername LOGGED IN from $USERIP");
        header( "refresh: 0; url=index.php" );
        exit;
    } else {
		$log->LogWarn("FAILED LOGIN by $authusername from $USERIP");
		header( "refresh: 0; url=./logout.php?loginerror=1" );
		exit;
	}
}
?>
<?php /*
<!DOCTYPE html>
<html>
<head>
<title>Control Center Authentication</title>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, target-densitydpi=medium-dpi, minimal-ui" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="icon" type="image/png" href="./favicon.ico">
<link rel="stylesheet" type="text/css" href="../css/front.css" />
	<script type="text/javascript">
	var iWebkit;if(!iWebkit){iWebkit=window.onload=function(){function fullscreen(){var a=document.getElementsByTagName("a");for(var i=0;i<a.length;i++){if(a[i].className.match("noeffect")){}else{a[i].onclick=function(){window.location=this.getAttribute("href");return false}}}}function hideURLbar(){window.scrollTo(0,0.9)}iWebkit.init=function(){fullscreen();hideURLbar()};iWebkit.init()}}
	</script>
</head>
<body>
<center>
  <br>
  <br><br>
<form action="login.php" method="post">
    <table width=259 cellpadding=3 cellspacing=0 id=1>
      <tr>
        <td align=center colspan=2 height=25><h2>Authentication</h2></td>
<?php if(isset($_SESSION['attempt']) && $_SESSION['attempt'] > 0) { ?>
	<tr>
	<td align=center colspan=4 height=25><br>Invalid Password. Try Again.</td>
	<tr>
	<td align=center height=25>&nbsp; &nbsp;</td>
<?php } ?>
<tr>
    <input type="hidden" name="user" value="<?php echo $authusername; ?>">
    <td align=left>&nbsp; &nbsp;Username:</td>
	<td align=center><?php echo $authusername; ?></td>
<tr>
	<td align=left>&nbsp; &nbsp;Password:</td>
	<td align=center><input type='password' name="password" size=15 /></td>
<tr>
	<td align=center colspan=2>&nbsp;</td>
<tr>
<td align=center colspan=2><input type='submit' value='Log in' /></td>
</table>
</form>
<br>
<h2><a href="logout.php">Back to User Selection</a></h2>
</center>
</body>
</html>*/?>