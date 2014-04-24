<?php
// you can set/use any of these variables in the following arrays:
//  $addonarray["$classification"]["$title"]       >   if your addon is "test.addon5" then $classification = test  and  $title = addon5.   the script should set them when this page is called.
//  $enabledaddonsarray["$THISROOMID"]['test.addon5']        >    where "test.addon5" is your addons name and $THISROOMID is the room the addon is associated with.  $THISROOMID is already set.

$wanip = $enabledaddonsarray["$THISROOMID"]['mediaplayer.xbmc']['ADDONIPW'];
if($WANCONNECTION == '1' && isset($wanip) && $wanip != '') {
	$ADDONIP = $wanip; 
} else { $ADDONIP = $enabledaddonsarray["$THISROOMID"]['mediaplayer.xbmc']['ADDONIP']; }
$ADDONMAC = $enabledaddonsarray["$THISROOMID"]['mediaplayer.xbmc']['MAC'];
$setting1 = $enabledaddonsarray["$THISROOMID"]['mediaplayer.xbmc']['setting1'];
		
		
		
	if($addontype == 'links') {
		// this is the section that adds the buttons next to the room name

		echo "<li><a href='#XBMCCONTROL1' class='panel persistent unloaded'><img src='$ADDONDIR/mediaplayer.xbmc/media/XBMC.png' height='35px'></a></li>";

		if($setting1 != '') {
			echo "<li><a href='#XBMCCONTROL2' class='panel persistent unloaded'><img src='$ADDONDIR/mediaplayer.xbmc/media/XBMC.png' height='35px'></a></li>";
		}
		
		
		
		
		
	} elseif($addontype == 'pages') {
		// this section is the actual frames that the links are displayed in
	
		echo "<div id='XBMCCONTROL1' class='item'>
			<div class='content'>";
			if($_SESSION[$ADDONIP] != 'alive') {
				echo "<iframe id='XBMCCONTROL1f' src=\"wakemachine.php?ip=$ADDONIP&mac=$ADDONMAC\" width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>";
			} else {
				echo "<iframe id='XBMCCONTROL1f' src=\"$ADDONIP\" width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>";
			}
		echo "</div>
		</div>";
		if($setting1 != '') {
			echo"<div id='XBMCCONTROL2' class='item'>
						<div class='content'>
							<iframe id='XBMCCONTROL2f' data-src=\"$setting1\" width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
						</div>
					</div>";
		}
	}
?>