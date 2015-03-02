<?php if($_GET['ip']) { $ip=$_GET['ip']; } // if($_GET['addon']) { $addonid=$_GET['addon']; } 
			$nowplayingarray = array();
			require "class.php";
			$KODI = new KODI();
			$kodialive = $KODI->Ping("$ip");
			if($kodialive == "alive") {
				$filetype='';
				// get active player
				$activeplayerid = $KODI->GetActivePlayer("$ip");
				$nowplayingarray = $KODI->GetPlayingItemInfo("$ip","$activeplayerid");
				
			}
			if(!isset($activeplayerid) || $activeplayerid == "none") {
				echo "There is nothing currently playing.";
				exit; 
			}
			return $nowplayingarray;
?>