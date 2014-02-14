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
	
	require_once "./addons.php";
	
	
	
									$arr = explode(",", $enabledaddons);
									
									foreach($arr as $thearr) {
										$arr = explode(".", $thearr, 2);
										$classification = $arr[0];
										$title = $arr[1];

										$sql3 = "SELECT * FROM rooms_addons WHERE roomid = $roomnum AND addonid = '$thearr' LIMIT 1";
											foreach ($configdb->query($sql3) as $addonSettings)
												{
												$ADDONIP = $addonSettings['ip'];
												$MAC = $addonSettings['mac'];
												$setting1 = $addonSettings['setting1'];
												$setting2 = $addonSettings['setting2'];
												$setting3 = $addonSettings['setting3'];
												$setting4 = $addonSettings['setting4'];
												$setting5 = $addonSettings['setting5'];
												$setting6 = $addonSettings['setting6'];
												$setting7 = $addonSettings['setting7'];
												$setting8 = $addonSettings['setting8'];
												$setting9 = $addonSettings['setting9'];
												$setting10 = $addonSettings['setting10'];
												}										
										
										include $addonarray["$classification"]["$title"]['path']."addonquicklink.php";
									}
	

		$ROOMNUMBER = "ROOM$roomnum"."N";
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
					echo "<img class='roomdetails' theroom=\"$i\" ip=\"$ip\" src='../media/options.png'>";
					echo "</li>";
				}
			}
		echo "</ul>";

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
		if(iframe2.src != '<? echo $ADDONIP; ?>') {
			iframe2.setAttribute('src','<? echo $ADDONIP; ?>');
			iframe2.setAttribute('data-src','<? echo $ADDONIP; ?>');
			iframe2.src = iframe2.src; }
			
		<? if($setting1 != '0' || $setting1 != '') { ?>
		document.getElementById('secondroomprogram').style.display = 'block';
		var iframe3 = document.getElementById('ROOMCONTROL2f');
		iframe3.setAttribute('data-src','<? echo $setting1; ?>');
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