<?
	$setroomnum = '';
	if(isset($_GET['room'])) { $setroomnum = $_GET['room']; } else { exit; }
	//if(isset($_GET['addonid'])) { $addonid = $_GET['addonid']; }
	
			require './config.php';
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
ini_set('display_errors', 'On');
if (strpos($enabledaddons,',') !== false) {
    $arr = explode(",", $enabledaddons);
	$addonid = $arr[0];
} else { $addonid = $enabledaddons; }

				$arr = explode(".", $addonid, 2);
				$classification = $arr[0];
				$title = $arr[1];

										$sql3 = "SELECT * FROM rooms_addons WHERE roomid = $setroomnum AND addonid = '$addonid' LIMIT 1";
											foreach ($configdb->query($sql3) as $addonSettings)
												{
												$ADDONIP = $addonSettings['ip'];
												$ADDONMAC = $addonSettings['mac'];
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

				$i = $setroomnum;
				$ip;
				if(!empty($ADDONIP)) {
					$ip = $ADDONIP;
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
						//$status = "alive";
						
						$filename = $addonarray["$classification"]["$title"]['path']."addoninfo.php";
						if (file_exists($filename)) {
							require $addonarray["$classification"]["$title"]['path']."addoninfo.php";
						} else {
							echo "<a href='#' class='pingicon'><img src='../media/orange.png' title='online with no addons' style='height:20px;'/></a>";
						}
					} else {
						//$status = "dead";
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
					opacity: 25,
					overlayClose: true});
			return false;
		});
	});	
</script>