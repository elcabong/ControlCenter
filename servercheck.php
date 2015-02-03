<?php
$acceptupgrade = 0;
if(isset($_GET['newdbversion'])) {
	$DBVERSION = $_GET['newdbversion'];
}
if(isset($_GET['acceptupgrade'])) {
	$acceptupgrade = '1';
}
require_once "./Portal/startsession.php";
require_once "$INCLUDES/includes/functions.php";
$getversion = "yes";
require "$INCLUDES/includes/config.php";
$log->LogInfo("User loaded " . basename(__FILE__));
?>
<html>
<head>
	<meta name='viewport' content="width=device-width,height:window-height, initial-scale=.9, maximum-scale=.9, minimum-scale=.9, user-scalable=auto, minimal-ui" />
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
	<link rel="icon" type="image/png" href="./favicon.ico">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Control Center Server Check</title>
<script type="text/javascript">
function redirect(){
  window.location = 'login.php?user=choose';
}
</script>
<link href="css/front.css" rel="stylesheet" type="text/css" />
	<link rel="icon" type="image/png" href="./favicon.ico">
<style type="text/css">
.widget {
  border:1px solid black;
  -moz-border-radius:6px 6px 6px 6px;
  border-radius:6px 6px 6px 6px;
  margin:0px 0px;
  box-shadow: 3px 3px 3px #000;
  background:#2C2D32;
}
.widget-head {
  -moz-border-radius:6px 6px 0px 0px;
  border-radius:6px 6px 0px 0px;
  background:#3d3d3d;
  border-bottom:1px solid black;
  width: 100%;
  height: 30px;
  line-height: 30px;
  font-weight:bold;
  cursor: move;
}
</style>
</head>
<body>
<center>
<br>
<br>
<br>
<table class="widget" width=400 cellpadding=0 cellspacing=0>
<tr>
  <td align=center colspan=2 height=25 style="padding:0;"><div class="widget-head">Welcome to your Control Center</div></td>
<tr>
<td align=center><br>
Something was updated.  Running checks.
  <br><br>
<?php
if(false){
?>
  If you have no text below, your PHP is not working.
<?php
}
$redirect = true;
$version = phpversion();

echo "<tr><td>PHP Version $version</td><td>";if($version > 5){echo "<img src='media/green-tick.png' height='15px'/>";}else{echo "<img src='media/red-cross.png' height='15px'/>";$redirect = false;} echo "</td></tr>";
if(extension_loaded('libxml')){
  echo "<tr><td>LibXML Enabled</td><td><img class='greencheck' src='http://etc-mysitemyway.s3.amazonaws.com/icons/legacy-previews/icons/green-jelly-icons-symbols-shapes/019227-green-jelly-icon-symbols-shapes-check-mark5-ps.png' height='15px'/></td></tr>";
}else{
  echo "<tr><td>LibXML <b>NOT</b> Enabled</td><td><img src='http://xqshost.ro/favicon.ico' height='15px'/></td></tr>";
  $redirect = false;
}
if(extension_loaded('sqlite3')){
  echo "<tr><td>Sqlite3 Enabled</td><td><img src='http://etc-mysitemyway.s3.amazonaws.com/icons/legacy-previews/icons/green-jelly-icons-symbols-shapes/019227-green-jelly-icon-symbols-shapes-check-mark5-ps.png' height='15px'/></td></tr>";
}else{
  echo "<tr><td>Sqlite3 <b>NOT</b> Enabled</td><td><img src='http://xqshost.ro/favicon.ico' height='15px'/></td></tr>";
  $redirect = false;
}
if(extension_loaded('curl')){
  echo "<tr><td>cURL Enabled</td><td><img src='http://etc-mysitemyway.s3.amazonaws.com/icons/legacy-previews/icons/green-jelly-icons-symbols-shapes/019227-green-jelly-icon-symbols-shapes-check-mark5-ps.png' height='15px'/></td></tr>";
}else{
  echo "<tr><td>cURL <b>NOT</b> Enabled</td><td><img src='http://xqshost.ro/favicon.ico' height='15px'/></td></tr>";
  $redirect = false;
}
if(extension_loaded('json')){
  echo "<tr><td>JSON Enabled</td><td><img src='http://etc-mysitemyway.s3.amazonaws.com/icons/legacy-previews/icons/green-jelly-icons-symbols-shapes/019227-green-jelly-icon-symbols-shapes-check-mark5-ps.png' height='15px'/></td></tr>";
}else{
  echo "<tr><td>JSON <b>NOT</b> Enabled</td><td><img src='http://xqshost.ro/favicon.ico' height='15px'/></td></tr>";
  $redirect = false;
}
echo "<tr><td>";
echo "<tr><td>";
// this function is also in config.php   remember to update as needed
	$folderlevel = "./";
	$missing = folderRequirements($folderlevel);
	if($missing > 0) {
        echo "<tr><td>Folder Structure Incomplete</td><td><img src='media/red-cross.png' height='15px'/></td></tr>";
		$redirect = false;
		foreach($folders as $dir) {
			$thefolder = "./".$dir."/";
			if(file_exists($thefolder) && is_dir($thefolder)) {
				echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;$thefolder is Found.</td><td><img src='media/green-tick.png' height='15px'/></td></tr>";
			} else {
				 echo "<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;$thefolder is Missing.</td><td><img src='media/red-cross.png' height='15px'/></td></tr>";
			}
		}
        echo "<tr><td>Check web folder permissions</td><td><img src='media/red-cross.png' height='15px'/></td></tr>";
		$log->LogError("User encountered error with the folder structure or folder permissions loaded " . basename(__FILE__));		
	}
if (!file_exists("$INCLUDES/sessions/config.db")){
echo "<tr><td>Trying to Create DB.</td></tr>";
	try {
		$configdb = new PDO('sqlite:' . $INCLUDES . '/sessions/config.db');
		if($configdb) { 
		echo "<tr><td>DB created.</td><td><img src='media/green-tick.png' height='15px'/></td></tr>"; 
		}
	} catch (PDOException $error) {
		$log->LogError("User encountered errors while opening the DB: $error->getMessage() " . basename(__FILE__));
        echo "<tr><td>error connecting to the DB,  error message: , $error->getMessage(), </td><td><img src='media/red-cross.png' height='15px'/></td></tr>";
		$redirect = false;
    }
}
if (file_exists("$INCLUDES/sessions/config.db")){
  $valid = true;
  if(!is_writable("$INCLUDES/sessions/config.db")){
    if(@chmod("$INCLUDES/sessions/config.db", 0777)){
      echo "";
    }else{
	  $log->LogError("User encountered error with the DB folder structure or access permissions " . basename(__FILE__));
      echo "<tr><td>Can <b>NOT</b> edit db.  check /sessions/ folder permissions 1</td><td><img src='media/red-cross.png' height='15px'/></td></tr>";
      $redirect = false;
      $valid = false;
    }
  } else {
		$configdb = new PDO('sqlite:' . $INCLUDES . '/sessions/config.db');
		function checkDBversion() {
				if(!isset($INCLUDES)) {
					$found = false;
					$path = './CCincludes';
					while(!$found){
						if(file_exists($path)){ 
							$found = true;
							$INCLUDES = $path;
						}
						else{ $path = '../'.$path; }
					}
				}
				if(!isset($configdb)) {	$configdb = new PDO("sqlite:" . $INCLUDES . "/sessions/config.db"); }
				try {
					$thedbversion = "none";
					$sql = "SELECT CCvalue FROM controlcenter WHERE CCsetting = 'dbversion' LIMIT 1";
					foreach ($configdb->query($sql) as $row)
					{
						if(isset($row['CCvalue'])) {
						$thedbversion = $row['CCvalue'];
					}}
					return $thedbversion;
				} catch(PDOException $e) {
					  $log->LogError("User encountered error accessing the DB: $e->getMessage() " . basename(__FILE__));
					  return "none";
				}
		}
		function checkDBsettings() {
				if(!isset($INCLUDES)) {
					$found = false;
					$path = './CCincludes';
					while(!$found){
						if(file_exists($path)){ 
							$found = true;
							$INCLUDES = $path;
						}
						else{ $path = '../'.$path; }
					}
				}
				if(!isset($configdb)) {	$configdb = new PDO('sqlite:' . $INCLUDES . '/sessions/config.db'); }
				$tempsettingarray = array();
				try {
					$sql = "SELECT * FROM settings";
					foreach ($configdb->query($sql) as $row)
					{
						if(isset($row['settingvalue1']) && $row['settingvalue1'] != '') {
							$thissetting = $row['setting'];
							$tempsettingarray["$thissetting"]['1'] = $row['settingvalue1'];
						}
					}
					return $tempsettingarray;
				} catch(PDOException $e) {
					  $log->LogError("User encountered error accessing the DB: $e->getMessage() " . basename(__FILE__));
					  return "none";
				}
		}
		function checkDBupgrade($old,$new) {
			$thisupgrade = "minimal";
			$old = explode(".","$old");
			$new = explode(".","$new");
			if($new[0] > $old[0] || $new[1] > $old[1]) {
				$thisupgrade = "bigupdate";
			}
			return $thisupgrade;
		}
  // write db tables here if they dont exist
   try {
  					$thedbversion = checkDBversion();
					$theolddbversion = $thedbversion;
					if($thedbversion < $DBVERSION && $thedbversion != 'none') {
						$upgrademe = checkDBupgrade($thedbversion,$DBVERSION);
						if($upgrademe == "bigupdate") {
							if($acceptupgrade != '1') {
								echo "<tr><td>Major Database Upgrade Needed.</td><td><img src='media/red-cross.png' height='15px'/></td></tr><tr><td>&nbsp;</td></tr>";
								echo "<tr><td>Export Database</td></tr><tr><td><a href=\"./Portal/exportdb.php?upgrade=1\" id=\"dlconfig\" target=\"_blank\">config.db</a></td></tr>";
								if(file_exists("$INCLUDES/sessions/config-bak.db")) {
									echo "<tr><td><a href=\"./Portal/exportdb.php?bak=1&upgrade=1\" id=\"dlconfig2\" target=\"_blank\">config-bak.db</a> <?php } ?></td></tr>";
									}
								echo "<tr><td>Database will need to be recreated.</td></tr><tr><td>&nbsp;</td></tr>";
								echo "<tr><td>To keep old settings/configuration:</td></tr>";
								echo "<tr><td>You can export the current db, then export the new one after upgrading.  Edit the new table changes to your old db, then import.</td></tr><tr><td>&nbsp;</td></tr>";
								echo "<tr><td>Remeber to change the dbversion in the controlcenter table to $DBVERSION</td></tr><tr><td>&nbsp;</td></tr>";
								echo "<tr><td>You can also roll back to a previous build to use your current db.</td></tr><tr><td>&nbsp;</td></tr>";
								echo "<tr><td><input type='button' onclick=\"window.location = './servercheck.php?newdbversion=$DBVERSION&acceptupgrade=1';\" value='Create new DB' /></td></tr>";
								$redirect = false;
								exit;
							}
							$thedbversion = $DBVERSION;
							$configdb = null;
							copy("$INCLUDES/sessions/config.db", "$INCLUDES/sessions/config-bak.db");
							unset($configdb);
							unlink("$INCLUDES/sessions/config.db");
							
							echo "<tr><td>Old DB backed up.</td><td><img src='media/green-tick.png' height='15px'/></td></tr>";
							$configdb = new PDO('sqlite:' . $INCLUDES . '/sessions/config.db');
						} else {
							echo "<tr><td>Small DB upgrade Needed.</td><td><img src='media/red-cross.png' height='15px'/></td></tr>";
							$configdb = null;
							copy("$INCLUDES/sessions/config.db", "$INCLUDES/sessions/config-bak.db");
							echo "<tr><td>Old DB backed up.</td><td><img src='media/green-tick.png' height='15px'/></td></tr>";
							$configdb = new PDO('sqlite:' . $INCLUDES . '/sessions/config.db');
							$thedbversion = $DBVERSION;							
						}
					}
		  $query = "CREATE TABLE IF NOT EXISTS users (userid integer PRIMARY KEY AUTOINCREMENT, username text UNIQUE NOT NULL, password text, passwordreset INTEGER NOT NULL DEFAULT 0, navgroupaccess string, homeroom integer, roomgroupaccess string, roomaccess string, roomdeny string, settingsaccess integer NOT NULL, wanenabled integer DEFAULT '0' NOT NULL, userlevel INTEGER NOT NULL DEFAULT '1')";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS rooms (roomid integer PRIMARY KEY AUTOINCREMENT, roomname text UNIQUE NOT NULL)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS rooms_addons (
									rooms_addonsid INTEGER  PRIMARY KEY AUTOINCREMENT NOT NULL,
									roomid INTEGER  NOT NULL,
									addonid TEXT  NOT NULL,
									enabled INTEGER NOT NULL DEFAULT 0,
									ip TEXT  NULL,
									ipw TEXT  NULL,
									mac TEXT  NULL,
									setting1 TEXT  NULL,
									setting2 TEXT  NULL,
									setting3 TEXT  NULL,
									setting4 TEXT  NULL,
									setting5 TEXT  NULL,
									setting6 TEXT  NULL,
									setting7 TEXT  NULL,
									setting8 TEXT  NULL,
									setting9 TEXT  NULL,
									setting10 TEXT  NULL,
									device_alive INTEGER NULL
									)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS roomgroups (roomgroupid integer PRIMARY KEY AUTOINCREMENT, roomgroupname text UNIQUE, roomaccess string, roomdeny string)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS navigation (navid integer PRIMARY KEY AUTOINCREMENT, navname text NULL, navip text, navipw text, mobilew text, mobile text, persistent integer DEFAULT '1' NOT NULL, autorefresh integer DEFAULT '0' NOT NULL)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS navigationgroups (navgroupid integer PRIMARY KEY AUTOINCREMENT, navgroupname text UNIQUE, navitems string)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS settings (settingid integer PRIMARY KEY AUTOINCREMENT, setting text UNIQUE, description text, settingvalue1type text, settingvalue1 text)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS alerts (alert_id	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE, userid INTEGER NOT NULL DEFAULT '0', userlevel INTEGER, message TEXT NOT NULL, from_userid INTEGER NOT NULL DEFAULT '0', created TEXT NOT NULL DEFAULT '0-0-0 00:00:00')";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS alerts_viewed (alerts_viewed_id	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE, userid INTEGER NOT NULL DEFAULT '0', alert_id INTEGER NOT NULL DEFAULT '0', viewed TEXT NOT NULL DEFAULT '0-0-0 00:00:00')";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS controlcenter (CCid integer PRIMARY KEY AUTOINCREMENT UNIQUE, CCsetting TEXT UNIQUE, CCvalue TEXT)";
		  $execquery = $configdb->exec($query);
			// stamp current version
			
			// need to check current version vs the new db version and run all updates in order to get current
			
			if($thedbversion == 'none') { $thedbversion = $DBVERSION; }
			if($thedbversion == '1.1.2' && $theolddbversion < $thedbversion) {
				$query = "ALTER TABLE rooms_addons ADD COLUMN device_alive INTEGER NULL;";
				$execquery = $configdb->exec($query);
			}
			if($thedbversion == '1.1.3' && $theolddbversion < $thedbversion) {
				$query = "DROP TABLE IF EXISTS controlcenter;";
				$execquery = $configdb->exec($query);
				$query = "CREATE TABLE IF NOT EXISTS controlcenter (CCid integer PRIMARY KEY AUTOINCREMENT UNIQUE, CCsetting TEXT UNIQUE, CCvalue TEXT)";
				$execquery = $configdb->exec($query);
			}
			if($thedbversion == '1.1.4' && $theolddbversion < $thedbversion) {
				$query = "DROP TABLE IF EXISTS alerts;";
				$execquery = $configdb->exec($query);
				$query = "CREATE TABLE IF NOT EXISTS alerts (alert_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE, userid INTEGER NOT NULL DEFAULT '0', userlevel INTEGER, message TEXT NOT NULL, from_userid INTEGER NOT NULL DEFAULT '0', created INTEGER NOT NULL DEFAULT current_timestamp)";
				$execquery = $configdb->exec($query);
				$query = "ALTER TABLE users ADD COLUMN userlevel INTEGER NOT NULL DEFAULT '1';";
				$execquery = $configdb->exec($query);
				$query = "CREATE TABLE IF NOT EXISTS alerts_viewed (alerts_viewed_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE, userid INTEGER NOT NULL DEFAULT '0', alert_id INTEGER NOT NULL DEFAULT '0', viewed INTEGER NOT NULL DEFAULT current_timestamp)";
				$execquery = $configdb->exec($query);
			}
			if($thedbversion == '1.1.5' && $theolddbversion < $thedbversion) {
				$query = "ALTER TABLE users ADD COLUMN passwordreset INTEGER NOT NULL DEFAULT 0;";
				$execquery = $configdb->exec($query);
			}
			if($thedbversion == '1.1.6' && $theolddbversion < $thedbversion) {
				$query = "ALTER TABLE rooms_addons ADD COLUMN enabled INTEGER NOT NULL DEFAULT 0;";
				$execquery = $configdb->exec($query);
				$configdb->exec("UPDATE rooms_addons SET enabled=1;");
			}
			if($thedbversion == '1.1.7' && $theolddbversion < $thedbversion) {
				$query = "ALTER TABLE alerts RENAME to _alerts_old;";
				$execquery = $configdb->exec($query);
				$query = "CREATE TABLE IF NOT EXISTS alerts (alert_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE, userid INTEGER NOT NULL DEFAULT '0', userlevel INTEGER, message TEXT NOT NULL, from_userid INTEGER NOT NULL DEFAULT '0', created TEXT NOT NULL DEFAULT '0-0-0 00:00:00')";
				$execquery = $configdb->exec($query);
				$query = "INSERT INTO alerts (alert_id, userid, userlevel, message, from_userid, created)
						  SELECT alert_id, userid, userlevel, message, from_userid, created
						  FROM _alerts_old;";
				$execquery = $configdb->exec($query);
				$query = "DROP TABLE IF EXISTS _alerts_old;";
				$execquery = $configdb->exec($query);
			}
			if($thedbversion == '1.1.8' && $theolddbversion < $thedbversion) {
				$query = "ALTER TABLE alerts_viewed RENAME to _alerts_viewed_old;";
				$execquery = $configdb->exec($query);
				$query = "CREATE TABLE IF NOT EXISTS alerts_viewed (alerts_viewed_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE, userid INTEGER NOT NULL DEFAULT '0', alert_id INTEGER NOT NULL DEFAULT '0', viewed TEXT NOT NULL DEFAULT '0-0-0 00:00:00')";
				$execquery = $configdb->exec($query);
				$query = "INSERT INTO alerts_viewed (alerts_viewed_id, userid, alert_id, viewed)
						  SELECT alerts_viewed_id, userid, alert_id, viewed
						  FROM _alerts_viewed_old;";
				$execquery = $configdb->exec($query);
				$query = "DROP TABLE IF EXISTS _alerts_viewed_old;";
				$execquery = $configdb->exec($query);
			}			
			$execquery = $configdb->exec("INSERT OR REPLACE INTO controlcenter (CCid, CCsetting, CCvalue) VALUES (1,'ccversion','$CCVERSION')");
			$execquery = $configdb->exec("INSERT OR REPLACE INTO controlcenter (CCid, CCsetting, CCvalue) VALUES (2,'dbversion','$thedbversion')");
			$execquery = $configdb->exec("INSERT OR REPLACE INTO controlcenter (CCid, CCsetting, CCvalue) VALUES (3,'lastcrontime','0')");
			echo "<tr><td>DB tables checked, Version: $thedbversion</td><td><img src='media/green-tick.png' height='15px'/></td></tr>";
			
			//insert all settings with default values
			$thedbsettings = checkDBsettings();
			if(!isset($thedbsettings["InputUserName"]['1']) || $thedbsettings["InputUserName"]['1'] == '') {
				$execquery = $configdb->exec("INSERT OR REPLACE INTO settings (settingid, setting, description, settingvalue1type, settingvalue1) VALUES (1, 'InputUserName','Requires user to type username instead of showing list for login','boolean','0')");
			}
			if(!isset($thedbsettings["LogLevel"]['1']) || $thedbsettings["LogLevel"]['1'] == '') {
				$execquery = $configdb->exec("INSERT OR REPLACE INTO settings (settingid, setting, description, settingvalue1type, settingvalue1) VALUES (2, 'LogLevel','Set Log Level. Options: DEBUG, INFO, WARN, ERROR, FATAL, OFF','TEXT','INFO')");
			}
			if(!isset($thedbsettings["TimeZone"]['1']) || $thedbsettings["TimeZone"]['1'] == '') {
				$execquery = $configdb->exec("INSERT OR REPLACE INTO settings (settingid, setting, description, settingvalue1type, settingvalue1) VALUES (3, 'TimeZone','Set Global Application Timezone','dropdown','America/Los_Angeles')");
			}
	} catch(PDOException $e)
		{
			  echo "<tr><td>Can <b>NOT</b> edit db.  check /sessions/ folder permissions</td><td><img src='media/red-cross.png' height='15px'/></td></tr>";
			  $log->LogFatal("Fatal: User could not open DB: $e->getMessage().  from " . basename(__FILE__));
			  $redirect = false;
		}

	$totalusernum = 0;
    $sql = "SELECT * FROM users LIMIT 1";
    foreach ($configdb->query($sql) as $row)
        {
		if(isset($row['userid'])) {
		$totalusernum ++;
        }}
} } else{
	$log->LogError("User encountered error with the DB folder structure or access permissions " . basename(__FILE__));
    echo "sqlite db could not be created.  ensure sqlite3 is enabled.";
	$redirect = false;
}
echo '</table>';
if($redirect){
  echo "<p>Congratulations! Everything seems to be in working order.</p>";
  if($totalusernum > 0) {
	header( 'Location: ./index.html' ) ;
	echo "<p><input type='button' onclick=\"window.location = './index.html';\" value='CONTINUE' /></p>";
  } else {
	header( 'Location: ./Portal/setup.php?setup=first' ) ;
	echo "<p><input type='button' onclick=\"window.location = './Portal/setup.php?setup=first';\" value='Setup Users and Configure' /></p>";
  }
} else {
  echo "<p>It looks like some problems were found, please fix them then <input type=\"button\" value=\"reload\" onClick=\"window.location.reload()\"> the page.</p>";
 // echo "<p>If further assistance is needed, please visit the <a href='http://forum.xbmc.org/showthread.php?t=83304' target='_blank'>forum</a> or our <a href='http://mediafrontpage.lighthouseapp.com' target='_blank'>project page</a>.</p>";
}
?>
</body>
</html>
