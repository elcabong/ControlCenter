<?php
if(isset($_GET['ip'])) { $ip=$_GET['ip']; } if(isset($_GET['filetype'])) { $thisfiletype=$_GET['filetype']; } if(isset($_GET['activeplayer'])) { $activeplayerid=$_GET['activeplayer']; }
$showtime=0;
if(isset($_GET['showtime'])) { $showtime=$_GET['showtime']; }

	if(!isset($activeplayerid)) { exit; }
	if($activeplayerid == 0) {
		$therequest = urlencode("\"jsonrpc\": \"2.0\", \"method\": \"Player.GetProperties\", \"params\": { \"properties\": [\"time\",\"totaltime\",\"position\",\"speed\"], \"playerid\": 0 }, \"id\": \"1\"");
		//$jsonnowplayingtime = "$ip/jsonrpc?request={%22jsonrpc%22%3A%20%222.0%22%2C%20%22method%22%3A%20%22Player.GetProperties%22%2C%20%22params%22%3A%20%7B%20%22properties%22%3A%20%5B%22time%22%2C%22totaltime%22%2C%22position%22%2C%22speed%22%5D%2C%20%22playerid%22%3A%200%20%7D%2C%20%22id%22%3A%20%221%22}";
		$jsonnowplayingtime = "$ip/jsonrpc?request={".$therequest."}";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, "$jsonnowplayingtime");
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
		$output = curl_exec($ch);
		$jsonnowplayingtime = json_decode($output,true);
	} elseif($activeplayerid == 1) {
		//$jsonnowplayingtime = "$ip/jsonrpc?request={%22jsonrpc%22%3A%20%222.0%22%2C%20%22method%22%3A%20%22Player.GetProperties%22%2C%20%22params%22%3A%20%7B%20%22properties%22%3A%20%5B%22time%22%2C%22totaltime%22%2C%22position%22%2C%22speed%22%5D%2C%20%22playerid%22%3A%201%20%7D%2C%20%22id%22%3A%20%221%22}";
		$therequest = urlencode("\"jsonrpc\": \"2.0\", \"method\": \"Player.GetProperties\", \"params\": { \"properties\": [\"time\",\"totaltime\",\"position\",\"speed\"], \"playerid\": 1 }, \"id\": \"1\"");
		$jsonnowplayingtime = "$ip/jsonrpc?request={".$therequest."}";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, "$jsonnowplayingtime");
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
		$output = curl_exec($ch);
		$jsonnowplayingtime = json_decode($output,true);
	}
		if(!isset($jsonnowplayingtime['result'])) {
			if($showtime!=0) {
				echo "Playback Ended";
			}
			exit;
		}	
		$thecurtime = implode(', ', $jsonnowplayingtime['result']['time']);
		$thetotaltime = implode(', ', $jsonnowplayingtime['result']['totaltime']);
		$thecurtime = explode(',',$thecurtime);
		$thetotaltime = explode(',',$thetotaltime);

		$currenttimesec = ($thecurtime[0]*3600)+($thecurtime[2]*60)+$thecurtime[3];
		$thetotaltimesec = ($thetotaltime[0]*3600)+($thetotaltime[2]*60)+$thetotaltime[3];
		$timeleft = $thetotaltimesec - $currenttimesec;
		$endtime = date("h:i a", time() + $timeleft);
		$timenow = date("h:i a", time());
		$currenttimesec+=1.55;
		$playerpercentage = round($currenttimesec / $thetotaltimesec * 100,1, PHP_ROUND_HALF_UP);
		if($showtime!=0) {
		echo         "<img src='../media/time-now.png'/> <span>$timenow</span>";
		echo "<Br><img class='end' src='../media/time-end.png'/> <span class='end'>$endtime</span>";
		}
?>
