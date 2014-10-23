<?php 
if(isset($_GET['thisroom'])) { $theroom=$_GET['thisroom'];$THISROOMID=$theroom; } else { exit; }
$ROOMNUMBER = "ROOM$theroom"."N";
require_once 'config.php';
$log->LogDebug("User $authusername from $USERIP loaded " . basename(__FILE__));
require 'addons.php';
$ADDONIP = $enabledaddonsarray["$THISROOMID"]["mediaplayer.xbmc"]['ADDONIP'];
?>
<!DOCTYPE html>
<html>
<head>
<title>Search</title>
<link type='text/css' href='../css/modal.css?<?php echo date ("m/d/Y-H.i.s", filemtime("../css/modal.css"));?>' rel='stylesheet' media='screen' />
</head>
<body>
<div id='searchcontainer'>
	<div id='logo'>
		<h1>Search: <span><input id="SendTextField" name="SendTextField" type="text" placeholder="Search Tems or Media Title"></span></h1>
	</div>
	<div id='searchproviders'>
	<Br>Click To Search:<br><Br>
	<?php
		foreach($searchproviders as $searchprovider) {
			$thisaddonid = explode(".", $searchprovider, 2);
			$classification = $thisaddonid[0];
			$title = $thisaddonid[1];
			$image = $addonarray["$classification"]["$title"]['path'] . "media/icon.png";
			echo "<a href='#' class='search' provider='$title'><img src='$image' style='width:120px;'/></a>";
		}
	?>
	</div>
	<hr style="border-color:grey;">
	<div id="searchresults"></div>
<script>
	<?php
		foreach($searchproviders as $searchprovider) {
			$thisaddonid = explode(".", $searchprovider, 2);
			$classification = $thisaddonid[0];
			$title = $thisaddonid[1];
			$addonpath = $addonarray["$classification"]["$title"]['path'];
			echo "	jQuery(function ($) {
							$('.search').click(function (e) {
								var thisterm = encodeURIComponent(($('#SendTextField').val()));
								var thisprovider = $(this).attr('provider');
								$('#searchresults').load('$ADDONDIR/search.'+ thisprovider + '/search.php?mediaplayerip=$ADDONIP&term='+ thisterm).modal({
										opacity: 75,
										overlayClose: true
								});
								return false;
							});
						});";
		}
	?>
</script>
</div>
</body>
</html>