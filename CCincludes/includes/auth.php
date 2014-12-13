<?php
if (!isset($_SESSION) || $_SESSION['usernumber'] == "choose" || ($authsecured && (!isset($_SESSION['username']) || !$_SESSION['username'] || $_SESSION['username'] != $authusername ))) {
	header("Location: login.php");
    exit;
}
?>