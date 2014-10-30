<?php
require "startsession.php";
if(isset($_SESSION['username'])) {
	$log->LogInfo("User " . $_SESSION['username'] . " LOGGED OUT");
} else {
	$log->LogInfo("User LOGGED OUT");
}
session_unset();
if(isset($_GET['loginerror']) && $_GET['loginerror'] =='1') {
	$_SESSION['loginerror'] = 1;
}
$_SESSION['usernumber'] = "choose";
$thispath = dirname(dirname($_SERVER['PHP_SELF']));
header("Location: $thispath");
echo true;
return true;
exit;
?>
