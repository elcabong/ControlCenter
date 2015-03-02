<?php
class KODI {

	function Ping($ip) {
		$pingurl = "$ip/jsonrpc?request={%22jsonrpc%22%3A%20%222.0%22%2C%20%22method%22%3A%20%22JSONRPC.Ping%22%2C%22id%22%3A%201}";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, "$pingurl");
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
		$output = curl_exec($ch);
		if($output === FALSE) {
			return "dead";
		} else {
			return "alive";
		}
	}
	
	function GetActivePlayer($ip) {
		$filetype='';
		// get active player
		$therequest = urlencode("\"jsonrpc\": \"2.0\", \"method\": \"Player.GetActivePlayers\", \"id\": \"1\"");
		$getactiveplayer = "$ip/jsonrpc?request={".$therequest."}";	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, "$getactiveplayer");
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
		$output = curl_exec($ch);
		$jsonactiveplayer = json_decode($output,true);
		if(empty($jsonactiveplayer['result'])) {
			//echo "There is nothing currently playing.";
			return "none";
		} else {
			$activeplayerid = '-1';
			if(isset($jsonactiveplayer['result'][0]['playerid'])) {
				$activeplayerid = $jsonactiveplayer['result'][0]['playerid'];
			}
			return $activeplayerid;
		}	
	}
	
	function GetPlayingItemInfo($ip,$activeplayerid) {
		$nowplayingarray = Array();
		if($activeplayerid=="0") {
			$filetype='';
			$therequest = urlencode("\"jsonrpc\": \"2.0\", \"method\": \"Player.GetItem\", \"params\": { \"properties\": [\"album\",\"artist\",\"director\",\"writer\",\"tagline\",\"episode\",\"file\",\"title\",\"showtitle\",\"season\",\"genre\",\"year\",\"rating\",\"runtime\",\"firstaired\",\"plot\",\"fanart\",\"thumbnail\",\"tvshowid\"], \"playerid\": 0 }, \"id\": \"1\"");
			//$jsoncontents = "$ip/jsonrpc?request={%22jsonrpc%22:%20%222.0%22,%20%22method%22:%20%22Player.GetItem%22,%20%22params%22:%20{%20%22properties%22:%20[%22album%22,%22title%22,%22year%22],%20%22playerid%22:%200%20},%20%22id%22:%20%221%22}";
			$jsoncontents = "$ip/jsonrpc?request={".$therequest."}";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, "$jsoncontents");
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
			$output = curl_exec($ch);
			$jsonnowplaying = json_decode($output,true);
			if(isset($jsonnowplaying) && $jsonnowplaying['result']['item']['label']!='') {
				$filetype=$jsonnowplaying['result']['item']['type'];
				$theartist = $jsonnowplaying['result']['item']['artist'];
				$thealbum = $jsonnowplaying['result']['item']['album'];
				$thelabel = $jsonnowplaying['result']['item']['label'];
				$thetitle = $jsonnowplaying['result']['item']['title'];
				$theyear = $jsonnowplaying['result']['item']['year'];
				$thesongid = $jsonnowplaying['result']['item']['id'];
			}

			return $jsonnowplaying;
		} elseif($activeplayerid=="1") {
			$filetype='unknown';
			$therequest = urlencode("\"jsonrpc\": \"2.0\", \"method\": \"Player.GetItem\", \"params\": { \"properties\": [\"director\",\"writer\",\"tagline\",\"episode\",\"file\",\"title\",\"showtitle\",\"season\",\"genre\",\"year\",\"rating\",\"runtime\",\"firstaired\",\"plot\",\"fanart\",\"thumbnail\",\"tvshowid\"], \"playerid\": 1 }, \"id\": \"1\"");
			$jsoncontents = "$ip/jsonrpc?request={".$therequest."}";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, "$jsoncontents");
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
			$output = curl_exec($ch);
			$jsonnowplaying = json_decode($output,true);
			foreach($jsonnowplaying['result']['item'] as $item=>$value) {
				if($value == "" || $value == "0") { continue; }
				
				//  special cases
				if($item == "fanart" || $item == "thumbnail") {
					if($value == '') { continue; }
					$nowplayingarray[$item] = "<img src='$ip/image/".urlencode($value)."'/>";;
				} elseif(is_array($value)) {
					if(empty($value)) { continue; }
					$nowplayingarray[$item] = implode(",",$value);

				} else {  // normal cases, everything else
					$nowplayingarray[$item] = $value;
				}
			}
	
			$tvshowneedles = array('1x','2x','3x','4x','5x','6x','7x','8x','9x','0x','s0','S0','00E','00e','e0','E0','e1','E1');
			$movieneedles = array('(19','(20','[19','[20');				
				
				if($jsonnowplaying['result']['item']['type'] == "unknown") {
					$ext = pathinfo($jsonnowplaying['result']['item']['file'], PATHINFO_EXTENSION);
					$file = basename($jsonnowplaying['result']['item']['file'], ".".$ext);
					$needles = $tvshowneedles;
					foreach($needles as $needle) {
						if (strpos($file,$needle) !== false) {
							$nowplayingarray['type'] = "atvshow";
							$nowplayingarray['title'] = "$file";							
							break;
						}
					}
				}
				if($jsonnowplaying['result']['item']['type'] == "unknown") {
					$ext = pathinfo($jsonnowplaying['result']['item']['file'], PATHINFO_EXTENSION);
					$file = basename($jsonnowplaying['result']['item']['file'], ".".$ext);
					$needles = $movieneedles;
					foreach($needles as $needle) {
						if (strpos($file,$needle) !== false) {
							$nowplayingarray['type'] = "amovie";
							$nowplayingarray['title'] = "$file";
							break;
						}
					}
				}
				
				
					if($jsonnowplaying['result']['item']['type'] == "channel") {
						$nowplayingarray['type'] = "channel";
						$nowplayingarray['channel'] = $jsonnowplaying['result']['item']['label'];
						$nowplayingarray['runtime'] = $nowplayingarray['runtime'] . " minutes";
					} else {
						if(isset($nowplayingarray['runtime']) && $nowplayingarray['runtime'] != '') {
							$nowplayingarray['runtime'] = round($nowplayingarray['runtime']/60) . " minutes";
						}
					}	
			

			return $nowplayingarray;		

		} elseif($activeplayerid=="2") {
			echo "pics";
			//return $jsonnowplaying;
		}
	}


	
	function GetPlaylistInfo($ip,$activeplayerid) {
		if($activeplayerid==0) {
			$jsoncontents = "$ip/jsonrpc?request={%22jsonrpc%22:%20%222.0%22,%20%22method%22:%20%22Playlist.GetItems%22,%20%22params%22:%20{%20%22playlistid%22:%200%20},%20%22id%22:%20%221%22}";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, "$jsoncontents");
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
			$output = curl_exec($ch);
			$jsonplaylist = json_decode($output,true);								
		} elseif($activeplayerid==1) {
			$jsoncontents = "$ip/jsonrpc?request={%22jsonrpc%22:%20%222.0%22,%20%22method%22:%20%22Playlist.GetItems%22,%20%22params%22:%20{%20%22playlistid%22:%201%20},%20%22id%22:%20%221%22}";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, "$jsoncontents");
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
			$output = curl_exec($ch);
			$jsonplaylist = json_decode($output,true);
			//print_r($jsonplaylist);
			//$test = in_array("$thelabel",$jsonplaylist);
		/*	echo $thelabel;
			if(in_array_like("$thelabel",$jsonplaylist)){
			echo "in the array";
			} else { echo "not in array"; }*/

		} elseif($activeplayerid==2) {
			echo "pics";
			//return $jsonnowplaying;
		}
	}
	
	
	
	
	
	
	function GetPlayingTimeInfo($ip,$activeplayerid) {

		$therequest = urlencode("\"jsonrpc\": \"2.0\", \"method\": \"Player.GetProperties\", \"params\": { \"properties\": [\"time\",\"totaltime\",\"position\",\"speed\"], \"playerid\": $activeplayerid }, \"id\": \"1\"");
		$jsonnowplayingtime = "$ip/jsonrpc?request={".$therequest."}";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, "$jsonnowplayingtime");
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
		$output = curl_exec($ch);
		$jsonnowplayingtime = json_decode($output,true);
		
		if(!isset($jsonnowplayingtime['result'])) {
			if($showtime!=0) {
				return "Playback Ended";
			}
		}
		$thecurtime = implode(', ', $jsonnowplayingtime['result']['time']);
		$thetotaltime = implode(', ', $jsonnowplayingtime['result']['totaltime']);
		$thecurtime = explode(',',$thecurtime);
		$thetotaltime = explode(',',$thetotaltime);
		
		$PlayingTime = Array();

		$PlayingTime['currenttimesec'] = ($thecurtime[0]*3600)+($thecurtime[2]*60)+$thecurtime[3];
		$PlayingTime['thetotaltimesec'] = ($thetotaltime[0]*3600)+($thetotaltime[2]*60)+$thetotaltime[3];
		$PlayingTime['timeleft'] = $PlayingTime['thetotaltimesec'] - $PlayingTime['currenttimesec'];
		$PlayingTime['endtime'] = date("h:i a", time() + $PlayingTime['timeleft']);
		$PlayingTime['timenow'] = date("h:i a", time());
		$PlayingTime['currenttimesec'] +=1.55;
		$PlayingTime['playerpercentage'] = round($PlayingTime['currenttimesec'] / $PlayingTime['thetotaltimesec'] * 100,1, PHP_ROUND_HALF_UP);
		
		return $PlayingTime;
	}
}








?>