<?php //control center upgrade info
$DBVERSION = "1.0.0";
$acceptupgrade = 0;
if(isset($_GET['newdbversion'])) {
	$DBVERSION = $_GET['newdbversion'];
}
if(isset($_GET['acceptupgrade'])) {
	$acceptupgrade = '1';
}
require "./Portal/functions.php";
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
  <?php if(false){
}
else{}
$redirect = true;
$version = phpversion();
if(false){
?>
  If you have no text below, your PHP is not working.
<?php
}
else{}
$redirect = true;
$version = phpversion();

echo "<tr><td>PHP Version $version</td><td>";if($version > 5){echo "<img src='media/green-tick.png' height='15px'/>";}else{echo "<img src='media/red-cross.png' height='15px'/>";$redirect = false;} echo "</td></tr>";
if(extension_loaded('libxml')){
  echo "<tr><td>LibXML found</td><td><img src='media/green-tick.png' height='15px'/></td></tr>";
}else{
  echo "<tr><td>LibXML <b>NOT</b> found</td><td><img src='media/red-cross.png' height='15px'/></td></tr>";
  $redirect = false;
}
if(extension_loaded('curl')){
  echo "<tr><td>cURL found </td><td><img src='media/green-tick.png' height='15px'/></td></tr>";
}else{
  echo "<tr><td>cURL <b>NOT</b> found</td><td><img src='media/red-cross.png' height='15px'/></td></tr>";
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
	}
if (!file_exists('./sessions/config.db')){
echo "<tr><td>Trying to Create DB.</td></tr>";
	try {
		$configdb = new PDO('sqlite:./sessions/config.db');
		if($configdb) { 
		echo "<tr><td>DB created.</td><td><img src='media/green-tick.png' height='15px'/></td></tr>"; 
		}
	} catch (PDOException $error) {
        echo "<tr><td>error connecting to the DB,  error message: , $error->getMessage(), </td><td><img src='media/red-cross.png' height='15px'/></td></tr>";
		$redirect = false;
    }
}
if (file_exists('./sessions/config.db')){
  $valid = true;
  if(!is_writable('./sessions/config.db')){
    if(@chmod("./sessions/config.db", 0777)){
      echo "";
    }else{
      echo "<tr><td>Can <b>NOT</b> edit db.  check /sessions/ folder permissions</td><td><img src='media/red-cross.png' height='15px'/></td></tr>";
      $redirect = false;
      $valid = false;
    }
  } else {
		$configdb = new PDO('sqlite:./sessions/config.db');
		function checkDBversion() {
				$configdb = new PDO('sqlite:./sessions/config.db');
				try {
					$thedbversion = "none";
					$sql = "SELECT dbversion FROM controlcenter ORDER BY dbversion DESC LIMIT 1";
					foreach ($configdb->query($sql) as $row)
					{
						if(isset($row['dbversion'])) {
						$thedbversion = $row['dbversion'];
					}}
					return $thedbversion;
				} catch(PDOException $e) {
					  return "none";
				}
		}		
  // write db tables here if they dont exist
   try {
  					$thedbversion = checkDBversion();
					if($thedbversion < $DBVERSION && $thedbversion != 'none') {
						if($acceptupgrade != '1') {
							echo "<tr><td>Database Upgrade Needed.</td><td><img src='media/red-cross.png' height='15px'/></td></tr><tr><td>&nbsp;</td></tr>";
							echo "<tr><td>Export Database</td></tr><tr><td><a href=\"./Portal/exportdb.php?upgrade=1\" id=\"dlconfig\" target=\"_blank\">config.db</a></td></tr>";
							if(file_exists("./sessions/config-bak.db")) {
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
						$configdb = null;
						copy('./sessions/config.db', './sessions/config-bak.db');
						unset($configdb);
						unlink('./sessions/config.db');
						
						echo "<tr><td>Old DB backed up.</td><td><img src='media/green-tick.png' height='15px'/></td></tr>";
						$configdb = new PDO('sqlite:./sessions/config.db');						
					/*
							//  need to stop and do a check.. maybe using break and $redirect = false;
							//  then restart the page and update the db if user input yes.
					
							//custom table upgrades here when needed
							$thenewdbversion = "1.0.2";
							if($thedbversion < $thenewdbversion && $thenewdbversion <= $DBVERSION) {
								//schema update queries
								$execquery = $configdb->exec("INSERT OR REPLACE INTO controlcenter (CCid, dbversion) VALUES (1,'$thenewdbversion')");
							}
							$thedbversion = checkDBversion();
							$thenewdbversion = "1.0.3";
							if($thedbversion < $thenewdbversion && $thenewdbversion <= $DBVERSION) {
								//schema update queries
								$execquery = $configdb->exec("INSERT OR REPLACE INTO controlcenter (CCid, dbversion) VALUES (1,'$thenewdbversion')");
							}
							$thedbversion = checkDBversion();
						echo "<tr><td>DB tables updated to $thedbversion</td><td><img src='media/green-tick.png' height='15px'/></td></tr>";*/
					}
		  $query = "CREATE TABLE IF NOT EXISTS users (userid integer PRIMARY KEY AUTOINCREMENT, username text UNIQUE NOT NULL, password text, navgroupaccess string, homeroom integer, roomgroupaccess string, roomaccess string, roomdeny string, settingsaccess integer NOT NULL, wanenabled integer DEFAULT '0' NOT NULL)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS rooms (roomid integer PRIMARY KEY AUTOINCREMENT, roomname text UNIQUE NOT NULL, addons TEXT NULL)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS rooms_addons (
									rooms_addonsid INTEGER  PRIMARY KEY AUTOINCREMENT NOT NULL,
									roomid INTEGER  NOT NULL,
									addonid TEXT  NOT NULL,
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
									setting10 TEXT  NULL
									)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS roomgroups (roomgroupid integer PRIMARY KEY AUTOINCREMENT, roomgroupname text UNIQUE, roomaccess string, roomdeny string)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS navigation (navid integer PRIMARY KEY AUTOINCREMENT, navname text NULL, navip text, navipw text, mobilew text, mobile text, persistent integer DEFAULT '1' NOT NULL, autorefresh integer DEFAULT '0' NOT NULL)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS navigationgroups (navgroupid integer PRIMARY KEY AUTOINCREMENT, navgroupname text UNIQUE, navitems string)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS controlcenter (CCid integer PRIMARY KEY AUTOINCREMENT, dbversion TEXT)";
		  $execquery = $configdb->exec($query);
			// stamp current version
			$execquery = $configdb->exec("INSERT OR REPLACE INTO controlcenter (CCid, dbversion) VALUES (1,'$DBVERSION')");
			echo "<tr><td>DB tables created, Version: $DBVERSION</td><td><img src='media/green-tick.png' height='15px'/></td></tr>";
	} catch(PDOException $e)
		{
			  echo "<tr><td>Can <b>NOT</b> edit db.  check /sessions/ folder permissions</td><td><img src='media/red-cross.png' height='15px'/></td></tr>";
			  $redirect = false;
		}
/*
		$thisdbversion = checkDBversion();
		if(isset($thisdbversion)) { $thedbversion = $thisdbversion; }
  echo "<tr><td>Settings DB found: Version $thedbversion";
  echo ($valid)?"</td><td><img src='media/green-tick.png' height='15px'/></td></tr>":"</td><td><img src='media/red-cross.png' height='15px'/></td></tr>";		
	*/
		
		

	$totalusernum = 0;
    $sql = "SELECT * FROM users LIMIT 1";
    foreach ($configdb->query($sql) as $row)
        {
		if(isset($row['userid'])) {
		$totalusernum ++;
        }}
} } else{
    echo "sqlite db could not be created.  ensure sqlite3 is enabled.";
	$redirect = false;
}
echo '</table>';
if($redirect){
  echo "<p>Congratulations! Everything seems to be in working order.</p>";
  if($totalusernum > 0) {
	echo "<p><input type='button' onclick=\"window.location = './index.html';\" value='CONTINUE' /></p>";
  } else {
	echo "<p><input type='button' onclick=\"window.location = './Portal/setup.php?setup=first';\" value='Setup Users and Configure' /></p>";
  }
  if (file_exists('./sessions/firstrun.php')){
    unlink('./sessions/firstrun.php');
  }
} else {
  echo "<p>It looks like some problems were found, please fix them then <input type=\"button\" value=\"reload\" onClick=\"window.location.reload()\"> the page.</p>";
 // echo "<p>If further assistance is needed, please visit the <a href='http://forum.xbmc.org/showthread.php?t=83304' target='_blank'>forum</a> or our <a href='http://mediafrontpage.lighthouseapp.com' target='_blank'>project page</a>.</p>";
}
?>
</body>
</html>
