<?php
require "startsession.php";
require_once "KLogger.php";
$date = date('Y-m-d');
$log = new KLogger ( "../logs/log-$date.txt" , KLogger::INFO );
if(isset($_GET['loginerror']) && $_GET['loginerror'] =='1') {
	$_SESSION['loginerror'] = 1;
}
$USERIP = $_SERVER['REMOTE_ADDR'];
$log->LogInfo("User " . $_SESSION['usernumber'] . " from $USERIP LOGGED OUT");
session_unset();
$_SESSION['usernumber'] = "choose";
$thispath = dirname(dirname($_SERVER['PHP_SELF']));
header("Location: $thispath");
echo true;
return true;
exit;
?>
