<?
			$found = false;
			$path = 'Portal';
			while(!$found){	
				if(file_exists($path)){ 
					$found = true;
							$thepath = $path;
				}
				else{ $path= '../'.$path; }
			}
			require "$thepath/config.php";
			if ($authsecured && (!isset($_SESSION["$authusername"]) || !$_SESSION["$authusername"] || $_SESSION["$authusername"] != $authusername )) {
				header("Location: login.php");
				exit; }
			if(isset($_SESSION['room'])) {
			$roomnum = $_SESSION['room'];
			}
			require_once './controls-include.php';	
			$i = 1;
			while($i<=$TOTALROOMS) {
				$ip;
				$ROOMXBMC = "ROOM$i"."XBMC";
				$ROOMXBMCM = $ROOMXBMC."M";
				$theperm = "USRPR$i";
				if(!empty(${$ROOMXBMC}) && ${$theperm} == "1"){
					$ip = ${$ROOMXBMC};
					if($roomnum == $i) { $nowplayingip = $ip; }
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
						$status = "alive";
					} else { 
						$status = "dead"; 
					}
					$xbmcmachine = $status;
					if($xbmcmachine == 'alive') {
						include "nowplayinginfo.php";
						if(empty($jsoncheckxbmc['result'])) {
							echo "<li><a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a></li>";
						} else {
							if(empty($jsonactiveplayer['result'])) {
								if($nowplayingip != $ip) {
									echo "<li class='nowplaying'><a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a>";
									echo "<span class='sendcontrols'><a href='#' ip='$ip' class='sendnowplaying' sendtype='start' room='$i'>start</a><a href='#' ip='$ip' class='sendnowplaying' sendtype='send' room='$i'>send</a><a href='#' ip='$ip' class='sendnowplaying' sendtype='clone' room='$i'>clone</a></span></li>";
								} else {
									echo "<li><a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a></li>";
								}
							} else {
								if($activeplayerid=='0' || $activeplayerid=='1' || $activeplayerid=='2') {
									if($nowplayingip == $ip) {
									?>
									<script>
										  var cols =     document.getElementsByClassName('sendcontrols');
										  for(i=0; i<cols.length; i++) {
											cols[i].style.display =    'block';
										  }
									</script>
									<?
									}
									echo "<li class='nowplaying'><a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a><span><a href='#' ip='$ip' class='nowplaying-modal'>";
									if($activeplayerid==0) {
										if($filetype=="unknown") {
											echo "File: ";
											echo $thelabel;
										} elseif($filetype=="song") {
											echo "Song: ";
											if(false !== stripos($thealbum, '$theyear')) { echo "$thealbum"; } else { echo "$thealbum ($theyear)"; }
											echo " - ".$thetitle;
										}
									} elseif($activeplayerid==1) {
										if($filetype=="unknown") {
											echo "File: ";
											echo $thelabel;
										} elseif($filetype=="movie") {
											echo "Movie: ";
											echo $thetitle;
											if(false !== stripos($thetitle, '$theyear')) { } else { echo " ($theyear)"; }
										} else {
											echo "Episode: ";
											echo "$theshowtitle - $theshowseason$theshowepisode - $thetitle";
										}
									} elseif($activeplayerid==2) {
										echo "pics";
									}
									echo "</a></span></li>"; 
								}
							}
						}
					} else {
						echo "<li><a href='#' class='pingicon' onclick=\"document.getElementById('loading').style.display='block';wakemachine('${$ROOMXBMCM}');\"><img src='../media/red.png' title='offline - click to try to wake machine' style='height:20px;'/></a></li>";
					}
				}
			$i++;
			}
?>
<script>
	function wakemachine(mac) {
		$.ajax({
			   type: "POST",
			   url: "wol-check.php?m="+mac+"",
			   data: 0, // data to send to above script page if any
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
		$('.nowplaying-modal').click(function (e) {
			var thisip = $(this).attr('ip');
			$('#nowplaying').load('nowplaying.php?ip='+thisip).modal({
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
	reSizeNowPlaying();
</script>