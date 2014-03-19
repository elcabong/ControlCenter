<?php
if(isset($_GET['mac'])) {
	$mac = $_GET['mac'];
}
if(isset($_GET['ip'])) {
	$ip = $_GET['ip'];
}
echo "<script type='text/javascript' src='../js/jquery-1.10.1.min.js'></script>
			<div style='color:#eee;margin: 5% auto 0;position: relative;text-align: center;'>
			<h1>Power On</h1>
			<a href='#' class='pingicon' onclick=\"wakethismachine('$mac','$ip');\" style='display: block;'>
			
			<img src='../media/powerbutton-red.png' title='offline - click to try to wake machine' style='max-width:90%;max-height:40%;'/></a>
			</div>";
?>
<script>
	function wakethismachine(mac,ip) {
		$.ajax({
			   type: "POST",
			   url: "wol-check.php?m="+mac+"",
			   //data: 0, // data to send to above script page if any
			   cache: false,
			   success: function(response)
			{
				parent.document.getElementById('loading').style.display='block';
		   }
		});
	}
</script>