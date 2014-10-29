<?php
if(!isset($log)) {
	require_once "./startsession.php";
}
if(isset($_GET['addon'])) {
	$addontype = $_GET['addon'];
}
require_once "$INCLUDES/includes/addons.php";
$log->LogDebug("User $authusername tried to get $addontype from " . basename(__FILE__));
if($addontype == 'links') {
	if(!empty($searchproviders)) {
		echo "<li><a href='#' class='nopanel' id='searchlink' thisroom='$THISROOMID'><img src='../media/search.png' height='35px'></a></li>";
	}
	$allenabledaddons = explode(",", $enabledaddons);
	foreach($allenabledaddons as $thisaddon1) {
		if($thisaddon1 == '') { break; }
		$thisaddon = explode(".", $thisaddon1, 2);
		$classification = $thisaddon[0];
		$title = $thisaddon[1];
		$filename = $addonarray["$classification"]["$title"]['path']."addonquicklinks.php";
		if (file_exists($filename)) {
			include $filename;
		}
	}
	echo "<script type='text/javascript' src='../js/scripts.js'></script>";
} elseif($addontype == 'pages') {
	$allenabledaddons = explode(",", $enabledaddons);
	foreach($allenabledaddons as $theaddon) {
		$allenabledaddons = explode(".", $theaddon, 2);
		$classification = $allenabledaddons[0];
		if(isset($allenabledaddons[1]) && $allenabledaddons[1] != '') {
			$title = $allenabledaddons[1];
			$filename = $addonarray["$classification"]["$title"]['path']."addonquicklinks.php";
			if (file_exists($filename)) {
				include $filename;
			}
		}
	}
}
?>