<?
			$found = false;
			$path = 'Portal';
			while(!$found){	
				if(file_exists($path)){ 
					$found = true;
							$thepath = $path;
				}
				else{ $path= '../'.$path; }
			}
			require "$thepath/config.php";
			if ($authsecured && (!isset($_SESSION["$authusername"]) || !$_SESSION["$authusername"] || $_SESSION["$authusername"] != $authusername )) {
				header("Location: login.php");
				exit; }
			$i = 1;
			while($i<=$TOTALROOMS) {
				ip;
				$ROOMXBMC = "ROOM$i"."XBMC";
				$ROOMXBMCM = $ROOMXBMC."M";
				$theperm = "USRPR$i";
				if(!empty(${$ROOMXBMC}) && ($ADMINP == "1" or ${$theperm} == "1")){
					$ip = ${$ROOMXBMC};
				   $disallowed = array('http://', 'https://');
				   foreach($disallowed as $d) {
					    if(strpos($ip, $d) === 0) {
						   $ip = strtok(str_replace($d, '', $ip),':');
					    }
				    }
					if (strncasecmp(PHP_OS, 'WIN', 3) == 0) {
						$pingresult = exec("ping -n 1 -w 1 $ip", $output, $status);
						// echo 'This is a server using Windows!';
					} else {
						$pingresult = exec("/bin/ping -c1 -w1 $ip", $outcome, $status);
						// echo 'This is a server not using Windows!';
					}
					if ($status == "0") {
						$status = "alive";
					} else { 
						$status = "dead"; 
					}
					$xbmcmachine = $status;					
					echo "<li>";
					if($xbmcmachine == 'alive') { echo "<a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a>"; } else { echo "<a href='#' class='pingicon' onclick=\"document.getElementById('loading').style.display='block';wakemachine('${$ROOMXBMCM}');\"><img src='../media/red.png' title='offline - click to try to wake machine' style='height:20px;'/></a>";}
					echo "</li>";
				}
			$i++; }
?>
<script>
	function wakemachine(mac) {
		$.ajax({
			   type: "POST",
			   url: "wol-check.php?m="+mac+"",
			   data: 0, // data to send to above script page if any
			   cache: false,
			   success: function(response)
			{
				// need to retry ping until successful or hit a set limit, then display none
				setTimeout(func, 35000);
				function func() {
					document.getElementById('loading').style.display='none';	
				}
		   }
		});				
	}
</script>