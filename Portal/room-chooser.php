<?php
			$found = false;
			$path = 'Portal';
			while(!$found){	
				if(file_exists($path)){ 
					$found = true;
							$thepath = $path;
				}
				else{ $path= '../'.$path; }
			}
			require_once "$thepath/config.php";
if ($authsecured && (!isset($_SESSION["$authusername"]) || !$_SESSION["$authusername"] || $_SESSION["$authusername"] != $authusername )) {
    header("Location: login.php");
    exit; }

if(!$_SESSION['room'] || !empty($_GET['newroom'])) {
$roomnum = (!empty($_GET['newroom']))?$_GET['newroom']:$HOMEROOMU; 
$_SESSION['room'] = $roomnum; } else {
$roomnum = $_SESSION['room']; }

		$ROOMNUM = "ROOM$roomnum"."N";
		echo "<a href='#' class='title'>${$ROOMNUM}</a>";
		echo "<ul>";
			$i = 1;
			while($i<=$TOTALROOMS) {
				$ROOMXBMC = "ROOM$i"."XBMC";
				$ROOMNUM = "ROOM$i"."N";
				$theperm = "USRPR$i";
				$theroom = $_SESSION['room'];
				if($i == $theroom) {$thisroom = 1;} else {$thisroom = 0;}
				if(!empty(${$ROOMXBMC}) && ($ADMINP == "1" or ${$theperm} == "1")){
					$xbmcmachine = pingAddress(${$ROOMXBMC});
					echo "<li>";
					if($thisroom) {
					echo "<a class='selected' href='#' onclick=\"changeroom('$i');\">${$ROOMNUM}</a></li>"; 				
					} else {
					echo "<a href='#' onclick=\"changeroom('$i');\">${$ROOMNUM}</a></li>"; 
					}
				}
			$i++; }	
		echo "</ul>";

$ROOMXBMC = "ROOM$roomnum"."XBMC";
$xbmcip = ${$ROOMXBMC};
?>
<script type="text/javascript">
		var iframe2 = document.getElementById('XBMC 1');
		if(iframe2.src != '<? echo $xbmcip; ?>') {
			iframe2.setAttribute('src','<? echo $xbmcip; ?>');
			iframe2.setAttribute('data-src','<? echo $xbmcip; ?>');
			iframe2.src = iframe2.src; }

		var iframe3 = document.getElementById('XBMCawxi 1');
		if(iframe3.hasAttribute('src')) {
			iframe3.setAttribute('src','');
			iframe3.src = iframe3.src; }
			iframe3.setAttribute('data-src','<? echo $xbmcip; ?>/addons/webinterface.awxi/');			

		setTimeout(func, 5000);
		function func() {
			document.getElementById('loading').style.display='none';	
		}
</script>