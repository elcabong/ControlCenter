<?php
require_once 'config.php';
if ($authsecured && (!isset($_SESSION["$authusername"]) || !$_SESSION["$authusername"] || $_SESSION["$authusername"] != $authusername )) {
    header("Location: login.php");
    exit;}
$theroom = $_SESSION['room'];
$theperm = "USRPR$theroom";
if (${$theperm}!="1" && $ADMINP!="1" or $theroom>$TOTALROOMS) {
    header("Location: index.php");
	exit; }
	/*
/////////////////////////////////////////
$found1 = false;
$path1 = './lib/xbmc.json.wrapper.php';
while(!$found1){	
	if(file_exists($path1)){ 
		$found1 = true;
                include("$path1");
	}
	else{ $path1= '../'.$path1; }
}	///////////////////////////////
	$xbmcHost = new xbmcHost('192.168.3.218:80');
	$xbmcJson = new xbmcJson($xbmcHost); */
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, target-densitydpi=medium-dpi" />
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">	
	<title>Control Center</title>
	<link rel='stylesheet' type='text/css' href="../css/room.css?<? echo date ("m/d/Y-H.i.s", filemtime('../css/room.css'));?>">
	<script type="text/javascript" src="../js/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery.scrollTo.js"></script>
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
				<li><a href='#Settings' class='navsettings panel' title='Settings' style="margin-bottom:3px;border-bottom:2px solid rgba(0, 0, 0, 0);"><img src="../media/gear.png" style="margin:7px 0 0;width:20px !important;"></a>
					<ul>
						<li><a href="#" class="title"><?echo $USERNAMES[$usernumber];?></a></li>
						<li><a href='#Settings' class='panel2nd' title='Settings'>Settings</a></li>
						<li><a href="#">&nbsp;</a></li>
						<li><a href='logout.php' />Logout</a></li>
					</ul>
				</li>
			</ul>
		</nav>
		<li id="loading" style="padding:10px;"><img src="../media/loading.gif" height='25px'></li>
		<? /*<li><a href='#maraschinoadmin' class='panel unloaded' title='maraschinoadmin'><img src="../config/Programs/MaraschinoAdmin.png" height='35px'></a></li> */?>
		<li><a href='#XBMC' class='panel selected' title='XBMC'><img src="../config/Programs/XBMC.png" height='35px'></a></li>
		<li><a href='#XBMCawxi' class='panel unloaded' title='XBMCawxi'><img src="../config/Programs/XBMC.png" height='35px'></a></li>
			<?php
			$c = 1;
			$count = 0;
			while($count<2 && $c<$TOTALROOMS) {
				$user = "USRPR$c";
				$count = $count + ${$user}; 
				$c++;
			}
			if(($count) > 0 or ($ADMINP) > 0 ) {
			echo "<div id='multiples'>";
			?>
		<nav>		
			<ul>
				<li>
					<div id='room-menu'><? include"room-chooser.php"; ?></div>
					<ul id="roomList"></ul>
				</li>
			</ul>
		</nav><? } ?>
	</div>
</div>
<div id='nav-menu'>
	<nav>
		<?php
			$navlist = 0;
			if($navlist == '1') {
				echo "<ul>";
				$navgroups = '0';
				$tempc = 0;
				if($gnavlinkcount + $navlinkcount > 1) { $navgroups = '1';}
				if(!empty($gnavlink)) {
					foreach( $gnavlink as $navlinklabel => $navlinkpath) {
					if($navlinklabel == "MaraschinoAdmin") {
						if($navlinkpath == "title") {
							if($navgroups == '1'){
							$filename = "../config/Programs/".$navlinklabel.".png";
							if (file_exists($filename)) {
								$linkto = "<img src=$filename height='35px' title='$navlinklabel'>";
							} else {
								$linkto = $navlinklabel;
							}
							$tempc++;
							if($tempc>1){ echo "</ul></li>"; }
							echo "<li><a href='#' class='main panel' title='$navlinklabel'>".$linkto."</a>";
							echo "<ul>";
							}
						} else {
						$filename = "../config/Programs/".$navlinklabel.".png";
						if (file_exists($filename)) {
							$linkto = "<img src=$filename height='35px' title='$navlinklabel'>";
						} else {
							$linkto = $navlinklabel;   
						}
						echo "<li><a href='#".$navlinklabel."' class='main panel' title='$navlinklabel'>".$linkto."</a></li>";
						}
						if($navgroups =='1' && $navlinkpath != "title"){ echo "</li>";}
					}}
				}
					if(!empty($navlink)){
						foreach( $navlink as $navlinklabel => $navlinkpath) {
							if($navlinklabel != "title") {
							$filename = "../config/Programs/".$navlinklabel.".png";
							if (file_exists($filename)) {
								$linkto = "<img src=$filename height='35px' title='$navlinklabel'>";
							} else {
								$linkto = $navlinklabel;
							}
							echo "<li><a href='#".$navlinklabel."' class='main panel' title='$navlinklabel'>".$linkto."</a></li>";
							}
						}
					}
						if($navgroups =='1'){ echo "</ul>";}	
			} else {
				echo "<ul class='sortable'>";
				$navgroups = '0';
				$tempc = 0;
				if($gnavlinkcount + $navlinkcount > 1) { $navgroups = '1';}
				if(!empty($gnavlink)) {
					foreach( $gnavlink as $navlinklabel => $navlinkpath) {
					if($navlinklabel == "MaraschinoAdmin") {
						if($navlinkpath == "title") {
							if($navgroups == '1'){
								$filename = "../config/Programs/".$navlinklabel.".png";
								if (file_exists($filename)) {
									$linkto = "<img src=$filename height='35px' title='$navlinklabel'>";
								} else {
									$linkto = $navlinklabel;
								}
								$tempc++;
								if($tempc>1){
									echo "</li>";
									echo "<li id=".$tempc." class='sortable secondary clear hidden'><a href='#' class='main panel title' title='$navlinklabel'>".$linkto."</a>";
								} else {
									echo "<li id=".$tempc." class='sortable clear'><a href='#' class='main panel title' title='$navlinklabel'>".$linkto."</a>";
								}
							}
						} else {
						$filename = "../config/Programs/".$navlinklabel.".png";
						if (file_exists($filename)) {
							$linkto = "<img src=$filename height='35px' title='$navlinklabel'>";
						} else {
							$linkto = $navlinklabel;
						}
						echo "<a href='#".$navlinklabel."' class='main panel unloaded' title='$navlinklabel'>".$linkto."</a>";
						}
					}
				}} /*
				if(!empty($navlink)){
						foreach( $navlink as $navlinklabel => $navlinkpath) {
						if($navlinkpath == "title") {
							if($navgroups == '1'){
								$filename = "../config/Programs/".$navlinklabel.".png";
								if (file_exists($filename)) {
									$linkto = "<img src=$filename height='35px' title='$navlinklabel'>";
								} else {
									$linkto = $navlinklabel;
								}
								$tempc++;
								if($tempc>1){
									echo "</li>";
									echo "<li id=".$tempc." class='sortable secondary clear hidden'><a href='#' class='main panel title' title='$navlinklabel'>".$linkto."</a>";
								} else {
									echo "<li id=".$tempc." class='sortable clear'><a href='#' class='main panel title' title='$navlinklabel'>".$linkto."</a>";
								}
							}
						} else {
						$filename = "../config/Programs/".$navlinklabel.".png";
						if (file_exists($filename)) {
							$linkto = "<img src=$filename height='35px' title='$navlinklabel'>";
						} else {
							$linkto = $navlinklabel;
						}
						echo "<a href='#".$navlinklabel."' class='main panel unloaded' title='$navlinklabel'>".$linkto."</a>";
						}
					}
				}
*/







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
		<? /*<div id="maraschinoadmin" class="item">
			<div class="content">
				<iframe id='maraschinoadmin 1' src="http://192.168.3.217:7000/mobile/" width='100%' height='100%' scrolling='auto'>Sorry your browser does not support frames or is currently not set to accept them.</iframe>
		</div>
		</div> */?>
		<div id="XBMC" class="item">
			<div class="content">
				<?php $ROOMXT = "ROOM$theroom"; $XBMC = "XBMC"; $ROOMXBMC = $ROOMXT.$XBMC; ?>
				<iframe id='XBMC 1' class='XBMC' src="<?echo ${$ROOMXBMC};?>" width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>
		<div id="XBMCawxi" class="item">
			<div class="content">
				<iframe id='XBMCawxi 1' class='XBMCawxi' data-src="<?echo ${$ROOMXBMC};?>/addons/webinterface.jquerymobile/" width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>
		<?php 
		if(!empty($gnavlink)) {
			foreach( $gnavlink as $navlinklabel => $navlinkpath) {
				if($navlinkpath != "title") {
					if($navlinklabel == "MaraschinoAdmin") {
						echo "<div id='$navlinklabel' class='item'>";
						echo "<div class='content'>";
						echo "<iframe id='$navlinklabel 1' class='$navlinklabel' data-src='".$navlinkpath."mobile/' width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>";
						echo "</div>";
						echo "</div>";
			}}}
		}  /*
			if(!empty($navlink)){
				foreach( $navlink as $navlinklabel => $navlinkpath) {
					if($navlinkpath != "title") {
					echo "<div id='$navlinklabel' class='item'>";
					echo "<div class='content'>";
			       	 	echo "<iframe id='$navlinklabel 1' class='$navlinklabel' data-src='".$navlinkpath."' width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>";
					echo "</div>";
					echo "</div>";		
				} }
			}
	*/	?>
	<!--	<div id="Settings" class="item">
			<div class="content">
				<iframe id='Settings 1' src='./settings.php' width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>	-->	
	</div>
</div>
</body>
</html>