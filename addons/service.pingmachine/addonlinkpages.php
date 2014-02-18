<?php
	$ADDONIP = $enabledaddonsarray["$roomid"]['service.pingmachine']['ADDONIP'];
		echo "<div id='PINGMACHINE1' class='item'>
			<div class='content'>
				<iframe id='PINGMACHINE1f' class='PINGMACHINE1' src=\"$ADDONIP\" width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>";
?>