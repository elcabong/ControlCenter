<?php
require_once 'config.php';
if (!isset($_SESSION["$authusername"]) || $_SESSION["$authusername"] != $authusername ) {
    exit;}

if(isset($_GET['bak']) &&	$_GET['bak'] == '1') { 
    header('Content-Type: application/download');
    header('Content-Disposition: attachment; filename="config-bak.db"');
    header("Content-Length: " . filesize("../sessions/config-bak.db"));

    $fp = fopen("../sessions/config-bak.db", "r");
    fpassthru($fp);
    fclose($fp);

} else {
    header('Content-Type: application/download');
    header('Content-Disposition: attachment; filename="config.db"');
    header("Content-Length: " . filesize("../sessions/config.db"));

    $fp = fopen("../sessions/config.db", "r");
    fpassthru($fp);
    fclose($fp);
}
?>