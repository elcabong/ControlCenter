<?php 
if(isset($_GET['thisroom'])) { $theroom=$_GET['thisroom'];$THISROOMID=$theroom; } else { exit; }
$ROOMNUMBER = "ROOM$theroom"."N";	
require_once 'config.php';
$log->LogDebug("User $authusername from $USERIP tried loading room $ROOMNUMBER from " . basename(__FILE__));
require 'addons.php';
?>
<!DOCTYPE html>
<html>
<head>
<title>RoomDetails</title>
<link type='text/css' href='../css/nowplaying.css' rel='stylesheet' media='screen' />
</head>
<body>
<div id='roominfocontainer'>
	<div id='logo'>
		<h1>Details:<span><?= ${$ROOMNUMBER};?></span></h1>
	</div>
	<div id='roominfocontent'>
	<?php
		foreach($enabledaddonsarray[$theroom] as $addonid => $array) {
		$thisaddonid = explode(".", $addonid, 2);
						$classification = $thisaddonid[0];
						$title = $thisaddonid[1];
						echo "<br>";
						echo $addonarray["$classification"]["$title"]['name'];
						echo "<br>";
						if(isset($_SESSION[$ip]) && $_SESSION[$ip] == "alive") {
							$filename = $addonarray["$classification"]["$title"]['path']."details.php";
							if (file_exists($filename)) {
								require $filename;
								echo "<br><br>";
							}
						} else {
							if(!empty($enabledaddonsarray["$theroom"]["$addonid"]['ADDONIP'])) {
								$ip = $enabledaddonsarray["$theroom"]["$addonid"]['ADDONIP'];
								$disallowed = array('http://', 'https://');
								foreach($disallowed as $d) {
									if(strpos($ip, $d) === 0) {
									   $thisip = strtok(str_replace($d, '', $ip),':');
									}
								}
								if(strpos($thisip, "/") != false) {
									$thisip = substr($thisip, 0, strpos($thisip, "/"));
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
									$filename = $addonarray["$classification"]["$title"]['path']."details.php";
									if (file_exists($filename)) {
										require $filename;
										echo "<br><br>";
									}
								} else {
									//$status = "dead";
									$ADDONMAC = $enabledaddonsarray["$theroom"]["$addonid"]['MAC'];
									echo "<a href='#' class='pingicon' onclick=\"document.getElementById('loading').style.display='block';wakemachine('$ADDONMAC');\"><img src='../media/red.png' title='offline - click to try to wake machine' style='height:20px;'/></a>";
								}
							}
						}	
		}
	?>
	</div>
<script>
	function wakemachine(mac) {
		$.ajax({
			   type: "POST",
			   url: "wol-check.php?m="+mac+"",
			   cache: false,
			   success: function(response)
			{
				setTimeout(func, 35000);
				function func() {
					document.getElementById('loading').style.display='none';	
				}
		   }
		});
	}
</script>	
</div>
</body>
</html>