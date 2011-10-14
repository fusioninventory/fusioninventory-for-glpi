<?php

include('emulatoragent.php');

function prolog() {
   $emulatorAgent = new emulatorAgent;
   // Send prolog to server and wait informations

   $input = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <DEVICEID>port003-2010-06-08-08-13-45</DEVICEID>
  <QUERY>PROLOG</QUERY>
  <TOKEN>CBXTMXLU</TOKEN>
</REQUEST>';
   $emulatorAgent->sendProlog($input);

}


$emulatorAgent = new emulatorAgent;
$emulatorAgent->server_urlpath = "/glpi078/plugins/fusioninventory/front/communication.php";
//$emulatorAgent->server_urlpath = "/ocsinventory";

//$emulatorAgent->server_urlpath = "/glpi072/plugins/fusioninventory/front/plugin_fusioninventory.communication.php";
//$emulatorAgent->server_urlpath = "/plugins/fusioninventory/front/plugin_fusioninventory.communication.php";
$emulatorAgent->server_ip="127.0.0.1";
//$emulatorAgent->Start('192.168.1.14','62354', 'prolog');

   $input_xml = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <DEVICEID>agenttest-2010-03-09-09-41-28</DEVICEID>
  <QUERY>PROLOG</QUERY>
  <TOKEN>CBXTMXLU</TOKEN>
</REQUEST>';
   $emulatorAgent->sendProlog($input_xml);


   $input_xml = file_get_contents("netdiscovery.xml");
$time_start = microtime(true);
   $return = $emulatorAgent->sendProlog($input_xml);
$time_end = microtime(true);
$time = $time_end - $time_start;

echo "Add/update in $time seconds\n";
   print_r($return);

?>
