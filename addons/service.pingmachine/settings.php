<?php
$ADDONIP = $enabledaddonsarray["$THISROOMID"]['service.pingmachine']['ADDONIP'];
$ADDONMAC = $enabledaddonsarray["$THISROOMID"]['service.pingmachine']['MAC'];
$setting1 = $enabledaddonsarray["$THISROOMID"]['service.pingmachine']['setting1'];
$setting2 = $enabledaddonsarray["$THISROOMID"]['service.pingmachine']['setting2'];
$setting3 = $enabledaddonsarray["$THISROOMID"]['service.pingmachine']['setting3'];
$setting4 = $enabledaddonsarray["$THISROOMID"]['service.pingmachine']['setting4'];
$setting5 = $enabledaddonsarray["$THISROOMID"]['service.pingmachine']['setting5'];
$setting6 = $enabledaddonsarray["$THISROOMID"]['service.pingmachine']['setting6'];
$setting7 = $enabledaddonsarray["$THISROOMID"]['service.pingmachine']['setting7'];
$setting8 = $enabledaddonsarray["$THISROOMID"]['service.pingmachine']['setting8'];
$setting9 = $enabledaddonsarray["$THISROOMID"]['service.pingmachine']['setting9'];
$setting10 = $enabledaddonsarray["$THISROOMID"]['service.pingmachine']['setting10'];


echo"<tr><td class='title'>IP</td><td colspan=5><input size='80' name='ip' value='$ADDONIP'></td></tr>
		<tr><td class='title'>MAC</td><td colspan=5><input size='80' name='mac' value='$ADDONMAC'></td></tr>
		<tr><td class='title'>Link</td><td colspan=5><input size='80' name='setting1' value='$setting1'></td></tr>
		<input type='hidden' size='80' name='setting2' value=''>
		<input type='hidden' size='80' name='setting3' value=''>
		<input type='hidden' size='80' name='setting4' value=''>
		<input type='hidden' size='80' name='setting5' value=''>
		<input type='hidden' size='80' name='setting6' value=''>
		<input type='hidden' size='80' name='setting7' value=''>
		<input type='hidden' size='80' name='setting8' value=''>
		<input type='hidden' size='80' name='setting9' value=''>
		<input type='hidden' size='80' name='setting10' value=''>";
		?>