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
require_once './controls-include.php';	
			$i = 1;
			while($i<=$TOTALROOMS) {
				$ip;
				$ROOMXBMC = "ROOM$i"."XBMC";
				$ROOMXBMCM = $ROOMXBMC."M";
				$theperm = "USRPR$i";
				if(!empty(${$ROOMXBMC}) && ${$theperm} == "1"){
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
						$status = "alive";
					} else { 
						$status = "dead"; 
					}
					$xbmcmachine = $status;
					if($xbmcmachine == 'alive') {
						//$ip = ${$ROOMXBMC};
						include "nowplayinginfo.php";
						if(empty($jsonactiveplayer['result'])) {
							echo "<li><a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a></li>";
						} else {
							if($activeplayerid=='0' || $activeplayerid=='1' || $activeplayerid=='2') {
								echo "<li class='nowplaying'><a href='#' ip='$ip' class='nowplaying-modal'>";
							}
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
							echo "</a><a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a></li>"; 
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
</script>