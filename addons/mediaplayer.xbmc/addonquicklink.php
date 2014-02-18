<?php
		echo "<li><a id='firstroomprogramlink' href='#ROOMCONTROL1' class='panel persistent selected'><img src='$ADDONDIR/mediaplayer.xbmc/media/XBMC.png' height='35px'></a></li>";

		$setting1 = $enabledaddonsarray["$roomid"]['mediaplayer.xbmc']['setting1'];
		if($setting1 == '') { $showorhide = "display:none;"; } else { $showorhide = ''; }
			echo "<li id='secondroomprogram' style='$showorhide'><a id='secondroomprogramlink' href='#ROOMCONTROL2' class='panel persistent unloaded'><img src='$ADDONDIR/mediaplayer.xbmc/media/XBMC.png' height='35px'></a></li>";
?>