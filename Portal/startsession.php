<?php
if(!isset($INCLUDES)) {
	$found = false;
	$path = './CCincludes';
	while(!$found){
		if(file_exists($path)){ 
			$found = true;
			$INCLUDES = $path;
		}
		else{ $path = '../'.$path; }
	}
}
if(!isset($_SESSION)){
	$found2 = false;
	$path2 = './sessions';
	while(!$found2){
		if(file_exists($path2)){ 
			$found2 = true;
			$sessionsloc = $path2;
		}
		else{ $path2= '../'.$path2; }
	}
	ini_set('display_errors', 'Off');
	ini_set('display_startup_errors', 'Off');
	ini_set('html_errors', 'Off');
	ini_set('log_errors', 'On');
	ini_set('error_log', "$INCLUDES/logs/PHP_errors.log");
	ini_set('session.gc_maxlifetime', 604800);     //  604800    >>  24 hours = 86400 sec
	ini_set('session.gc_probability', 1);
	ini_set('session.gc_divisor', 100	);
	ini_set('session.save_path', "$sessionsloc");
	ini_set('session.cookie_lifetime', 604800);
	session_start();
}
$USERIP = $_SERVER['REMOTE_ADDR'];		
require_once "KLogger.php";
$date = date('Y-m-d');
// klogger options: DEBUG, INFO, WARN, ERROR, FATAL, OFF
$log = new KLogger ( $INCLUDES."/logs/log-$date.log" , KLogger::DEBUG );
 
// Do database work that throws an exception
//$log->LogError("An exception was thrown in ThisFunction()");
 
// Print out some information
//$log->LogInfo("Internal Query Time: $time_ms milliseconds");
 
// Print out the value of some variables
//$log->LogDebug("Loaded Config.php");
?>