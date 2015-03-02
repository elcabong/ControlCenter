<?php
if(isset($_GET['ip'])) { $ip=$_GET['ip']; } if(isset($_GET['filetype'])) { $thisfiletype=$_GET['filetype']; } if(isset($_GET['activeplayer'])) { $activeplayerid=$_GET['activeplayer']; }
$showtime=0;
if(isset($_GET['showtime'])) { $showtime=$_GET['showtime']; }
		
require "class.php";
$KODI = new KODI();
$nowplayingtime = $KODI->GetPlayingTimeInfo("$ip","$activeplayerid");
$timenow = $nowplayingtime['timenow'];
$endtime = $nowplayingtime['endtime'];

if($showtime!=0) {
echo         "<img src='../media/time-now.png'/> <span>$timenow</span>";
echo "<Br><img class='end' src='../media/time-end.png'/> <span class='end'>$endtime</span>";
}
?>
