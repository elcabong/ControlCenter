<?php
require_once 'config.php';
if ($authsecured && (!isset($_SESSION["$authusername"]) || $_SESSION["$authusername"] != $authusername )) {
    header("Location: login.php");
    exit;}
require_once 'controls-include.php';
if($TOTALROOMS>0 && $TOTALALLOWEDROOMS>0){
	$theroom = $_SESSION['room'];
	$theperm = "USRPR$theroom";
	if (${$theperm}!="1" or !in_array($theroom, $roomgroupaccessarray)) {
		header("Location: index.php");
		exit; }
}
require_once 'addons.php';
?>
<!DOCTYPE html>
<html>
<head>
	<meta name='viewport' content="width=device-width,height:window-height, initial-scale=.9, maximum-scale=.9, minimum-scale=.9, user-scalable=auto" />
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
	<link rel="icon" type="image/png" href="./favicon.ico">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Control Center</title>
	<link rel='stylesheet' type='text/css' href="../css/room.css?<? echo date ("m/d/Y-H.i.s", filemtime('../css/room.css'));?>">
	<script type="text/javascript" src="../js/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery.scrollTo.js"></script>
	<script type="text/javascript" src="../js/jquery.simplemodal.js"></script>
	<script type="text/javascript" src="../js/jquery.touchwipe.js"></script>	
	<script type="text/javascript" src="../js/scripts.js?<? echo date ("m/d/Y-H.i.s", filemtime('../js/scripts.js'));?>"></script>
	<script type="text/javascript">
		if (window.navigator.standalone) {
			var iWebkit;if(!iWebkit){iWebkit=window.onload=function(){function fullscreen(){var a=document.getElementsByTagName("a");for(var i=0;i<a.length;i++){if(a[i].className.match("noeffect")){}else{a[i].onclick=function(){window.location=this.getAttribute("href");return false}}}}function hideURLbar(){window.scrollTo(0,0.9)}iWebkit.init=function(){fullscreen();hideURLbar()};iWebkit.init()}}
		}
	</script>
</head>
<body>
<div id='header' class="nav-menu-z">
	<div id='nav-menu2'>
		<nav id="navsettings">
			<ul>
				<li><a href='#' class='navsettings panel'><img src="../media/options.png"></a>
					<ul>
						<li><a href="#" class="title"><?echo $USERNAMES[$usernumber];?></a></li>
						<? if($SETTINGSACCESS == "1") { ?>
						<li><a href='#Settings' class='panel2nd'>Settings</a></li> <? } ?>
						<li><a href="#">&nbsp;</a></li>
						<li><a href='logout.php' />Logout</a></li>
					</ul>
				</li>
			</ul>
		</nav>
		<li id="loading" style="padding:10px;"><img src="../media/loading.gif" height='25px'></li>
		<? if($TOTALROOMS>0 && $TOTALALLOWEDROOMS>0){
		
						// these may have to be set inside a div, and moved to another file that can be reloaded on room change.  same with loading addonlinkpages below.
									$allenabledaddons = explode(",", $enabledaddons);
									
									foreach($allenabledaddons as $thisaddon) {
										$allenabledaddons = explode(".", $thisaddon, 2);
										$classification = $allenabledaddons[0];
										$title = $allenabledaddons[1];
										include $addonarray["$classification"]["$title"]['path']."addonquicklink.php";
									}
			$c = 1;
			$count = 0;
			while($count<2 && $c<$TOTALROOMS) {
				$user = "USRPR$c";
				$count = $count + ${$user}; 
				$c++;
			}
			if(($count) > 0) {
			echo "<div id='multiples'>";
			?>
			<nav>		
				<ul>
					<li>
						<div id='room-menu'><? include"./room-chooser.php"; ?></div>
						<ul id="roomList">
							<?php 
								foreach ($roomgroupaccessarray as $i) {
									echo "<li class='roominfo' id=\"roominfo$i\"></li>";
								}
							?>
						</ul>
					</li>
				</ul>
			</nav><? } ?>
		</div>
		<? } ?>
</div>
<div id='nav-menu'>
	<nav>
		<?php
				echo "<ul class='sortable'>";
				try {
					$sql = "SELECT navgroup,navgrouptitle,persistent FROM navigation ORDER BY navgrouptitle ASC";
					$navgroupamt = 0;
					$totalnonpersistentnav = 0;
					foreach ($configdb->query($sql) as $row)
						{
							$navgroup = $row['navgroup'];
							if(strpos($NAVGROUPS,$navgroup) !== false) {
								if($row['navgrouptitle'] == "1") { $navgroupamt++; }
							}
							if($row['persistent'] == '0') {
								$totalnonpersistentnav ++;
							}	
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
				try {
					$thenavgroups = explode(",",$NAVGROUPS);
					$tempc = 0;
					$linkcount = 1;
					foreach($thenavgroups as $x) {
					$sql = "SELECT * FROM navigation WHERE navgroup = $x";
					foreach ($configdb->query($sql) as $row)
						{
						$navtitle = $row['navname'];
						if(isset($row['navip'])) { $navdestination = $row['navip']; }
						$navgroup = $row['navgroup'];
						$navgrouptitle = $row['navgrouptitle'];
							if($navgrouptitle == "1") {
								if($navgroupamt > '1'){
									$filename = "../media/Programs/".$navtitle.".png";
									if (file_exists($filename)) {
										$linkto = "<img src=$filename height='35px'>";
									} else {
										$linkto = $navtitle;
									}
									$tempc++;
									if($tempc>1){
										echo "</li>";
										echo "<li id=".$tempc." class='sortable secondary clear hidden'><a href='#' class='main panel persistent title'>".$linkto."</a>";
									} else {
										echo "<li id=".$tempc." class='sortable clear'><a href='#' class='main panel persistent title'>".$linkto."</a>";
									}
								}
							} else {
							$filename = "../media/Programs/".$navtitle.".png";
							if (file_exists($filename)) {
								$linkto = "<img src=$filename height='35px'>";
							} else {
								$linkto = $navtitle;
							}
							if($linkcount == '1' && $TOTALALLOWEDROOMS<1) { $loadthis = "selected";$selectedpanel = $navtitle;$loadpersistent = 0; } else { $loadthis = "unloaded"; }
							if($row['persistent'] == '0') {
							if($linkcount == '1' && $TOTALALLOWEDROOMS<1) { $loadpersistent = $row['navip']; }
							echo "<a href='".$row['navip']."' class='panel nonpersistent main $loadthis' target='nonpersistent'>".$linkto."</a>";
							} else {
							echo "<a href='#".$navtitle."' class='main panel persistent $loadthis'>".$linkto."</a>";
							}
							$linkcount++;
							}
						}
					}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}				
		?>
		</ul>
	</nav>
</div>
<div class="clear"></div>	
</div>
<div class="clearcover" style="position:absolute;width:100%;top:50px;bottom:0;display:none;background-color:rgba(0,0,0,.30);z-index:150;"></div>
<div id="wrapper" scrolling="auto">
	<div id="mask">
	<? if($TOTALROOMS>0 && $TOTALALLOWEDROOMS>0){
			$allenabledaddons = explode(",", $enabledaddons);
									
			foreach($allenabledaddons as $theaddon) {
				$allenabledaddons = explode(".", $theaddon, 2);
				$classification = $allenabledaddons[0];
				$title = $allenabledaddons[1];
				include $addonarray["$classification"]["$title"]['path']."addonlinkpages.php";
			}
		}
				try {
					$sql = "SELECT * FROM navigation WHERE navgroup IN (".$NAVGROUPS.") AND persistent == '1' ORDER BY navgroup ASC, navid ASC";
					foreach ($configdb->query($sql) as $row)
						{
						$navtitle = $row['navname'];
						if(isset($row['navip'])) { $navdestination = $row['navip']; }
						$navgroup = $row['navgroup'];
						$navgrouptitle = $row['navgrouptitle'];
						if(strpos($NAVGROUPS,$navgroup) !== false) {
							if($navgrouptitle == "1") {
							} else {
								echo "<div id='$navtitle' class='item'>";
								echo "<div class='content'>";
								if($navtitle == $selectedpanel) {
									echo "<iframe id='".$navtitle."f' class='$navtitle' src='".$navdestination."' data-src='".$navdestination."' width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>";
								} else {
									echo "<iframe id='".$navtitle."f' class='$navtitle' data-src='".$navdestination."' width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>";
								}
								echo "</div>";
								echo "</div>";
							}
						}
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
		if($totalnonpersistentnav > "0") {?>
		<div id="nonpersistent" class="item">
			<div class="content">
				<iframe id='nonpersistentf' class='nonpersistent' name='nonpersistent' width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>
		<? }
		if($SETTINGSACCESS == "1") {?>
		<div id="Settings" class="item">
			<div class="content">
				<iframe id='Settingsf' class='Settings' data-src='./settings.php' width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>
		<? } ?>
	</div>
</div>
<script>
	<? if($TOTALALLOWEDROOMS==0){ ?>
		$(document).ready(function() {
			<? if($loadpersistent != '0') { ?>
				var iframepersist = document.getElementById('nonpersistentf');
				iframepersist.src = '<? echo $loadpersistent; ?>';
				$('#wrapper').scrollTo(iframepersist, 0);
			<? } else {?>
				var iframe = document.getElementById('<?echo $selectedpanel;?>');
				$('#wrapper').scrollTo(iframe, 0);
			<? } ?>
			setTimeout(func, 4500);
			function func() {
				document.getElementById('loading').style.display='none';
			}
		});
	<? } else {?>
		$(document).ready(function() {
		<?php
			foreach ($roomgroupaccessarray as $i) {
				$thedelay = 1000 + (180 * $i);
				echo "
				function refreshRoom$i() {
				$(\"#roominfo$i\").load(\"./getaddons.php?room=$i\", function () {
					refreshtheroom$i = setTimeout(refreshRoom$i, 2500);
					reSizeRoomInfo();
				});
				}
				refreshtheroom$i = setTimeout(refreshRoom$i, $thedelay);";
			}
		?>
			setTimeout(func, 4500);
			function func() {
				document.getElementById('loading').style.display='none';
			}
		});
	<? } ?>	
</script>
<div id="modal"></div>
</body>
</html>