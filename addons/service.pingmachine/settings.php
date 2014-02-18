<?php

$ADDONIP = $enabledaddonsarray[$roomid]['service.pingmachine']['ADDONIP'];
$ADDONMAC = $enabledaddonsarray[$roomid]['service.pingmachine']['MAC'];
$setting1 = $enabledaddonsarray[$roomid]['service.pingmachine']['setting1'];
$setting2 = $enabledaddonsarray[$roomid]['service.pingmachine']['setting2'];
$setting3 = $enabledaddonsarray[$roomid]['service.pingmachine']['setting3'];
$setting4 = $enabledaddonsarray[$roomid]['service.pingmachine']['setting4'];
$setting5 = $enabledaddonsarray[$roomid]['service.pingmachine']['setting5'];
$setting6 = $enabledaddonsarray[$roomid]['service.pingmachine']['setting6'];
$setting7 = $enabledaddonsarray[$roomid]['service.pingmachine']['setting7'];
$setting8 = $enabledaddonsarray[$roomid]['service.pingmachine']['setting8'];
$setting9 = $enabledaddonsarray[$roomid]['service.pingmachine']['setting9'];
$setting10 = $enabledaddonsarray[$roomid]['service.pingmachine']['setting10'];


										echo "<tr><td>&nbsp;</td></tr><tr><td></td><td colspan=5>$title</td></tr>
										 <tr><td class='title'>IP</td><td colspan=5><input size='80' name='ip' value='$ADDONIP'></td></tr>
										 <tr><td class='title'>MAC</td><td colspan=5><input size='80' name='mac' value='$ADDONMAC'></td></tr>
										 <tr><td class='title'>Link</td><td colspan=5><input size='80' name='setting1' value='$setting1'></td></tr>";
?>