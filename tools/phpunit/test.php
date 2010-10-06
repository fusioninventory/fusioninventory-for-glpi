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
$emulatorAgent->server_urlpath = "/glpi072/plugins/fusioninventory/front/plugin_fusioninventory.communication.php";
//$emulatorAgent->Start('192.168.1.14','62354', 'prolog');


   $input = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <DEVICEID>port003-2010-06-08-08-13-45</DEVICEID>
  <QUERY>PROLOG</QUERY>
  <TOKEN>CBXTMXLU</TOKEN>
</REQUEST>';
   $emulatorAgent->sendProlog($input);

?>