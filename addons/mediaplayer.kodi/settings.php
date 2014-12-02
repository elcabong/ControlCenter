<?php
$ADDONIP = $enabledaddonsarray["$THISROOMID"]['mediaplayer.kodi']['ADDONIP'];
$ADDONIPW = $enabledaddonsarray["$THISROOMID"]['mediaplayer.kodi']['ADDONIPW'];
$ADDONMAC = $enabledaddonsarray["$THISROOMID"]['mediaplayer.kodi']['MAC'];
$setting1 = $enabledaddonsarray["$THISROOMID"]['mediaplayer.kodi']['setting1'];
$setting2 = $enabledaddonsarray["$THISROOMID"]['mediaplayer.kodi']['setting2'];
$setting3 = $enabledaddonsarray["$THISROOMID"]['mediaplayer.kodi']['setting3'];
$setting4 = $enabledaddonsarray["$THISROOMID"]['mediaplayer.kodi']['setting4'];
$setting5 = $enabledaddonsarray["$THISROOMID"]['mediaplayer.kodi']['setting5'];
$setting6 = $enabledaddonsarray["$THISROOMID"]['mediaplayer.kodi']['setting6'];
$setting7 = $enabledaddonsarray["$THISROOMID"]['mediaplayer.kodi']['setting7'];
$setting8 = $enabledaddonsarray["$THISROOMID"]['mediaplayer.kodi']['setting8'];
$setting9 = $enabledaddonsarray["$THISROOMID"]['mediaplayer.kodi']['setting9'];
$setting10 = $enabledaddonsarray["$THISROOMID"]['mediaplayer.kodi']['setting10'];

echo "<tr><td>&nbsp;</td></tr><tr><td></td><td colspan=5>$title</td></tr>
		<tr><td class='title'>IP LAN</td><td colspan=5><input size='80' name='ip' value='$ADDONIP'></td></tr>
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