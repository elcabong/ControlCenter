<?php
require_once "startsession.php";
if(isset($authusername)) {
	$username = $authusername; 
} elseif(isset($_SESSION['username'])) {
	$username = $_SESSION['username'];
}
if(isset($username)) {
	$log->LogINFO("User $username loaded " . basename(__FILE__));
} else {
	$log->LogINFO("User NOUSERNAME loaded " . basename(__FILE__));
}
if (!file_exists("$INCLUDES/sessions/config.db")) {
	header('Location: ../servercheck.php');
	exit;
}
$configdb = new PDO("sqlite:$INCLUDES/sessions/config.db");
$linkto = "0";
if(isset($_GET['linkto'])){
	$linkto = $_GET['linkto'];
}
//if(!empty($_GET) && strpos($_SERVER['HTTP_REFERER'],'settings') && !isset($_GET['setup'])){
if(!empty($_GET) && !isset($_GET['setup']) && !isset($_GET['linkto'])){
	//if there is no section parameter, we will not do anything.
  if(isset($_GET['remove']) && $_GET['remove'] == 'yes' && isset($_GET['table']) && $_GET['table'] != '' && isset($_GET['rowid']) && $_GET['rowid'] != ''){
	$theidtodelete = $_GET['rowid'];
	if($_GET['table'] == 'users') {
		$configdb->exec("DELETE FROM users WHERE userid = $theidtodelete");
	} elseif($_GET['table'] == 'rooms') {
		$configdb->exec("DELETE FROM rooms WHERE roomid = $theidtodelete");
		$configdb->exec("DELETE FROM rooms_addons WHERE roomid = $theidtodelete");
	} elseif($_GET['table'] == 'roomgroups') {
		$configdb->exec("DELETE FROM roomgroups WHERE roomgroupid = $theidtodelete");
	} elseif($_GET['table'] == 'navigation') {
	/*	if(isset($_GET['navgroup']) && $_GET['navgroup'] != '0') {
			$thegrouptodelete = $_GET['navgroup'];
			$configdb->exec("DELETE FROM navigation WHERE navgroup = $thegrouptodelete");
		} else {*/
			$configdb->exec("DELETE FROM navigation WHERE navid = $theidtodelete");
		//}
	} elseif($_GET['table'] == 'navigationgroups') {
			$configdb->exec("DELETE FROM navigationgroups WHERE navgroupid = $theidtodelete");
	}
	echo true;
	return true;

	//if there is no section parameter, we will not do anything.
  } elseif(!isset($_GET['section'])){
    echo false; return false;
  } else {
    $section_name = $_GET['section'];
    unset($_GET['section']);     //Unset section so that we can use the GET array to manipulate the other parameters in a foreach loop.
	$section_namet = explode("-",$section_name);
	$section_name = $section_namet[0];
	$section_unique = $section_namet[1];
	$vararray = '';
	$valuearray = '';
	if (!empty($_GET)){
      foreach ($_GET as $var => $value){
		  //Here we go through all $_GET variables and add the values one by one.
			$var = urlencode($var);
			$value = ltrim($value, ',');
			$vararray .= $var.",,";
			$valuearray .= "'".$value."',,";
	  }
	}
	$vararray = rtrim($vararray, ',,');
	$valuearray = rtrim($valuearray, ',,');
	$vararraye = explode(',,',$vararray);
	$valuearraye = explode(',,',$valuearray);
	$vararray = str_replace(",,",",",$vararray);
	$valuearray = str_replace(",,",",",$valuearray);	
	try {
		if($section_name == "users") {
			if(strstr($section_unique,'new')) {
				$configdb->exec("INSERT INTO users ($vararray) VALUES ($valuearray)");
			} else {
				$configdb->exec("UPDATE users SET $vararraye[0]=$valuearraye[0],$vararraye[1]=$valuearraye[1],$vararraye[2]=$valuearraye[2],$vararraye[3]=$valuearraye[3],$vararraye[4]=$valuearraye[4],$vararraye[5]=$valuearraye[5],$vararraye[6]=$valuearraye[6],$vararraye[7]=$valuearraye[7],$vararraye[8]=$valuearraye[8] WHERE userid=$section_unique");
			}
		} else if($section_name == "rooms") {
			if(strstr($section_unique,'new')) {
				$configdb->exec("INSERT INTO rooms ($vararray) VALUES ($valuearray)");
						foreach ($configdb->query("SELECT max(roomid) FROM rooms LIMIT 1") as $row15) {
							$section_unique = "$row15[0]";
						}				
			} else {
				$configdb->exec("UPDATE rooms SET $vararraye[0]=$valuearraye[0],$vararraye[1]=$valuearraye[1] WHERE roomid=$section_unique");
				}
				
				$checkthisshit = explode(',',ltrim(rtrim($valuearraye[1], "'"), "'"));
				foreach($checkthisshit as $thisvalue) {
					if($thisvalue != '') {
						$row5 = '';
							try {
								foreach ($configdb->query("SELECT rooms_addonsid FROM rooms_addons WHERE roomid = '$section_unique' AND addonid = '$thisvalue' LIMIT 1") as $row5) {
									//print_r($row5);
								}
							} catch(PDOException $e) {
								echo $e->getMessage();
							}			
							
							if ($row5 != '') {
									//echo 'entry Found'; 
							} else {
								//echo 'entry NOT Found, try to create';
								$configdb->exec("INSERT INTO rooms_addons (roomid,addonid) VALUES ($section_unique,'$thisvalue')");
							}				
					}
				}
		} else if($section_name == "roomsaddons") {
									/*		echo "<pre>";
									print_r($vararraye);
									echo "</pre>";
											echo "<pre>";
									print_r($valuearraye);
									echo "</pre>";							
*/
						$row5 = '';
							try {
								foreach ($configdb->query("SELECT rooms_addonsid FROM rooms_addons WHERE roomid = $valuearraye[0] AND addonid = $valuearraye[1] LIMIT 1") as $row5) {
									//print_r($row5);
									if ($row5 != '') {
											$i=0;
											$roomaddonsid = $row5['rooms_addonsid'];
											$setthis = '';
											foreach($vararraye as $item) {
												$setthis .= "$item=$valuearraye[$i]".",";
												$i++;
											}
											$setthis = rtrim($setthis, ',');
											//echo $setthis;
											//echo 'entry Found';
											$configdb->exec("UPDATE rooms_addons SET $setthis WHERE roomid = $valuearraye[0] AND addonid = $valuearraye[1]");
									} else {
											$i=0;
											$setthisvar = '';
											$setthisval = '';
											foreach($vararraye as $item) {
												$setthisvar .= $item.",";
												$setthisval .= $valuearraye[$i].",";
												$i++;
											}
											$setthisvar = rtrim($setthisvar, ',');
											$setthisval = rtrim($setthisval, ',');
										//echo 'entry NOT Found, try to create';
										$configdb->exec("INSERT INTO rooms_addons ($setthisvar) VALUES ($setthisval)");
									}				

	
								}
							} catch(PDOException $e) {
								echo $e->getMessage();
							}




		
		} else if($section_name == "roomgroups") {
			if(strstr($section_unique,'new')) {
				$configdb->exec("INSERT INTO roomgroups ($vararray) VALUES ($valuearray)");
			} else {
				$configdb->exec("UPDATE roomgroups SET $vararraye[0]=$valuearraye[0],$vararraye[1]=$valuearraye[1],$vararraye[2]=$valuearraye[2] WHERE roomgroupid=$section_unique");
			}
		} else if($section_name == "navigation") {
			if(strstr($section_unique,'new')) {
				$configdb->exec("INSERT INTO navigation ($vararray) VALUES ($valuearray)");
			} else {
				$configdb->exec("UPDATE navigation SET $vararraye[0]=$valuearraye[0],$vararraye[1]=$valuearraye[1],$vararraye[2]=$valuearraye[2],$vararraye[3]=$valuearraye[3],$vararraye[4]=$valuearraye[4],$vararraye[5]=$valuearraye[5],$vararraye[6]=$valuearraye[6] WHERE navid=$section_unique");
			}
		} else if($section_name == "navgroups") {
			if(strstr($section_unique,'new')) {
				$configdb->exec("INSERT INTO navigationgroups ($vararray) VALUES ($valuearray)");
			} else {
				$configdb->exec("UPDATE navigationgroups SET $vararraye[0]=$valuearraye[0],$vararraye[1]=$valuearraye[1] WHERE navgroupid=$section_unique");
			}
		} else if($section_name == "settings") {
			$configdb->exec("UPDATE settings SET $vararraye[0]=$valuearraye[0],$vararraye[1]=$valuearraye[1],$vararraye[2]=$valuearraye[2],$vararraye[3]=$valuearraye[3] WHERE settingid=$section_unique");
		}
	} catch(PDOException $e)
		{
		echo $e->getMessage();
		}
	echo true;
	return true;
  }
} else {
require '../lib/class.github.php';
	$totalusernum = 0;
    $sql = "SELECT userid FROM users LIMIT 1";
    foreach ($configdb->query($sql) as $row)
        {
		if(isset($row['userid'])) {
		$totalusernum ++;
        } }
    $sql = "SELECT roomid FROM rooms LIMIT 1";
    foreach ($configdb->query($sql) as $row)
        {
		if(isset($row['roomid'])) { $roomsareset = 1; }
        }
    $sql = "SELECT navid FROM navigation LIMIT 1";
    foreach ($configdb->query($sql) as $row)
        {
		if(isset($row['navid'])) { $navisset = 1; }
        }
if($totalusernum != 0 && !isset($_GET['setup'])) {
	require('startsession.php');
	require("$INCLUDES/includes/config.php");
	require_once "$INCLUDES/includes/auth.php";
}
require_once "$INCLUDES/includes/addons.php";
$getinfo = "";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<?php
	if(isset($_GET['setup'])){ ?>
  <title>First Time Configuration</title>	
	<?php } else { ?>
  <title>Settings</title>
  <?php } ?>
  <script src="../js/jquery-1.10.1.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../css/UI/jquery-ui-1.8.14.custom.css">
  <link href="../css/room.css" rel="stylesheet" type="text/css">
  <link href="../css/chosen.css" rel="stylesheet" type="text/css">
  <link href="../css/settings.css?2" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="../css/jquery.pnotify.default.css">
  <script src="../js/jquery.pnotify.js" type="text/javascript"></script>
  <script src="../js/dropzone.js"></script>
  <script src="../js/chosen.jquery.min.js"></script>
  <script src="../js/chosen.proto.min.js"></script>
  <script type="text/javascript">
		if (window.navigator.standalone) {
			var iWebkit;if(!iWebkit){iWebkit=window.onload=function(){function fullscreen(){var a=document.getElementsByTagName("a");for(var i=0;i<a.length;i++){if(a[i].className.match("noeffect")){}else{a[i].onclick=function(){window.location=this.getAttribute("href");return false}}}}function hideURLbar(){window.scrollTo(0,0.9)}iWebkit.init=function(){fullscreen();hideURLbar()};iWebkit.init()}}
		}
		//$('.chosen-choices').sortable("serialize");
	</script>		
	<script type="text/javascript">
		function Settingswakemachine(mac,machinename) {

		var count=totalcount=20;

		var counter=setInterval(timer, 1000); //1000 will  run it every 1 second

		function timer()
		{
		  count=count-1;
		  if (count <= 0)
		  {
			 clearInterval(counter);
			 //counter ended, do something here
			 return;
		  }
			$.pnotify({
				pnotify_title: 'WOL Sent to '+decodeURIComponent(machinename)+' This page will refresh in '+count+' seconds.',
				pnotify_opacity: .75,
				pnotify_delay: 995,
				pnotify_animation:"none"
			});
		}
			$.ajax({
				   type: "POST",
				   url: "wol-check.php?m="+mac+"",
				   data: 0, // data to send to above script page if any
				   cache: false,
				   success: function(response)
				{
					// need to retry ping until successful or hit a set limit, then display none
					var timeout = totalcount+"000";
					setTimeout(func1, timeout);
					function func1() {
					window.location.href += "#ROOMS";
						window.location.reload(true);
						//document.getElementById("Settingsf").contentWindow.location.hash = "#ROOMS";
						//document.getElementById('loading').style.display='none';
					}
			   },
				error: function(response) {
				$.pnotify({
				  pnotify_title: 'Error!',
				  pnotify_text: "Could Not Send WOL Packet, or there was an error communicating with destination machine",
				  pnotify_type: 'error'
			  });
				}
			});				
		}
		
$(document).ready(function() {
  $(".inputcheck.nospaces").keyup(function(){
        var t = $(this);
		if( !/[^a-zA-Z0-9]/.test( t.val() ) && t.val().length > 2) {
		t.css({'background-color' : 'rgb(224, 255, 224)'});
		} else {
		t.css({'background-color' : 'rgb(255, 204, 207)'});
		}
  });
});

	$(function(){
		document.oncontextmenu = function() {return false;};
	});
	</script>
</head>
<body style="overflow: hidden;color:#666;">
<center>
<div style="width:100%; height:100%;position:relative;" class="widget">
	<div class="widget-head">
	<?php	if(isset($_GET['setup'])){ ?>
	  <h3>First Time Configuration</h3>	
		<?php } else { ?>
	  <h3>Settings</h3>
	  <?php } ?>	  
	</div><br />
	<div id="slider">
		<ul class="navigation">
			<li><a class="settings<?php if($linkto === "0" || $linkto === "About") { echo " selected"; }?>" section="About" href="#About">About</a></li>|
			<li><a class="settings<?php if($linkto === "Settings") { echo " selected"; }?>" section="Settings" href="#SETTINGS">Settings</a></li>|
			<li><a class="settings<?php if($linkto === "Rooms") { echo " selected"; }?>" section="Rooms" href="#ROOMS" <?php if(!isset($roomsareset)) { echo "id='blink'"; } ?>>Room List</a></li>
			<li><a class="settings<?php if($linkto === "Roomgroups") { echo " selected"; }?>" section="Roomgroups" href="#ROOMGROUPS">Room Groups</a></li>|
			<li><a class="settings<?php if($linkto === "Navigation") { echo " selected"; }?>" section="Navigation" href="#NAVIGATION" <?php if(isset($roomsareset) && !isset($navisset)) { echo "id='blink'"; } ?>>Applications</a></li>
			<li><a class="settings<?php if($linkto === "Navigationgroups") { echo " selected"; }?>" section="Navigationgroups" href="#NAVIGATIONGROUPS" <?php if(isset($roomsareset) && !isset($navisset)) { echo "id='blink'"; } ?>>App Groups</a></li>|
			<li><a class="settings<?php if($linkto === "Users") { echo " selected"; }?>" section="Users" href="#USERS" <?php if($totalusernum==0 && isset($roomsareset) && isset($navisset)) { echo "id='blink'"; } ?>>User List</a></li>		 
		</ul>
				<?php $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
					if (false !== strpos($url,'setup')) {
						if($totalusernum>0) {
							echo "<p align='justify' style='width: 450px;'>
								<b class='orange'>ALERT:</b>  Ensure at least 1 user has allow access to settings.
							<br><h3><a class='orange' href='../login.php?user=choose' target='_parent'>>>> Continue to Control Center <<<</a></h3>
							</p>"; 
						}
					}
				?>
        <div class="scroll">
			<div id="info">
				<a href="#" class='showhidebutton orange'>info</a><br>
				<div class="scrollContainer">
					<?php $getinfo = "yes"; include "$INCLUDES/includes/settings-sections.php"; $getinfo = "";?>
				</div>
			</div>			  
			<div class="scrollContainer">
				<?php include "$INCLUDES/includes/settings-sections.php";?>
			</div>
		</div>
	</div>
</div>
</center>
<script type="text/javascript" src="../js/settings.js?<?php echo date ("m/d/Y-H.i.s", filemtime('../js/settings.js'));?>"></script>
<script>
$( "a.settings" ).click(function() {
	var linkto = $(this).attr("section");
	var url = window.location.href;
	var goingto = url.split('#')[0];
	var goingto = url.split('?')[0];
	
	 window.location.href = goingto + "?linkto=" + linkto;
	event.preventDefault();
});
</script>	
</body>
</html>
<?php } ?>