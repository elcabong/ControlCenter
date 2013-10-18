<?php
	try {
	$sql = "SELECT * FROM users WHERE userid = $usernumber LIMIT 1";
		foreach ($configdb->query($sql) as $row) {
			$HOMEROOMU = $row['homeroom'];
			if(isset($row['roomgroupaccess'])) { $ROOMGROUPA = $row['roomgroupaccess']; }
			if(isset($row['roomaccess'])) { $roomaccess = $row['roomaccess']; }
			if(isset($row['roomdeny'])) { $roomdeny = $row['roomdeny']; }
		}	 
	} catch(PDOException $e)
		{
			echo $e->getMessage();
		}

	function removeFromString($str, $item) {
		$parts = explode(',', $str);
		while(($i = array_search($item, $parts)) !== false) {
			unset($parts[$i]);
		}
		return implode(',', $parts);
	}		
	function addToString($str, $item) {
		$parts = explode(',', $str);
		$addtoarray = '';
		if(!in_array("$item", $parts)) {	
			$addtoarray .= ",".$item;
		}
		$arrayt = implode(',', $parts);
		$arrayt = $arrayt.$addtoarray;
		return $arrayt;
	}
	$roomgroupaccess = '';
	if(isset($ROOMGROUPA) && ($ROOMGROUPA !="" || $ROOMGROUPA != "0")) {
		try {
		$sql = "SELECT * FROM roomgroups WHERE roomgroupid = $ROOMGROUPA LIMIT 1";
			foreach ($configdb->query($sql) as $row) {
				$roomgroupaccess = $row['roomaccess'];
				$roomgroupdeny = $row['roomdeny'];				
			}	 
		} catch(PDOException $e)
			{
			echo $e->getMessage();
			}		
	}
	if(isset($roomgroupaccess) && $roomgroupaccess != '' ) {
		if(isset($roomgroupdeny) && $roomgroupdeny !='') {
			$roomgroupdenyarray = explode(',', $roomgroupdeny);
			foreach ($roomgroupdenyarray as $denyroom) {
				$roomgroupaccess = removeFromString("$roomgroupaccess", "$denyroom");
			}
		}
	}
	if(isset($roomaccess) && $roomaccess !='') {
		if($roomgroupaccess != '' ) {
			$roomallowarray = explode(',', $roomaccess);
			foreach ($roomallowarray as $allowroom) {
				$roomgroupaccess = addToString("$roomgroupaccess", "$allowroom");
			}
		} else {
			$roomgroupaccess = $roomaccess;
		}
	}
	if(isset($roomgroupaccess) && $roomgroupaccess != '' ) {
		if(isset($roomdeny) && $roomdeny !='') {
			$roomdenyarray = explode(',', $roomdeny);
			foreach ($roomdenyarray as $denyroom) {
				$roomgroupaccess = removeFromString("$roomgroupaccess", "$denyroom");
			}
		}
	}
	$y = 1;
    while($y<=$TOTALROOMS) {
		$theperm = "USRPR$y";
		${$theperm} = "0";
		$y++;
	}
	$TOTALALLOWEDROOMS = 0;
	if(isset($roomgroupaccess) && $roomgroupaccess != '' ) {
		$roomgroupaccessarray = explode(',', $roomgroupaccess);
		foreach ($roomgroupaccessarray as $allowroom) {
			$TOTALALLOWEDROOMS ++;
			$theperm = "USRPR$allowroom";
			${$theperm} = "1";
		}
		$checkhomeroomaccess = "USRPR$HOMEROOMU";
		if(${$checkhomeroomaccess} != '1' ) { $HOMEROOMU = $allowroom; }
	}
?>