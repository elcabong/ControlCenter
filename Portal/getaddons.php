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
					$thisaddonpart = explode(".", $addonSettings['addonid'], 2);
					$classification = $thisaddonpart[0];
					if($classification == "mediaplayer"){
						$nowplayingip = $addonSettings['ip'];
						break;
					}
				}
			}
	}

		require './addons.php';
		$allenabledaddons = explode(",", $enabledaddons);			
		$howmanyaddons=0;
		foreach($allenabledaddons as $theaddon) {
				if($theaddon == '') { break; }
				$addonid = $theaddon;
				$thisaddonpart = explode(".", $theaddon, 2);
				$classification = $thisaddonpart[0];
				$title = $thisaddonpart[1];				
				$i = $THISROOMID;
				$ip;
				if(!empty($enabledaddonsarray["$i"]["$addonid"]['ADDONIP'])) {
					$ip = $enabledaddonsarray["$i"]["$addonid"]['ADDONIP']; 
				}
				if(($WANCONNECTION == '1' && !empty($enabledaddonsarray["$i"]["$addonid"]['ADDONIPW']))) {
					$ip = $enabledaddonsarray["$i"]["$addonid"]['ADDONIPW']; 
				}
				if($ip != '') {
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
							$howmanyaddons++;
							include $addonarray["$classification"]["$title"]['path']."addoninfo.php";
						} else {
							echo "<a href='#' class='pingicon'><img src='../media/cyan.png' title='online with no addons' style='height:20px;'/></a>";
						}
					} else {
						if($_SESSION[$ip] == "alive" && $ip == $nowplayingip) {
							$_SESSION[$ip] = 'dead'; ?>
							<script>
								$("#room-menu").load("./room-chooser.php?noreset=1");
							</script>
			<?php } else {
							$_SESSION[$ip] = 'dead';
						}
						//$status = "dead";
						if($classification == "mediaplayer") {
							$sessvar = "playinginroom$THISROOMID";
							$_SESSION[$sessvar] = 0;
						}
						if(isset($enabledaddonsarray["$i"]["$addonid"]['MAC'])) {
							$ADDONMAC = $enabledaddonsarray["$i"]["$addonid"]['MAC'];
						} else {
							$ADDONMAC = '';
						}
						echo "<a href='#' class='pingicon' onclick=\"document.getElementById('loading').style.display='block';wakemachine('$ADDONMAC');\"><img src='../media/red.png' title='offline - click to try to wake machine' style='height:20px;'/></a>";
					}
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