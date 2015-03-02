<?php
if($_GET['ip']) {
	$ip=$_GET['ip'];
}
if($_GET['thisroom']) {
	$thisroom=$_GET['thisroom'];
}
require('startsession.php');
require_once("$INCLUDES/includes/config.php");
require_once "$INCLUDES/includes/addons.php";
$log->LogDebug("User $authusername loaded $ip $thisroom from " . basename(__FILE__) . " from " . $_SERVER['SCRIPT_FILENAME']);
if (strpos($enabledaddons,',') !== false) {
    $arr = explode(",", $enabledaddons);
	$addonid = $arr[0];
} else {
	$addonid = $enabledaddons;
}
$arr = explode(".", $addonid, 2);
$classification = $arr[0];
$title = $arr[1];
require $addonarray["$classification"]["$title"]['path']."nowplaying.php";
$ROOMNUMBER = "ROOM$thisroom"."N";
?>
<!DOCTYPE html>
<html>
<head>
<title>NowPlaying</title>
<link type='text/css' href='../css/nowplaying.css?<?php echo date ("m/d/Y-H.i.s", filemtime('../css/nowplaying.css'));?>' rel='stylesheet' media='screen' />
</head>
<body>
<div id='roominfocontainer'>
	<div id='logo'>
			<div id="timeUpdateField"></div>
		<h1>Now<span>Playing</span> in <span><?php echo ${$ROOMNUMBER};?></span></h1>
	</div>
	<div id='roominfocontent'>
			<div class='title'>
			<?
			if(isset($nowplayingarray['thumbnail']) && $nowplayingarray['thumbnail'] != '') {
			echo $nowplayingarray['thumbnail'];
			}
			echo "<table>";
			if(isset($nowplayingarray['title']) && $nowplayingarray['title'] != '') {
				echo "<th colspan=2><b>" . $nowplayingarray['title'] . "</b></th>";		
			}elseif(isset($nowplayingarray['label']) && $nowplayingarray['label'] != '') {
				echo "<th colspan=2><b>" . $nowplayingarray['label'] . "</b></th>";
			}
			foreach($nowplayingarray as $item=>$value) {
				$itembl = array("file","fanart","thumbnail","thumbnail","label","title","id","type","plot","series");
				$valuebl = array("''","0","-1");
				if(in_array($value, $valuebl) || in_array($item, $itembl)) { continue; }
				echo "<tr><td><b>".ucwords($item).":</b></td><td> ".$value."</td></tr>";
			}
			echo "</table>";?>
		</div>
		<?php if(isset($nowplayingarray['fanart']) && $nowplayingarray['fanart'] != '') { ?>
		<div id="images">
			<?php echo $nowplayingarray['fanart'];?>
		</div>
		<?php
		}
		if(isset($nowplayingarray['plot']) && $nowplayingarray['plot'] != '') {?>
			<br>
			<div class='plot'>
			<b>Plot:</b> <?php echo $nowplayingarray['plot']; ?>
			</div>
		<?php } ?>
	</div>
<script>
	//thenowplayingtimer();
	function thenowplayingtimer()
	{
	  if(!document.contains(timeUpdateField))
	  {
		 clearInterval(nowplayingtimer);
		 return;
	  } else {
		$("#timeUpdateField").load("<?php echo $addonarray["$classification"]["$title"]['path'];?>nowplayingtime.php?ip=<?echo $ip;?>&filetype=<?echo $filetype;?>&activeplayer=<?echo $activeplayerid;?>&addon=<?php echo $addonid;?>&showtime=1");
	  }
	}
	clearInterval(nowplayingtimer);
	var nowplayingtimer=setInterval(thenowplayingtimer, 4000);
</script>	
</div>
</body>
</html>
