Control Center Addon Readme

when the php files are called, they have access to the following data arrays:
  $addonarray["$classification"]["$title"]       >   if your addon is "test.addon5" then $classification = test  and  $title = addon5.   the script should set them when this page is called.
  $enabledaddonsarray["$THISROOMID"]["$classification"."."."$title"]        >    $THISROOMID is already set.
  
  to see what info you can use print this in the php file and run it.
  
  echo "<pre>";
  print_r($addonarray["$classification"]["$title"]);
  echo "<br>";
  print_r($enabledaddonsarray["$THISROOMID"]["$classification"."."."$title"]);
  echo "</pre>";



Required Files:
addon.xml   -    addon name/general/version info
settings.php    -     what is displayed on the settings page for this addon
addoninfo.php   -   this is only required if the addon will display info like nowplaying or send/copy here next to each room in the selection dropdown.
details.php  -  quick info displayed on room detail popup




mediaplayer specific files:
nowplaying.php
nowplayinginfo.php
nowplayingsend.php
nowplayingtime.php




Other Files:
addonquicklinks.php   -   adds quick links that are specific to the rooms and show up to the left of the currently selected room in the top bar.