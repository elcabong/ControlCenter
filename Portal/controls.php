<?php
require_once 'config.php';
if ($authsecured && (!isset($_SESSION["$authusername"]) || !$_SESSION["$authusername"] || $_SESSION["$authusername"] != $authusername )) {
    header("Location: login.php");
    exit;}
require_once 'controls-include.php';
$theroom = $_SESSION['room'];
$theperm = "USRPR$theroom";
if (${$theperm}!="1" or $theroom>$TOTALROOMS) {
    header("Location: index.php");
	exit; }
?>
<?php $ROOMXT = "ROOM$theroom"; $XBMC = "XBMC"; $ROOMXBMC = $ROOMXT.$XBMC; $ROOMXBMC2 = $ROOMXBMC."2"; ?>
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
		<nav id="navsettings" style="float:right;width:50px;">
			<ul>
				<li><a href='#<? if($SETTINGSACCESS == "1") { ?>Settings<? } ?>' class='navsettings panel' style="margin-bottom:3px;border-bottom:2px solid rgba(0, 0, 0, 0);"><img src="../media/gear.png" style="margin:7px 0 0;width:20px !important;"></a>
					<ul>
						<li><a href="#" class="title"><?echo $USERNAMES[$usernumber];?></a></li>
						<? if($SETTINGSACCESS == "1") { ?>
						<li><a href='#Settings' class='panel2nd' title='Settings'>Settings</a></li> <? } ?>
						<li><a href="#">&nbsp;</a></li>
						<li><a href='logout.php' />Logout</a></li>
					</ul>
				</li>
			</ul>
		</nav>
		<li id="loading" style="padding:10px;"><img src="../media/loading.gif" height='25px'></li>
		<li><a id='firstroomprogramlink' href='#ROOMCONTROL1' class='panel selected'><img src="../media/Programs/XBMC.png" height='35px'></a></li>
		<li id="secondroomprogram" <? if(${$ROOMXBMC2} == '0') { echo "style='display:none;'"; }?>><a id='secondroomprogramlink' href='#ROOMCONTROL2' class='panel unloaded'><img src="../media/Programs/XBMC.png" height='35px'></a></li>
			<?php
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
					<ul id="roomList"></ul>
				</li>
			</ul>
		</nav><? } ?>
	</div>
</div>
<div id='nav-menu'>
	<nav>
		<?php
				echo "<ul class='sortable'>";
				try {
					$sql = "SELECT navgroup,navgrouptitle FROM navigation ORDER BY navgrouptitle ASC";
					$navgroupamt = 0;
					foreach ($configdb->query($sql) as $row)
						{
							$navgroup = $row['navgroup'];
							if(strpos($NAVGROUPS,$navgroup) !== false) {
								if($row['navgrouptitle'] == "1") { $navgroupamt++; }
							}
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
				try {
					$thenavgroups = explode(",",$NAVGROUPS);
					$tempc = 0;
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
										$linkto = "<img src=$filename height='35px' title='$navtitle'>";
									} else {
										$linkto = $navtitle;
									}
									$tempc++;
									if($tempc>1){
										echo "</li>";
										echo "<li id=".$tempc." class='sortable secondary clear hidden'><a href='#' class='main panel title' title='$navtitle'>".$linkto."</a>";
									} else {
										echo "<li id=".$tempc." class='sortable clear'><a href='#' class='main panel title' title='$navtitle'>".$linkto."</a>";
									}
								}
							} else {
							$filename = "../media/Programs/".$navtitle.".png";
							if (file_exists($filename)) {
								$linkto = "<img src=$filename height='35px' title='$navtitle'>";
							} else {
								$linkto = $navtitle;
							}
							echo "<a href='#".$navtitle."' class='main panel unloaded' title='$navtitle'>".$linkto."</a>";
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
		<div id="ROOMCONTROL1" class="item">
			<div class="content">
				<iframe id='ROOMCONTROL1f' class='ROOMCONTROL1' src="<?echo ${$ROOMXBMC};?>" width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>
		<div id="ROOMCONTROL2" class="item">
			<div class="content">
				<iframe id='ROOMCONTROL2f' class='ROOMCONTROL2' data-src="<?echo ${$ROOMXBMC2};?>" width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>
		<?php
				try {
					$sql = "SELECT * FROM navigation ORDER BY navgroup ASC";
					$tempc = 0;
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
									echo "<iframe id='".$navtitle."f' class='$navtitle' data-src='".$navdestination."' width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>";
								echo "</div>";
								echo "</div>";
							}
						}
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}		
		if($SETTINGSACCESS == "1") {?>
		<div id="Settings" class="item">
			<div class="content">
				<iframe id='Settingsf' class='Settings' data-src='./settings.php' width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>
		<? } ?>
	</div>
</div>
</body>
</html>