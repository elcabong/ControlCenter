<?php
// you can set/use any of these variables in the following arrays:
//  $addonarray["$classification"]["$title"]       >   if your addon is "test.addon5" then $classification = test  and  $title = addon5.   the script should set them when this page is called.
//  $enabledaddonsarray["$THISROOMID"]['test.addon5']        >    where "test.addon5" is your addons name and $THISROOMID is the room the addon is associated with.  $THISROOMID is already set.

$wanip = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['ADDONIPW'];
if($WANCONNECTION == '1' && isset($wanip) && $wanip != '') {
	$ADDONIP = $wanip; 
} else { $ADDONIP = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['ADDONIP']; }
$ADDONMAC = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['MAC'];
$setting1 = $enabledaddonsarray["$THISROOMID"]['utility.diskripper']['setting1'];
		
		
		
	if($addontype == 'links') {
		// this is the section that adds the buttons next to the room name

		echo "<li><a href='#DISKRIPPER1' class='panel persistent unloaded'><img src='$ADDONDIR/utility.diskripper/media/XBMC.png' height='35px'></a></li>";

		if($setting1 != '') {
			echo "<li><a href='#DISKRIPPER2' class='panel persistent unloaded'><img src='$ADDONDIR/utility.diskripper/media/XBMC.png' height='35px'></a></li>";
		}
		
		
		
		
		
	} elseif($addontype == 'pages') {
		// this section is the actual frames that the links are displayed in
	
		echo "<div id='DISKRIPPER1' class='item'>
			<div class='content'>";
			if($_SESSION[$ADDONIP] != 'alive') {
				echo "<iframe id='DISKRIPPER1f' src=\"wakemachine.php?ip=$ADDONIP&mac=$ADDONMAC\" width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>";
			} else {
				echo "<iframe id='DISKRIPPER1f' src=\"$ADDONIP\" data-src=\"$ADDONIP\" width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>";
			}
		echo "</div>
		</div>";
		if($setting1 != '') {
			echo"<div id='DISKRIPPER2' class='item'>
						<div class='content'>
							<iframe id='DISKRIPPER2f' data-src=\"$setting1\" width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
						</div>
					</div>";
		}
	}
?>