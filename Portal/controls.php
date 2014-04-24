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
	require_once 'addons.php';
}
require_once "mobile_device_detect.php";
if(mobile_device_detect(true,false,true,true,true,true,true,false,false) ) {
	$isMobile = 1;
} else {
	$isMobile = 0;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php if($isMobile == 1) { ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, target-densitydpi=medium-dpi, minimal-ui" />
<?php } else { ?>
	<meta name='viewport' content="width=device-width,height:window-height, initial-scale=.9, maximum-scale=.9, minimum-scale=.9, user-scalable=auto" />
<?php } ?>
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
	<link rel="icon" type="image/png" href="./favicon.ico">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Control Center</title>
	<link rel='stylesheet' type='text/css' href="../css/room.css?<?php echo date ("m/d/Y-H.i.s", filemtime('../css/room.css'));?>">
	<script type="text/javascript" src="../js/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="../js/jquery.scrollTo.js"></script>
	<script type="text/javascript" src="../js/jquery.simplemodal.js"></script>
	<script type="text/javascript" src="../js/jquery.touchwipe.js"></script>	
	<script type="text/javascript" src="../js/ifvisible.js"></script>
	<script type="text/javascript" src="../js/scripts.js"></script>
	<script type="text/javascript">
		if (window.navigator.standalone) {
			var iWebkit;if(!iWebkit){iWebkit=window.onload=function(){function fullscreen(){var a=document.getElementsByTagName("a");for(var i=0;i<a.length;i++){if(a[i].className.match("noeffect")){}else{a[i].onclick=function(){window.location=this.getAttribute("href");return false}}}}function hideURLbar(){window.scrollTo(0,0.9)}iWebkit.init=function(){fullscreen();hideURLbar()};iWebkit.init()}}
		}
	</script>
<?php if($isMobile == 1) { ?>
	<style>
	ul.sortable > li > a:first-child {
	width: auto !important;
	}
	#nav-menu nav ul ul li {
	float: left !important;
	}
	#multiples li a { padding:0 5px !important; }
	#room-menu > a { padding:0 5px !important; }
	#nav-menu > nav > ul > li > a { padding:5px 0; }
	#nav-menu > nav > ul > li > a > img { margin:0;width:20px; }
	#nav-menu > nav > ul > li > ul > li { padding-top:3px; }
	</style>
<?php } ?>	
</head>
<body>
<div id='header' class="nav-menu-z">
	<div id='nav-menu2'>
		<nav id="navsettings">
			<ul>
				<li><a href='#' class='navsettings panel'><img src="../media/options.png"></a>
					<ul>
						<li><a href="#" class="title"><?php echo $USERNAMES[$usernumber];?></a></li>
						<?php if($SETTINGSACCESS == "1" && $isMobile == "0") { ?>
						<li><a href='#Settings' class='panel2nd'>Settings</a></li> <?php } ?>
						<li><a href="#">&nbsp;</a></li>
						<li><a href='logout.php' />Logout</a></li>
					</ul>
				</li>
			</ul>
		</nav>
		<li id="loading" style="padding:10px;"><img src="../media/loading.gif" height='25px'></li>
		<?php if($TOTALROOMS>0 && $TOTALALLOWEDROOMS>0){
		echo "<nav id='addonlinks'>";
		$addontype = 'links';
		include"./addonslinks.php";
		echo "</nav>";						
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
						<div id='room-menu'><?php include"./room-chooser.php"; ?></div>
						<ul id="roomList">
							<?php 
								foreach ($roomgroupaccessarray as $i) {
									echo "<li class='roominfo' id=\"roominfo$i\"></li>";
								}
							?>
						</ul>
					</li>
				</ul>
			</nav><?php } ?>
		</div>
		<?php } ?>
</div>
<div id='nav-menu'>
	<nav>
		<?php
		if(isset($NAVGROUPS) && $NAVGROUPS != '') {
			$thenavgroups = explode(",",$NAVGROUPS);
			$navitems = '';
			$allnavitems = '';
			if($isMobile == "1") {
				try {
					$sql = "SELECT mobile,persistent FROM navigation WHERE mobile != ''";
					$mobileamt = 0;
					$totalnonpersistentnav = 0;					
					foreach ($configdb->query($sql) as $row)
						{
							if(isset($row['mobile']) && ($row['mobile'] != "0" || $row['mobile'] != "")) { $mobileamt++; }
							if($row['persistent'] == '0') { $totalnonpersistentnav ++; }								
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
				if($mobileamt > '1'){				
					echo "<ul><li><a href='#'><img src='../media/menudropdown.png'></a>";				
				}
				try {
					echo "<ul>";
					$tempc = 0;
					$linkcount = 1;
					$thenavitems = '';
					$itemarray = array();
					foreach($thenavgroups as $x) {
						$sql = "SELECT * FROM navigationgroups WHERE navgroupid = $x";
						foreach ($configdb->query($sql) as $row) {
							$thenavitems .= ",".$row['navitems'];
						}
						$thenavitems = explode(",",$thenavitems);
						$navitems = $row['navitems'];
						$allnavitems .= ",".$navitems;
						foreach($thenavitems as $x) {
							if($x == '') { continue; }
							if(in_array($x, $itemarray)) { continue; }
							array_push($itemarray, $x);								
							$sql = "SELECT * FROM navigation WHERE navid = $x AND mobile != ''";
							foreach ($configdb->query($sql) as $row) {
								$navtitle = $row['navname'];
							//	if(isset($row['navip'])) { $navdestination = $row['navip']; }
							//	if(isset($row['mobile']) && ($row['mobile'] != '' || $row['mobile'] != '1' || $row['mobile'] != '0')) { $navdestination = $row['mobile']; }
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
										if($WANCONNECTION == '1' && isset($row['navipw']) && $row['navipw'] != '') { $loadpersistent = $row['navipw']; }
										echo "<a href='".$loadpersistent."' class='panel nonpersistent main $loadthis' target='nonpersistent'>".$linkto."</a>";
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
					}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}				
				echo "</ul>";
				if($mobileamt > '1'){				
					echo "</li></ul>";				
				}
			} else {
				echo "<ul class='sortable'>";
					try {
						$totalnonpersistentnav = 0;
						$tempc = 0;
						$linkcount = 1;
						$navgroupamt = substr_count($NAVGROUPS, ",") +1;
						foreach($thenavgroups as $x) {
						if($x == '') { break; }
						$sql = "SELECT * FROM navigationgroups WHERE navgroupid = $x";
						foreach ($configdb->query($sql) as $row)
							{
							$navtitle = $row['navgroupname'];
							$navgroup = $row['navgroupid'];
							$navitems = $row['navitems'];
							$allnavitems .= ",".$navitems;
							
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
							
							$thenavitems = explode(",",$navitems);
								foreach($thenavitems as $item) {
									$sql = "SELECT navname,navip,mobile,persistent FROM navigation WHERE navid = $item";
									foreach ($configdb->query($sql) as $row) {									
									$navtitle = $row['navname'];
									if($row['persistent'] == '0') {
										$totalnonpersistentnav ++;
									}
									
									$filename = "../media/Programs/".$navtitle.".png";
									if (file_exists($filename)) {
										$linkto = "<img src=$filename height='35px'>";
									} else {
										$linkto = $navtitle;
									}
									if($linkcount == '1' && $TOTALALLOWEDROOMS<1) { $loadthis = "selected";$selectedpanel = $navtitle;$loadpersistent = 0; } else { $loadthis = "unloaded"; }
									if($row['persistent'] == '0') {
									if($linkcount == '1' && $TOTALALLOWEDROOMS<1) { $loadpersistent = $row['navip']; }
									if($WANCONNECTION == '1' && isset($row['navipw']) && $row['navipw'] != '') { $loadpersistent = $row['navipw']; }
									echo "<a href='".$loadpersistent."' class='panel nonpersistent main $loadthis' target='nonpersistent'>".$linkto."</a>";
									} else {
									echo "<a href='#".$navtitle."' class='main panel persistent $loadthis'>".$linkto."</a>";
									}
									$linkcount++;
									}
								}
							}
						}
					} catch(PDOException $e)
						{
						echo $e->getMessage();
						}
				}
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
	<?php if($TOTALROOMS>0 && $TOTALALLOWEDROOMS>0){
		echo "<span id='addonlinkspages'>";
		$addontype = 'pages';
		include"./addonslinks.php";
		echo "</span>";	
		}
			try {
				$thenavitems = explode(",",$allnavitems);
				$namearray = array();
				foreach($thenavitems as $item) {
					if($item == '') { continue; }
					if(in_array($item, $namearray)) { continue; }
					array_push($namearray, $item);
					if($isMobile == "1") {
						$sql = "SELECT * FROM navigation WHERE navid = $item AND mobile != '' AND mobile != '0' AND persistent == '1' ";
					} else {
						$sql = "SELECT * FROM navigation WHERE navid = $item AND persistent == '1' ";
					}
					foreach ($configdb->query($sql) as $row) {
						$navtitle = $row['navname'];
						if($WANCONNECTION == '1' && isset($row['navipw']) && $row['navipw'] != '' && $row['navipw'] != '0' && $row['navipw'] != '1') { 
							$navdestination = $row['navipw']; 
						} elseif(isset($row['navip'])) { 
							$navdestination = $row['navip']; 
						}	
						if($isMobile == "1") {
							if(isset($row['mobile']) && $row['mobile'] != '') { $mobiledestination = $row['mobile']; }
							if($mobiledestination != '0') {
								if($mobiledestination != "1") {
									if($WANCONNECTION == '1' && isset($row['mobilew']) && $row['mobilew'] != '' && $row['mobilew'] != '0' && $row['mobilew'] != '1') { 
										$navdestination = $row['mobilew'];
									} else {
										$navdestination = $mobiledestination; 
									}	
								}
							}
						}
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
			} catch(PDOException $e) {
				echo $e->getMessage();
			}
		if($totalnonpersistentnav > "0") {?>
		<div id="nonpersistent" class="item">
			<div class="content">
				<iframe id='nonpersistentf' class='nonpersistent' name='nonpersistent' width='100%' height='100%' scrolling='auto'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>
		<?php }
		if($SETTINGSACCESS == "1" && $isMobile == "0") {?>
		<div id="Settings" class="item">
			<div class="content">
				<iframe id='Settingsf' class='Settings' data-src='./settings.php' width='100%' height='100%' scrolling='no'> Sorry your browser does not support frames or is currently not set to accept them.</iframe>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<script>
	<?php if($TOTALALLOWEDROOMS==0){ ?>
		$(document).ready(function() {
			<?php if($loadpersistent != '0') { ?>
				var iframepersist = document.getElementById('nonpersistentf');
				iframepersist.src = '<?php echo $loadpersistent; ?>';
				$('#wrapper').scrollTo(iframepersist, 0);
			<?php } else {?>
				var iframe = document.getElementById('<?php echo $selectedpanel;?>');
				$('#wrapper').scrollTo(iframe, 0);
			<?php } ?>
			setTimeout(func, 4500);
			function func() {
				document.getElementById('loading').style.display='none';
			}
		});
	<?php } else {?>
		$(document).ready(function() {
				var today = new Date();
				var expire = new Date();
				expire.setTime(today.getTime() + 3600000*24*5);
				document.cookie="sleeping=0;expires="+expire.toGMTString()+";path=/";		
		<?php
			foreach ($roomgroupaccessarray as $i) {
				$thedelay = 1000 + (180 * $i);
				echo "
				var roomcheckcount$i = 0;
				function refreshRoom$i() {
					var isSleeping = 0;
					if (document.cookie.indexOf(\"sleeping\") >= 0) {
						var isSleeping = getCookie('sleeping');
					}
					roomcheckcount$i++;
					if(isSleeping == 0 || roomcheckcount$i > 100) {
						$(\"#roominfo$i\").load(\"./getaddons.php?room=$i\", function () {
							reSizeRoomInfo();
							roomcheckcount$i = 0;
						});
					}
					refreshtheroom$i = setTimeout(refreshRoom$i, 2500);					
				}
				refreshtheroom$i = setTimeout(refreshRoom$i, $thedelay);";
			}
		?>
			setTimeout(func, 4500);
			function func() {
				document.getElementById('loading').style.display='none';
			}
		});
	<?php } ?>

function getCookie(cname)
{
var name = cname + "=";
var ca = document.cookie.split(';');
for(var i=0; i<ca.length; i++) 
  {
  var c = ca[i].trim();
  if (c.indexOf(name)==0) return c.substring(name.length,c.length);
  }
return "";
}
</script>
<div id="modal"></div>
</body>
</html>