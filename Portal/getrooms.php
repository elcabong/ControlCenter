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
require_once './controls-include.php';	
			$i = 1;
			while($i<=$TOTALROOMS) {
				$ip;
				$ROOMXBMC = "ROOM$i"."XBMC";
				$ROOMXBMCM = $ROOMXBMC."M";
				$theperm = "USRPR$i";
				if(!empty(${$ROOMXBMC}) && ${$theperm} == "1"){
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
					if($xbmcmachine == 'alive') {
						$videotype='';
						$jsoncontents = "${$ROOMXBMC}/jsonrpc?request={%22jsonrpc%22:%20%222.0%22,%20%22method%22:%20%22Player.GetItem%22,%20%22params%22:%20{%20%22properties%22:%20[%22title%22,%22episode%22,%22showtitle%22,%22season%22,%22year%22],%20%22playerid%22:%201%20},%20%22id%22:%20%221%22}";
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_URL, "$jsoncontents");
						curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
						$output = curl_exec($ch);
						$jsonnowplaying = json_decode($output,true);
						if($jsonnowplaying['result']['item']['label']!='') {
							echo "<li class='nowplaying'><a href='#' ip='${$ROOMXBMC}' class='title nowplaying-modal'>";
							if($jsonnowplaying['result']['item']['type']!='') {
								if($jsonnowplaying['result']['item']['type']=='unknown') {
									echo ucfirst($jsonnowplaying['result']['item']['filetype']).": ";
								} else {
									echo ucfirst($jsonnowplaying['result']['item']['type']).": "; }
									$videotype=$jsonnowplaying['result']['item']['type'];
							}
							$thelabel = $jsonnowplaying['result']['item']['label'];
							$theshowtitle = $jsonnowplaying['result']['item']['showtitle'];
							$thetitle = $jsonnowplaying['result']['item']['title'];
							$theshowseason = $jsonnowplaying['result']['item']['season'];
							$theshowepisode = str_pad($jsonnowplaying['result']['item']['episode'], 2, '0', STR_PAD_LEFT);
							$theyear = $jsonnowplaying['result']['item']['year'];
							if($videotype=="movie") {
								echo $thetitle;
								if(false !== stripos($thetitle, '$theyear')) { } else { echo " ($theyear)"; }
							} else {
								echo "$theshowtitle - $theshowseason$theshowepisode - $thetitle";
							}
							echo "</a><a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a>"; 
						} else {
							echo "<li><a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a>"; 
						}
					} else {
						echo "<li><a href='#' class='pingicon' onclick=\"document.getElementById('loading').style.display='block';wakemachine('${$ROOMXBMCM}');\"><img src='../media/red.png' title='offline - click to try to wake machine' style='height:20px;'/></a>";}
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
	jQuery(function ($) {
		$('.nowplaying-modal').click(function (e) {
			var thisip = $(this).attr('ip');
			$('#nowplaying').load('nowplaying.php?ip='+thisip).modal({
					opacity: 25,
					overlayClose: true});
			return false;
		});
	});	
</script>