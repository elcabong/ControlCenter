<?php
		echo "<li><a id='firstroomprogramlink' href='#ROOMCONTROL1' class='panel persistent selected'><img src='../media/Programs/XBMC.png' height='35px'></a></li>";
		//if($setting1 != '') {
		if($setting1 == '') { $showorhide = "display:none;"; } else { $showorhide = ''; }
			echo "<li id='secondroomprogram' style='$showorhide'><a id='secondroomprogramlink' href='#ROOMCONTROL2' class='panel persistent unloaded'><img src='../media/Programs/XBMC.png' height='35px'></a></li>";
		//}
?>