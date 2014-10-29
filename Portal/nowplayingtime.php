<?php if(isset($_GET['ip'])) { $ip=$_GET['ip']; } if(isset($_GET['filetype'])) { $thisfiletype=$_GET['filetype']; } if(isset($_GET['activeplayer'])) { $activeplayerid=$_GET['activeplayer']; } 
if(isset($_GET['addon'])) { $addonid=$_GET['addon']; } 
$arr = explode(".", $addonid, 2);
				$classification = $arr[0];
				$title = $arr[1];
require_once "config.php";
require_once "$INCLUDES/includes/addons.php";

$filename = $addonarray["$classification"]["$title"]['path']."nowplayingtime.php";
if (file_exists($filename)) {
	require $filename;
}
?>
