<?php
require '../lib/class.settings.php';require '../lib/class.github.php';
$config = new ConfigMagik('../config/config.ini', true, true);
    if(!is_writeable('../config/config.ini')){
    echo 'Could not write to config.ini';
    return false;
  }

if(!empty($_GET) && strpos($_SERVER['HTTP_REFERER'],'settings')){
  if(!is_writeable('../config/config.ini')){
    echo 'Could not write to config.ini';
    return false;
  }
  //if there is no section parameter, we will not do anything.
  if(!isset($_GET['section'])){
    echo false; return false;
  } else {
    $section_name = $_GET['section'];
    unset($_GET['section']);     //Unset section so that we can use the GET array to manipulate the other parameters in a foreach loop.
    if (!empty($_GET)){
      foreach ($_GET as $var => $value){
      //Here we go through all $_GET variables and add the values one by one.
        $var = urlencode($var);
        try{
          $config->set($var, $value, $section_name); //Setting variable '. $var.' to '.$value.' on section '.$section_name;
        } catch(Exception $e) {
          echo 'Could not set variable '.$var.'<br>';
          echo $e;
          return false;
        }
      }
    }
    try{
      $section = $config->get($section_name); //Get the entire section so that we can check the variables in it.
      foreach ($section as $title=>$value){
      //Here we go through all variables in the section and delete the ones that are in there but not in the $_GET variables
      //Used mostly for deleting things.
        if(!isset($_GET[$title]) && ($config->get($title, $section_name) !== NULL)){
          $title = urlencode($title);
          try{
            $config = new ConfigMagik('../config/config.ini', true, true);
            $config->removeKey($title, $section_name);  //$title removed;
            $config->save();
          } catch(Exception $e){
            echo 'Unable to remove variable '.$title.' on section'.$section_name.'<br>';
            echo $e;
          }
        }
      }
    } catch(Exception $e){
      echo $e;
    }
    echo true;
    return true;
  }
} else {
require './config.php';
if($HOWMANYUSERS != 0) {
if ($authsecured && (!isset($_SESSION["$authusername"]) || !$_SESSION["$authusername"] || $_SESSION["$authusername"] != $authusername )) {
    header("Location: login.php");
    exit;}}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!--
   @author: Gustavo Hoirisch
  -->

<html>
<head>
  <title>Settings</title>
  <link href="../css/room.css" rel="stylesheet" type="text/css">
  <link href="../css/settings.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
  <script type="text/javascript" src="../js/fisheye-iutil.min.js"></script>
  <script type="text/javascript" src="../js/settings.js"></script>
  <link rel="stylesheet" type="text/css" href="css/widget.css">
  <link rel="stylesheet" type="text/css" href="css/static_widget.css">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.js"></script>  
  <link rel="stylesheet" type="text/css" href="../css/jquery.pnotify.default.css">
  <link rel="stylesheet" type="text/css" href="../css/UI/jquery-ui-1.8.14.custom.css">
  <script src="../js/jquery.pnotify.js" type="text/javascript"></script>
 	<script type="text/javascript">
		if (window.navigator.standalone) {
			var iWebkit;if(!iWebkit){iWebkit=window.onload=function(){function fullscreen(){var a=document.getElementsByTagName("a");for(var i=0;i<a.length;i++){if(a[i].className.match("noeffect")){}else{a[i].onclick=function(){window.location=this.getAttribute("href");return false}}}}function hideURLbar(){window.scrollTo(0,0.9)}iWebkit.init=function(){fullscreen();hideURLbar()};iWebkit.init()}}
		}
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
          <li><a href="#GLOBAL">General</a></li>
       <!--   <li><a href="#PROGRAMS">Programs</a></li>
          <li><a href="#SEARCH">Search Widget</a></li>
          <li><a href="#TRAKT">Trakt.tv</a></li>
          <li><a href="#NAVBAR">Nav Bar</a></li>
          <li><a href="#SUBNAV">Sub Nav</a></li>
          <li><a href="#HDD">Hard Drives</a></li>
          <li><a href="#MESSAGE">Message Widget</a></li>
          <li><a href="#SECURITY">Security</a></li>
          <li><a href="#MODS">CSS Mods</a></li>-->
          <li><a href="#ROOMS">Room List</a></li>
         <li><a href="#ADMINGROUPS">Admin Groups</a></li> 
         <li><a href="#NAVGROUPS">NAV Groups</a></li>
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
                   echo $date['pushed_at'];
                  ?>
                  </td>
                </tr>
                <tr align="left">
                  <td>
                    <?php
                      $commit = $github->getCommits();
                      $commitNo = $commit['0']['sha'];
					  echo print_r($commit['0']['1']['sha']);
					  echo print_r($commit['sha']);
					  echo $commitNo."/commitNo/";
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
            <div id="GLOBAL" class="panel">
              <h3>Global Settings</h3>
                <table>
                  <tr>
                    <td colspan="2"><p align="justify" style="width: 500px;">Use Global Settings if all your programs are installed to one computer and/or if you use the same Username and Password throughout. Changing a setting for that particular program overrides this page.</p></td>
                  </tr>
                  <tr>
                    <td align="right"><p>Global URL:</p></td>
                    <td align="left"><p><input type="checkbox"  title="Tick to Enable" name="ENABLED" <?php echo ($config->get('ENABLED','GLOBAL')=="true")?'CHECKED':'';?>></td>
                  </tr>
                  <tr>
                    <td align="right"><p>Global IP:</p></td>
                    <td align="left"><p><input name="URL" size="20" title="Insert IP Address or Network Name" value="<?php echo $config->get('URL','GLOBAL')?>"></td>
                  </tr>
                  <tr>
                    <td align="right"><p>Global Authentication:</p></td>
                    <td align="left"><p><input type="checkbox" title="Tick to Enable" name="AUTHENTICATION" <?php echo ($config->get('AUTHENTICATION','GLOBAL') == "true")?'CHECKED':'';?>></p></td>
                  </tr>
                  <tr>
                    <td align="right"><p>Global Username:</p></td>
                    <td align="left"><input name="USERNAME" title="Insert your Global Username" size="20" value="<?php echo $config->get('USERNAME','GLOBAL')?>"></td>
                  </tr>
                  <tr>
                    <td align="right"><p>Global Password:</p></td>
                    <td align="left"><input type="password" title="Insert your Global Password" name="PASSWORD" size="20" value="<?php echo $config->get('PASSWORD','GLOBAL')?>"></td>
                  </tr>
                </table>
              <input type="button" title="Save these Settings" value="Save" class="ui-button ui-widget ui-state-default ui-corner-all" onClick="updateSettings('GLOBAL');" />
            </div>
            <div id="ROOMS" class="panel">
              <h3>Room List</h3>
              <p align="justify" style="width: 500px;">;this is where you name and point to your xbmc locations
					; the room name, ip are required and mac address is needed for WOL..  the format is: ROOM# = "room name,http://ip,mac"
					;the ip you set for the xbmc machine in that room. if the port is anything but 80 you must include
					; the :port# after the ip address ie:  "http://192.168.3.1:8080"
					</p>
              <table id="table_rooms">
                <tr>
                  <td>ROOM#</td>
                  <td>Room Name,http://roomip:port,mac:address</td>
                </tr>
                <?php
                $x = $config->get('ROOMS');
                foreach ($x as $title=>$url){
                  echo "<tr>
                          <td>
                            <input size='20' name='title' value='".urldecode(str_ireplace('_', ' ', $title))."'/>
                          </td>
                          <td>
                            <input size='55' name='VALUE' value='$url'/>
                          </td>
                        </tr>";
                }
                ?>
              </table>
              <input type="button" class="ui-button ui-widget ui-state-default ui-corner-all" value="ADD" onclick="addRowToTable('rooms', 20, 55);" />
              <input type="button" class="ui-button ui-widget ui-state-default ui-corner-all" value="REMOVE" onclick="removeRowToTable('rooms');" />
              <br />
              <br />
              <input type="button" class="ui-button ui-widget ui-state-default ui-corner-all" value="Save" onclick="updateAlternative('ROOMS');" />
            </div>
			<div id="ADMINGROUPS" class="panel">
              <h3>Admin Groups</h3>
              <p align="justify" style="width: 500px;">;the [ADMINGROUP#] are for grouping room permissions for users.
				;if ADMIN = "1" in the [user#] section, that user will have access to all rooms listed above.
				;if ADMIN = "2" in the [user#] section, that user will have access to the rooms specified in [ADMINGROUP2] below
				;you can add as many [ADMINGROUP#] as you need.  they will follow the same format as [USERPERM#] section in each [USER#] section
				;each user can only be assigned to 1 goup
				;the rooms listed above are numbered and will be refered to as ROOM#  where # is its number of lines from the top of the list
				;you can skip room numbers in the [ADMINGROUP#] sections, all rooms not defined will be set to 0
				;do not add anything to [ADMINGROUP1]. this is the full admin setting and no changes will be made if anything is here.</p>
              <table id="table_admingroups">
                <?php
				$u=2;
				$gnavlink;
				while($u>0) {
					if($config->get("ADMINGROUP$u")) {
					echo "<span id=\"ADMINGROUP$u\"><table id=\"table_admingroup$u\"><tr><td>ADMINGROUP$u</td><td></td></tr><tr><td>ROOM#</td><td>0 for no access; 1 for access</td></tr>";
					$x = $config->get("ADMINGROUP$u");	 
					foreach ($x as $title=>$url){
					  echo "<tr>
							  <td>
								<input size='20' name='title' value='".urldecode(str_ireplace('_', ' ', $title))."'/>
							  </td>
							  <td>
								<input size='20' name='VALUE' value='$url'/>
							  </td>
							</tr>";
					}
					 		echo "</table><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='ADD' onclick=\"addRowToTable('admingroup$u', 20, 20);\" />
							  <input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='REMOVE' onclick=\"removeRowToTable('admingroup$u');\" />
							  <input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Save' onclick=\"updateAlternative('ADMINGROUP$u');\" /><br><br><br></span>";
						$u++;					
					 } else { $u = -5; }
			  }
                 ?>
              </table>
			  <hr>
              <input type="button" class="ui-button ui-widget ui-state-default ui-corner-all" value="ADD" onclick="addRowToTable('admingroups', 20, 20);" />
              <input type="button" class="ui-button ui-widget ui-state-default ui-corner-all" value="REMOVE" onclick="removeRowToTable('admingroups');" />
              <br />
              <br />
              <input type="button" class="ui-button ui-widget ui-state-default ui-corner-all" value="Save" onclick="updateAlternative('ADMINGROUPS');" />
            </div>			
			<div id="NAVGROUPS" class="panel">
              <h3>NAV Groups</h3>
              <p align="justify" style="width: 500px;">;static nav does not change when rooms are changed.
					;set default navigation links available as options for all users
					;these are the links across the top of the system, opens in the main web frame
					;each [NAVGROUP#] can be added to any user account by adding the # to NAVGROUPACCESS = "#" in the [user#] section
					;NAVGROUPACCESS in [user#] can have multiple numbers like the below example:
					;NAVGROUPACCESS = "1, 3,7"   the order can be changed.  the first number will be the default one shown on page load
					;the above will add the 1 3 and 7 NAVGROUPs to this users menu in addition to their own [navbar#] in their [user#] section
					;you can have spaces or no spaces, but there must be ',' separating all the numbers with no ',' at the end
					;you can create as many [NAVGROUP#] groups as you need below.
					;the "title" attribute will not be shown and is for organization
					;to include a .png for the link, place it like this:  ./Programs/SickBeard.png  where this file is ./config.php
					;it is case sensitive and the LinkName below is the LinkName.png filename. ALL PICS MUST BE .png
					;LinkName = "full url of the webpage"
					;if the site has login credentials, you can use: "http://username:password@url:port/" and the url is masked in the browser unless you look at the source.
					;example:  SickBeard = "http://username:password@192.168.1.217:8081/"
					;example:  XBMC-Site = "http://www.xbmc.org"   ... the LinkName must have NO spaces </p>
              <table id="table_navgroups">
                <?php
				$u=1;
				$gnavlink;
				while($u>0) {
					if($config->get("NAVGROUP$u")) {
					echo "<span id=\"NAVGROUP$u\"><table id=\"table_navgroup$u\"><tr><td>title</td><td>URL</td></tr>";
					$x = $config->get("NAVGROUP$u");	 
					foreach ($x as $title=>$url){
					  echo "<tr>
							  <td>
								<input size='20' name='title' value='".urldecode(str_ireplace('_', ' ', $title))."'/>
							  </td>
							  <td>
								<input size='55' name='VALUE' value='$url'/>
							  </td>
							</tr>";
					}
					 		echo "</table><input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='ADD' onclick=\"addRowToTable('navgroup$u', 20, 55);\" />
							  <input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='REMOVE' onclick=\"removeRowToTable('navgroup$u');\" />
							  <input type='button' class='ui-button ui-widget ui-state-default ui-corner-all' value='Save' onclick=\"updateAlternative('NAVGROUP$u');\" /><br><br><br></span>";
						$u++;					
					 } else { $u = -5; }
			  }
                 ?>
              </table>
			  <hr>
              <input type="button" class="ui-button ui-widget ui-state-default ui-corner-all" value="ADD" onclick="addRowToTable('navgroups', 20, 55);" />
              <input type="button" class="ui-button ui-widget ui-state-default ui-corner-all" value="REMOVE" onclick="removeRowToTable('navgroups');" />
              <br />
              <br />
              <input type="button" class="ui-button ui-widget ui-state-default ui-corner-all" value="Save" onclick="updateAlternative('NAVGROUPS');" />
            </div>
          </div>
        </div>
        <!-- <input type="button" value="Save ALL" onclick="saveAll();">  -->
      </div>
    </div>  
  </center>
<!--
  <div>
    <input value="Regular Notice" onclick="$.pnotify({
            pnotify_title: 'Regular Notice',
            pnotify_text: 'Check me out! I\'m a notice.'
          });" type="button" class="ui-button ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">  
  </div>
-->
</body>
</html>
<?php 
}
?>