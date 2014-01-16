<?php
$found2 = false;
$path2 = './sessions';
while(!$found2){
	if(file_exists($path2)){ 
		$found2 = true;
		$sessionsloc = $path2;
	}
	else{ $path2= '../'.$path2; }
}
ini_set('display_errors', 'On');
ini_set('session.gc_maxlifetime', 86400);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100	);
ini_set('session.save_path', "$sessionsloc");
ini_set('session.cookie_lifetime', 86400);
session_start();
$_SESSION['usernumber'] = "choose";
session_unset();
//session_destroy();
$thispath = dirname(dirname($_SERVER['PHP_SELF']));
header("Location: $thispath");
echo true;
return true;
exit;
?>
