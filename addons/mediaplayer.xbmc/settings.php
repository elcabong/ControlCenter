<?php
$ADDONIP = $enabledaddonsarray[$roomid]['mediaplayer.xbmc']['ADDONIP'];
$ADDONMAC = $enabledaddonsarray[$roomid]['mediaplayer.xbmc']['MAC'];
$setting1 = $enabledaddonsarray[$roomid]['mediaplayer.xbmc']['setting1'];
$setting2 = $enabledaddonsarray[$roomid]['mediaplayer.xbmc']['setting2'];
$setting3 = $enabledaddonsarray[$roomid]['mediaplayer.xbmc']['setting3'];
$setting4 = $enabledaddonsarray[$roomid]['mediaplayer.xbmc']['setting4'];
$setting5 = $enabledaddonsarray[$roomid]['mediaplayer.xbmc']['setting5'];
$setting6 = $enabledaddonsarray[$roomid]['mediaplayer.xbmc']['setting6'];
$setting7 = $enabledaddonsarray[$roomid]['mediaplayer.xbmc']['setting7'];
$setting8 = $enabledaddonsarray[$roomid]['mediaplayer.xbmc']['setting8'];
$setting9 = $enabledaddonsarray[$roomid]['mediaplayer.xbmc']['setting9'];
$setting10 = $enabledaddonsarray[$roomid]['mediaplayer.xbmc']['setting10'];

echo "<tr><td>&nbsp;</td></tr><tr><td></td><td colspan=5>$title</td></tr>
		<tr><td class='title'>IP</td><td colspan=5><input size='80' name='ip' value='$ADDONIP'></td></tr>
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