<?php 
if(isset($_GET['ip'])) {
	$ip=$_GET['ip'];
}
if(isset($_GET['filetype'])) {
	$thisfiletype=$_GET['filetype'];
}
if(isset($_GET['activeplayer'])) {
	$activeplayerid=$_GET['activeplayer'];
} 
if(isset($_GET['addon'])) {
	$addonid=$_GET['addon'];
} else {
	exit;
}
$arr = explode(".", $addonid, 2);
$classification = $arr[0];
$title = $arr[1];
require('startsession.php');
$log->LogDebug("User $authusername loaded $ip $thisfiletype $activeplayer $addonid from " . basename(__FILE__) . " from " . $_SERVER['SCRIPT_FILENAME']);
require_once("$INCLUDES/includes/config.php");
require_once "$INCLUDES/includes/addons.php";
$filename = $addonarray["$classification"]["$title"]['path']."nowplayingtime.php";
if (file_exists($filename)) {
	require $filename;
}
?>
