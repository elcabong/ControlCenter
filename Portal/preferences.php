<?php
require('startsession.php');
require_once("$INCLUDES/includes/config.php");
$log->LogDebug("User $authusername loading Preferences from " . basename(__FILE__) . " from " . $_SERVER['SCRIPT_FILENAME']);
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
						echo "<td><select id='TimeZone' name='$thispref'>
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
</script>	
</div>
</body>
</html>