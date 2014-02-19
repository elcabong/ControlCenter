<?php
	$ADDONIP = $enabledaddonsarray["$roomid"]['service.pingmachine']['ADDONIP'];
		$setting1 = $enabledaddonsarray["$roomid"]['service.pingmachine']['setting1'];
		if($setting1 == '1') { 	
		echo "<div id='PINGMACHINE1' class='item'>
			<div class='content'>
				<iframe id='PINGMACHINE1f' src=\"$ADDONIP\" width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>";
		}
?>