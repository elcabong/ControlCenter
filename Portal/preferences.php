<?php
require('startsession.php');
require_once("$INCLUDES/includes/config.php");
$log->LogDebug("User $authusername loading Preferences from " . basename(__FILE__) . " from " . $_SERVER['SCRIPT_FILENAME']);

if(isset($_POST['params']) && isset($usernumber)) {
	$preferencearray = array();
	try {
		$sql = "SELECT * FROM preferences_options";
		foreach ($configdb->query($sql) as $row) {
			$preferencearray[$row['preftitle']] = $row['prefoptionid'];
		}
	} catch(PDOException $e) {
		$log->LogFatal("Fatal: User could not open DB: $e->getMessage().  from " . basename(__FILE__));
	}	
	$params = $_POST['params'];
	$theparams = explode('&',$params);
	foreach($theparams as $thisparam) {
		$thisparam = explode('=',$thisparam);
		$poid = $preferencearray[$thisparam[0]];
		$pref = $thisparam[1];
		if($pref == 'None') {
			continue;
		}
		try {
			$execquery = $configdb->exec("INSERT OR REPLACE INTO preferences (prefid, prefoptionid, userid, preference) VALUES ((SELECT prefid FROM preferences WHERE userid = $usernumber AND prefoptionid = $poid),$poid,$usernumber,'$pref')");
		} catch(PDOException $e) {
			$log->LogFatal("Fatal: User could not save to DB: $e->getMessage().  from " . basename(__FILE__));
		}
	}
	echo "Preferences Saved.";
	exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Preferences</title>
<link type='text/css' href='../css/nowplaying.css' rel='stylesheet' media='screen' />
<script type="text/javascript" src="../js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="../js/jsTimeZoneDetect.js"></script>
</head>
<body>
<div id='roominfocontainer'>
	<div id='logo'>
		<h1>Preferences for <span><?php echo $authusername; ?></span></h1>
	</div>
	<div id='content'>

	<table>
	<?php
		try {
			$sql = "SELECT p.preference,po.preftitle FROM preferences_options AS po LEFT JOIN preferences AS p ON p.prefoptionid = po.prefoptionid AND p.userid = $usernumber OR p.userid is NULL";
			foreach ($configdb->query($sql) as $row)
				{
					if(isset($row['preference']) && $row['preference'] != '') {
						$thispref = $row['preference'];
					} else {
						$thispref = "None";
					}
					
					echo "<tr><td>" . $row['preftitle'] . ":</td>";
					
					if($row['preftitle'] === "TimeZone") {
						echo "<td><select id='TimeZone' name='TimeZone'>
						  <option selected='selected'>$thispref</option>";
							foreach(timezone_identifiers_list() as $id => $timezone) {
							  echo "<option value='$timezone'>$timezone</option>";
							}
						echo "</select></td>";
						echo "<td><button id='CheckTimeZone'>Check Time Zone</button></td>";
					} elseif ($row['preftitle'] === "Theme") {
						echo "<td></td>";
					}
					echo "</tr>";
				}
		} catch(PDOException $e)
			{
			echo $e->getMessage();
			}	
	?>
	<tr>	
	<td>Password: </td><td class='button right'><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Update Password' onclick="changepassword(<?php echo $usernumber ?>);" /><tr>
	</tr>
	</table>
	<br><br><Br>
	<input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Save Preferences' id='SetPreferences' />
	<br><br>
	<span id="error"></span>
	</div>
<script>
	function changepassword(user){
		$('#modal').load('changepassword.php?user=' + user).modal({
			opacity: 75,
			overlayClose: true
		});
	}

	$("#CheckTimeZone").click(function() {
		var timezone = jstz.determine();
		var confirmTZ = confirm("Do you want to set the following Time Zone? \n \n" + timezone.name());
		if(confirmTZ) {
			// Loop through all the items in drop down list
			var s = document.getElementById("TimeZone")
			for (i = 0; i< s.options.length; i++)
			{
				if (s.options[i].value == timezone.name())
				{
					// Item is found. Set its property and exit
					s.options[i].selected = true;
					break;
				}
			}
			return;
		}
	});
	

	$("#SetPreferences").click(function() {
		var contents = document.getElementsByTagName('input'); //$("#result").html(contents);
		var origparams = '';
		var params = '';
		var addonnum = 0;
		/*
		for (i = 0; i < contents.length; i++) { //alert(contents[i].name+'='+contents[i].value);
			var value = contents[i].value;
			if (contents[i].type == 'checkbox') {
				if (contents[i].value == 'on') {
					value = 'true';
				} else {
					value = 'false';
				}
				params = params + '&' + contents[i].name + '=' + value;
			} else if (contents[i].type == 'radio') {
				var name = contents[i].name;
				while (contents[i].type == 'radio') {
					if (contents[i].checked && contents[i].name == name) { //alert(contents[i].name+' '+contents[i].value);
						value = contents[i].value;
						params = params + '&' + contents[i].name + '=' + encodeURIComponent(value);
					}
					i++;
				}
				i--;
			} else if (contents[i].name != '') {
				//alert(contents[i].name);
				if(contents[i].name == 'roomid') { addonnum++; }
				if(addonnum > 1) { 
					alert(params);
					addonnum = 1;
					params = origparams;
				}
				params = params + '&' + contents[i].name + '=' + encodeURIComponent(value);
			}
		}*/

		var contents = document.getElementsByTagName('select');
		for (i = 0; i < contents.length; i++) {
			if (contents[i].name != '') {
				var thecontents = '&' + contents[i].name + '=' + escape(contents[i++].value);
				//alert(thecontents);
				params += thecontents;
			}
		}	

		params = params.replace(/^[&]/,'');
		
		//alert(params);
		$.post( "./preferences.php", {
			params: params
		}).done(function(data){
			if (data != "err"){
				$("span#error").text(data);
			}
		});

	});
</script>	
</div>
</body>
</html>