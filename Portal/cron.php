<?php
$time = time();
$configdb = new PDO('sqlite:../sessions/config.db');

try {
	$sql = "SELECT dbversion FROM controlcenter WHERE CCid = 2 LIMIT 1";
	foreach ($configdb->query($sql) as $lastcrontime) {
		$lastcron = $lastcrontime['dbversion'];
	}
	//echo $lastcron;
} catch(PDOException $e)
	{
	$log->LogError("$e->getMessage()");
	//echo $e->getMessage();
	}
	//echo "<br>".$time;

require_once "KLogger.php";
$date = date('Y-m-d');
// klogger options: DEBUG, INFO, WARN, ERROR, FATAL, OFF
$log = new KLogger ( "../logs/log-$date.txt" , KLogger::INFO );	
$USERIP = $_SERVER['REMOTE_ADDR'];
if($lastcron < ($time - 45)) {
echo "takeover";
$log->LogInfo("Cron taken over by $USERIP");
} else if(($lastcron + 5) > $time) {
	echo "release";
	$log->LogInfo("Cron released by $USERIP");
	return;
	exit;
}

try {
	$sql3 = "SELECT * FROM rooms_addons";
	foreach ($configdb->query($sql3) as $addonSettings)
		{
			$THISROOMID = $addonSettings['roomid'];
			$rooms_addonsid = $addonSettings['rooms_addonsid'];
			$statusorig = $addonSettings['device_alive'];
			if($addonSettings['ip'] != '') {
				$disallowed = array('http://', 'https://');
				foreach($disallowed as $d) {
					if(strpos($addonSettings['ip'], $d) === 0) {
					   $thisip = strtok(str_replace($d, '', $addonSettings['ip']),':');
					}
				}
				if(strpos($thisip, "/") != false) {
					$thisip = substr($thisip, 0, strpos($thisip, "/"));
				}
				if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
					$pingresult = exec("ping -n 1 -w 1 $thisip", $output, $status);
					// echo 'This is a server using Windows!';
				} else {
					$pingresult = exec("/bin/ping -c1 -w1 $thisip", $outcome, $status);
					// echo 'This is a server not using Windows!';
				}
				if ($status == "0") {
					if($statusorig==0) {
						$execquery = $configdb->exec("UPDATE rooms_addons SET device_alive = 1 WHERE rooms_addonsid = '$rooms_addonsid';");
					}
					//$status = "alive";
				} else {
					if($statusorig==1) {
						$execquery = $configdb->exec("UPDATE rooms_addons SET device_alive = 0 WHERE rooms_addonsid = '$rooms_addonsid';");
					}
					//$status = "dead";
				}
			}
		}
} catch(PDOException $e)
	{
	$log->LogError("$e->getMessage()");
	//echo $e->getMessage();
	}
$execquery = $configdb->exec("INSERT OR REPLACE INTO controlcenter (CCid, dbversion) VALUES (2,'$time')");
?>