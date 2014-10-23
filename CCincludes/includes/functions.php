<?php
// this function is also in servercheck.php   remember to update as needed
function folderRequirements($folderlevel) {
	$missing = 0;
	$folders = array('addons','androidapps','css','js','lib','media','Portal');
	foreach($folders as $dir) {
		$thefolder = $folderlevel . $dir."/";
		if(file_exists($thefolder) && is_dir($thefolder)) {
			//return true;
		} else {
			$missing += 1;
		}
	}
	return $missing;
}
?>