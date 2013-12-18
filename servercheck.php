<? //control center upgrade info
$DBVERSION = "1.0.0";
?>
<html>
<head>
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
  <td align=center colspan=2 height=25 style="padding:0;"><div class="widget-head">Welcome to the Control Center</div></td>
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
if (!file_exists('./sessions/config.db')){
echo "<tr><td>Trying to Create DB.</td></tr>";
	try {
		$configdb = new PDO('sqlite:./sessions/config.db');
		if($configdb) { echo "<tr><td>DB created.</td><td><img src='media/green-tick.png' height='15px'/></td></tr>"; }
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
				$sql = "SELECT dbversion FROM controlcenter ORDER BY dbversion DESC LIMIT 1";
				foreach ($configdb->query($sql) as $row)
				{
					if(isset($row['dbversion'])) {
					$thedbversion = $row['dbversion'];
				}}
				return $thedbversion;
		}
		$thedbver = checkDBversion();		
  echo "Settings DB found: Version $thedbver";
  echo ($valid)?"</td><td><img src='media/green-tick.png' height='15px'/></td></tr>":"</td><td><img src='media/red-cross.png' height='15px'/></td></tr>";
  // write db tables here if they dont exist
   try {
		  $query = "CREATE TABLE IF NOT EXISTS users (userid integer PRIMARY KEY AUTOINCREMENT, username text UNIQUE NOT NULL, password text, navgroupaccess string, homeroom integer, roomgroupaccess string, roomaccess string, roomdeny string, settingsaccess integer NOT NULL)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS rooms (roomid integer PRIMARY KEY AUTOINCREMENT, roomname text UNIQUE NOT NULL, ip1 text NOT NULL, ip2 text, mac text)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS roomgroups (roomgroupid integer PRIMARY KEY AUTOINCREMENT, roomgroupname text UNIQUE, roomaccess string, roomdeny string)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS navigation (navid integer PRIMARY KEY AUTOINCREMENT, navname text UNIQUE NULL, navip text, navgroup integer NOT NULL, navgrouptitle integer, mobile text, persistent integer DEFAULT '1' NOT NULL)";
		  $execquery = $configdb->exec($query);
		  $query = "CREATE TABLE IF NOT EXISTS controlcenter (CCid integer PRIMARY KEY AUTOINCREMENT, dbversion TEXT)";
		  $execquery = $configdb->exec($query);
					$thedbversion = checkDBversion();
					if(!isset($thedbversion)) {
						// stamp current version
						$execquery = $configdb->exec("INSERT OR REPLACE INTO controlcenter (CCid, dbversion) VALUES (1,'$DBVERSION')");
						echo "<tr><td>DB tables created</td><td><img src='media/green-tick.png' height='15px'/></td></tr>";
					} elseif($thedbversion < $DBVERSION) {
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
						echo "<tr><td>DB tables updated</td><td><img src='media/green-tick.png' height='15px'/></td></tr>";
					}
	} catch(PDOException $e)
		{
			  echo "<tr><td>Can <b>NOT</b> edit db.  check /sessions/ folder permissions</td><td><img src='media/red-cross.png' height='15px'/></td></tr>";
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