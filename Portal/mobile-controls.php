<?php
require_once 'config.php';
if ($authsecured && (!isset($_SESSION["$authusername"]) || !$_SESSION["$authusername"] || $_SESSION["$authusername"] != $authusername )) {
    header("Location: login.php");
    exit;}
require_once 'controls-include.php';	
if($TOTALROOMS>0 && $TOTALALLOWEDROOMS>0){
	$theroom = $_SESSION['room'];
	$theperm = "USRPR$theroom";
	if (${$theperm}!="1" or $theroom>$TOTALROOMS) {
		header("Location: index.php");
		exit; }
}
?>
<?php $ROOMXT = "ROOM$theroom"; $XBMC = "XBMC"; $ROOMXBMC = $ROOMXT.$XBMC; $ROOMXBMC2 = $ROOMXBMC."2"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, target-densitydpi=medium-dpi" />
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
	<link rel="icon" type="image/png" href="./favicon.ico">
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
	<style>
	ul.sortable > li > a:first-child {
	width: auto !important;
	}
	#nav-menu nav ul ul li {
	float: left !important;
	}
	</style>
</head>
<body>
<div id='header' class="nav-menu-z">
	<div id='nav-menu2'>
		<nav id="navsettings" style="float:right;width:50px;">
			<ul>
				<li><a href='#' class='navsettings panel' style="margin-bottom:3px;border-bottom:2px solid rgba(0, 0, 0, 0);"><img src="../media/gear.png" style="margin:7px 0 0;width:20px !important;"></a>
					<ul>
						<li><a href="#" class="title"><?echo $USERNAMES[$usernumber];?></a></li>
						<li><a href="#">&nbsp;</a></li>
						<li><a href='logout.php' />Logout</a></li>
					</ul>
				</li>
			</ul>
		</nav>
		<li id="loading" style="padding:10px;"><img src="../media/loading.gif" height='25px'></li>
		<? if($TOTALROOMS>0 && $TOTALALLOWEDROOMS>0){ ?>
		<li><a id='firstroomprogramlink' href='#ROOMCONTROL1' class='panel persistent selected'><img src="../media/Programs/XBMC.png" height='35px'></a></li>
		<li id="secondroomprogram" <? if(${$ROOMXBMC2} == '0') { echo "style='display:none;'"; }?>><a id='secondroomprogramlink' href='#ROOMCONTROL2' class='panel persistent unloaded'><img src="../media/Programs/XBMC.png" height='35px'></a></li>
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
							<div id='room-menu'><? include"room-chooser.php"; ?></div>
							<ul id="roomList"></ul>
						</li>
					</ul>
				</nav><? } ?>
			</div>
		<? } ?>
</div>
<div id='nav-menu'>
	<nav>
		<?php
				try {
					$sql = "SELECT navgroup,navgrouptitle,mobile,persistent FROM navigation WHERE mobile != '' ORDER BY navgrouptitle ASC";
					$navgroupamt = 0;
					$mobileamt = 0;
					$totalnonpersistentnav = 0;					
					foreach ($configdb->query($sql) as $row)
						{
							$navgroup = $row['navgroup'];
							if(strpos($NAVGROUPS,$navgroup) !== false) {
								if($row['navgrouptitle'] == "1") { $navgroupamt++; }
								if(isset($row['mobile']) && ($row['mobile'] != "0" || $row['mobile'] != "")) { $mobileamt++; }
							}
							if($row['persistent'] == '0') {
								$totalnonpersistentnav ++;
							}								
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
				if($mobileamt > '1'){					
					echo "<ul><li><a href='#'><img src='../media/menudropdown.png' style='width:25px;margin:5px;'></a>";				
				}
				try {
				echo "<ul>";
					$thenavgroups = explode(",",$NAVGROUPS);
					$tempc = 0;
					$linkcount = 1;
					foreach($thenavgroups as $x) {
					$sql = "SELECT * FROM navigation WHERE navgroup = $x AND mobile != ''";
					foreach ($configdb->query($sql) as $row)
						{
						$navtitle = $row['navname'];
						if(isset($row['navip'])) { $navdestination = $row['navip']; }
						$navgroup = $row['navgroup'];
						$navgrouptitle = $row['navgrouptitle'];
						if(isset($row['mobile']) && ($row['mobile'] != '' || $row['mobile'] != '1' || $row['mobile'] != '0')) { $navdestination = $row['mobile']; }
							if($mobileamt > '1'){
								$tempc++;
								echo "<li id=".$tempc." class='clear'>";
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
								echo "</li>";
							} else {
							$filename = "../media/Programs/".$navtitle.".png";
							if (file_exists($filename)) {
								$linkto = "<img src=$filename height='35px'>";
							} else {
								$linkto = $navtitle;
							}
								echo "<a href='#".$navtitle."' class='main panel persistent unloaded'>".$linkto."</a>";
							}
						}
					}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}				
		?>
		</ul><?
				if($mobileamt > '1'){					
					echo "</li></ul>";				
				}		?>
	</nav>
</div>
<div class="clear"></div>	
</div>
<div class="clearcover" style="position:absolute;width:100%;top:50px;bottom:0;display:none;background-color:rgba(0,0,0,.30);z-index:150;"></div>
<div id="wrapper" scrolling="auto">
	<div id="mask">
	<? if($TOTALROOMS>0 && $TOTALALLOWEDROOMS>0){ ?>
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
	<? } ?>	
		<?php
				try {
					$sql = "SELECT * FROM navigation WHERE navgroup IN (".$NAVGROUPS.") AND persistent == '1' ORDER BY navgroup ASC, navid ASC";
					$tempc = 0;
					foreach ($configdb->query($sql) as $row)
						{
						$navtitle = $row['navname'];
						if(isset($row['navip'])) { $navdestination = $row['navip']; }
						$navgroup = $row['navgroup'];
						$navgrouptitle = $row['navgrouptitle'];
						$mobiledestination = 0;
						if(isset($row['mobile']) && $row['mobile'] != '') { $mobiledestination = $row['mobile']; }
						if($mobiledestination != '0') {
							if($mobiledestination != "1") { $navdestination = $mobiledestination; }
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
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
		if($totalnonpersistentnav > "0") {?>
		<div id="nonpersistent" class="item">
			<div class="content">
				<iframe id='nonpersistentf' class='nonpersistent' name='nonpersistent' width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
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
		});
	<? } ?>
</script>
</body>
</html>