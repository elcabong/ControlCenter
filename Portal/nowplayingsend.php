<? if(isset($_GET['to'])) { $to=$_GET['to']; } if(isset($_GET['from'])) { $from=$_GET['from']; }
$ip = $from;

include "nowplayinginfo.php";

	$filepath = urlencode($filepath);
$thisactiveplayer = $activeplayerid;
	
 include "nowplayingtime.php";	
	
	if($activeplayerid==0) {
	
	} elseif($activeplayerid==1) {
			$jsoncontents = "$to/jsonrpc?request=%7B%22jsonrpc%22:%222.0%22,%22id%22:%221%22,%22method%22:%22Player.Open%22,%22params%22:%7B%22item%22:%7B%22file%22:%22$filepath%22%7D%7D%7D";
			$jsoncontents2 = "$to/jsonrpc?request=%7B%22jsonrpc%22:%222.0%22,%22id%22:1,%22method%22:%22Player.Seek%22,%22params%22:%7B%22playerid%22:1,%22value%22:$playerpercentage%7D%7D";
			echo $jsoncontents2;
	} elseif($activeplayerid==2) {
	
	}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, "$jsoncontents");
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
		$output = curl_exec($ch);
		$jsonoutput = json_decode($output,true);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, "$jsoncontents2");
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
		$output = curl_exec($ch);
		$jsonoutput = json_decode($output,true);
		
		print_r($jsonoutput);
		
?>