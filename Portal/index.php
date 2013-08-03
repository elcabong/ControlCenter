<?php
require_once "config.php";
if ($authsecured && (!isset($_SESSION["$authusername"]) || !$_SESSION["$authusername"] || $_SESSION["$authusername"] != $authusername )) {
    header("Location: login.php");
    exit; }

	
//may need to set cookie for room for this to work properly	
if($_SESSION['room']) {
$roomnum = $_SESSION['room'];
} elseif(!empty($_GET['room'])) {
$roomnum = $_GET['room']; 
} else { $roomnum = $HOMEROOMU; }
$_SESSION['room'] = $roomnum;

require_once "mobile_device_detect.php";
if(mobile_device_detect(true,false,true,true,true,true,true,false,false) ) {
	header( "Location: ./mobile-controls.php" );
	  exit; } else {
	header( "Location: ./controls.php" );
	exit; }
?>