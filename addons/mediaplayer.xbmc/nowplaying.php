<?php if($_GET['ip']) { $ip=$_GET['ip']; } // if($_GET['addon']) { $addonid=$_GET['addon']; } 
$nowplayingarray = array();

			require "nowplayinginfo.php";
			if(!isset($jsonactiveplayer['result'])) {
				echo "There is nothing currently playing.";
				return;
			}

			if(!isset($activeplayerid)) { exit; }
			if($activeplayerid==0) {
				$jsoncontents = "$ip/jsonrpc?request={%22jsonrpc%22%3A%20%222.0%22%2C%20%22method%22%3A%20%22Player.GetItem%22%2C%20%22params%22%3A%20%7B%20%22properties%22%3A%20%5B%22director%22%2C%22writer%22%2C%22tagline%22%2C%22episode%22%2C%22title%22%2C%22showtitle%22%2C%22season%22%2C%22genre%22%2C%22year%22%2C%22rating%22%2C%22runtime%22%2C%22firstaired%22%2C%22plot%22%2C%22fanart%22%2C%22thumbnail%22%2C%22tvshowid%22%5D%2C%20%22playerid%22%3A%200%20%7D%2C%20%22id%22%3A%20%221%22}";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_URL, "$jsoncontents");
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
				$output = curl_exec($ch);
				$jsonnowplaying = json_decode($output,true);
				$jsonmusicinfo = "$ip/jsonrpc?request={%22jsonrpc%22%3A%20%222.0%22%2C%20%22method%22%3A%20%22AudioLibrary.GetSongDetails%22%2C%20%22params%22%3A%20%7B%20%22songid%22%3A%20$thesongid%2C%20%22properties%22%3A%20%5B%20%22fanart%22%2C%20%22genre%22%2C%20%22title%22%2C%20%22year%22%2C%20%22rating%22%2C%20%22thumbnail%22%5D%20%7D%2C%20%22id%22%3A%201}";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_URL, "$jsonmusicinfo");
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
				$output = curl_exec($ch);
				$jsonmusicinfo = json_decode($output,true);
				//print_r ($jsonmusicinfo);
				$thegenre = implode(', ', $jsonmusicinfo['result']['songdetails']['genre']);
				
				if(false !== stripos($thealbum, '$theyear')) { $nowplayingarray['Album'] =  "$thealbum"; } else { $nowplayingarray['Album'] =  "$thealbum ($theyear)"; }
				
				$nowplayingarray['Track'] = $thetitle; 
				$nowplayingarray['Genre'] = $thegenre; 
				$nowplayingarray['Year'] = $theyear;
				$thumbnail = "<img src='$ip/image/".urlencode($jsonnowplaying['result']['item']['thumbnail'])."'/>";
				
				// maybe try to put playlist in the fanart area
				$fanart =  "<img src='$ip/image/".urlencode($jsonnowplaying['result']['item']['thumbnail'])."'/>";				

					
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
						$nowplayingarray['Series'] = $jsonnowplaying['result']['item']['showtitle'];
						$nowplayingarray['Episode'] = $jsonnowplaying['result']['item']['season'] . str_pad($jsonnowplaying['result']['item']['episode'], 2, '0', STR_PAD_LEFT) . " - " .$jsonnowplaying['result']['item']['title'];
						$nowplayingarray['Genre'] = implode(', ', $jsontvshowinfo['result']['tvshowdetails']['genre']);
						$nowplayingarray['Year'] = $jsontvshowinfo['result']['tvshowdetails']['year'];
						$nowplayingarray['First Aired'] = $jsonnowplaying['result']['item']['firstaired'];
						$thumbnail = "<img src='$ip/image/".urlencode($jsontvshowinfo['result']['tvshowdetails']['thumbnail'])."'/>";
						$fanart = "<img src='$ip/image/".urlencode($jsontvshowinfo['result']['tvshowdetails']['fanart'])."'/>";
					} else {
						$nowplayingarray['Movie'] = $jsonnowplaying['result']['item']['title'];
						$nowplayingarray['Genre'] = implode(', ', $jsonnowplaying['result']['item']['genre']);
						$nowplayingarray['Year'] = $jsonnowplaying['result']['item']['year'];
						$nowplayingarray['Tagline'] = $jsonnowplaying['result']['item']['tagline'];
						$thumbnail = "<img src='$ip/image/".urlencode($jsonnowplaying['result']['item']['thumbnail'])."'/>";
						$fanart =  "<img src='$ip/image/".urlencode($jsonnowplaying['result']['item']['fanart'])."'/>";
					}
					$nowplayingarray['Runtime'] = round($jsonnowplaying['result']['item']['runtime']/60)." minutes";
					$nowplayingarray['User Rating'] = round($jsonnowplaying['result']['item']['rating'],2)."/10";
					
					$nowplayingarray['Director'] = implode(', ', $jsonnowplaying['result']['item']['director']);
					$nowplayingarray['Writer'] = implode(', ', $jsonnowplaying['result']['item']['writer']);
					$thelabel = $jsonnowplaying['result']['item']['label'];
					$plot = $jsonnowplaying['result']['item']['plot'];
				}
			} elseif($activeplayerid==2) {
				echo "pics";
			}
			?>
