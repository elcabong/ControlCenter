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
	<meta name='viewport' content="width=device-width,height:window-height, initial-scale=.9, maximum-scale=.9, minimum-scale=.9, user-scalable=auto" />
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<title>Media Center</title>
	<link rel='stylesheet' type='text/css' href='../css/room.css'>
	<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" ></script>-->
	<script type="text/javascript" src="../js/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery.scrollTo.js"></script>
	<script type="text/javascript" src="../js/scripts.js"></script>
	   <script type="text/javascript">
$(document).ready(function(){
//	$(window).resize(function () {	resizePanel(); });
	
	window.onresize = function(event) {
//	function resizePanel() {

		width = $(window).width();
		height = $(window).height();

		mask_width = width * $('.item').length;
		mask_height = height * .94;
		mask_dif = height * .10;
			
		$('#wrapper, .item').css({width: width, height: height});
		//$('#mask').css({width: mask_width, height: mask_height});
		//$('#mask').css({height: mask_height, top: mask_dif});
		//$('#mask').css({width: mask_width, height: height});
		$('#wrapper').scrollTo($('a.selected').attr('href'), 0);
	}
//});	
	   </script>
	<script type="text/javascript">
	function logout(){
		    var xmlhttp;
		    if (window.XMLHttpRequest)
		      {
			xmlhttp=new XMLHttpRequest();
		      } else {
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		      }
		    xmlhttp.onreadystatechange=function()
		      {
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
			  if(xmlhttp.responseText)
			  {
			    window.top.document.location.href = "/";
			    alert("Logout successful");
			  }
			}
		      }
		    xmlhttp.open("GET","logout.php",true);
		    xmlhttp.send();
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
						<? //<li><a href='#Settings' class='panel2nd' title='Settings'>Settings</a></li> ?>
						<li><a href="#">&nbsp;</a></li>
						<?php
						$mainurl="http://".$_SERVER['HTTP_HOST'];
						if ($authsecured) {
						  echo "<li><a href='#' onclick=\"logout();\"/>Logout</a></li>";
						}
						if (!$authsecured) {
						  echo "<li><a href='$mainurl' target=\"_top\" />Logout</a></li>";
						}
						?>
					</ul>
				</li>
			</ul>
		</nav>
		<li id="loading" style="padding:10px;"><img src="../media/loading.gif" height='25px'></li>
		<? //<li><a href='#maraschino' class='panel selected' title='maraschino'><img src="../config/Programs/Maraschino.png" height='35px'></a></li>?>
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
			if(($count) > 1 or ($ADMINP) > 0 ) {
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
				if($dnavlinkcount + $navlinkcount > 1) { $navgroups = '1';}
				if(!empty($dnavlink)) {
					foreach( $dnavlink as $navlinklabel => $navlinkpath) {
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
					}
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
				if($dnavlinkcount + $navlinkcount > 1) { $navgroups = '1';}
				if(!empty($dnavlink)) {
					foreach( $dnavlink as $navlinklabel => $navlinkpath) {
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
			}
		?>
		</ul>
	</nav>
</div>
<div class="clear"></div>	
</div>
<div id="wrapper" scrolling="auto">
	<div id="mask">
		<? /*
		<div id="maraschino" class="item">
			<div class="content">
				<iframe id='maraschino 1' width='100%' height='100%' scrolling='auto'>Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>
		*/?>
		<div id="XBMC" class="item">
			<div class="content">
				<?php $ROOMXT = "ROOM$theroom"; $XBMC = "XBMC"; $ROOMXBMC = $ROOMXT.$XBMC; ?>
				<iframe id='XBMC 1' class='XBMC' src="<?echo ${$ROOMXBMC};?>" width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>
		<div id="XBMCawxi" class="item">
			<div class="content">
				<iframe id='XBMCawxi 1' class='XBMCawxi' data-src="<?echo ${$ROOMXBMC};?>/addons/webinterface.awxi/" width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>
		<?php
		if(!empty($dnavlink)) {
			foreach( $dnavlink as $navlinklabel => $navlinkpath) {
					if($navlinkpath != "title") {
				echo "<div id='$navlinklabel' class='item'>";
				echo "<div class='content'>";
		       	 	echo "<iframe id='$navlinklabel 1' class='$navlinklabel' data-src='".$navlinkpath."' width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>";
				echo "</div>";
				echo "</div>";
			}}
		}
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
		?>
		<? /*
		<div id="Settings" class="item">
			<div class="content">
				<iframe id='Settings 1' data-src='./settings.php' width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>
		*/ ?>
	</div>
</div>
</body>
</html>