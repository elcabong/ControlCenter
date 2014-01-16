<?php
	require_once "./config.php";
	if ($authsecured && (!isset($_SESSION["$authusername"]) || !$_SESSION["$authusername"] || $_SESSION["$authusername"] != $authusername )) {
		header("Location: login.php");
		exit;
	}
	require_once "./controls-include.php";
	
	if(isset($_COOKIE["currentRoom$usernumber"])) {
	$roomnum = $_COOKIE["currentRoom$usernumber"];
	$theperm = "USRPR$roomnum";
	if(${$theperm} == "1") {
	$_SESSION['room'] = $roomnum; } }
	if(!$_SESSION['room']) {
	$roomnum = $HOMEROOMU;
	$_SESSION['room'] = $roomnum; } else {
	$roomnum = $_SESSION['room']; }

		$ROOMNUMBER = "ROOM$roomnum"."N";
		echo "<a href='#' onclick=\"return false;\" class='title'>${$ROOMNUMBER}</a>";
		echo "<ul>";
			$thisroom = 0;
			foreach ($roomgroupaccessarray as $i) {
				$ROOMXBMC = "ROOM$i"."XBMC";
				$ip = ${$ROOMXBMC};
				$ROOMNUMBER = "ROOM$i"."N";
				$theperm = "USRPR$i";
				if(!empty(${$ROOMXBMC}) && ${$theperm} == "1"){
					echo "<li>";
					if($i == $_SESSION['room']) {
					echo "<a class='selected changeroom' href='#' newroom=\"$i\" >${$ROOMNUMBER}</a>"; 				
					$thisroom = 1;
					} else {
					echo "<a class='changeroom' href='#' newroom=\"$i\" >${$ROOMNUMBER}</a>"; 
					}
					echo "<img class='roomdetails' theroom=\"$i\" ip=\"$ip\" src='../media/options.png'>";
					echo "</li>";
				}
			}
		echo "</ul>";

	$ROOMXBMC = "ROOM$roomnum"."XBMC";
	$ROOMXBMC2 = $ROOMXBMC."2";
	$xbmcip = ${$ROOMXBMC};
	$xbmcip2 = ${$ROOMXBMC2};
?>
<script type="text/javascript">
	$(document).ready(function() {
	// needs to set cookie for menu size, then on load see which one it is and set properly
	//document.cookie="roomMenuSize=75;expires="+expire.toGMTString()+";path=/";
		//var menusize = $.cookie("roomMenuSize");
		//if(menusize != null && menusize > 0) {
			//$('#room-menu > ul').css("width",menusize+'px');
		//}
	
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
			var ip = $(this).attr('ip');
			$('#modal').load('roomdetails.php?thisroom='+thisroom+'&ip='+ip).modal({
					opacity: 25,
					overlayClose: true});
			return false;
		});
	});		
	
	$('a.changeroom').click(function () {
        var thenewroom = $(this).attr('newroom');
		changeroom(thenewroom,<?echo $usernumber; ?>);
		return false;
	});	

	function changeroom(newroom,	usernumber) {
		document.getElementById('loading').style.display='block';
		var today = new Date();
		var expire = new Date();
		expire.setTime(today.getTime() + 3600000*24*5);
		document.cookie="currentRoom"+usernumber+"="+ escape(newroom) + ";expires="+expire.toGMTString()+";path=/";
		$("#firstroomprogramlink").removeClass('unloaded');
		$("#room-menu").load("./room-chooser.php");
	}
		
		var t;
		clearTimeout(t);
		t=setTimeout(func, 1700);
		function func() {
			document.getElementById('loading').style.display='none';	
		}

		var iframe2 = document.getElementById('ROOMCONTROL1f');
		if(iframe2.src != '<? echo $xbmcip; ?>') {
			iframe2.setAttribute('src','<? echo $xbmcip; ?>');
			iframe2.setAttribute('data-src','<? echo $xbmcip; ?>');
			iframe2.src = iframe2.src; }
			
		<? if($xbmcip2 != '0' || $xbmcip2 != '') { ?>
		document.getElementById('secondroomprogram').style.display = 'block';
		var iframe3 = document.getElementById('ROOMCONTROL2f');
		iframe3.setAttribute('data-src','<? echo $xbmcip2; ?>');
		iframe3.removeAttribute('src');
		$('#secondroomprogramlink').addClass('unloaded');
		<? } else { ?>
			document.getElementById('secondroomprogram').style.display = 'none';
			var iframe3 = document.getElementById('ROOMCONTROL2f');
			iframe3.setAttribute('data-src','');			
			iframe3.setAttribute('src','');	
			<? } ?>

			var iframeclear = document.getElementById('nonpersistentf');
			iframeclear.src = '';
			$('a.panel.nonpersistent').addClass('unloaded');			
			
			$('a.panel').removeClass('selected');
			$('#firstroomprogramlink').addClass('selected');
			$('#wrapper').scrollTo(0,0);
</script>