<?
	$setroomnum = '';
	if(isset($_GET['room'])) { $setroomnum = $_GET['room']; } else { exit; }

			require './config.php';
		//	if ($authsecured && (!isset($_SESSION["$authusername"]) || !$_SESSION["$authusername"] || $_SESSION["$authusername"] != $authusername )) {
		//		header("Location: login.php");
		//		exit; }
			if(isset($_SESSION['room'])) {
			$roomnum = $_SESSION['room'];
			$ROOMXBMC = "ROOM$roomnum"."XBMC";
			$nowplayingip = ${$ROOMXBMC};
			}
			
				$i = $setroomnum;
				$ip;
				$ROOMXBMC = "ROOM$i"."XBMC";
				$ROOMXBMCM = $ROOMXBMC."M";
				if(!empty(${$ROOMXBMC})) {
					$ip = ${$ROOMXBMC};
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
						include "nowplayinginfo.php";
						if(empty($jsoncheckxbmc['result'])) {
							echo "<a href='#' class='pingicon'><img src='../media/orange.png' title='online' style='height:20px;'/></a>";
						} else {
							if(empty($jsonactiveplayer['result'])) {
								echo "<a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a>";
								$sessvar = "playinginroom$i";
								$_SESSION[$sessvar] = 0;
								$thissessvar = "playinginroom$roomnum";
								$checkstillplaying = $_SESSION[$thissessvar];
								if($nowplayingip != $ip && $checkstillplaying == 1) {
									echo "<span class='sendcontrols'><a href='#' ip='$ip' class='sendnowplaying' sendtype='start' room='$i'>start</a><a href='#' ip='$ip' class='sendnowplaying' sendtype='send' room='$i'>send</a><a href='#' ip='$ip' class='sendnowplaying' sendtype='clone' room='$i'>clone</a></span>";
								}
							} else {
								$sessvar = "playinginroom$i";
								$_SESSION[$sessvar] = 1;
								if($activeplayerid=='0' || $activeplayerid=='1' || $activeplayerid=='2') {
									echo "<a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a><span><a href='#' ip='$ip' class='roominfo-modal'><p class='scrolling'>";
									if($activeplayerid==0) {
										if($filetype=="unknown") {
											echo "<img src='../media/DefaultPlaying.png' height='35px' style='float:left;margin-top:-3px;'>";
											echo $thelabel;
										} elseif($filetype=="song") {
											echo "<img src='../media/DefaultMusic.png' height='35px' style='float:left;margin-top:-3px;'>";
											if(false !== stripos($thealbum, '$theyear')) { echo "$thealbum"; } else { echo "$thealbum ($theyear)"; }
											echo " - ".$thetitle;
										}
									} elseif($activeplayerid==1) {
										if($filetype=="unknown") {
											echo "<img src='../media/DefaultPlaying.png' height='35px' style='float:left;margin-top:-3px;'>";
											echo $thelabel;
										} elseif($filetype=="movie") {
											echo "<img src='../media/DefaultMovies.png' height='35px' style='float:left;margin-top:-3px;'>";
											echo $thetitle;
											if(false !== stripos($thetitle, '$theyear')) { } else { echo " ($theyear)"; }
										} else {
											echo "<img src='../media/DefaultTVShows.png' height='35px' style='float:left;margin-top:-3px;'>";
											echo "$theshowtitle - $theshowseason$theshowepisode - $thetitle";
										}
									} elseif($activeplayerid==2) {
										echo "pics";
									}
									echo "</p></a></span>";
								}
							}
						}
					} else {
						//$status = "dead";
						echo "<a href='#' class='pingicon' onclick=\"document.getElementById('loading').style.display='block';wakemachine('${$ROOMXBMCM}');\"><img src='../media/red.png' title='offline - click to try to wake machine' style='height:20px;'/></a>";
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
			$('#modal').load('nowplaying.php?ip='+thisip).modal({
					opacity: 25,
					overlayClose: true});
			return false;
		});
	});	
	
	var from = "<? echo $nowplayingip; ?>";
	jQuery(function ($) {
		$('.sendnowplaying').click(function (e) {
			var thisip = $(this).attr('ip');
			var thistype = $(this).attr('sendtype');
			var theroomnum = $(this).attr('room');
			var usernumber = <?echo $usernumber; ?>;
			$.ajax({
				type: "POST",
				url : "nowplayingsend.php?to="+thisip+"&from="+from+"&sendtype="+thistype+"",
				data: 0, // data to send to above script page if any
				cache: false,
				success : function (data) {
					if(thistype=="send" || thistype=="start") {
						document.getElementById('loading').style.display='block';
						var today = new Date();
						var expire = new Date();
						expire.setTime(today.getTime() + 3600000*24*5);
						document.cookie="currentRoom"+usernumber+"="+ escape(theroomnum) + ";expires="+expire.toGMTString()+";path=/";
						$("#firstroomprogramlink").removeClass('unloaded');
						$("#room-menu").load("./room-chooser.php");
					}
				}
			});
			return false;
		});
	});
</script>