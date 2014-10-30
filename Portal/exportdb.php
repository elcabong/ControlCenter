<?php
if(!isset($log)) {
	require_once "startsession.php";
}
$log->LogWarn("User " . $_SESSION['username'] . " loaded " . basename(__FILE__));
if(!isset($_GET['upgrade'])) {
	require("$INCLUDES/includes/config.php");
	require_once "$INCLUDES/includes/auth.php";
}
if(!isset($folderlevel)) {
	$folderlevel = "../";
}
if(isset($_GET['bak']) &&	$_GET['bak'] == '1') {
	$thefile = $INCLUDES."/sessions/config-bak.db";
    header('Content-Type: application/download');
    header('Content-Disposition: attachment; filename="config-bak.db"');
    header("Content-Length: " . filesize("$thefile"));
    $fp = fopen("$thefile", "r");
    fpassthru($fp);
    fclose($fp);
} else {
	$thefile = $INCLUDES."/sessions/config.db";
    header('Content-Type: application/download');
    header('Content-Disposition: attachment; filename="config.db"');
    header("Content-Length: " . filesize("$thefile"));
    $fp = fopen("$thefile", "r");
    fpassthru($fp);
    fclose($fp);
}
?>