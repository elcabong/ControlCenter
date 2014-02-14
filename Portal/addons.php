<?php
//require_once"config.php";

$addonDIRarray = scandir($ADDONDIR);

array_splice($addonDIRarray, 0, 1);
array_splice($addonDIRarray, 0, 1);
	
$addonarray = array();
$availableaddons = array();
	
   for ($i = 0; $i < count($addonDIRarray); ++$i) {
		$thisxml = $ADDONDIR . $addonDIRarray[$i] . "/addon.xml";
		$response = simplexml_load_file($thisxml);

		$arr = explode(".", $addonDIRarray[$i], 2);
		$thisclassification = $arr[0];
		$thistitle = $arr[1];

		$result = $response->metadata;
		
		if($result->id != $addonDIRarray[$i] && $thisclassification != "disabled") { $thisclassification = "error"; }
		
		$addonarray["$thisclassification"]["$thistitle"]['id'] = "$result->id";
		$addonarray["$thisclassification"]["$thistitle"]['version'] = "$result->version";
		$addonarray["$thisclassification"]["$thistitle"]['name'] = "$result->name";
		$addonarray["$thisclassification"]["$thistitle"]['author'] = "$result->author";	
		$addonarray["$thisclassification"]["$thistitle"]['summary'] = "$result->summary";
		$addonarray["$thisclassification"]["$thistitle"]['description'] = "$result->description";
		$addonarray["$thisclassification"]["$thistitle"]['path'] = "$ADDONDIR$addonDIRarray[$i]/";

		if($thisclassification != 'error' && $thisclassification != 'disabled') {
			$availableaddons[] = "$result->id";
		}
    }
		if(isset($theroom) && $theroom != '' ) {
			$roomid = $theroom;
		}	
		if(isset($setroomnum) && $setroomnum != '' ) {
			$roomid = $setroomnum;
		}
		if(isset($roomid)) {
			$enabledaddons = '';
			$sql2 = "SELECT addons FROM rooms WHERE roomid = $roomid LIMIT 1";
			foreach ($configdb->query($sql2) as $row2){
				$enabledaddons = $row2['addons'];
				//echo $enabledaddons."<br>";
			}
		}
	
/*
	echo "<pre>";
print_r($addonarray);	
	echo "</pre>";
	echo "<pre>";
print_r($availableaddons);	
	echo "</pre>";	
	*/
	
?>