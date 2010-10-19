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
//$emulatorAgent->Start('192.168.1.14','62354', 'prolog');

   $input_xml = file_get_contents("xml/inventory_snmp/1.2/cisco2960.xml");

   $return = $emulatorAgent->sendProlog($input_xml);
   print_r($return);

?>
