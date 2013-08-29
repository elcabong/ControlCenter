<?php
require_once('config.php');
session_destroy();
$thispath = dirname(dirname($_SERVER['PHP_SELF']));
header("Location: $thispath");
echo true;
return true;
exit;
?>
