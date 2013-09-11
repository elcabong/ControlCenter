<?php
require_once "config.php";
if ($authsecured && (!isset($_SESSION["$authusername"]) || !$_SESSION["$authusername"] || $_SESSION["$authusername"] != $authusername )) {
    header("Location: login.php");
    exit; }
	
	$HOMEROOMU	= $Config2->get('HOMEROOM',"USER$usernumber");
	$ADMINP		= $Config2->get('ADMIN',"USER$usernumber");
	
	$theperm;
	$y = 1;
	if($ADMINP > "1") {
		$x = $Config2->get("ADMINGROUP$ADMINP");
	} else {
		$x = $Config2->get("USER$usernumber");
	}
          if(!empty($x)){
              while($y<=$TOTALROOMS) {
		$theperm = "USRPR$y";
		$ROOMXT = "ROOM$y";
		if($ADMINP > "1") {
			${$theperm} = $Config2->get($ROOMXT,"ADMINGROUP$ADMINP");
		} else {
			${$theperm} = $Config2->get($ROOMXT,"USER$usernumber");
		}
		$y++;
		}
	  }
	  
if(isset($_COOKIE["currentRoom$usernumber"])) {
$roomnum = $_COOKIE["currentRoom$usernumber"];
$theperm = "USRPR$roomnum";
if(${$theperm} == "1") {
$_SESSION['room'] = $roomnum; } }

if(!$_SESSION['room']) {
$roomnum = $HOMEROOMU; 
$_SESSION['room'] = $roomnum; } else {
$roomnum = $_SESSION['room']; } 

require_once "mobile_device_detect.php";
if(mobile_device_detect(true,false,true,true,true,true,true,false,false) ) {
	header( "Location: ./mobile-controls.php" );
	  exit; } else {
	header( "Location: ./controls.php" );
	exit; }
?>