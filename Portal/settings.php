<?php
$configdb = new PDO('sqlite:../sessions/config.db');

if(!empty($_GET) && strpos($_SERVER['HTTP_REFERER'],'settings')){
  //if there is no section parameter, we will not do anything.
  if(!isset($_GET['section'])){
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
			$value = $value;
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
			if($section_unique == "new") {
				$configdb->exec("INSERT INTO users ($vararray) VALUES ($valuearray)");
			} else {
				$configdb->exec("UPDATE users SET $vararraye[0]=$valuearraye[0],$vararraye[1]=$valuearraye[1],$vararraye[2]=$valuearraye[2],$vararraye[3]=$valuearraye[3],$vararraye[4]=$valuearraye[4],$vararraye[5]=$valuearraye[5],$vararraye[6]=$valuearraye[6] WHERE userid=$section_unique");
			}
		} else if($section_name == "rooms") {
			if($section_unique == "new") {
				$configdb->exec("INSERT INTO rooms ($vararray) VALUES ($valuearray)");
			} else {
				$configdb->exec("UPDATE rooms SET $vararraye[0]=$valuearraye[0],$vararraye[1]=$valuearraye[1],$vararraye[2]=$valuearraye[2],$vararraye[3]=$valuearraye[3] WHERE roomid=$section_unique");
			}
		} else if($section_name == "roomgroups") {
			if($section_unique == "new") {
				$configdb->exec("INSERT INTO roomgroups ($vararray) VALUES ($valuearray)");
			} else {
				$configdb->exec("UPDATE roomgroups SET $vararraye[0]=$valuearraye[0],$vararraye[1]=$valuearraye[1],$vararraye[2]=$valuearraye[2] WHERE roomgroupid=$section_unique");
			}
		} else if($section_name == "navigation") {
			if($section_unique == "new") {
				$configdb->exec("INSERT INTO navigation ($vararray) VALUES ($valuearray)");
			} else {
				$configdb->exec("UPDATE navigation SET $vararraye[0]=$valuearraye[0],$vararraye[1]=$valuearraye[1],$vararraye[2]=$valuearraye[2],$vararraye[3]=$valuearraye[3],$vararraye[4]=$valuearraye[4] WHERE navid=$section_unique");
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
    $sql = "SELECT * FROM users LIMIT 1";
    foreach ($configdb->query($sql) as $row)
        {
		if(isset($row['userid'])) {
		$totalusernum ++;
        } }
    $sql = "SELECT * FROM rooms LIMIT 1";
    foreach ($configdb->query($sql) as $row)
        {
		if(isset($row['roomid'])) /*{
			if(!$_GET['setup']) {
				header("Location: index.php");
				exit; 
			} else */{ $roomsareset = 1; }
        }
if($totalusernum != 0 && !isset($_GET['setup'])) {
require './config.php';
if ($authsecured && (!isset($_SESSION["$authusername"]) || !$_SESSION["$authusername"] || $_SESSION["$authusername"] != $authusername )) {
    header("Location: login.php");
    exit;}}
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
  <script src="../js/ui.dropdownchecklist-1.4-min.js"></script>
	<script type="text/javascript">
		if (window.navigator.standalone) {
			var iWebkit;if(!iWebkit){iWebkit=window.onload=function(){function fullscreen(){var a=document.getElementsByTagName("a");for(var i=0;i<a.length;i++){if(a[i].className.match("noeffect")){}else{a[i].onclick=function(){window.location=this.getAttribute("href");return false}}}}function hideURLbar(){window.scrollTo(0,0.9)}iWebkit.init=function(){fullscreen();hideURLbar()};iWebkit.init()}}
		}
		
function resetpage() {
				setTimeout(function(){
				alert('fired');
				var settings = $("#Settings 1")[0].GetAttribute('src');    //document.getElementById("Settings 1");
				alert(settings);
				document.getElementById("#Settings 1").contentDocument.location.reload(true);
				}, 1500);
}				
	</script>
</head>
<body style="overflow: hidden;">
  <center>
    <div style="width:90%; height:95%;" class="widget">
      <div class="widget-head">
        <h3>Settings</h3><a onclick="resetpage()">Reset me</a>
      </div>
          <br />
      <div id="slider">
        <ul class="navigation">
          <li><a href="#ABOUT">About</a></li>
        <!--  <li><a href="#GLOBAL">General</a></li>
         --> <li><a href="#USERS" <? if($totalusernum==0) { echo "id='blink'"; } ?>>User List</a></li>
          <li><a href="#ROOMS" <? if(!isset($roomsareset) && $totalusernum>0) { echo "id='blink'"; } ?>>Room List</a></li>
         <li><a href="#ROOMGROUPS">Room Groups</a></li> 
         <li><a href="#NAVIGATION">Navigation</a></li>
 	  </ul>
      <!-- element with overflow applied -->
        <div class="scroll">
          <!-- the element that will be scrolled during the effect -->
          <div class="scrollContainer">
            <div id="ABOUT" class="panel">
              <table cellpadding="5px">
                <tr>
                 <?// <img src="media/mfp.png" /> ?>
                </tr>
                <tr>
                  <td colspan="2">
                    <p align="justify" style="width: 500px;padding-bottom: 20px;">
                      Control Center is a HTPC Web Program Organiser built on a stripped down version of MediaFrontPage. You can think of this as the universal remote that ties your individual home media and automation softare/hardware together.
                    </p>
                    <?/*<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                      <input type="hidden" name="cmd" value="_s-xclick">
                      <input type="hidden" name="hosted_button_id" value="D2R8MBBL7EFRY">
                      <input type="image" src="https://www.paypalobjects.com/en_AU/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
                      <img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
                    </form>*/?>
                  </td>
                </tr>
                <tr align="left">
                  <td>Forum</td><td><a href="#">no thread yet</a></td>
                </tr>
                <tr align="left">
                  <td>Source</td><td><a href="https://github.com/elcabong/MediaCenter-Portal" target='_blank'>https://github.com/elcabong/MediaCenter-Portal</a></td>
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
					if(isset($branchname) && $branchname != "master") { $github = new GitHub('elcabong','MediaCenter-Portal', $branchname); } else { $github = new GitHub('elcabong','MediaCenter-Portal'); }
                    $date = $github->getInfo();
					if(isset($branchname)) {
					 //  echo $date['name']; //['author']['date'];
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
                      $commit = $github->getCommits();
					  if(isset($branchname)) {
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
							$currentVersion = $config->get('version','ADVANCED');
						}
                      echo "Version </td><td><a href='https://github.com/elcabong/MediaCenter-Portal/commit/".$currentVersion."' target='_blank'>".$currentVersion.'</a>';
                      if($commitNo != $currentVersion){
                        echo "<br><a href='#' onclick='updateVersion();' title='".$commitNo." - Description: ".$commit['0']['commit']['message']."'>***UPDATE Available***</a>";
                      }
                    ?>
                  </td>
                </tr>
              </table>
            </div>
          <!--  <div id="GLOBAL" class="panel">
              <h3>Global Settings</h3>
                <table>
                  <tr>
                    <td colspan="2"><p align="justify" style="width: 500px;">Use Global Settings if all your programs are installed to one computer and/or if you use the same Username and Password throughout. Changing a setting for that particular program overrides this page.</p></td>
                  </tr>
                  <tr>
                    <td align="right"><p>Global URL:</p></td>
                    <td align="left"><p><input type="checkbox"  title="Tick to Enable" name="ENABLED" <?php //echo ($config->get('ENABLED','GLOBAL')=="true")?'CHECKED':'';?>></td>
                  </tr>
                  <tr>
                    <td align="right"><p>Global IP:</p></td>
                    <td align="left"><p><input name="URL" size="20" title="Insert IP Address or Network Name" value="<?php //echo $config->get('URL','GLOBAL')?>"></td>
                  </tr>
                  <tr>
                    <td align="right"><p>Global Authentication:</p></td>
                    <td align="left"><p><input type="checkbox" title="Tick to Enable" name="AUTHENTICATION" <?php //echo ($config->get('AUTHENTICATION','GLOBAL') == "true")?'CHECKED':'';?>></p></td>
                  </tr>
                  <tr>
                    <td align="right"><p>Global Username:</p></td>
                    <td align="left"><input name="USERNAME" title="Insert your Global Username" size="20" value="<?php //echo $config->get('USERNAME','GLOBAL')?>"></td>
                  </tr>
                  <tr>
                    <td align="right"><p>Global Password:</p></td>
                    <td align="left"><input type="password" title="Insert your Global Password" name="PASSWORD" size="20" value="<?php //echo $config->get('PASSWORD','GLOBAL')?>"></td>
                  </tr>
                </table>
              <input type="button" title="Save these Settings" value="Save" class="ui-button ui-widget ui-state-default ui-corner-all" onClick="updateSettings('GLOBAL');" />
            </div> -->
            <div id="USERS" class="panel">
              <h3>User List</h3>
              <table id="table_users" class="headers">
			  <tr>
			  <td>username<span>the login username</span></td>
			  <td>password<span>The user password. If blank, auth will be disabled for this user.</span></td>
			  <td>navgroups<span>What navigation groups does this user have access to. ie:  1,3,4</span></td>
			  <td>homeroom<span>What room will this user default to.</span></td>
			  <td>roomgroups<span>What rooms groups this user has access to.  if 1, user has access to all rooms.</span></td>
			  <td>roomallow<span>individual override to allow rooms</span></td>
			  <td>roomdeny<span>individual override to deny rooms</span></td>
			  <td></td>
			  </tr></table>
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
					$sql = "SELECT * FROM users";
					$userid = 0;
					foreach ($configdb->query($sql) as $row)
						{
						$userid = $row['userid'];
						if(isset($row['homeroom'])) {
								$sql2 = "SELECT * FROM rooms WHERE roomid = ".$row['homeroom'];
								foreach ($configdb->query($sql2) as $row2) {
								$thehomeroom = "<option selected='selected' value=".$row2['roomid'].">".$row2['roomname']."</option>"; 
								}
						} else {
							$thehomeroom = "<option selected='selected'>GOTO Room List</option>";
						}
						if(isset($row['roomaccess']) && $row['roomaccess'] != '') {
								$theroomaccess = '';
								$sql3 = "SELECT * FROM rooms WHERE roomid IN (".$row['roomaccess'].")";
								foreach ($configdb->query($sql3) as $row3) {
								$theroomaccess .= "<option selected='selected' value=".$row3['roomid'].">".$row3['roomname']."</option>"; 
								}
								$sql3 = "SELECT * FROM rooms WHERE roomid NOT IN (".$row['roomaccess'].")";
								foreach ($configdb->query($sql3) as $row3) {
								$therooms .= "<option value=".$row3['roomid'].">".$row3['roomname']."</option>"; 
								}
						} else {
							$theroomaccess = '';
							$therooms = '';
							$therooms = $roomlist;
						}
						echo "<table id='users-$userid'>";
						echo "<tr><td><input size='10' name='username' value='" . $row['username'] . "'></td>
										<td><input size='10' type='password' name='password' value=" . $row['password'] . "></td>
										<td><input size='10' name='navgroupaccess' value=" . $row['navgroupaccess'] . "></td>
										<td><select name='homeroom'>".$thehomeroom.$roomlist."</select></td><td><input size='10' name='roomgroupaccess' value=" . $row['roomgroupaccess'] . "></td>
										<td><select class='chosen-select multiple' id='roomaccess$userid' data-placeholder='Allow Overrides' multiple='multiple'>".$theroomaccess.$therooms."</select><input size='10' class='roomaccess$userid' type='hidden' name='roomaccess' value=" . $row['roomaccess'] . "></td>
										<td><input size='10' name='roomdeny' value=" . $row['roomdeny'] . "></td>
										<td><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Save' onclick='updateSettings(\"users-$userid\");' /></td></tr>";
						echo "</table>";
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
				echo "<table id='users-new'>";	
				echo "<tr><td><input size='10' name='username' value=''></td><td><input size='10' type='password' name='password' value=''></td><td><input size='10' name='navgroupaccess' value=''></td><td><select name='homeroom'><option selected='selected'>Homeroom</option>".$roomlist."</select></td><td><input size='10' name='roomgroupaccess' value=''></td><td><input size='10' name='roomaccess' value=''></td><td><input size='10' name='roomdeny' value=''></td><td><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='ADD' onclick='updateSettings(\"users-new\");' /></td></tr>";
				echo "</table>";
				echo "<br><br><br>";
				try {
					foreach ($configdb->query($sql) as $row)
						{
						$userid = $row['userid'];
						$filename = "../media/Users/user$userid.jpg";
						if (file_exists($filename)) {
						$theuserpic = "$filename";
						} else {
						$theuserpic = "../media/Users/user-default.jpg";   
						}
						echo "<div class='container'><form action='upload.php?user=$userid' class='dropzone' id='user$userid' style='position:relative;z-index:1;background-color:rgba(0,0,0,.5);color:#eee;'><input type='file' name='user$userid' /></form><span class='text'>" . $row['username'] . "</span><img src='$theuserpic' class='image' /></div>";
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}				
				?>
			<br><br>	
            </div>
            <div id="ROOMS" class="panel">
              <h3>Room List</h3>
			    <p>These Rooms are different xbmc machines or other network items you can control from a web interface</p>
				<p align="justify" style="width: 700px;">
				    <b>Title:</b>  The title of the room or device
	<br><br><b>MAC:</b>  This optional input is for the MAC Address and is used to WOL the device if available.
	<br><br><b>IP1:</b>  A control web interface such as the webinterface plugins for xbmc ie  http://ip:port  or   http://username:pass@ip:port 
	<br><br><b>IP2:</b>  An optional second control web interface such as the webinterface plugins for xbmc ie  http://ip:port  or   http://username:pass@ip:port 
				<br>
				</p>			  
                <?php
				echo "<table id='rooms-new'>";
				echo "<tr><td></td><td class='title'>Title</td><td><input size='10' name='roomname' value=''></td><td class='title'>MAC</td><td><input size='20' name='mac' value=''></td><td><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Add' onclick='updateSettings(\"rooms-new\");' /></td></tr>";
				echo "<tr><td></td><td class='title'>IP1</td><td  colspan=4><input size='60' name='ip1' value=''></td></tr><tr><td></td><td class='title'>IP2</td><td colspan=4><input size='60' name='ip2' value=''></td></tr>";
				echo "</table><br><br><br>";
				try {
					$sql = "SELECT * FROM rooms";
					$roomid = 0;
					foreach ($configdb->query($sql) as $row)
						{
						$roomid = $row['roomid'];
						echo "<table id='rooms-$roomid'>";
						echo "<tr><td class='orange'>" . $row['roomid'] . "</td><td class='title'>Title</td><td><input size='10' name='roomname' value='" . $row['roomname'] . "'></td><td class='title'>MAC</td><td><input size='20' name='mac' value=" . $row['mac'] . "></td><td><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Save' onclick='updateSettings(\"rooms-$roomid\");' /></td></tr>";
						echo "<tr><td></td><td class='title'>IP1</td><td colspan=4><input size='60' name='ip1' value='" . $row['ip1'] . "'></td></tr><tr><td></td><td class='title'>IP2</td><td colspan=4><input size='60' name='ip2' value=" . $row['ip2'] . "></td></tr>";
						echo "</table><br><br>";
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
                ?>
           </div>
			<div id="ROOMGROUPS" class="panel">
              <h3>Room Groups</h3>
              <table id="table_admingroups" class="headers">
			  <tr>
			  <td>roomgroupname<span>the name for this group, ie: admin   or   downloads</span></td>
			  <td>roomallow<span>room numbers allowed for this group.  ie:  1,3,4,5</span></td>
			  <td>roomdeny<span>optional room numbers denied for this group.  ie: 2,6</span></td>
			  <td></td>
			  </tr></table>
                <?php
				try {
					$sql = "SELECT * FROM roomgroups";
					$roomid = 0;
					foreach ($configdb->query($sql) as $row)
						{
						$roomid = $row['roomgroupid'];
						echo "<table id='roomgroups-$roomid'>";						
						echo "<tr><td>" . $roomid . "</td><td><input size='10' name='roomgroupname' value=" . $row['roomgroupname'] . "></td><td><input size='10' name='roomaccess' value=" . $row['roomaccess'] . "></td><td><input size='10' name='roomdeny' value=" . $row['roomdeny'] . "></td><td><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Save' onclick='updateSettings(\"roomgroups-$roomid\");' /></td></tr>";
						echo "</table>";
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
				echo "<table id='roomgroups-new'>";
				$nextroomgroupid = 1 + $roomid;
				echo "<tr><td>" . $nextroomgroupid . "</td><td><input size='10' name='roomgroupname' value=''></td><td><input size='10' name='roomaccess' value=''></td><td><input size='10' name='roomdeny' value=''></td><td><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='ADD' onclick='updateSettings(\"roomgroups-new\");' /></td></tr>";
				echo "</table>";
				?>
            </div>			
			<div id="NAVIGATION" class="panel">
              <h3>Navigation</h3>
			    <p>These links will be available in the upper left menu</p>
				<p align="justify" style="width: 700px;">
				    <b>Title:</b>  The title of the link unless an icon of the same name (image.png) is uploaded.
	<br><br><b>Full IP:</b>  The complete address to the link.  Can include username and password which is masked in the browser unless the source is viewed when those pages have already been accessed.  ie:  http://name:pass@ip:port
	<br><br><b>M IP:</b>  Adds this link to the mobile specific site. set to 1 if the ip source scales on its own, or specify the full address here of the mobile site.  ie  http://m.ip:port  or   http://ip:port/m/ 
	<br><br><b>NG #:</b>  This number represents the navigation group this link will be in.  this number goes in the user settings under "navgroups"
	<br><br><b>NG Title:</b>  Set to 1 if this entry is the title for the group.  if set to 1, then the IP entries are ignored.   <br>
				</p>
                <?php
				echo "<table id='navigation-new'>";
				echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td class='title'>Title</td><td><input size='40' name='navname' value=''></td><td class='title'>NG #</td><td><input size='1' name='navgroup' value=''></td></tr>";
				echo "<tr><td><img src='../media/Programs/ProgramDefault.png' height='50'><img src='../media/Programs/ProgramDefault.png' height='50'></td><td><img src='../media/Programs/ProgramDefault.png' height='50'></td><td class='title'>Full IP</td><td><input size='40' name='navip' value=''></td><td class='title'>NG TItle</td><td><input size='1' name='navgrouptitle' value=''></td></tr>";
				echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td class='title'>M IP</td><td><input size='40' name='mobile' value=''></td><td colspan='2' style='text-align:center;'><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Add' onclick='updateSettings(\"navigation-new\");' /></td></tr>";
				echo "</table><br><br>";
				try {
					$sql = "SELECT * FROM navigation ORDER BY navgroup, navgrouptitle ASC";
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
						echo "<br><table id='navigation-$navid'>";
						echo "<tr><td></td><td></td><td class='title'>Title</td><td><input size='40' name='navname' value=" . $row['navname'] . "></td><td class='title'>NG #</td><td><input size='1' name='navgroup' value=" . $row['navgroup'] . "></td></tr>";
						echo "<tr><td><form action=\"upload.php?program=" . $row['navname'] . "\" class='dropzone' id='program" . $row['navname'] . "' style='position:relative;z-index:1;background-color:rgba(0,0,0,.5);color:#eee;width:100px;'></form></td><td><img src=" . $theprogrampic ." style='position:relative;height:50px;'></td><td class='title'>Full IP</td><td><input size='40' name='navip' value=" . $row['navip'] . "></td></td><td class='title'>NG TItle</td><td><input size='1' name='navgrouptitle' value=" . $row['navgrouptitle'] . "></td></tr>";
						echo "<tr><td></td><td></td></td><td class='title'>M IP</td><td><input size='40' name='mobile' value=" . $row['mobile'] . "></td><td colspan='2' style='text-align:center;'><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Save' onclick='updateSettings(\"navigation-$navid\");' /></td></tr>";
						echo "</table><br><br>";
						}
				} catch(PDOException $e)
					{
					echo $e->getMessage();
					}
                ?>
           </div>
          </div>
        </div>
        <!-- <input type="button" value="Save ALL" onclick="saveAll();">  -->
      </div>
    </div>  
  </center>
  <script type="text/javascript" src="../js/settings.js"></script>  
</body>
</html>
<? } ?>