<? if($_GET['ip']) { $ip=$_GET['ip']; } ?>
<!DOCTYPE html>
<html>
<head>
<title>NowPlaying</title>
<link type='text/css' href='../css/nowplaying.css?<? echo date ("m/d/Y-H.i.s", filemtime('../css/nowplaying.css'));?>' rel='stylesheet' media='screen' />
</head>
<body>
<div id='nowplayingcontainer'>
	<div id='logo'>
			<div id="timeUpdateField"></div>
		<h1>Now<span>Playing</span></h1>
			<?
			include "nowplayinginfo.php";
			if(!isset($jsonactiveplayer['result'])) {
				echo "There is nothing currently playing.";
				return;
			}
			?>
	</div>
	<div id='nowplayingcontent'>
			<div class='title'>
			<?
			if(!isset($activeplayerid)) { exit; }
			if($activeplayerid==0) {
				$jsonmusicinfo = "$ip/jsonrpc?request={%22jsonrpc%22%3A%20%222.0%22%2C%20%22method%22%3A%20%22AudioLibrary.GetSongDetails%22%2C%20%22params%22%3A%20%7B%20%22songid%22%3A%20$thesongid%2C%20%22properties%22%3A%20%5B%20%22fanart%22%2C%20%22genre%22%2C%20%22title%22%2C%20%22year%22%2C%20%22rating%22%2C%20%22thumbnail%22%5D%20%7D%2C%20%22id%22%3A%201}";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_URL, "$jsonmusicinfo");
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
				$output = curl_exec($ch);
				$jsonmusicinfo = json_decode($output,true);
				//print_r ($jsonmusicinfo);
				$thegenre = implode(', ', $jsonmusicinfo['result']['songdetails']['genre']);
				
				if(false !== stripos($thealbum, '$theyear')) { echo "$thealbum"; } else { echo "$thealbum ($theyear)"; }
				echo "<br>Track: $thetitle";
				//echo "<br>Runtime: ".round($jsonnowplaying['result']['item']['runtime']/60)." minutes";
				echo "<br>Genre: ".$thegenre;
				echo "<br>Year: ".$theyear;
				//echo "<br>User Rating: ".round($jsonnowplaying['result']['item']['rating'],2)."/10";

					
			} elseif($activeplayerid==1) {
				$jsoncontents = "$ip/jsonrpc?request={%22jsonrpc%22%3A%20%222.0%22%2C%20%22method%22%3A%20%22Player.GetItem%22%2C%20%22params%22%3A%20%7B%20%22properties%22%3A%20%5B%22director%22%2C%22writer%22%2C%22tagline%22%2C%22episode%22%2C%22title%22%2C%22showtitle%22%2C%22season%22%2C%22genre%22%2C%22year%22%2C%22rating%22%2C%22runtime%22%2C%22firstaired%22%2C%22plot%22%2C%22fanart%22%2C%22thumbnail%22%2C%22tvshowid%22%5D%2C%20%22playerid%22%3A%201%20%7D%2C%20%22id%22%3A%20%221%22}";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_URL, "$jsoncontents");
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
				$output = curl_exec($ch);
				$jsonnowplaying = json_decode($output,true);
				if(isset($jsonnowplaying['result']['item']['tvshowid']) && $jsonnowplaying['result']['item']['tvshowid']!='') {
					$theshowid = $jsonnowplaying['result']['item']['tvshowid'];
				}	
				if($jsonnowplaying['result']['item']['label']!='') {
					if($jsonnowplaying['result']['item']['type']!='') {
						if($jsonnowplaying['result']['item']['type']=='unknown') {
						//	echo ucfirst($jsonnowplaying['result']['item']['filetype']).": ";
						} else {
						//	echo ucfirst($jsonnowplaying['result']['item']['type']).": "; 
							$filetype = $jsonnowplaying['result']['item']['type'];
						}
					}
					if($filetype == "episode"){
						$jsontvshowinfo = "$ip/jsonrpc?request={%22jsonrpc%22%3A%20%222.0%22%2C%20%22method%22%3A%20%22VideoLibrary.GetTVShowDetails%22%2C%20%22params%22%3A%20%7B%20%22tvshowid%22%3A%20$theshowid%2C%20%22properties%22%3A%20%5B%20%22art%22%2C%20%22votes%22%2C%20%22premiered%22%2C%20%22cast%22%2C%20%22genre%22%2C%20%22plot%22%2C%20%22title%22%2C%20%22originaltitle%22%2C%20%22year%22%2C%20%22rating%22%2C%20%22thumbnail%22%2C%20%22playcount%22%2C%20%22file%22%2C%20%22fanart%22%2C%20%22episode%22%5D%20%7D%2C%20%22id%22%3A%201}";
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_URL, "$jsontvshowinfo");
						curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
						$output = curl_exec($ch);
						$jsontvshowinfo = json_decode($output,true);
						$thegenre = implode(', ', $jsontvshowinfo['result']['tvshowdetails']['genre']);
						$theyear = $jsontvshowinfo['result']['tvshowdetails']['year'];
						echo "<img src='$ip/image/".urlencode($jsontvshowinfo['result']['tvshowdetails']['thumbnail'])."'/>";
					} else {
						$thegenre = implode(', ', $jsonnowplaying['result']['item']['genre']);
						$theyear = $jsonnowplaying['result']['item']['year'];
						echo "<img src='$ip/image/".urlencode($jsonnowplaying['result']['item']['thumbnail'])."'/>";
					}
					$thedirector = implode(', ', $jsonnowplaying['result']['item']['director']);
					$thewriter = implode(', ', $jsonnowplaying['result']['item']['writer']);
					$thelabel = $jsonnowplaying['result']['item']['label'];
					$theshowtitle = $jsonnowplaying['result']['item']['showtitle'];
					$thetitle = $jsonnowplaying['result']['item']['title'];
					$theshowseason = $jsonnowplaying['result']['item']['season'];
					$theshowepisode = str_pad($jsonnowplaying['result']['item']['episode'], 2, '0', STR_PAD_LEFT);
					if($filetype=="movie") {
						echo "Movie: ".$thetitle;
					} else {
						echo "Series: $theshowtitle <br>Episode: $theshowseason$theshowepisode - $thetitle";
					}
					echo "<br>Runtime: ".round($jsonnowplaying['result']['item']['runtime']/60)." minutes";
					if($filetype == "episode"){
						echo "<br>First Aired: ".$jsonnowplaying['result']['item']['firstaired'];
					}else{
						echo "<br>Year: ".$theyear;
						echo "<br>Tagline: ".$jsonnowplaying['result']['item']['tagline'];
					}
					echo "<br>User Rating: ".round($jsonnowplaying['result']['item']['rating'],2)."/10";
					echo "<br>Genre: ".$thegenre;
					echo "<br>Director: ".$thedirector;
					echo "<br>Author: ".$thewriter;
				}
			} elseif($activeplayerid==2) {
				echo "pics";
			}
			?>		
		</div>
		<div id='nowplaying-info'>
			<?
			if($activeplayerid==0) {
				//echo "music info";
					?> <br><br><br><div id="images"> <?
					echo "<img src='$ip/image/".urlencode($jsonmusicinfo['result']['songdetails']['thumbnail'])."'/>";
					
			} elseif($activeplayerid==1) {	
				echo "Plot:<br>".$jsonnowplaying['result']['item']['plot'];

				if($filetype == "movie"){
					?> <br><br><br><div id="images"> <?
					echo "<img src='$ip/image/".urlencode($jsonnowplaying['result']['item']['fanart'])."'/>";
				} elseif($filetype == "episode"){
					?> <br><br><br><div id="images"> <?
					echo "<img src='$ip/image/".urlencode($jsontvshowinfo['result']['tvshowdetails']['fanart'])."'/>";
				}
			} elseif($activeplayerid==2) {
				echo "pics";
			}				
			?> </div>
		</div>
	</div>
	<div id='nowplayingfooter'>
	</div>
<script>
	timer();
	function timer()
	{
	  if(!document.contains(timeUpdateField))
	  {
		 clearInterval(nowplayingtimer);
		 return;
	  }
	  $("#timeUpdateField").load("nowplayingtime.php?ip=<?echo $ip;?>&filetype=<?echo $filetype;?>&activeplayer=<?echo $activeplayerid;?>");
	}
	clearInterval(nowplayingtimer);
	var nowplayingtimer=setInterval(timer, 3000);
</script>	
</div>
</body>
</html>