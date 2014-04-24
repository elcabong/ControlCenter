<?php
require_once 'config.php';
if (!isset($_SESSION["$authusername"]) || $_SESSION["$authusername"] != $authusername ) {
    exit;}

    header('Content-Type: application/download');
    header('Content-Disposition: attachment; filename="config.db"');
    header("Content-Length: " . filesize("../sessions/config.db"));

    $fp = fopen("../sessions/config.db", "r");
    fpassthru($fp);
    fclose($fp);


?>