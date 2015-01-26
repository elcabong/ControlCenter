<?php
$ADDONIP = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['ADDONIP'];
$ADDONIPW = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['ADDONIPW'];
$ADDONMAC = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['MAC'];
$setting1 = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['setting1'];
$setting2 = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['setting2'];
$setting3 = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['setting3'];
$setting4 = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['setting4'];
$setting5 = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['setting5'];
$setting6 = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['setting6'];
$setting7 = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['setting7'];
$setting8 = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['setting8'];
$setting9 = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['setting9'];
$setting10 = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['setting10'];

echo "<tr><td class='title'>IP LAN</td><td colspan=5><input size='80' name='ip' value='$ADDONIP'></td></tr>
		<tr><td class='title'>IP WAN</td><td colspan=5><input size='80' name='ipw' value='$ADDONIPW'></td></tr>
		<tr><td class='title'>MAC</td><td colspan=5><input size='80' name='mac' value='$ADDONMAC'></td></tr>
		<td class='title'>IP2</td><td><input size='80' name='setting1' value='$setting1'></td></tr>
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