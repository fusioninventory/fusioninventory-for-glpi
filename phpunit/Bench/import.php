<?php

if (isset($_SERVER['argv'])
        && isset($_SERVER['argv'][1])) {

   
} else {
   echo "problem with arg\n";
   exit (0);
}

include('../emulatoragent.php');
$emulatorAgent = new emulatorAgent;
$emulatorAgent->server_urlpath = "/glpi085/plugins/fusioninventory/";
$emulatorAgent->server_ip = "127.0.0.1";

$overload = 0;
$start_time = microtime(TRUE);

$inputXML = file_get_contents($_SERVER['argv'][1]);
$prologXML = $emulatorAgent->sendProlog($inputXML);
echo $prologXML;
exit;

if (strstr("SERVER OVERLOADED", $prologXML)) {
   $overload++;
}
 
echo "Time: ".(microtime(TRUE) - $start_time);
echo " seconds ";
echo "(overload: ".$overload.")\n";

?>
