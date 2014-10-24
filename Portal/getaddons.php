<?php
	require './config.php';
	require './addons.php';
	if ($authsecured && (!isset($_SESSION["$authusername"]) || $_SESSION["$authusername"] != $authusername )) {
		header("Location: login.php");
		exit;
	}

	$THISROOMID = '';
	if(isset($_GET['room'])) { $THISROOMID = $_GET['room']; } else { exit; }
	if(isset($_SESSION['room'])) {
		$roomid = $_SESSION['room'];
		$sql3 = "SELECT * FROM rooms_addons WHERE roomid = $roomid";
		foreach ($configdb->query($sql3) as $addonSettings)
			{
				if($addonSettings['ip'] != '') {
					$thisaddonpart = explode(".", $addonSettings['addonid'], 2);
					$classification = $thisaddonpart[0];
					// this may cause other issues.. may need to look into this.
					if($classification == "mediaplayer"){
						$nowplayingip = $addonSettings['ip'];
						break;
					}
				}
			}
	}
	$ROOMNUMBER = "ROOM$THISROOMID"."N";
	$log->LogDebug("User $authusername loaded the addons from room " . ${$ROOMNUMBER} . " " . basename(__FILE__));
	$sql3 = "SELECT * FROM rooms_addons WHERE roomid = $THISROOMID";
	$howmanyaddons = 0;
	foreach ($configdb->query($sql3) as $addonSettings) {
		$device_alive = $addonSettings['device_alive'];
		$thisaddonpart = explode(".", $addonSettings['addonid'], 2);
		$classification = $thisaddonpart[0];
		$title = $thisaddonpart[1];
		$ip = $addonSettings['ip'];

		if($device_alive == 1) {
			if(!isset($_SESSION[$ip])) { $_SESSION[$ip] = 'alive'; }
			if($_SESSION[$ip] == "dead" && $ip == $nowplayingip) {
				$_SESSION[$ip] = 'alive'; 
			?>
				<script>
					$("#room-menu").load("./room-chooser.php?noreset=1");
				</script>
 <?php } else {
				$_SESSION[$ip] = 'alive';
			}
			//$status = "alive";
			$filename = $addonarray["$classification"]["$title"]['path']."addoninfo.php";
			if (file_exists($filename)) {
				$howmanyaddons++;
				//include $addonarray["$classification"]["$title"]['path']."addoninfo.php";
				if((include $filename) === false)
					{
						//	 handle error
						$log->LogError("Error: User $authusername failed to load file $filename from " . basename(__FILE__));
					}
			} else {
				echo "<a href='#' class='pingicon'><img src='../media/cyan.png' title='online with no addons' style='height:20px;'/></a>";
			}
		} else {
			if(!isset($_SESSION[$ip])) { $_SESSION[$ip] = 'dead'; }
			if($_SESSION[$ip] == "alive" && $ip == $nowplayingip) {
				$_SESSION[$ip] = 'dead'; ?>
				<script>
					$("#room-menu").load("./room-chooser.php?noreset=1");
				</script>
<?php  } else {
				$_SESSION[$ip] = 'dead';
			}
			//$status = "dead";
			if($classification == "mediaplayer") {
				$sessvar = "playinginroom$THISROOMID";
				$_SESSION[$sessvar] = 0;
			}
			if(isset($addonSettings['mac'])) {
				$ADDONMAC = $addonSettings['mac'];
			} else {
				$ADDONMAC = '';
			}
			echo "<a href='#' class='pingicon' onclick=\"document.getElementById('loading').style.display='block';wakemachine('$ADDONMAC');\"><img src='../media/red.png' title='offline - click to try to wake machine' style='height:20px;'/></a>";
		}
	}
	$log->LogDebug("User $authusername Got Addons for room $THISROOMID from " . basename(__FILE__));
?>
<script>
	function wakemachine(mac) {
		$.ajax({
			   type: "POST",
			   url: "wol-check.php?m="+mac+"",
			   //data: 0, // data to send to above script page if any
			   cache: false,
			   success: function(response)
			{
				// need to retry ping until successful or hit a set limit, then display none
				setTimeout(func, 35000);
				function func() {
					document.getElementById('loading').style.display='none';	
				}
		   }
		});
	}
	jQuery(function ($) {
		$('.roominfo-modal').click(function (e) {
			var thisip = $(this).attr('ip');
			var thisroom = $(this).attr('thisroom');
			$('#modal').load('nowplaying.php?ip='+thisip+'&thisroom='+thisroom).modal({
					opacity: 75,
					overlayClose: true});
			return false;
		});
	});	
	jQuery(function ($) {
		$('.roominfo-link').click(function (e) {
			e.preventDefault();
			var href = $(this).attr('href');
			if(href == '#') { return false; }
			href = href.replace(/#/g, "" );
			var iframe = document.getElementById(href + 'f');
			if (!iframe.src) {
				$('iframe.' + href).attr('src',$('iframe.' + href).attr('data-src'));
			}
			$('#wrapper').scrollTo($(this).attr('href'), 0);
			$('a.panel').removeClass('selected');
			$('a.panel[href="#'+href+'"]').addClass('selected');
		});
	});	
</script>