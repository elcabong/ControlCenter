<?php
require "config.php";
$log->LogDebug("User $authusername from $USERIP loaded " . basename(__FILE__));
if ($authsecured && (!isset($_SESSION["$authusername"]) || !$_SESSION["$authusername"] || $_SESSION["$authusername"] != $authusername )) {
	header("Location: login.php");
    exit; }
require_once 'controls-include.php';

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