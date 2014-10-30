<?php
if ($authsecured && (!isset($_SESSION['username']) || !$_SESSION['username'] || $_SESSION['username'] != $authusername )) {
	header("Location: login.php");
    exit;
}
?>