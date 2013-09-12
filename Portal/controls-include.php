<?php  //top section also in /portal/index/.php
	$HOMEROOMU	= $Config2->get('HOMEROOM',"USER$usernumber");
	$ADMINP		= $Config2->get('ADMIN',"USER$usernumber");
	
	$theperm;
	$y = 1;
	if($ADMINP > "1") {
		$x = $Config2->get("ADMINGROUP$ADMINP");
	} else {
		$x = $Config2->get("USER$usernumber");
	}
          if(!empty($x)){
              while($y<=$TOTALROOMS) {
		$theperm = "USRPR$y";
		$ROOMXT = "ROOM$y";
		if($ADMINP > "1") {
			${$theperm} = $Config2->get($ROOMXT,"ADMINGROUP$ADMINP");
		} else {
			${$theperm} = $Config2->get($ROOMXT,"USER$usernumber");
		}
		$y++;
		}
	  }

		  $navlinkcount = '0';
          $navlink;
          $x = $Config2->get("NAVBAR$usernumber");
          if(!empty($x)){
              foreach ($x as $k=>$e){
                  $k = str_ireplace('_', ' ', $k);
                  $navlink["$k"]         = "$e";
				  if($k == 'title') {
				  $navlinkcount++; }
		          }
		      }

		  $gnavlinkcount = '0';
		  $gnavlink;	
          $z = str_ireplace(' ', '', $NAVGROUPS);
	  $y = explode(",",$z);
              foreach ($y as $n) {
          $x = $Config2->get("NAVGROUP$n");
          if(!empty($x)) {
              foreach ($x as $k=>$e) {
                  $k = str_ireplace('_', ' ', $k);
                  $gnavlink["$k"]         = "$e";
				  if($e == 'title') {
				  $gnavlinkcount ++;	}
		          }
		      }
		}

		
		/*
	if(!empty($gnavlink)){
		$c = 1;
		foreach( $gnavlink as $gnav => $gnavlinks) {
			$gnavlinks = explode(",",$gnavlinks);
			
			${$ROOMname} = $gnavlinks[0];
			${$ROOMXBMC} = $gnavlinks[1];
			if($gnavlinks[2] != '') { ${$ROOMXBMCM} = $gnavlinks[2]; } else { ${$ROOMXBMCM} = 0; }
			$c++;
		}
	}		
		*/
?>