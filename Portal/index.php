<?php
require('startsession.php');
require_once "$INCLUDES/includes/config.php";
$log->LogDebug("User $authusername loaded " . basename(__FILE__));
require_once "$INCLUDES/includes/auth.php";
require_once "$INCLUDES/includes/controls-include.php";
if(isset($_COOKIE["currentRoom$usernumber"])) {
	$roomnum = $_COOKIE["currentRoom$usernumber"];
	$theperm = "USRPR$roomnum";
	if(${$theperm} == "1") {
		$_SESSION['room'] = $roomnum;
	}
}
if(!isset($_SESSION['username'])) {
	$_SESSION['username'] = $authusername;
}
if(!isset($_SESSION['room'])) {
	$roomnum = $HOMEROOMU; 
	$_SESSION['room'] = $roomnum; 
} else {
	$roomnum = $_SESSION['room']; 
}
header( "Location: ./controls.php" );
exit;
?>