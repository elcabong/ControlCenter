<?php
if (file_exists('../sessions/firstrun.php') || !file_exists('../sessions/config.db')) { header('Location: ../servercheck.php');exit; }
$configdb = new PDO('sqlite:../sessions/config.db');
    $sql = "SELECT * FROM users LIMIT 1";
    foreach ($configdb->query($sql) as $row)
        {
		if(isset($row['userid'])) {
			if(!$_GET['setup']) {
				header("Location: index.php");
				exit; 
			} else { $usersareset = 1; }
        } else {
		}
		}
    $sql = "SELECT * FROM rooms LIMIT 1";
    foreach ($configdb->query($sql) as $row)
        {
		if(isset($row['roomid'])) {
			if(!$_GET['setup']) {
				header("Location: index.php");
				exit; 
			} else { $roomsareset = 1; }
        } else {
		}
		}		
?>
<!DOCTYPE html>
<html>
<head>
	<meta name='viewport' content="width=device-width,height:window-height, initial-scale=.9, maximum-scale=.9, minimum-scale=.9, user-scalable=auto" />
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
	<link rel="icon" type="image/png" href="./favicon.ico">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Control Center Setup</title>
	<link rel='stylesheet' type='text/css' href="../css/room.css?<?php echo date ("m/d/Y-H.i.s", filemtime('../css/room.css'));?>">
	<script type="text/javascript" src="../js/jquery-1.10.1.min.js"></script>
	<script type="text/javascript">
		if (window.navigator.standalone) {
			var iWebkit;if(!iWebkit){iWebkit=window.onload=function(){function fullscreen(){var a=document.getElementsByTagName("a");for(var i=0;i<a.length;i++){if(a[i].className.match("noeffect")){}else{a[i].onclick=function(){window.location=this.getAttribute("href");return false}}}}function hideURLbar(){window.scrollTo(0,0.9)}iWebkit.init=function(){fullscreen();hideURLbar()};iWebkit.init()}}
		}
	</script>
</head>
<body>
<div id='header' class="nav-menu-z">
<div id='nav-menu'>
	<nav>
	<ul><li><!--
<?php if(!isset($usersareset)) { ?>
<a href='#' class='main panel title'>Configure A User Account to Continue</a>
<?php } else if(!isset($roomsareset)) { ?>
<a href='#' class='main panel title'>Configure A Room for XBMC Control</a>
<?php } else { ?>
<a href='../index.html' class='main panel'>Begin Using Control Panel</a>
<?php } ?>
		--></li></ul>
	</nav>
</div>
<div class="clear"></div>	
</div>
<div id="wrapper" scrolling="auto">
	<div id="mask">
		<div id="Settings" class="item">
			<div class="content">
				<iframe id="Settingsf" class='Settings' src='./settings.php<?php if($_GET['setup']) { echo "?setup=".$_GET['setup']; }?>' width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>
	</div>
</div>
</body>
</html>
