<? 
if(isset($_GET['thisroom'])) { $theroom=$_GET['thisroom']; }
$ROOMNUMBER = "ROOM$theroom"."N";	
require 'config.php';
require 'addons.php';
?>
<!DOCTYPE html>
<html>
<head>
<title>RoomDetails</title>
<link type='text/css' href='../css/nowplaying.css?<? echo date ("m/d/Y-H.i.s", filemtime('../css/nowplaying.css'));?>' rel='stylesheet' media='screen' />
</head>
<body>
<div id='roominfocontainer'>
	<div id='logo'>
		<h1>Details:<span><?= ${$ROOMNUMBER};?></span></h1>
	</div>
	<div id='roominfocontent'>
	<?php
		foreach($enabledaddonsarray[$theroom] as $thisaddonid => $array) {
		$thisaddonid = explode(".", $thisaddonid, 2);
						$classification = $thisaddonid[0];
						$title = $thisaddonid[1];

			if(file_exists($addonarray["$classification"]["$title"]['path']."details.php")){
				include $addonarray["$classification"]["$title"]['path']."details.php";
			}
		}
	?>
			<div class='title'>
			<?
			echo $thumbnail;
			echo "<table>";
			foreach($nowplayingarray as $item=>$value) {
				echo "<tr><td><b>".$item.":</b></td><td> ".$value."</td></tr>";
			}
			echo "</table>";?>
		</div>
		<?php	if(isset($plot) && $plot != '') {?>
			<br>
			<div class='plot'>
			<b>Plot:</b> <?php echo $plot; ?>
			</div><?php } ?>
		<div id="images">
			<?php echo $fanart;?>
		</div>
	</div>
<script>
/*
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
	*/
</script>	
</div>
</body>
</html>