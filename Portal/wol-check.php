<?php
///////////////////////////////////////////////////////////
// The following are the only parameters you need to set //
// manually                                              //
///////////////////////////////////////////////////////////
$g_address="255.255.255.255";
$g_mac="xx.xx.xx.xx.xx.xx";

if($_GET['m']) { $g_mac=$_GET['m']; } else { exit; }

$ip_addy = '255.255.255.255'; //'192.168.3.255'; //gethostbyname($g_address);

WakeOnLan($ip_addy, $g_mac);

flush();
function WakeOnLan($addr, $mac) {
  $socket_number = "7";
  $addr_byte = explode(':', $mac);
  $hw_addr = '';
  for ($a=0; $a <6; $a++) $hw_addr .= chr(hexdec($addr_byte[$a]));
  $msg = chr(255).chr(255).chr(255).chr(255).chr(255).chr(255);
  for ($a = 1; $a <= 16; $a++) $msg .= $hw_addr;
  // send it to the broadcast address using UDP
  // SQL_BROADCAST option isn't help!!
  $s = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
  if ($s == false) {
    echo "Error creating socket!\n";
    echo "Error code is '".socket_last_error($s)."' - " . socket_strerror(socket_last_error($s));
    return FALSE;
    }
  else {
    // setting a broadcast option to socket:
    $opt_ret = socket_set_option($s, 1, 6, TRUE);
    if($opt_ret <0) {
      echo "setsockopt() failed, error: " . strerror($opt_ret) . "\n";
      return FALSE;
      }
    if(socket_sendto($s, $msg, strlen($msg), 0, $addr, $socket_number)) {
      echo "Magic Packet sent successfully!";
      socket_close($s);
      return TRUE;
      }
    else {
      echo "Magic packet failed!";
      return FALSE;
      }
    }
  }
exit;
?>