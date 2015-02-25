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

function date_convert($dt, $tz1, $df1, $tz2, $df2) {
  $res = '';
  if(!in_array($tz1, timezone_identifiers_list())) { // check source timezone
    trigger_error(__FUNCTION__ . ': Invalid source timezone ' . $tz1, E_USER_ERROR);
  } elseif(!in_array($tz2, timezone_identifiers_list())) { // check destination timezone
    trigger_error(__FUNCTION__ . ': Invalid destination timezone ' . $tz2, E_USER_ERROR);
  } else {
    // create DateTime object
    $d = DateTime::createFromFormat($df1, $dt, new DateTimeZone($tz1));
    // check source datetime
    if($d && DateTime::getLastErrors()["warning_count"] == 0 && DateTime::getLastErrors()["error_count"] == 0) {
      // convert timezone
      $d->setTimeZone(new DateTimeZone($tz2));
      // convert dateformat
      $res = $d->format($df2);
    } else {
      trigger_error(__FUNCTION__ . ': Invalid source datetime ' . $dt . ', ' . $df1, E_USER_ERROR);
    }
  }
  return $res;
}

function ThisBiggerThanThat($thisbigger, $thatbigger) {
	$thisbigger = explode(".",$thisbigger);
	$thatbigger = explode(".",$thatbigger);
	$isthisbigger = "no";
	for($i=0; $i<10; $i++) {
		if(!isset($thisbigger[$i]) || !isset($thatbigger[$i])) { break; } 
		if($thisbigger[$i] > $thatbigger[$i]) {
			$isthisbigger = "yes";
		}
	}
	return $isthisbigger;
}
?>