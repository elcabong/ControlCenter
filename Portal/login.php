<?php require_once('config.php');
if($_GET['user']=='choose') {
header( "refresh: 0; url=../login.php?user=choose" );
exit;
}
If (!$authsecured) {
header( "refresh: 0; url=index.php" );
    exit;
}
if(isset($_POST['user']) && isset($_POST['password'])) {
    if ($_POST['user']==$authusername && $_POST['password']==$authpassword) {
        $_SESSION["$authusername"] = $authusername;
        header( "refresh: 0; url=index.php" );
        exit;
    } else {
		if(!isset($_SESSION['attempt']) || $_SESSION['attempt'] < 0) {
			$_SESSION['attempt'] = 0;
		}
		$_SESSION['attempt']++;
		if($_SESSION['attempt'] > 2) {
		$_SESSION['attempt'] = 0;
		header( "refresh: 0; url=../login.php?user=choose" );
		exit;
} } }
 ?>
<html>
<head>
<title>Media Center</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=.9, maximum-scale=.9, minimum-scale=.9, target-densitydpi=medium-dpi" />
<link rel="shortcut icon" href="favicon.ico" />
<link rel="stylesheet" type="text/css" href="../css/front.css" />
</head>
<body>
<center>
  <br>
  <br><br>
<form action="login.php" method="post">
    <table width=259 cellpadding=3 cellspacing=0 id=1>
      <tr>
        <td align=center colspan=2 height=25><h2>Media Center Authentication</h2></td>
<? if($_SESSION['attempt'] > 0) { ?>
	<tr>
	<td align=center colspan=4 height=25><br>Invalid Password. Try Again.</td>
	<tr>
	<td align=center height=25>&nbsp; &nbsp;</td>
<? } ?>
<tr>
      <input type="hidden" name="user" value="<?php echo $authusername; ?>">
        <td align=left>&nbsp; &nbsp;Username:</td>
<td align=center>
         <?php echo $authusername; ?>
        </td>
<!--<td align=center>
          <input type='text' name="user" size=15 value="<?php //echo $authusername; ?>" />
        </td>
-->
<tr>
        <td align=left>&nbsp; &nbsp;Password:</td>
<td align=center>
         <input type='password' name="password" size=15 />
        </td>

<tr>
        <td align=center colspan=2>&nbsp;</td>

<tr>
<td align=center colspan=2>
         <input type='submit' value='Log in' />
        </td>

</table>
</form>
<br>
<h2><a href="../index.html">Back to User Selection</a></h2>
</center>
</body>
</html>