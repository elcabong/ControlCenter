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
require_once $INCLUDES."/includes/KLogger.php";
$date = date('Y-m-d');
// klogger options: DEBUG, INFO, WARN, ERROR, FATAL, OFF
$log = new KLogger ( $INCLUDES."/logs/log-$date.log" , KLogger::INFO );

// Do database work that throws an exception
//$log->LogError("An exception was thrown in ThisFunction()");
 
// Print out some information
//$log->LogInfo("Internal Query Time: $time_ms milliseconds");
 
// Print out the value of some variables
//$log->LogDebug("Loaded Config.php");

try {
	if(!isset($_SESSION)){
		ini_set('display_errors', 'Off');
		ini_set('display_startup_errors', 'Off');
		ini_set('html_errors', 'Off');
		ini_set('log_errors', 'On');
		ini_set('error_log', "$INCLUDES/logs/PHP_errors.log");
		ini_set('session.gc_maxlifetime', 604800);     //  604800    >>  24 hours = 86400 sec
		ini_set('session.gc_probability', 1);
		ini_set('session.gc_divisor', 100	);
		ini_set('session.save_path', $INCLUDES . "/sessions");
		ini_set('session.cookie_lifetime', 604800);
		session_start();
	}
} catch(PDOException $e)
	{
		  $log->LogFatal("Fatal: Could NOT not start session: $e->getMessage().  from " . basename(__FILE__));
	}
?>