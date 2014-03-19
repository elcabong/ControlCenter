<?php
						$ip = $enabledaddonsarray["$THISROOMID"]["$addonid"]['ADDONIP'];
						require "nowplayinginfo.php";
						if(empty($jsoncheckxbmc['result'])) {
							echo "<a href='#' class='pingicon'><img src='../media/orange.png' title='online with no xbmc running' style='height:20px;'/></a>";
						} else {
							if(empty($jsonactiveplayer['result'])) {
								echo "<a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a>";
							} else {
								if($activeplayerid=='0' || $activeplayerid=='1' || $activeplayerid=='2') {
									echo "<a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a><br>";
									echo "<a href='#' ip='$ip' thisroom='$THISROOMID' class='roominfo-modal'>";
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
											//echo "<img src='../media/DefaultPlaying.png' height='35px' style='float:left;margin-top:-3px;'>";
											//echo $thelabel;
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
									echo "</a>";
								}
							}
						}
?>