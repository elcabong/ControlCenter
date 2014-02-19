<?php
		echo "<li><a href='#XBMCCONTROL1' class='panel persistent unloaded'><img src='$ADDONDIR/mediaplayer.xbmc/media/XBMC.png' height='35px'></a></li>";

		if($enabledaddonsarray["$roomid"]['mediaplayer.xbmc']['setting1'] != '') {
			echo "<li><a href='#XBMCCONTROL2' class='panel persistent unloaded'><img src='$ADDONDIR/mediaplayer.xbmc/media/XBMC.png' height='35px'></a></li>";
		}	
?>