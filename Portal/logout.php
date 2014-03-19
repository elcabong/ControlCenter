<?php
require "startsession.php";
$_SESSION['usernumber'] = "choose";
session_unset();
//session_destroy();
$thispath = dirname(dirname($_SERVER['PHP_SELF']));
header("Location: $thispath");
echo true;
return true;
exit;
?>
