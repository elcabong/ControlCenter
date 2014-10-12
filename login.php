<?php
if (file_exists('./sessions/firstrun.php') || !file_exists('./sessions/config.db')) { header('Location: servercheck.php');exit; }

require('./Portal/config.php');
$USERIP = $_SERVER['REMOTE_ADDR'];
$log->LogInfo("User from $USERIP on Login Screen");
	if(isset($_SESSION['usernumber']) && $_SESSION['usernumber'] != "choose" || $_GET['user'] != "choose") {
	$log->LogInfo("User $authusername from $USERIP already logged in redirect ");
    header("Location: ./Portal/index.php");
    exit;} ?>
<!DOCTYPE html>
<html>
<head>
<title>Control Center User Selection</title>
<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, target-densitydpi=medium-dpi, user-scalable=no, minimal-ui' />
<meta name='apple-mobile-web-app-capable' content='yes' />
<link rel='stylesheet' type='text/css' href='./css/front.css' />
<link rel="icon" type="image/png" href="./favicon.ico">
<script type='text/javascript' src='./js/jquery-1.10.1.min.js'></script>
<body background='./media/background.png'>
<?php
if(strstr($_SERVER['HTTP_USER_AGENT'],'Android') && !strstr($_SERVER['HTTP_USER_AGENT'],'webview')) {
	$therealip = $_SERVER['SERVER_ADDR'];
	$theip = str_replace(".","","$therealip");
	$theapp =  "./androidapps/ControlCenter-$theip.apk";
	if(file_exists($theapp)) {
		echo "<center><br><a href='$theapp'><h3>Download the Android App</h3></a></center>";
	}
} elseif((strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod'))) { ?>
	<script type="text/javascript" src="./js/add2home.js"></script>
	<link rel='stylesheet' type='text/css' href='./css/add2home.css' />
	<script type="text/javascript">
	var iWebkit;if(!iWebkit){iWebkit=window.onload=function(){function fullscreen(){var a=document.getElementsByTagName("a");for(var i=0;i<a.length;i++){if(a[i].className.match("noeffect")){}else{a[i].onclick=function(){window.location=this.getAttribute("href");return false}}}}function hideURLbar(){window.scrollTo(0,0.9)}iWebkit.init=function(){fullscreen();hideURLbar()};iWebkit.init()}}
	</script><?php
} ?>
	<script type="text/javascript">
	$(document).ready(function(){
	/*  OPTION 1
		$('a.container').hover(function(){
			$(this).children("div").delay(150).animate({height:'100%'},550);
		}, function(){
			$(this).children("div").animate({height:'40px'},450);
		});*/	
	
		$('a.container').click(function(){
			$(this).children("div.locked").animate({height:'100%'},350);
		});
		$('a.container').hover(function(){	}, function(){
			$(this).children("div.locked").animate({height:'40px'},450);
		});		
	});
	
	$(function(){
		document.oncontextmenu = function() {return false;};
	});	
	</script>
<?php
	echo "<div id='tiles'>";
	echo "<br><h2 class='container right'>Control Center</h2><h2  class='container left'> User Selection</h2>";
	echo "<br><br><br>";
	if(isset($_SESSION['loginerror']) && $_SESSION['loginerror'] == 1 ) {
	echo "<h3 class='orange'>Login Error, Try Again</h3><br>";
	}
	// check for input users or display user selection
	if($settingsarray["InputUserName"]["value1"] == 1) { ?>
		<form action="./Portal/login.php?inputusername=1" method="post" style="width:280px;margin:0 auto;">
			<table width=259 cellpadding=3 cellspacing=0 id=1>
		<tr>
			<td align=left>&nbsp; &nbsp;Username:</td>
			<td align=center><input type='text' name="user" size=15 /></td>
		<tr>
			<td align=left>&nbsp; &nbsp;Password:</td>
			<td align=center><input type='password' name="password" size=15 /></td>
		<tr>
			<td align=center colspan=2>&nbsp;</td>
		<tr>
		<td align=center colspan=2><input type='submit' value='Log in' /></td>
		</table>
		</form>
	<?php } else {
		try {
			if($WANCONNECTION == '1') {
				$sql = "SELECT * FROM users WHERE wanenabled = 1";
			} else {
				$sql = "SELECT * FROM users";		
			}	
			foreach ($configdb->query($sql) as $row) {
				$u = $row['userid'];
				$filename = "./media/Users/user$u.jpg";
				if (file_exists($filename)) {
					$theuserpic = "$filename";
				} else {
					$theuserpic = "./media/Users/user-default.jpg";   
				}
				if (isset($row['password']) && $row['password']!='') { ?>
				  <a href='#' class='container' id='user$u'><div id='login$u' class='locked'>
					<form action='./Portal/login.php' method='post' class='userpick'>
					<table id=<?php echo $u;?>><br><br>
					  <tr>
						<td align=center colspan=2 height=25><h2>Account Locked</h2></td>
						<tr>
						<td>&nbsp;
						<input type='hidden' name='user' value=<?php echo $row['username'];?>>
						<input type='hidden' name='usernumber' value=<?php echo "$u";?>>
						</td>
						<tr>
						<td align=center><input type='password' name='password' size=13 placeholder='Password' style='text-align:center;' /></td>
						<tr>
						<td align=center colspan=2>&nbsp;</td>
						<tr>
					<td align=center colspan=2><input type='submit' value='Log In' /></td>
					</table>
					</form>
				<?php } else {
				 echo "<a href='./Portal/index.php?user=$u' class='container' id='user$u'><div id='login$u'>";?>
					<div class='userpick'>
					<table id=<?php echo $u;?>><br><br>
					  <tr>
						<td align=center colspan=2 height=25><h2>Click to Login</h2></td>
					</table>
					</div>	 
				<?php }
				echo "<span class='text'>" . $row['username'] . "</span></div><img src='$theuserpic' class='image' /></a>";
			}
		} catch(PDOException $e)
			{
				echo $e->getMessage();
			}
	}
echo "</div>";
echo "</head>";
echo "</body>";
echo "</html>";
?>
