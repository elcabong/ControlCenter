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

/*echo $_COOKIE["currentRoom$usernumber"];
echo 1111;
echo $_COOKIE['currentRoom1'];*/
	/*
if(isset($_COOKIE['currentRoom'])) {
$roomnum = $_COOKIE['currentRoom'];
$_SESSION['room'] = $roomnum; } else {
if(!$_SESSION['room'] || !empty($_GET['newroom'])) {
$roomnum = (!empty($_GET['newroom']))?$_GET['newroom']:$HOMEROOMU; 
$_SESSION['room'] = $roomnum; } else {
$roomnum = $_SESSION['room']; } }*/

if(isset($_COOKIE["currentRoom$usernumber"])) {
$roomnum = $_COOKIE["currentRoom$usernumber"];
$theperm = "USRPR$roomnum";
if(${$theperm} == "1") {
$_SESSION['room'] = $roomnum; } }

if(!$_SESSION['room']) {
$roomnum = $HOMEROOMU; 
$_SESSION['room'] = $roomnum; } else {
$roomnum = $_SESSION['room']; } 




		$ROOMNUM = "ROOM$roomnum"."N";
		echo "<a href='#' class='title'>${$ROOMNUM}</a>";
		echo "<ul>";
			$thisroom = 0;
			$i = 1;
			while($i<=$TOTALROOMS) {
				$ROOMXBMC = "ROOM$i"."XBMC";
				$ROOMNUM = "ROOM$i"."N";
				$theperm = "USRPR$i";
				if(!empty(${$ROOMXBMC}) && ($ADMINP == "1" or ${$theperm} == "1")){
					$xbmcmachine = pingAddress(${$ROOMXBMC});
					echo "<li>";
					if($i == $_SESSION['room']) {
					echo "<a class='selected' href='#' onclick=\"changeroom('$i','$usernumber');\">${$ROOMNUM}</a></li>"; 				
					$thisroom = 1;
					} else {
					echo "<a href='#' onclick=\"changeroom('$i','$usernumber');\">${$ROOMNUM}</a></li>"; 
					}
				}
			$i++; }
		echo "</ul>";

$ROOMXBMC = "ROOM$roomnum"."XBMC";
$xbmcip = ${$ROOMXBMC};
?>
<script type="text/javascript">
		setTimeout(func, 5000);
		function func() {
			document.getElementById('loading').style.display='none';	
		}
		
		var iframe2 = document.getElementById('XBMC 1');
		if(iframe2.src != '<? echo $xbmcip; ?>') {
			iframe2.setAttribute('src','<? echo $xbmcip; ?>');
			iframe2.setAttribute('data-src','<? echo $xbmcip; ?>');
			iframe2.src = iframe2.src; }

		var iframe3 = document.getElementById('XBMCawxi 1');
		iframe3.setAttribute('data-src','<? echo $xbmcip; ?>/addons/webinterface.awxi/');
		if(iframe3.hasAttribute('src')) {
			iframe3.setAttribute('src','<? echo $xbmcip; ?>/addons/webinterface.awxi/');
			iframe3.src = iframe3.data-src; }
		
</script>