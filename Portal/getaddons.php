<?
	$THISROOMID = '';
	if(isset($_GET['room'])) { $THISROOMID = $_GET['room']; } else { exit; }

	require './config.php';
	if ($authsecured && (!isset($_SESSION["$authusername"]) || $_SESSION["$authusername"] != $authusername )) {
		header("Location: login.php");
		exit;}
	if(isset($_SESSION['room'])) {
		$roomid = $_SESSION['room'];
		$sql3 = "SELECT * FROM rooms_addons WHERE roomid = $roomid";
		foreach ($configdb->query($sql3) as $addonSettings)
			{
				if($addonSettings['ip'] != '') {
					$nowplayingip = $addonSettings['ip'];
					break;
				}
			}
	}

		require './addons.php';
		if (strpos($enabledaddons,',') !== false) {
			$arr = explode(",", $enabledaddons);
			$addonid = $arr[0];
		} else { $addonid = $enabledaddons; }

				$arr = explode(".", $addonid, 2);
				$classification = $arr[0];
				$title = $arr[1];

				$i = $THISROOMID;
				$ip;
				if(!empty($enabledaddonsarray["$i"]["$addonid"]['ADDONIP'])) {
					$ip = $enabledaddonsarray["$i"]["$addonid"]['ADDONIP'];
				    $disallowed = array('http://', 'https://');
				    foreach($disallowed as $d) {
					    if(strpos($ip, $d) === 0) {
						   $thisip = strtok(str_replace($d, '', $ip),':');
					    }
				    }
					if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
						$pingresult = exec("ping -n 1 -w 1 $thisip", $output, $status);
						// echo 'This is a server using Windows!';
					} else {
						$pingresult = exec("/bin/ping -c1 -w1 $thisip", $outcome, $status);
						// echo 'This is a server not using Windows!';
					}
					if ($status == "0") {
						if($_SESSION[$ip] == "dead" && $ip == $nowplayingip) {
							$_SESSION[$ip] = 'alive'; ?>
							<script>
								$("#room-menu").load("./room-chooser.php?noreset=1");
							</script>
			<?php } else {					
							$_SESSION[$ip] = 'alive';
						}
						//$status = "alive";
						
						$filename = $addonarray["$classification"]["$title"]['path']."addoninfo.php";
						if (file_exists($filename)) {
							require $addonarray["$classification"]["$title"]['path']."addoninfo.php";
						} else {
							echo "<a href='#' class='pingicon'><img src='../media/orange.png' title='online with no addons' style='height:20px;'/></a>";
						}
					} else {
						if($_SESSION[$ip] == "alive" && $ip == $nowplayingip) {
							$_SESSION[$ip] = 'dead'; ?>
							<script>
								$("#room-menu").load("./room-chooser.php");
							</script>
			<?php } else {
							$_SESSION[$ip] = 'dead';
						}
						//$status = "dead";
						$sessvar = "playinginroom$THISROOMID";
						$_SESSION[$sessvar] = 0;
						$ADDONMAC = $enabledaddonsarray["$i"]["$addonid"]['MAC'];
						echo "<a href='#' class='pingicon' onclick=\"document.getElementById('loading').style.display='block';wakemachine('$ADDONMAC');\"><img src='../media/red.png' title='offline - click to try to wake machine' style='height:20px;'/></a>";
					}
				}
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
</script>