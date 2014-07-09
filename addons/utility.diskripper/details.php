<?php
echo "<a href='#' class='pingicon'><img src='../media/green.png' title='online' style='height:20px;'/></a>";
$ip = $enabledaddonsarray["$THISROOMID"]["$addonid"]['ADDONIP'];
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "$ip?remotecheck=currentlyripping"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
$content = curl_exec($ch);
echo "<span><a href='#' ip='$ip' thisroom='$THISROOMID' class='roominfo-modal'><p class='scrolling'>$content</p></a></span>";
?>