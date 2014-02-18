<? if(isset($_GET['to'])) { $to=$_GET['to']; } if(isset($_GET['from'])) { $from=$_GET['from']; } if(isset($_GET['sendtype'])) { $sendtype=$_GET['sendtype']; } if(isset($_GET['addon'])) { $addonid=$_GET['addon']; }
$ip = $from;

$found2 = false;
$path2 = "addons";
while(!$found2){
	if(file_exists($path2)){ 
		$found2 = true;
		$addonsloc = $path2;
	}
	else{ $path2= '../'.$path2; }
}

set_include_path(dirname(__FILE__).DIRECTORY_SEPARATOR);

//require "config.php";
require "nowplayinginfo.php";

	$filepath = urlencode($filepath);
	$thisactiveplayer = $activeplayerid;
	
	require "nowplayingtime.php";	
	$jsoncontents = '';	

	if($activeplayerid==0) {
	
	} elseif($activeplayerid==1) {
		//if($thelabel !in playlist array || !isset(playlist array)) {
			$jsoncontents = "$to/jsonrpc?request=%7B%22jsonrpc%22:%222.0%22,%22id%22:%221%22,%22method%22:%22Player.Open%22,%22params%22:%7B%22item%22:%7B%22file%22:%22$filepath%22%7D%7D%7D";
			if($sendtype=="clone") {
				$jsoncontents .= "====$to/jsonrpc?request=%7B%22jsonrpc%22:%222.0%22,%22id%22:1,%22method%22:%22Player.Seek%22,%22params%22:%7B%22playerid%22:1,%22value%22:$playerpercentage%7D%7D";
			} elseif($sendtype=="send") {
				$jsoncontents .= "====$to/jsonrpc?request=%7B%22jsonrpc%22:%222.0%22,%22id%22:1,%22method%22:%22Player.Seek%22,%22params%22:%7B%22playerid%22:1,%22value%22:$playerpercentage%7D%7D";
				$jsoncontents .= "====$from/jsonrpc?request=%7B%22jsonrpc%22:%222.0%22,%22id%22:1,%22method%22:%22Player.Stop%22,%22params%22:%7B%22playerid%22:1%7D%7D";
			}
		//} elseif(currentlyplayingtitle != playlist[0]title) {
		//		
		
		//} else {
		
		
		//}
	} elseif($activeplayerid==2) {
	
	}
	$thejsoncontents = explode("====", $jsoncontents);
	foreach($thejsoncontents as $jsoncontents) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, "$jsoncontents");
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
		$output = curl_exec($ch);
		$jsonoutput = json_decode($output,true);
		//print_r($jsonoutput);
	}
?>