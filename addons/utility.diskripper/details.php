<?php
echo "<a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a>";
$ip = $enabledaddonsarray["$THISROOMID"]["$addonid"]['ADDONIP'];
$options[CURLOPT_URL] = "$ip?remotecheck=currentlyripping";
$options[CURLOPT_PORT] = 80;
$options[CURLOPT_FRESH_CONNECT] = true;
$options[CURLOPT_FOLLOWLOCATION] = false;
$options[CURLOPT_FAILONERROR] = true;
$options[CURLOPT_RETURNTRANSFER] = true; // curl_exec will not return true if you use this, it will instead return the request body
$options[CURLOPT_TIMEOUT] = 2;
$content = "";
$response = false;
$curl = curl_init();
curl_setopt_array($curl, $options);
$content = curl_exec($curl);
curl_close($curl);
if($content !== false) {
	$content = trim(preg_replace('/\t+/', '', $content));
	echo "<span><a href='#";
	if(isset($_COOKIE["currentRoom$usernumber"]) && $_COOKIE["currentRoom$usernumber"]=="$THISROOMID") {
		echo "DISKRIPPER1";
	}
	echo "' ip='$ip' thisroom='$THISROOMID' class='roominfo-link'><p class='scrolling'>$content</p></a></span>";
}
?>