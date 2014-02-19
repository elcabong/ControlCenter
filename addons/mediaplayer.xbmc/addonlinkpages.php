<?php
	$ADDONIP = $enabledaddonsarray["$roomid"]['mediaplayer.xbmc']['ADDONIP'];
	$setting1 = $enabledaddonsarray["$roomid"]['mediaplayer.xbmc']['setting1'];
		echo "<div id='XBMCCONTROL1' class='item'>
			<div class='content'>
				<iframe id='XBMCCONTROL1f' src=\"$ADDONIP\" width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>";
		if($enabledaddonsarray["$roomid"]['mediaplayer.xbmc']['setting1'] != '') {
			echo"<div id='XBMCCONTROL2' class='item'>
						<div class='content'>
							<iframe id='XBMCCONTROL2f' data-src=\"$setting1\" width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
						</div>
					</div>";
		}			
?>