<?php
						$ip = $enabledaddonsarray["$THISROOMID"]["$addonid"]['ADDONIP'];
						require "nowplayinginfo.php";
						if(empty($jsoncheckxbmc['result'])) {
							$sessvar = "playinginroom$THISROOMID";
							$_SESSION[$sessvar] = 0;
							echo "<a href='#' class='pingicon'><img src='../media/orange.png' title='online with no xbmc running' style='height:20px;'/></a>";
						} else {
							if(empty($jsonactiveplayer['result'])) {
								echo "<a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a>";
								$sessvar = "playinginroom$THISROOMID";
								$_SESSION[$sessvar] = 0;
								$thissessvar = "playinginroom$_SESSION[room]";
								if(isset($_SESSION[$thissessvar])) { $checkstillplaying = $_SESSION[$thissessvar]; } else { $checkstillplaying = 0; }
								if($nowplayingip != $ip && $checkstillplaying == 1) {
									echo "<span class='sendcontrols'><a href='#' ip='$ip' class='sendnowplaying' sendtype='start' room='$THISROOMID'>start</a><a href='#' ip='$ip' class='sendnowplaying' sendtype='send' room='$THISROOMID'>send</a><a href='#' ip='$ip' class='sendnowplaying' sendtype='clone' room='$THISROOMID'>clone</a></span>";
								}
							} else {
								$sessvar = "playinginroom$THISROOMID";
								$_SESSION[$sessvar] = 1;
								if($activeplayerid=='0' || $activeplayerid=='1' || $activeplayerid=='2') {
									echo "<a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a><span><a href='#' ip='$ip' thisroom='$THISROOMID' class='roominfo-modal'><p class='scrolling'>";
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
?>
<script>
	var from = "<?php echo $nowplayingip; ?>";
	jQuery(function ($) {
		$('.sendnowplaying').click(function (e) {
			var thisip = $(this).attr('ip');
			var thistype = $(this).attr('sendtype');
			var theroomnum = $(this).attr('room');
			var usernumber = <?echo $usernumber; ?>;
			$.ajax({
				type: "POST",
				url : "<?= $addonarray["$classification"]["$title"]['path'];?>nowplayingsend.php?to="+thisip+"&from="+from+"&sendtype="+thistype+"&addon=<?php echo $classification. '.' .$title; ?>",
				//data: 0, // data to send to above script page if any
				cache: false,
				success : function (data) {
					if(thistype=="send" || thistype=="start") {
						document.getElementById('loading').style.display='block';
						var today = new Date();
						var expire = new Date();
						expire.setTime(today.getTime() + 3600000*24*5);
						document.cookie="currentRoom"+usernumber+"="+ escape(theroomnum) + ";expires="+expire.toGMTString()+";path=/";
						//$("#firstroomprogramlink").removeClass('unloaded');
						$("#room-menu").load("./room-chooser.php");
					}
				}
			});
			return false;
		});
	});
</script>