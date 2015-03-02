<?php
						$ip = $enabledaddonsarray["$THISROOMID"]["$addonid"]['ADDONIP'];
						require "class.php";
						$KODI = new KODI();
						$kodialive = $KODI->Ping("$ip");
							if($kodialive == "alive") {
								$filetype='';
								// get active player
								$activeplayerid = $KODI->GetActivePlayer("$ip");
								$nowplayingarray = $KODI->GetPlayingItemInfo("$ip","$activeplayerid");
							}
						if($kodialive != "alive") {
							echo "<a href='#' class='pingicon'><img src='../media/orange.png' title='online with no xbmc running' style='height:20px;'/></a>";
						} else {
							if(!isset($activeplayerid) || $activeplayerid == "none") {
								echo "<a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a>";
							} else {
								echo "<a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a><br>";
								echo "<a href='#' ip='$ip' thisroom='$THISROOMID' class='roominfo-modal $THISROOMID'>";
								if($activeplayerid==0) {
									if($filetype=="unknown") {
										echo "<img src='../media/DefaultPlaying.png' height='35px' style='float:left;margin-top:-3px;'>";
										echo $nowplayingarray['title'];
									} elseif($filetype=="song") {
										echo "<img src='../media/DefaultMusic.png' height='35px' style='float:left;margin-top:-3px;'>";
										if(false !== stripos($thealbum, '$theyear')) { echo "$thealbum"; } else { echo "$thealbum ($theyear)"; }
										echo " - ".$nowplayingarray['title'];
									}
								} elseif($activeplayerid==1) {
									$filetype = $nowplayingarray['type'];
									if($filetype == "amovie") {
										echo "<img src='../media/DefaultMovies.png' height='35px' style='float:left;margin-top:-3px;'>";
										echo $nowplayingarray['title'];
										exit;
									} elseif($filetype == "atvshow") {
										echo "<img src='../media/DefaultTVShows.png' height='35px' style='float:left;margin-top:-3px;'>";
										echo $nowplayingarray['title'];
										exit;
									} elseif($filetype=="tvshow") {
										echo "<img src='../media/DefaultTVShows.png' height='35px' style='float:left;margin-top:-3px;'>";
										echo "$theshowtitle - $theshowseason$theshowepisode - $thetitle";										
									} elseif($filetype=="movie") {
										echo "<img src='../media/DefaultMovies.png' height='35px' style='float:left;margin-top:-3px;'>";
										echo $nowplayingarray['title'];
										if(false !== stripos($thetitle, '$theyear')) { } else { echo " ($theyear)"; }
									} else {
										echo "<img src='../media/DefaultPlaying.png' height='35px' style='float:left;margin-top:-3px;'>";
										echo $nowplayingarray['title'];
									}
								} elseif($activeplayerid==2) {
									echo "pics";
								}
								echo "</a>";
							}
						}
?>
