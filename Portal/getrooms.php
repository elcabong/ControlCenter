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
			
			foreach ($roomgroupaccessarray as $i) {
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
							echo "<li class='roominfo'><a href='#' class='pingicon'><img src='../media/orange.png' title='online' style='height:20px;'/></a></li>";
						} else {
							if(empty($jsonactiveplayer['result'])) {
									echo "<li class='roominfo'><a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a>";
								if($nowplayingip != $ip) {
									echo "<span class='sendcontrols'><a href='#' ip='$ip' class='sendnowplaying' sendtype='start' room='$i'>start</a><a href='#' ip='$ip' class='sendnowplaying' sendtype='send' room='$i'>send</a><a href='#' ip='$ip' class='sendnowplaying' sendtype='clone' room='$i'>clone</a></span>";
								}
									echo "</li>";
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
									echo "<li class='roominfo'><a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a><span><a href='#' ip='$ip' class='roominfo-modal'><p class='scrolling'>";
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
									echo "</p></a></span></li>";
								}
							}
						}
					} else {
						echo "<li class='roominfo'><a href='#' class='pingicon' onclick=\"document.getElementById('loading').style.display='block';wakemachine('${$ROOMXBMCM}');\"><img src='../media/red.png' title='offline - click to try to wake machine' style='height:20px;'/></a></li>";
					}
				}
			}
?>
<script>
	$(document).ready(function() {
		reSizeRoomInfo();
	});
	
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

	
	/*
  var elements = document.getElementsByClassName('scrolling');
  for(var i=0; i < elements.length; i++) {
     var thescrollingelement = elements[i];
		thescrollingelement.className = 'hiding scrolling';

		thescrollingelement.onmouseover = thescrollingelement.onmouseout = thescrollingelement.touchstart = function (e) {
			e = e || window.event;
			e = e.type === 'mouseover';
			clearTimeout(slide_timer);
			this.className = e ? 'scrolling now' : 'hiding scrolling';
			if (e) {
				slide();
			} else {
				this.scrollLeft = 0;
			}
		};
	}

		var slide_timer;
			slide = function () {
			  var newelements = document.getElementsByClassName('scrolling now');
			  for(var i=0; i < newelements.length; i++) {
				 var thiscrollingelement = newelements[i];			
				max = thiscrollingelement.scrollWidth;
				thiscrollingelement.scrollLeft += 1;
					if (thiscrollingelement.scrollLeft <= max) {
						slide_timer = setTimeout(slide, 30);
						return;
					} 
				};
			}

			$(".roominfo-modal").bind('mouseenter touchstart', function() {
			clearTimeout(refreshTheRooms);clearTimeout(refreshTheRooms);
			});	

			$(".roominfo-modal").bind('mouseleave touchend', function() {
			clearTimeout(refreshTheRooms);
			refreshTheRooms = setTimeout(refreshRooms2, 1);
			});

			function refreshRooms2() {
			$("#roomList").load("./getrooms.php");
			refreshTheRooms = setTimeout(refreshRooms2, 1500);
			}
			*/
</script>