<?php
if($howmanyaddons<2){
echo "<a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a>";
}
$ip = $enabledaddonsarray["$THISROOMID"]["$addonid"]['ADDONIP'];
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "$ip?remotecheck=currentlyripping"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
$content = curl_exec($ch);
$content = trim(preg_replace('/\t+/', '', $content));
echo "<span><a href='#";
if(isset($_COOKIE["currentRoom$usernumber"]) && $_COOKIE["currentRoom$usernumber"]=="$THISROOMID") {
	echo "DISKRIPPER1";
}
echo "' ip='$ip' thisroom='$THISROOMID' class='roominfo-link'><p class='scrolling'>$content</p></a></span>";
?>