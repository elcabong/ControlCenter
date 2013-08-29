<?php
require('./Portal/config.php');
	if($_SESSION['usernumber'] != "choose") {
    header("Location: ./Portal/index.php");
    exit;}
echo "<html>";
echo "<head>";
echo "<title>Control Center User Selection</title>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, target-densitydpi=medium-dpi, user-scalable=no' />";
echo "<meta name='apple-mobile-web-app-capable' content='yes' />";
echo "<link rel='stylesheet' type='text/css' href='./css/front.css' />";
echo "<body background='./media/background.png'>";
echo "<center>";
require_once "./Portal/mobile_device_detect.php";
if(strstr($_SERVER['HTTP_USER_AGENT'],'Android') && !strstr($_SERVER['HTTP_USER_AGENT'],'webview')) {
	$therealip = $_SERVER['SERVER_ADDR'];
	$theip = str_replace(".","","$therealip");
	$theapp =  "./androidapps/ControlCenter-$theip.apk";
	if(file_exists($theapp)) {
		echo "<br><a href='$theapp'><h3>Download the Android App</h3></a>";
	}
}
if(mobile_device_detect(true,false,true,true,true,true,true,false,false) ) {
	echo "<br><h2>Control Center<br><br> User Selection</h2>";
	echo "<br>";
	$u = 1;
	while($u<=$HOWMANYUSERS) {
	$filename = "./config/Users/user$u.jpg";
	if (file_exists($filename)) {
	$theuserpic = "$filename";
	} else {
	$theuserpic = "./config/Users/user-default.jpg";   
	}
	echo "<a href='./Portal/index.php?user=$u'><img src='$theuserpic' title='User $u' width=60%  style='margin:10px;'/></a>";
	$u++;
	}
} else {
	echo "<br><h2>Control Center User Selection</h2>";
	echo "<br>";
	$u = 1;
	while($u<=$HOWMANYUSERS) {
	$filename = "./config/Users/user$u.jpg";
	if (file_exists($filename)) {
	$theuserpic = "$filename";
	} else {
	$theuserpic = "./config/Users/user-default.jpg";   
	}
	$authusername = $Config2->get('USERNAME',"USER$u");
	echo "<a href='./Portal/index.php?user=$u'><img src='$theuserpic' title='$authusername' style='max-width: 22%;margin:10px 10px;' height=40% /></a>";
	$u++;
	}
}
echo "</center>";
echo "</head>";
echo "</body>";
echo "</html>";
?>
