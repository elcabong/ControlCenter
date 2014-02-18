<?php
	$ADDONIP = $enabledaddonsarray["$roomid"]['mediaplayer.xbmc']['ADDONIP'];
	$setting1 = $enabledaddonsarray["$roomid"]['mediaplayer.xbmc']['setting1'];
		echo "<div id='ROOMCONTROL1' class='item'>
			<div class='content'>
				<iframe id='ROOMCONTROL1f' class='ROOMCONTROL1' src=\"$ADDONIP\" width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>";
		//if($enabledaddonsarray["$roomid"]['mediaplayer.xbmc']['setting1'] != '') {
			echo"<div id='ROOMCONTROL2' class='item'>
						<div class='content'>
							<iframe id='ROOMCONTROL2f' class='ROOMCONTROL2' data-src=\"$setting1\" width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
						</div>
					</div>";
		//}			
?>