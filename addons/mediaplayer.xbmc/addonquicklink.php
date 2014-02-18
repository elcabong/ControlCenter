<?php
		echo "<li><a href='#XBMCCONTROL1' class='panel persistent unloaded'><img src='$ADDONDIR/mediaplayer.xbmc/media/XBMC.png' height='35px'></a></li>";

		$setting1 = $enabledaddonsarray["$roomid"]['mediaplayer.xbmc']['setting1'];
		if($setting1 == '') { $showorhide = "display:none;"; } else { $showorhide = ''; }
			echo "<li style='$showorhide'><a href='#XBMCCONTROL2' class='panel persistent unloaded'><img src='$ADDONDIR/mediaplayer.xbmc/media/XBMC.png' height='35px'></a></li>";
?>