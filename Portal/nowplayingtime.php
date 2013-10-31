<? if($_GET['ip']) { $ip=$_GET['ip']; } 
		$jsonnowplayingtime = "$ip/jsonrpc?request={%22jsonrpc%22%3A%20%222.0%22%2C%20%22method%22%3A%20%22Player.GetProperties%22%2C%20%22params%22%3A%20%7B%20%22properties%22%3A%20%5B%22time%22%2C%22totaltime%22%2C%22position%22%2C%22speed%22%5D%2C%20%22playerid%22%3A%201%20%7D%2C%20%22id%22%3A%20%221%22}";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, "$jsonnowplayingtime");
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
		$output = curl_exec($ch);
		$jsonnowplayingtime = json_decode($output,true);
			if(!isset($jsonnowplayingtime['result'])) {
				echo "Playback Ended";
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
		echo "Current Time: $timenow";
		echo "&nbsp;&nbsp;||&nbsp;&nbsp;End Time: $endtime";
?>