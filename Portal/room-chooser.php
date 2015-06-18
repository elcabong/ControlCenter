<?php
require('startsession.php');
require_once("$INCLUDES/includes/config.php");
$log->LogDebug("User $authusername loaded " . basename(__FILE__) . " from " . $_SERVER['SCRIPT_FILENAME']);
require_once "$INCLUDES/includes/auth.php";
require_once "$INCLUDES/includes/controls-include.php";
$noreset = 0;
if(isset($_GET['noreset'])) {
	$noreset = $_GET['noreset'];
}
if(isset($_COOKIE["currentRoom$usernumber"])) {
	$roomnum = $_COOKIE["currentRoom$usernumber"];
	$theperm = "USRPR$roomnum";
	if(${$theperm} == "1") {
		$_SESSION['room'] = $roomnum; 
	} 
}
if(!$_SESSION['room']) {
	$roomnum = $HOMEROOMU;
	$_SESSION['room'] = $roomnum;
} else {
	$roomnum = $_SESSION['room'];
}
$roomid = $roomnum;
require_once "$INCLUDES/includes/addons.php";
$ROOMNUMBER = "ROOM$roomnum"."N";
$log->LogInfo("User $authusername loaded room ${$ROOMNUMBER}");
echo "<a href='#' onclick=\"return false;\" class='title'>${$ROOMNUMBER}</a>";
echo "<ul>";
$thisroom = 0;
foreach ($roomgroupaccessarray as $i) {
	$ROOMNUMBER = "ROOM$i"."N";
	$theperm = "USRPR$i";
	if(!empty(${$ROOMNUMBER}) && ${$theperm} == "1"){
		echo "<li>";
		if($i == $_SESSION['room']) {
		echo "<a class='selected changeroom' href='#' newroom=\"$i\" >${$ROOMNUMBER}</a>"; 				
		$thisroom = 1;
		} else {
		echo "<a class='changeroom' href='#' newroom=\"$i\" >${$ROOMNUMBER}</a>"; 
		}
		echo "<img class='roomdetails' theroom=\"$i\" src='../media/options.png'>";
		echo "</li>";
	}
}
echo "</ul>";
?>
<script type="text/javascript">
	$(document).ready(function() {
	<?php /*
	// needs to set cookie for menu size, then on load see which one it is and set properly
	//document.cookie="roomMenuSize=75;expires="+expire.toGMTString()+";path=/";
		//var menusize = $.cookie("roomMenuSize");
		//if(menusize != null && menusize > 0) {
			//$('#room-menu > ul').css("width",menusize+'px');
		//}
	*/ ?>
	
		$("#room-menu > ul").touchwipe({
			wipeLeft: function(e) {
				$('#room-menu > ul').css("width",'180px');
				reSizeRoomInfo();
				//document.cookie="roomMenuSize=180;expires="+expire.toGMTString()+";path=/";
			},
			wipeRight: function(e) {
				$('#room-menu > ul').css("width",'75px');
				reSizeRoomInfo();
				//document.cookie="roomMenuSize=75;expires="+expire.toGMTString()+";path=/";
			}
		});
	});

	jQuery(function ($) {
		$('img.roomdetails').click(function (e) {
			var thisroom = $(this).attr('theroom');
			//var ip = $(this).attr('ip');
			$('#modal').load('roomdetails.php?thisroom='+thisroom).modal({
					opacity: 75,
					overlayClose: true});
			return false;
		});
	});		
	
	$('a.changeroom').click(function () {
        var thenewroom = $(this).attr('newroom');
		changeroom(thenewroom,<?php echo $usernumber; ?>);
		return false;
	});	

	function changeroom(newroom,	usernumber) {
		document.getElementById('loading').style.display='block';
		var today = new Date();
		var expire = new Date();
		expire.setTime(today.getTime() + 3600000*24*5*50);
		document.cookie="currentRoom"+usernumber+"="+ escape(newroom) + ";expires="+expire.toGMTString()+";path=/";
		if(!window.location.hash) {
			$("a.panel").removeClass('selected');
		}
		$("#room-menu").load("./room-chooser.php");
	}

	var t;
	clearTimeout(t);
	t=setTimeout(func, 1000);
	function func() {
		document.getElementById('loading').style.display='none';	
	}

	$("#addonlinks").load("./addonslinks.php?addon=links");
	$("#addonlinkspages").load("./addonslinks.php?addon=pages");
	<?php if($noreset == 0) { ?>
		$('#wrapper').scrollTo(0,0);
	<?php } ?>
</script>