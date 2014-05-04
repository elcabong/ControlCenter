<?php
require "startsession.php";
$_SESSION['usernumber'] = "choose";
session_unset();
//session_destroy();
if(isset($_GET['loginerror']) && $_GET['loginerror'] =='1') {
	$_SESSION['loginerror'] = 1;
}
$thispath = dirname(dirname($_SERVER['PHP_SELF']));
header("Location: $thispath");
echo true;
return true;
exit;
?>
