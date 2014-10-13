<?php  if(isset($_GET['ip'])) { $ip=$_GET['ip']; }
						$checkxbmc = "$ip/jsonrpc?request={%22jsonrpc%22%3A%20%222.0%22%2C%20%22method%22%3A%20%22JSONRPC.Ping%22%2C%22id%22%3A%201}";
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_URL, "$checkxbmc");
						curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
						if(curl_exec($ch) === false)
							{
								$log->LogError("Addon Error TEST: xbmc: curl error to host $ip curl_error($ch)" . curl_error($ch) . " ");
							}
						$output = curl_exec($ch);
						$jsoncheckxbmc = json_decode($output,true);
						if(empty($jsoncheckxbmc['result'])) {
							//echo "There is nothing currently playing.";
						} else {
							$filetype='';
							// get active player
							$getactiveplayer = "$ip/jsonrpc?request={%22jsonrpc%22%3A%20%222.0%22%2C%20%22method%22%3A%20%22Player.GetActivePlayers%22%2C%22id%22%3A%201}";
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_URL, "$getactiveplayer");
							curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
							$output = curl_exec($ch);
							$jsonactiveplayer = json_decode($output,true);
							if(empty($jsonactiveplayer['result'])) {
								//echo "There is nothing currently playing.";
								return;
							} else {
								$activeplayerid = '-1';
								if(isset($jsonactiveplayer['result'][0]['playerid'])) {
									$activeplayerid = $jsonactiveplayer['result'][0]['playerid'];
								}
								if($activeplayerid==0) {
									$filetype='';
									$therequest = urlencode("\"jsonrpc\": \"2.0\", \"method\": \"Player.GetItem\", \"params\": { \"properties\": [\"album\",\"artist\",\"director\",\"writer\",\"tagline\",\"episode\",\"file\",\"title\",\"showtitle\",\"season\",\"genre\",\"year\",\"rating\",\"runtime\",\"firstaired\",\"plot\",\"fanart\",\"thumbnail\",\"tvshowid\"], \"playerid\": 0 }, \"id\": \"1\"");
									//$jsoncontents = "$ip/jsonrpc?request={%22jsonrpc%22:%20%222.0%22,%20%22method%22:%20%22Player.GetItem%22,%20%22params%22:%20{%20%22properties%22:%20[%22album%22,%22title%22,%22year%22],%20%22playerid%22:%200%20},%20%22id%22:%20%221%22}";
									$jsoncontents = "$ip/jsonrpc?request={".$therequest."}";
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_URL, "$jsoncontents");
									curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
									$output = curl_exec($ch);
									$jsonnowplaying = json_decode($output,true);
									if($jsonnowplaying['result']['item']['label']!='') {
										$filetype=$jsonnowplaying['result']['item']['type'];
										$theartist = $jsonnowplaying['result']['item']['artist'];
										$thealbum = $jsonnowplaying['result']['item']['album'];
										$thelabel = $jsonnowplaying['result']['item']['label'];
										$thetitle = $jsonnowplaying['result']['item']['title'];
										$theyear = $jsonnowplaying['result']['item']['year'];
										$thesongid = $jsonnowplaying['result']['item']['id'];
									}
									$jsoncontents = "$ip/jsonrpc?request={%22jsonrpc%22:%20%222.0%22,%20%22method%22:%20%22Playlist.GetItems%22,%20%22params%22:%20{%20%22playlistid%22:%200%20},%20%22id%22:%20%221%22}";
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_URL, "$jsoncontents");
									curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
									$output = curl_exec($ch);
									$jsonplaylist = json_decode($output,true);								
									// info for playlist items
								//	print_r($jsonplaylist);
								//	return $jsonnowplaying;
								} elseif($activeplayerid==1) {
									$filetype='';
	//								$jsoncontents = "$ip/jsonrpc?request={%22jsonrpc%22:%20%222.0%22,%20%22method%22:%20%22Player.GetItem%22,%20%22params%22:%20{%20%22properties%22:%20[%22file%22,%22title%22,%22episode%22,%22showtitle%22,%22season%22,%22year%22],%20%22playerid%22:%201%20},%20%22id%22:%20%221%22}";
									$therequest = urlencode("\"jsonrpc\": \"2.0\", \"method\": \"Player.GetItem\", \"params\": { \"properties\": [\"director\",\"writer\",\"tagline\",\"episode\",\"file\",\"title\",\"showtitle\",\"season\",\"genre\",\"year\",\"rating\",\"runtime\",\"firstaired\",\"plot\",\"fanart\",\"thumbnail\",\"tvshowid\"], \"playerid\": 1 }, \"id\": \"1\"");
									$jsoncontents = "$ip/jsonrpc?request={".$therequest."}";
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_URL, "$jsoncontents");
									curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
									$output = curl_exec($ch);
									$jsonnowplaying = json_decode($output,true);
									//print_r($jsonnowplaying);
									if($jsonnowplaying['result']['item']['label']!='') {
										if(isset($jsonnowplaying['result']['item']['file'])) { $filepath=$jsonnowplaying['result']['item']['file']; }
										if(isset($jsonnowplaying['result']['item']['type'])) { $filetype=$jsonnowplaying['result']['item']['type']; }
										if(isset($jsonnowplaying['result']['item']['label'])) { $thelabel = $jsonnowplaying['result']['item']['label']; }
										if(isset($jsonnowplaying['result']['item']['showtitle'])) { $theshowtitle = $jsonnowplaying['result']['item']['showtitle']; }
										if(isset($jsonnowplaying['result']['item']['title'])) { $thetitle = $jsonnowplaying['result']['item']['title']; }
										if(isset($jsonnowplaying['result']['item']['season'])) { $theshowseason = $jsonnowplaying['result']['item']['season']; }
										if(isset($jsonnowplaying['result']['item']['episode'])) { $theshowepisode = str_pad($jsonnowplaying['result']['item']['episode'], 2, '0', STR_PAD_LEFT); }
										if(isset($jsonnowplaying['result']['item']['year'])) { $theyear = $jsonnowplaying['result']['item']['year']; }
									}
									$jsoncontents = "$ip/jsonrpc?request={%22jsonrpc%22:%20%222.0%22,%20%22method%22:%20%22Playlist.GetItems%22,%20%22params%22:%20{%20%22playlistid%22:%201%20},%20%22id%22:%20%221%22}";
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_URL, "$jsoncontents");
									curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 1);
									$output = curl_exec($ch);
									$jsonplaylist = json_decode($output,true);
									//print_r($jsonplaylist);
									//$test = in_array("$thelabel",$jsonplaylist);
								/*	echo $thelabel;
									if(in_array_like("$thelabel",$jsonplaylist)){
									echo "in the array";
									} else { echo "not in array"; }*/
									
									$tvshowneedles = array('1x','2x','3x','4x','5x','6x','7x','8x','9x','0x','s0','S0','00E','00e','e0','E0','e1','E1');
									$movieneedles = array('(19','(20','[19','[20');
									
									return $jsonnowplaying;
								} elseif($activeplayerid==2) {
									echo "pics";
									//return $jsonnowplaying;
								}
							}			
						}
?>
