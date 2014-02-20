<?php if($_GET['ip']) { $ip=$_GET['ip']; } if($_GET['thisroom']) { $thisroom=$_GET['thisroom']; }
require 'config.php';
require 'addons.php';
if (strpos($enabledaddons,',') !== false) {
    $arr = explode(",", $enabledaddons);
	$addonid = $arr[0];
} else { $addonid = $enabledaddons; }
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
		<h1>Now<span>Playing</span> in <span><?= ${$ROOMNUMBER};?></span></h1>
			<?
			/*if(!isset($jsonactiveplayer['result'])) {
				echo "There is nothing currently playing.";
				return;
			}*/
			?>
	</div>
	<div id='roominfocontent'>
			<div class='title'>
			<?
			echo $thumbnail;
			echo "<table>";
			foreach($nowplayingarray as $item=>$value) {
				echo "<tr><td><b>".$item.":</b></td><td> ".$value."</td></tr>";
			}
			echo "</table>";?>
		</div>
		<div id="images">
			<?php echo $fanart;?>
		</div>
		<?php	if(isset($plot) && $plot != '') {?>
			<br>
			<div class='plot'>
			<b>Plot:</b> <?php echo $plot; ?>
			</div><?php } ?>
	</div>
<script>
	thenowplayingtimer();
	function thenowplayingtimer()
	{
	  if(!document.contains(timeUpdateField))
	  {
		 clearInterval(nowplayingtimer);
		 return;
	  } else {
		$("#timeUpdateField").load("<?php echo $addonarray["$classification"]["$title"]['path'];?>nowplayingtime.php?ip=<?echo $ip;?>&filetype=<?echo $filetype;?>&activeplayer=<?echo $activeplayerid;?>&addon=<?php echo $addonid;?>");
	  }
	}
	clearInterval(nowplayingtimer);
	var nowplayingtimer=setInterval(thenowplayingtimer, 5000);
</script>	
</div>
</body>
</html>
