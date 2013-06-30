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
				//header("Location: login.php");
				echo "<script type='text/javascript'>logout();</script>";				
				exit; }	
			$i = 1;
			$theroom = $_SESSION['room'];
			$XBMC = "XBMC";
			while($i<=$TOTALROOMS) {
				$ROOMXT = "ROOM$i";
				$ROOMXBMC = $ROOMXT.$XBMC;
				$ROOMXBMCM = $ROOMXBMC."M";
				$theperm = "USRPR$i";
				if($i == $theroom) {$thisroom = 1;} else {$thisroom = 0;}
				if(!empty(${$ROOMXBMC}) && ($ADMINP == "1" or ${$theperm} == "1")){
					$xbmcmachine = pingAddress(${$ROOMXBMC});
					echo "<li>";
					if($xbmcmachine == 'alive') { echo "<a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a>"; } else { echo "<a href='#' class='pingicon' onclick=\"document.getElementById('loading').style.display='block';wakemachine('${$ROOMXBMCM}');\"><img src='../media/red.png' title='offline - click to try to wake machine' style='height:20px;'/></a>";}
					echo "</li>";
					}
			$i++; }
			?>
			<script>
			function wakemachine(mac) {
				$.ajax(
					{
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