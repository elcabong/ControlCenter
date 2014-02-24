<?php
if (file_exists('../sessions/firstrun.php') || !file_exists('../sessions/config.db')) { header('Location: ../servercheck.php');exit; }
$configdb = new PDO('sqlite:../sessions/config.db');

//if(!empty($_GET) && strpos($_SERVER['HTTP_REFERER'],'settings') && !isset($_GET['setup'])){
if(!empty($_GET) && !isset($_GET['setup'])){
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
		if(isset($_GET['navgroup']) && $_GET['navgroup'] != '0') {
			$thegrouptodelete = $_GET['navgroup'];
			$configdb->exec("DELETE FROM navigation WHERE navgroup = $thegrouptodelete");
		} else {
			$configdb->exec("DELETE FROM navigation WHERE navid = $theidtodelete");
		}
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
				$configdb->exec("UPDATE users SET $vararraye[0]=$valuearraye[0],$vararraye[1]=$valuearraye[1],$vararraye[2]=$valuearraye[2],$vararraye[3]=$valuearraye[3],$vararraye[4]=$valuearraye[4],$vararraye[5]=$valuearraye[5],$vararraye[6]=$valuearraye[6],$vararraye[7]=$valuearraye[7] WHERE userid=$section_unique");
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
								}
							} catch(PDOException $e) {
								echo $e->getMessage();
							}			
							
							if ($row5 != '') {
									$i=0;
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
				$configdb->exec("UPDATE navigation SET $vararraye[0]=$valuearraye[0],$vararraye[1]=$valuearraye[1],$vararraye[2]=$valuearraye[2],$vararraye[3]=$valuearraye[3],$vararraye[4]=$valuearraye[4],$vararraye[5]=$valuearraye[5] WHERE navid=$section_unique");
			}
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
require './config.php';
if ($authsecured && (!isset($_SESSION["$authusername"]) || !$_SESSION["$authusername"] || $_SESSION["$authusername"] != $authusername ) || $SETTINGSACCESS != "1") {
    header("Location: login.php");
    exit;}}
require './addons.php';	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Settings</title>
  <script src="../js/jquery-1.10.1.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../css/UI/jquery-ui-1.8.14.custom.css">
  <link href="../css/room.css" rel="stylesheet" type="text/css">
  <link href="../css/settings.css" rel="stylesheet" type="text/css">
  <link href="../css/chosen.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="../css/jquery.pnotify.default.css">
  <script src="../js/jquery.pnotify.js" type="text/javascript"></script>
  <script src="../js/dropzone.js"></script>
  <script src="../js/chosen.jquery.min.js"></script>
  <script src="../js/chosen.proto.min.js"></script>
	<script type="text/javascript">
		if (window.navigator.standalone) {
			var iWebkit;if(!iWebkit){iWebkit=window.onload=function(){function fullscreen(){var a=document.getElementsByTagName("a");for(var i=0;i<a.length;i++){if(a[i].className.match("noeffect")){}else{a[i].onclick=function(){window.location=this.getAttribute("href");return false}}}}function hideURLbar(){window.scrollTo(0,0.9)}iWebkit.init=function(){fullscreen();hideURLbar()};iWebkit.init()}}
		}
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
	</script>
</head>
<body style="overflow: hidden;">
  <center>
    <div style="width:90%; height:95%;" class="widget">
      <div class="widget-head">
        <h3>Settings</h3>
      </div>
          <br />
      <div id="slider">
        <ul class="navigation">
          <li><a href="#ABOUT">About</a></li>
          <li><a href="#ROOMS" <? if(!isset($roomsareset)) { echo "id='blink'"; } ?>>Room List</a></li>
         <li><a href="#ROOMGROUPS">Room Groups</a></li> 
         <li><a href="#NAVIGATION" <? if(isset($roomsareset) && !isset($navisset)) { echo "id='blink'"; } ?>>Navigation</a></li>
		<li><a href="#USERS" <? if($totalusernum==0 && isset($roomsareset) && isset($navisset)) { echo "id='blink'"; } ?>>User List</a></li>		 
 	  </ul>
      <!-- element with overflow applied -->
        <div class="scroll">
          <!-- the element that will be scrolled during the effect -->
          <div class="scrollContainer">
            <div id="ABOUT" class="panel">
              <table cellpadding="5px">
                <tr>
                  <td colspan="2">
                    <p align="justify" style="width: 500px;#padding-bottom: 20px;">
                      Control Center is a Web-based Service Organiser, inspired by MediaFrontPage. You can think of this as the universal remote that ties your individual home media and automation softare/hardware together.
						<br><br>
					I have and will continue to put a bit of time and effort into this project.  If you find it useful, please consider buying me a tasty snack or refreshing beverage for brain power by donating below.
					</p>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" style="text-align:center;">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="ZM5MSNYFM657A">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
                 </td>                    
                </tr>
                <tr align="left">
                  <td>Forum</td><td><a href="http://forum.xbmc.org/showthread.php?tid=176684" target='_blank'>Thread on XBMC Forum</a></td>
                </tr>
                <tr align="left">
                  <td>Source</td><td><a href="https://github.com/elcabong/ControlCenter" target='_blank'>https://github.com/elcabong/ControlCenter</a></td>
                </tr>
                <tr align="left">
                  <td>Last Updated</td>
                  <td>
                  <?php
						$filename = '../.git/logs/HEAD';
						$branchname = "";
						if (file_exists($filename)) {
							$stringfromfile = file('../.git/HEAD', FILE_USE_INCLUDE_PATH);
							$stringfromfile = $stringfromfile[0]; //get the string from the array
							$explodedstring = array_filter(array_map('trim',explode('/',$stringfromfile)));
							$branchname = $explodedstring[2]; //get the one that is always the branch name
						}
					if(isset($branchname) && $branchname != "master") { $github = new GitHub('elcabong','ControlCenter', $branchname); } else { $github = new GitHub('elcabong','ControlCenter'); }
                    $date = $github->getInfo();
					if(isset($branchname) && $branchname != "master") {
					   echo $date['commit']['commit']['author']['date'];
					} else {
                       echo $date['pushed_at'];
					}
                  ?>
                  </td>
                </tr>
                <tr align="left">
                  <td>
                    <?php
					$commitNo = '';
					$currentVersion = '';
                      $commit = $github->getCommits();
					  if(isset($branchname) && $branchname != "master") {
					  $commitNo = $commit['sha'];
					  } else {
                      $commitNo = $commit['0']['sha'];
					  }
						if (file_exists($filename)) {
							$data = file($filename);
							$line = $data[count($data)-1];
							$curver = explode(" ",$line);
							$currentVersion = $curver[1];
						} else {
							//$currentVersion = $config->get('version','ADVANCED');
						}
                      echo "Version </td><td><a href='https://github.com/elcabong/ControlCenter/commit/".$currentVersion."' target='_blank'>".$currentVersion.'</a>';
                      if($commitNo != $currentVersion){
                       // echo "<br><a href='#' onclick='updateVersion();' title='".$commitNo." - Description: ".$commit['0']['commit']['message']."'>***UPDATE Available***</a>";
                       echo "<br><a href='https://github.com/elcabong/ControlCenter/' title='".$commitNo." - Description: ".$commit['0']['commit']['message']."'>***UPDATE Available***Download From github here or git pull</a>";
					   }
                    ?>
                  </td>
                </tr>
              </table>
            </div>
            <div id="USERS" class="panel">
              <h3>User List</h3>
				<p align="justify" style="width: 500px;">
				    <b>Username:</b>  The username/login name for each user
	<br><br><b>Password:</b>  Optional.  if not set auth is disabled for this user
	<br><br><b>Navigation:</b>  Adds Navigation group(s) for the user which are available in the upper left menu bar.  Add the groups in the order you want them to be displayed in.
	<br><br><b>Homeroom:</b>  The default room that this user will will log into unless they logout while controlling another room (set with cookie, so device specific)
	<br><br><b>Room Group:</b> Set a configured room group for this user 
	<br><br><b>Allow:</b>  Adds access to rooms, overrides room group access
	<br><br><b>Deny:</b>  can remove access to rooms, overrides room group access and the allow option
	<br><br><b>Settings:</b>  This allows or denies the user to this settings area. DO NOT FORGET TO GIVE ACCESS TO AT LEAST 1 USER.
	<br><br><b>Icon:</b>  After users are created, drag a .jpg image into the designated area to assign each user avatar.<br>
				</p>			  
                <?php
				try {
					$sql = "SELECT * FROM rooms";
					$roomlist = '';
					foreach ($configdb->query($sql) as $row)
						{
						$roomlist .= "<option value=".$row['roomid'].">".$row['roomname']."</option>";
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
				try {
					$sql5 = "SELECT * FROM roomgroups";
					$roomgrouplist = '';
					foreach ($configdb->query($sql5) as $row5)
						{
						$roomgrouplist .= "<option value=".$row5['roomgroupid'].">".$row5['roomgroupname']."</option>";
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
							$setnavgroups = '';
							$thenavgroups = '';
							$allnavgroups = '';
							$sql4 = "SELECT * FROM navigation WHERE navgrouptitle = '1'";
							foreach ($configdb->query($sql4) as $row4) {
							$allnavgroups .= "<option value=".$row4['navgroup'].">".$row4['navname']."</option>"; }
						echo "<table id='users-new'>";
						echo "<tr><td class='title'>Username</td><td><input class='inputcheck nospaces' size='10' name='username' value=''></td>
									<td class='title'>Password</td><td><input size='10' type='password' name='password' value=''></td>
									<td class='button right'><input type='button'class='ui-button ui-widget ui-state-default ui-corner-all' value='ADD' onclick='updateSettings(\"users-new\");' /></td><tr>
								<tr><td class='title'>Homeroom</td><td><select name='homeroom'>".$roomlist."</select></td>
									<td class='title'>Navigation</td><td colspan=2><select class='chosen-select multiple' id='navgroupaccessnew' data-placeholder='Add Navigation' multiple='multiple'>".$allnavgroups."</select><input size='10' class='navgroupaccessnew' type='hidden' name='navgroupaccess' value=''></td></tr>
								<tr><td class='title'>Room Group</td><td><select name='roomgroupaccess'><option selected='selected' value=''></option>".$roomgrouplist."</td>
									<td class='title'>Allow</td><td colspan=2><select class='chosen-select multiple' id='roomaccessnew' data-placeholder='Allow Overrides' multiple='multiple'>".$roomlist."</select><input size='10' class='roomaccessnew' type='hidden' name='roomaccess' value=''></td></tr>
									<tr><td class='title'>Settings</td><td><select name='settingsaccess'><option selected='selected' value='0'>Deny</option><option value='1'>Allow</option></select></td>
									<td class='title'>Deny</td><td colspan=2><select class='chosen-select multiple' id='roomdenynew' data-placeholder='Deny Overrides' multiple='multiple'>".$roomlist."</select><input size='10' class='roomdenynew' type='hidden' name='roomdeny' value=''></td></tr>";
						echo "</table>";
						echo "<br><br><br>";
				try {
					$sql = "SELECT * FROM users";
					$userid = 0;
					foreach ($configdb->query($sql) as $row)
						{
						$userid = $row['userid'];
						$thehomeroom = '';
						if(isset($row['homeroom']) && ($row['homeroom'] != '' || $row['homeroom'] != "0")) {
								$sql2 = "SELECT * FROM rooms WHERE roomid = ".$row['homeroom'];
								foreach ($configdb->query($sql2) as $row2) {
								$thehomeroom = "<option selected='selected' value=".$row2['roomid'].">".$row2['roomname']."</option>"; 
								}
						} else {
							$thehomeroom = "";
						}
						$theroomgroup = '';
						if(isset($row['roomgroupaccess']) && $row['roomgroupaccess']!='') {
							try {
								$sql2 = "SELECT * FROM roomgroups WHERE roomgroupid = ".$row['roomgroupaccess'];
								foreach ($configdb->query($sql2) as $row2) {
								$theroomgroup = "<option selected='selected' value=".$row2['roomgroupid'].">".$row2['roomgroupname']."</option>"; 
								}
							} catch(PDOException $e)
								{
								echo $e->getMessage();
								}
						} else {
							$theroomgroup = "<option selected='selected' value=''></option>";
						}						
						$theroomaccess = '';
						$theallowrooms ='';
						if(isset($row['roomaccess']) && $row['roomaccess'] != '') {
								$sql3 = "SELECT * FROM rooms WHERE roomid IN (".$row['roomaccess'].")";
								foreach ($configdb->query($sql3) as $row3) {
								$theroomaccess .= "<option selected='selected' value=".$row3['roomid'].">".$row3['roomname']."</option>"; 
								}
								$sql3 = "SELECT * FROM rooms WHERE roomid NOT IN (".$row['roomaccess'].")";
								foreach ($configdb->query($sql3) as $row3) {
								$theallowrooms .= "<option value=".$row3['roomid'].">".$row3['roomname']."</option>"; 
								}
						} else {
							$theroomaccess = '';
							$theallowrooms = '';
							$theallowrooms = $roomlist;
						}
						$theroomdeny = '';
						$thedenyrooms = '';
						if(isset($row['roomdeny']) && $row['roomdeny'] != '') {
								$sql3 = "SELECT * FROM rooms WHERE roomid IN (".$row['roomdeny'].")";
								foreach ($configdb->query($sql3) as $row3) {
								$theroomdeny .= "<option selected='selected' value=".$row3['roomid'].">".$row3['roomname']."</option>"; 
								}
								$sql3 = "SELECT * FROM rooms WHERE roomid NOT IN (".$row['roomdeny'].")";
								foreach ($configdb->query($sql3) as $row3) {
								$thedenyrooms .= "<option value=".$row3['roomid'].">".$row3['roomname']."</option>"; 
								}
						} else {
							$theroomdeny = '';
							$thedenyrooms = '';
							$thedenyrooms = $roomlist;
						}
						if(isset($row['navgroupaccess']) && $row['navgroupaccess'] != '') {
								$setnavgroups = '';
								$thenavgroupss = explode(",",$row['navgroupaccess']);					
								foreach($thenavgroupss as $x) {
									$sql4 = "SELECT * FROM navigation WHERE navgroup = $x AND navgrouptitle = '1'";								
										foreach ($configdb->query($sql4) as $row4) {
									$setnavgroups .= "<option selected='selected' value=".$row4['navgroup'].">".$row4['navname']."</option>"; 
									}
								}
								$sql4 = "SELECT * FROM navigation WHERE navgroup NOT IN (".$row['navgroupaccess'].") AND navgrouptitle = '1'";
								foreach ($configdb->query($sql4) as $row4) {
								$thenavgroups .= "<option value=".$row4['navgroup'].">".$row4['navname']."</option>"; 
								}
						} else {
							$setnavgroups = '';
							$thenavgroups = '';
							$thenavgroups = $allnavgroups;
						}
						$filename = "../media/Users/user$userid.jpg";
						if (file_exists($filename)) {
						$theuserpic = "$filename";
						} else {
						$theuserpic = "../media/Users/user-default.jpg";   
						}
						$accesstosettings = '';
						if($row['settingsaccess'] == "1") {
						$accesstosettings .= "<option selected='selected' value='1'>Allow</option><option value='0'>Deny</option>"; 
						} else {
						$accesstosettings .= "<option selected='selected' value='0'>Deny</option><option value='1'>Allow</option>"; 
						}
						echo "<div class='container'><form action='upload.php?user=$userid' class='dropzone' id='user$userid' style='position:relative;z-index:1;background-color:rgba(0,0,0,.5);color:#eee;'><input type='file' name='user$userid' /></form><span class='text'>" . $row['username'] . "</span><img src='$theuserpic' class='image' /></div>";
						echo "<table id='users-$userid'>";
						echo "<tr><td class='title'>Username</td><td><input class='inputcheck nospaces' size='10' name='username' value='" . $row['username'] . "'></td>
										<td class='title'>Password</td><td><input size='10' type='password' name='password' value='" . $row['password'] . "'></td>
										<td class='button right'><input type='button'class='ui-button ui-widget ui-state-default ui-corner-all' value='Save' onclick='updateSettings(\"users-$userid\");' /><input type='button'class='ui-button ui-widget ui-state-default ui-corner-all remove' value='Remove' onclick='deleteRecord(\"users\"," . $row['userid'] . ");' /></td></tr>
								  <tr><td class='title'>Homeroom</td><td><select name='homeroom'>".$thehomeroom.$roomlist."</select></td>
										<td class='title'>Navigation</td><td colspan=2><select class='chosen-select multiple' id='navgroupaccess$userid' data-placeholder='Add Navigation' multiple='multiple'>".$setnavgroups.$thenavgroups."</select><input size='10' class='navgroupaccess$userid' type='hidden' name='navgroupaccess' value=" . $row['navgroupaccess'] . "></td></tr>
									<tr><td class='title'>Room Group</td><td><select name='roomgroupaccess'>".$theroomgroup.$roomgrouplist."></td>
										<td class='title'>Allow</td><td colspan=2><select class='chosen-select multiple' id='roomaccess$userid' data-placeholder='Allow Overrides' multiple='multiple'>".$theroomaccess.$theallowrooms."</select><input size='10' class='roomaccess$userid' type='hidden' name='roomaccess' value=" . $row['roomaccess'] . "></td></tr>
									<tr><td class='title'>Settings</td><td><select name='settingsaccess'>".$accesstosettings."</select></td>
										<td class='title'>Deny</td><td colspan=2><select class='chosen-select multiple' id='roomdeny$userid' data-placeholder='Deny Overrides' multiple='multiple'>".$theroomdeny.$thedenyrooms."</select><input size='10' class='roomdeny$userid' type='hidden' name='roomdeny' value=" . $row['roomdeny'] . "></td></tr>";
						echo "</table><br><br><br>";
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
				?>
				<? $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
					if (false !== strpos($url,'setup')) {
						//if($totalusernum>0 && isset($roomsareset) && isset($navisset)) {
						if($totalusernum>0) {   //  add alerts to see if they want to add rooms or nav if those are note set
						echo "				<p align='justify' style='width: 450px;'>
							<b>ALERT:</b>  Ensure atleast 1 user has allow access to settings.
						<br><br><!--<b>ALERT:</b>  Please make sure your users have access to their Homeroom.  you need to 'Allow' the room, or configure and add a '<a href='#ROOMGROUPS'>Room Group</a>' to each user.  If a user has no rooms allowed, they will have a redirect loop when they try to login.-->
						<br><br><h3><a class='orange' href='../login.php?user=choose' target='_parent'>Continue to Control Center</a></h3>
						<br>
				</p>"; }}?>
			<br><br>	
            </div>


            <div id="ROOMS" class="panel">
              <h3>Room List</h3>
				<p align="justify" style="width: 500px;">
				    <b>Title:</b>  The title of the room/set of devices
			<br><b>Addons:</b>  The list of addons assignable to this room.  each addon will add any settings they need to the assigned room. (ensure the addon you want info displaying for is first in the list.  usually the mediaplayer.addon)

				<br>
				</p>
                <?php
				echo "<table id='rooms-new'>";
				echo "<tr><td class='title'>Title</td><td><input size='10' name='roomname' value=''></td><td class='button right'><input type='button'class='ui-button ui-widget ui-state-default ui-corner-all' value='Add' onclick='updateSettings(\"rooms-new\");' /></td></tr>";
						$theavailableaddons = '';
						for ($i = 0; $i < count($availableaddons); ++$i) {
								$theavailableaddons .= "<option value=".$availableaddons[$i].">".$availableaddons[$i]."</option>"; 
						}
						echo "<tr><td class='title'>Addons</td><td colspan=2><select class='chosen-select multiple' id='addonsnew' data-placeholder='Choose' multiple='multiple' onchange='addonselect('new')'>".$theavailableaddons."</select></td><input size='10' class='addonsnew' type='hidden' name='addons' value=''></td></tr>";


				//echo "<tr><td></td><td class='title'>IP1</td><td  colspan=4><input size='60' name='ip1' value=''></td></tr><tr><td></td><td class='title'>IP2</td><td colspan=4><input size='60' name='ip2' value=''></td></tr>";
				echo "</table><br><br><br>";
				try {
					$sql = "SELECT * FROM rooms";
					$roomid = 0;
					foreach ($configdb->query($sql) as $row)
						{
						$roomid = $row['roomid'];
						echo "<hr><table id='rooms-$roomid'>";
						echo "<tr><td class='title'>Room Name</td><td><input size='10' name='roomname' value='" . $row['roomname'] . "'></td>";
						echo "<td class='button right'><input type='button'class='ui-button ui-widget ui-state-default ui-corner-all' value='Save' onclick='updateSettings(\"rooms-$roomid\");' /><input type='button'class='ui-button ui-widget ui-state-default ui-corner-all remove' value='Remove' onclick='deleteRecord(\"rooms\"," . $row['roomid'] . ");' /></td></tr>";
						
						$addonids = '';
						$theenabledaddons = '';
						$enabledaddons = '';
						$sql2 = "SELECT addons FROM rooms WHERE roomid = $roomid LIMIT 1";
							foreach ($configdb->query($sql2) as $row2)
								{
									$addonid = $row2['addons'];
								//	echo $addonid."<br>";
									
									$arr = explode(",", $addonid);
									
									foreach($arr as $thearr) {
										if($thearr == '') { break; }
										$arr = explode(".", $thearr, 2);
										$classification = $arr[0];
										$title = $arr[1];
										
									//	echo $addonarray["$classification"]["$title"]['path'];
										
										$enabledaddons .= "<option selected='selected' value=".$thearr.">".$thearr."</option>"; 
										if($thearr != '') { $theenabledaddons .= $thearr.","; }
									}
								}

						$theavailableaddons = '';
						$theseenabledaddons = explode(',',$theenabledaddons);
						for ($i = 0; $i < count($availableaddons); ++$i) {
							
							if(!in_array($availableaddons[$i],$theseenabledaddons)) {
								$theavailableaddons .= "<option value=".$availableaddons[$i].">".$availableaddons[$i]."</option>"; 
							}
						
						}
						echo "<tr><td class='title'>Addons</td><td colspan=2><select class='chosen-select multiple' id='addons$roomid' data-placeholder='Choose' multiple='multiple' onchange='addonselect($roomid)'>".$enabledaddons.$theavailableaddons."</select></td><input size='10' class='addons$roomid' type='hidden' name='addons' value=" . $theenabledaddons . "></td></tr>";
						 echo "</table><table id='roomsaddons-$roomid'>";
							for ($i = 0; $i < count($theseenabledaddons); ++$i) {
									$addonid = $theseenabledaddons[$i];
									if($addonid != '') {
									
										$arr = explode(".", $addonid, 2);
										$classification = $arr[0];
										$title = $arr[1];

										$THISROOMID = $roomid;
										$sql3 = "SELECT * FROM rooms_addons WHERE roomid = $roomid AND addonid = '$addonid' LIMIT 1";
											foreach ($configdb->query($sql3) as $addonSettings)
												{
												$enabledaddonsarray["$roomid"]["$addonid"]['classification'] = $classification;
												$enabledaddonsarray["$roomid"]["$addonid"]['title'] = $title;
												$enabledaddonsarray["$roomid"]["$addonid"]['ADDONIP'] = $addonSettings['ip'];
												$enabledaddonsarray["$roomid"]["$addonid"]['MAC'] = $addonSettings['mac'];
												$enabledaddonsarray["$roomid"]["$addonid"]['setting1'] = $addonSettings['setting1'];
												$enabledaddonsarray["$roomid"]["$addonid"]['setting2'] = $addonSettings['setting2'];
												$enabledaddonsarray["$roomid"]["$addonid"]['setting3'] = $addonSettings['setting3'];
												$enabledaddonsarray["$roomid"]["$addonid"]['setting4'] = $addonSettings['setting4'];
												$enabledaddonsarray["$roomid"]["$addonid"]['setting5'] = $addonSettings['setting5'];
												$enabledaddonsarray["$roomid"]["$addonid"]['setting6'] = $addonSettings['setting6'];
												$enabledaddonsarray["$roomid"]["$addonid"]['setting7'] = $addonSettings['setting7'];
												$enabledaddonsarray["$roomid"]["$addonid"]['setting8'] = $addonSettings['setting8'];
												$enabledaddonsarray["$roomid"]["$addonid"]['setting9'] = $addonSettings['setting9'];
												$enabledaddonsarray["$roomid"]["$addonid"]['setting10'] = $addonSettings['setting10'];
												}
										echo "<input type='hidden' size='80' name='roomid' value='$roomid'>";
										echo "<input type='hidden' size='80' name='addonid' value='$addonid'>";
										include  $addonarray["$classification"]["$title"]['path'] . "settings.php";
						
									}
							}
						 echo "</table><br><br>";
						 }
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
                ?>
           </div>
			<div id="ROOMGROUPS" class="panel">
              <h3>Room Permission Groups</h3>
			    <p>Create a group of permissions for easy multiple user permissions.  Individual permissions override these.</p>			  
				<p align="justify" style="width: 350px;">
				  <b>Group Name:</b> the name of the permission group
	<br><br><b>Allow:</b>  gives this group access to the room
	<br><br><b>Deny:</b>  removes group access to this room<br>
				</p>					  
                <?php
				echo "<table id='roomgroups-new'>";
				echo "<tr><td class='title'>Group Name</td><td colspan=2><input size='20' name='roomgroupname' value=''></td><td class='button right'><input type='button'class='ui-button ui-widget ui-state-default ui-corner-all' value='ADD' onclick='updateSettings(\"roomgroups-new\");' /></td></tr>
									<tr><td class='title'>Allow</td><td colspan=3><select class='chosen-select multiple' id='roomgroupaccessnew' data-placeholder='Allow Rooms' multiple='multiple'>".$roomlist."</select><input size='10' class='roomgroupaccessnew' type='hidden' name='roomaccess' value=''></td>
									</tr><tr><td class='title'>Deny</td><td colspan=3><select class='chosen-select multiple' id='roomgroupdenynew' data-placeholder='Deny Rooms' multiple='multiple'>".$roomlist."</select><input size='10' class='roomgroupdenynew' type='hidden' name='roomdeny' value=''></td>
								</tr>";
				echo "</table><br><br><br>";
				try {
					$sql = "SELECT * FROM roomgroups";
					$roomid = 0;
					foreach ($configdb->query($sql) as $row)
						{
							$theroomaccess = '';
							$theallowrooms ='';
							if(isset($row['roomaccess']) && $row['roomaccess'] != '') {
									$sql3 = "SELECT * FROM rooms WHERE roomid IN (".$row['roomaccess'].")";
									foreach ($configdb->query($sql3) as $row3) {
									$theroomaccess .= "<option selected='selected' value=".$row3['roomid'].">".$row3['roomname']."</option>"; 
									}
									$sql3 = "SELECT * FROM rooms WHERE roomid NOT IN (".$row['roomaccess'].")";
									foreach ($configdb->query($sql3) as $row3) {
									$theallowrooms .= "<option value=".$row3['roomid'].">".$row3['roomname']."</option>"; 
									}
							} else {
								$theroomaccess = '';
								$theallowrooms = '';
								$theallowrooms = $roomlist;
							}
							$theroomdeny = '';
							$thedenyrooms = '';
							if(isset($row['roomdeny']) && $row['roomdeny'] != '') {
									$sql3 = "SELECT * FROM rooms WHERE roomid IN (".$row['roomdeny'].")";
									foreach ($configdb->query($sql3) as $row3) {
									$theroomdeny .= "<option selected='selected' value=".$row3['roomid'].">".$row3['roomname']."</option>"; 
									}
									$sql3 = "SELECT * FROM rooms WHERE roomid NOT IN (".$row['roomdeny'].")";
									foreach ($configdb->query($sql3) as $row3) {
									$thedenyrooms .= "<option value=".$row3['roomid'].">".$row3['roomname']."</option>"; 
									}
							} else {
								$theroomdeny = '';
								$thedenyrooms = '';
								$thedenyrooms = $roomlist;
							}						
							$roomid = $row['roomgroupid'];
							echo "<table id='roomgroups-$roomid'>";						
							echo "<tr><td class='title'>Group Name</td><td colspan=2><input size='20' name='roomgroupname' value='" . $row['roomgroupname'] . "'></td><td class='button right'><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Save' onclick='updateSettings(\"roomgroups-$roomid\");' /><input type='button'class='ui-button ui-widget ui-state-default ui-corner-all remove' value='Remove' onclick='deleteRecord(\"roomgroups\"," . $row['roomgroupid'] . ");' /></td></tr>
										<tr><td class='title'>Allow</td><td colspan=3><select class='chosen-select multiple' id='roomgroupaccess$roomid' data-placeholder='Allow Rooms' multiple='multiple'>".$theroomaccess.$theallowrooms."</select><input size='10' class='roomgroupaccess$roomid' type='hidden' name='roomaccess' value=" . $row['roomaccess'] . "></td>
										</tr><tr><td class='title'>Deny</td><td colspan=3><select class='chosen-select multiple' id='roomgroupdeny$roomid' data-placeholder='Deny Rooms' multiple='multiple'>".$theroomdeny.$thedenyrooms."</select><input size='10' class='roomgroupdeny$roomid' type='hidden' name='roomdeny' value=" . $row['roomdeny'] . "></td>
										</tr>";
							echo "</table><br><br><br>";
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
				?>
            </div>			
			<div id="NAVIGATION" class="panel">
              <h3>Navigation</h3>
			    <p>These links will be available in the upper left menu</p>
				<p align="justify" style="width: 500px;">
					<b>Nav Group:</b>  The Display name for the navigation group.  Will only be shown if a user has access to multiple groups.  The links must be grouped under a navigation group.
	<br><br><b>Title:</b>  The title of the link unless an icon is uploaded (see below).  Please no spaces in the title.
	<br><br><b>Full IP:</b>  The complete address to the link.  Can include username and password which is masked in the browser unless the source is viewed when those pages have already been accessed.  ie:  http://name:pass@ip:port
	<br><br><b>M IP:</b>  Adds this link to the mobile specific site. set to 1 if the ip source scales on its own, or specify the full address here of the mobile site.  ie  http://m.ip:port  or   http://ip:port/m/ 
	<br><br><b>Persistent:</b>  Persistent links will keep their frame state once loaded until individually reset (clicking on the link while the link is selected), individually unload page (click and hold the link while the link is selected) or until the whole control center is refreshed. Non-Persistent links will close the frame connection to the site when a different link is chosen (this is for security camera feeds or other highly active content you do not want running unless your viewing it)
	<br><br><b>Icon:</b>  Drag a .png image to the designated area to replace the Title in the top navigation bar   <br>
				</p>
				<?php
				try {
					$sql = "SELECT navgroup FROM navigation ORDER BY navgroup DESC LIMIT 1";
					foreach ($configdb->query($sql) as $row)
						{
							$lastnavgroup = $row['navgroup'];
							$newnavgroup = $lastnavgroup +='1';
						}
					} catch(PDOException $e)
						{
						echo $e->getMessage();
						}
				if(!isset($newnavgroup)) { $newnavgroup = 1; }
				echo "<table id='navigation-new'>";
				echo "<tr><td></td><td class='title'>Add New Navigation Group</td></tr>";
				echo "<tr><td class='title'>Nav Group</td><td><input size='40' name='navname' value=''></td><td><input size='1' type='hidden' name='navgroup' value='$newnavgroup'><input size='1' type='hidden' name='navgrouptitle' value='1'></td><td colspan='2' style='text-align:center;'><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Add' onclick='updateSettings(\"navigation-new\");' /></td></tr>";
				echo "</table><br><br>";
				try {
					$sql = "SELECT * FROM navigation WHERE navgrouptitle = '1' ORDER BY navgrouptitle ASC, navgroup ASC";
					$navid = 0;
					foreach ($configdb->query($sql) as $row)
						{
						$navid = $row['navid'];
						$navgroup = $row['navgroup'];
							echo "<br><br><table class='tabletitle' id='navigation-$navid'>";
							echo "<tr><td class='title'>Nav Group</td><td><input size='40' name='navname' value='" . $row['navname'] . "'></td>
											<td><input size='1' type='hidden' name='navgroup' value='" . $row['navgroup'] . "'><input size='1' type='hidden' name='navgrouptitle' value='" . $row['navgrouptitle'] . "'><input size='1' type='hidden' name='navip' value=''><input size='1' type='hidden' name='mobile' value=''></td>
											<td colspan='2' style='text-align:center;'><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Save' onclick='updateSettings(\"navigation-$navid\");' /><input type='button'class='ui-button ui-widget ui-state-default ui-corner-all remove' value='Remove' onclick='deleteRecord(\"navigation\"," . $navid . "," . $navgroup . " );' /></td></tr>";
							echo "</table><br><br>";
								echo "<table id='navigation-new$navid'>";
								echo "<tr><td></td><td></td><td class='title'>Title</td><td><input class='inputcheck nospaces' size='20' name='navname' value=''></td><td class='title'>Persistent</td><td><select name='persistent'><option selected='selected' value='1'>Yes</option><option value='0'>No</option></select></td><td><input size='1' type='hidden' name='navgroup' value='" . $row['navgroup'] . "'></td><td colspan='2' style='text-align:center;'><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Add' onclick='updateSettings(\"navigation-new$navid\");' /></td></tr>";
								echo "<tr><td><img src='../media/Programs/ProgramDefault.png' height='50'><img src='../media/Programs/ProgramDefault.png' height='50'></td><td><img src='../media/Programs/ProgramDefault.png' height='50'></td><td class='title'>Full IP</td><td colspan=6><input size='60' name='navip' value=''></td></td><td><input size='1' type='hidden' name='navgrouptitle' value=''></td></tr>";
								echo "<tr><td></td><td></td></td><td class='title'>M IP</td><td colspan=6><input size='60' name='mobile' value=''></td></tr>";
								echo "</table><br><br>";
							$sql = "SELECT * FROM navigation WHERE navgroup = '$navgroup' AND navgrouptitle != '1'";
							$navid = 0;
							foreach ($configdb->query($sql) as $row)
								{
								$navid = $row['navid'];
									$filename = "../media/Programs/" . $row['navname'] . ".png";
									if (file_exists($filename)) {
									$theprogrampic = "$filename";
									} else {
									$theprogrampic = "../media/Programs/ProgramDefault.png";
									}
									$persistentnavigation = '';
									if($row['persistent'] == "1") {
									$persistentnavigation .= "<option selected='selected' value='1'>Yes</option><option value='0'>No</option>"; 
									} else {
									$persistentnavigation .= "<option selected='selected' value='0'>No</option><option value='1'>Yes</option>"; 
									}
								echo "<br><table id='navigation-$navid'>";
								echo "<tr><td></td><td></td><td class='title'>Title</td><td><input class='inputcheck nospaces' size='20' name='navname' value='" . $row['navname'] . "'></td><td class='title'>Persistent</td><td><select name='persistent'>".$persistentnavigation."</select></td><td><input size='1' type='hidden' name='navgroup' value='" . $row['navgroup'] . "'></td><td colspan='2' style='text-align:center;'><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Save' onclick='updateSettings(\"navigation-$navid\");' /><input type='button'class='ui-button ui-widget ui-state-default ui-corner-all remove' value='Remove' onclick='deleteRecord(\"navigation\"," . $row['navid'] . ");' /></td></tr>";
								echo "<tr><td><form action=\"upload.php?program=" . $row['navname'] . "\" class='dropzone' id='program" . $row['navname'] . "' style='position:relative;z-index:1;background-color:rgba(0,0,0,.5);color:#eee;width:100px;'></form></td><td><img src=" . $theprogrampic ." style='position:relative;height:50px;'></td><td class='title'>Full IP</td><td colspan=6><input size='80' name='navip' value=" . $row['navip'] . "></td></td><td><input size='1' type='hidden' name='navgrouptitle' value='" . $row['navgrouptitle'] . "'></td></tr>";
								echo "<tr><td></td><td></td></td><td class='title'>M IP</td><td colspan=6><input size='80' name='mobile' value=" . $row['mobile'] . "></td></tr>";
								echo "</table><br><br>";
								}
						}		
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
                ?>
           </div>
          </div>
        </div>
      </div>
    </div>  
  </center>
  <script type="text/javascript" src="../js/settings.js"></script>  
</body>
</html>
<? } ?>