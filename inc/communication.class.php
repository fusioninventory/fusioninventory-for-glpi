<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Vincent Mazzoni
   @co-author David Durieux
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Class to communicate with agents using XML
 **/
class PluginFusioninventoryCommunication {
   protected $message;


   function __construct() {
      $this->message = new SimpleXMLElement(
                 "<?xml version='1.0' encoding='UTF-8'?><REPLY></REPLY>"
              );
      PluginFusioninventoryToolbox::logIfExtradebug(
         'pluginFusioninventory-communication',
         'New PluginFusioninventoryCommunication object.'
      );
   }



   /**
    * Get readable XML message (add carriage returns)
    *
    * @return readable XML message
    **/
   function getMessage() {
      return $this->message;
   }



   /**
    * Set XML message
    *
    * @param $message XML message
    *
    * @return nothing
    **/
   function setMessage($message) {
      // avoid xml warnings
      $this->message = @simplexml_load_string(
         $message, 'SimpleXMLElement',
         LIBXML_NOCDATA
      );
   }

   /**
    * Send data, using given compression algorithm
    *
    **/
   function sendMessage($compressmode = 'none') {

      if (!$this->message) {
         return;
      }

      switch($compressmode) {
         case 'none':
            header("Content-Type: application/xml");
            echo PluginFusioninventoryToolbox::formatXML($this->message);
            break;

         case 'zlib':
            # rfc 1950
            header("Content-Type: application/x-compress-zlib");
            echo gzcompress(
               PluginFusioninventoryToolbox::formatXML($this->message)
            );
            break;

         case 'deflate':
            # rfc 1951
            header("Content-Type: application/x-compress-deflate");
            echo gzdeflate(
               PluginFusioninventoryToolbox::formatXML($this->message)
            );
            break;

         case 'gzip':
            # rfc 1952
            header("Content-Type: application/x-compress-gzip");
            echo gzencode(
               PluginFusioninventoryToolbox::formatXML($this->message)
            );
            break;

      }
   }



   /**
    * Add logs
    *
    * @param $p_logs logs to write
    *
    * @return nothing (write text in log file)
    **/
   static function addLog($p_logs) {

      if ($_SESSION['glpi_use_mode']==Session::DEBUG_MODE) {
         if (PluginFusioninventoryConfig::isExtradebugActive()) {
            file_put_contents(GLPI_LOG_DIR.'/pluginFusioninventory-communication.log',
                              "\n".time().' : '.$p_logs,
                              FILE_APPEND);
         }
      }
   }



   /**
    * Import data
    *
    * @param $arrayinventory array to import
    *
    * @return TRUE (import ok) / FALSE (import ko)
    **/
   function import($arrayinventory) {

      $pfAgent = new PluginFusioninventoryAgent();

      PluginFusioninventoryToolbox::logIfExtradebug(
         'pluginFusioninventory-communication',
         'Function import().'
      );

      $this->message = $arrayinventory;
      $errors = '';

      $xmltag = $this->message['QUERY'];
      if ($xmltag == "NETDISCOVERY") {
         $xmltag = "NETWORKDISCOVERY";
      }
      if ($xmltag == "SNMPQUERY"
              OR $xmltag == "SNMPINVENTORY") {
         $xmltag = "NETWORKINVENTORY";
      }

      if (!isset($_SESSION['plugin_fusioninventory_agents_id'])) {
         $agent = $pfAgent->InfosByKey($this->message['DEVICEID']);
      } else {
         $agent = array('id' => $_SESSION['plugin_fusioninventory_agents_id']);
      }
      if ($xmltag == "PROLOG") {
         return FALSE;
      }

      if (isset($this->message['CONTENT']['MODULEVERSION'])) {
         $pfAgent->setAgentVersions($agent['id'],
                                    $xmltag,
                                    $this->message['CONTENT']['MODULEVERSION']);
      } else if (isset($this->message['CONTENT']['VERSIONCLIENT'])) {
         $version = str_replace("FusionInventory-Agent_",
                                "",
                                $this->message['CONTENT']['VERSIONCLIENT']);
         $pfAgent->setAgentVersions($agent['id'], $xmltag, $version);
      }

      if (isset($this->message->CONTENT->MODULEVERSION)) {
         $pfAgent->setAgentVersions($agent['id'],
                                    $xmltag,
                                    (string)$this->message->CONTENT->MODULEVERSION);
      } else if (isset($this->message->CONTENT->VERSIONCLIENT)) {
         $version = str_replace("FusionInventory-Agent_",
                                "",
                                (string)$this->message->CONTENT->VERSIONCLIENT);
         $pfAgent->setAgentVersions($agent['id'], $xmltag, $version);
      }


      if (isset($_SESSION['glpi_plugin_fusioninventory']['xmltags']["$xmltag"])) {
         $moduleClass = $_SESSION['glpi_plugin_fusioninventory']['xmltags']["$xmltag"];
         $moduleCommunication = new $moduleClass();
         $errors.=$moduleCommunication->import($this->message['DEVICEID'],
                 $this->message['CONTENT'],
                 $arrayinventory);
      } else {
         $errors.=__('Unattended element in', 'fusioninventory').' QUERY : *'.$xmltag."*\n";
      }
      $result=TRUE;
      // TODO manage this error ( = delete it)
      if ($errors != '') {
         echo $errors;
         if (isset($_SESSION['glpi_plugin_fusioninventory_processnumber'])) {
            $result=TRUE;
         } else {
            // It's PROLOG
            $result=FALSE;
         }
      }
      return $result;
   }



   /**
    * Get all tasks prepared for this agent
    *
    * @param $agent_id interger id of agent
    *
    **/
   function getTaskAgent($agent_id) {

      $pfTask = new PluginFusioninventoryTask();

      /**
       * TODO: the following must be definitely done differently !
       * (... but i'm kind in a hurry right now ;-) )
       */
      $methods = array();
      $classnames = array();
      foreach( PluginFusioninventoryStaticmisc::getmethods() as $method) {
         if (isset($method['classname'])) {
            $methods[] = $method['method'];
            $classnames[$method['method']] = $method['classname'];
         }
      }

      $jobstates = $pfTask->getTaskjobstatesForAgent($agent_id,$methods);
      foreach ($jobstates as $jobstate) {
         $className = $classnames[$jobstate->method];
         if (class_exists($className)) {
            /*
             * TODO: check if use_rest is enabled in Staticmisc::get_methods.
             * Also, this get_methods function need to be reviewed
             */
            if (  $className != "PluginFusioninventoryInventoryComputerESX"
               && $className != "PluginFusioninventoryDeployCommon"
               && $className != "PluginFusioninventoryCollect"
            ) {
               $class = new $className();
               $sxml_temp = $class->run($jobstate);
               PluginFusioninventoryToolbox::append_simplexml(
                  $this->message, $sxml_temp
               );
            }
         }
      }
   }



   /**
    * Set prolog for agent
    *
    **/
   function addProlog() {
      $pfConfig = new PluginFusioninventoryConfig();
      $this->message->addChild('PROLOG_FREQ', $pfConfig->getValue("inventory_frequence"));
   }



   /**
    * order to agent to do inventory if module inventory is activated for this agent
    *
    * @param $items_id interger Id of this agent
    *
    **/
   function addInventory($items_id) {
      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      if ($pfAgentmodule->isAgentCanDo('INVENTORY', $items_id)) {
         $this->message->addChild('RESPONSE', "SEND");
      }
   }



   /**
    * Manage communication with old protocol (XML over POST)
    *
    **/
   function handleOCSCommunication($rawdata, $xml='', $output='ext') {

      // ***** For debug only ***** //
      //$rawdata = gzcompress('');
      // ********** End ********** //

      $config = new PluginFusioninventoryConfig();
      $user   = new User();

//      ob_start();
      if (!isset($_SESSION['glpiID'])) {
         $users_id  = $config->getValue('users_id');
         $_SESSION['glpiID'] = $users_id;
         $user->getFromDB($users_id);
         Session::changeActiveEntities();
         $_SESSION["glpiname"] = $user->getField('name');
         $_SESSION['glpiactiveprofile'] = array();
         $_SESSION['glpiactiveprofile']['interface']  = 'central';
         $_SESSION['glpiactiveprofile']['internet']   = 'w';
         $_SESSION['glpiactiveprofile']['computer']   = 'w';
         $_SESSION['glpiactiveprofile']['monitor']    = 'w';
         $_SESSION['glpiactiveprofile']['printer']    = 'w';
         $_SESSION['glpiactiveprofile']['peripheral'] = 'w';
         $_SESSION['glpiactiveprofile']['networking'] = 'w';

         $_SESSION["glpi_plugin_fusioninventory_profile"]['unmanaged'] = 'w';
      }

      $communication  = new PluginFusioninventoryCommunication();
      $pfToolbox = new PluginFusioninventoryToolbox();

      // identify message compression algorithm
      $agent = new PluginFusioninventoryAgent();
      $agent->disableDebug();
      $compressmode = '';
      if (!empty($xml)) {
            $compressmode = 'none';
      } else if ($_SERVER['CONTENT_TYPE'] == "application/x-compress-zlib") {
            $xml = gzuncompress($rawdata);
            $compressmode = "zlib";
      } else if ($_SERVER['CONTENT_TYPE'] == "application/x-compress-gzip") {
            $xml = $pfToolbox->gzdecode($rawdata);
            $compressmode = "gzip";
      } else if ($_SERVER['CONTENT_TYPE'] == "application/xml") {
            $xml = $rawdata;
            $compressmode = 'none';
      } else {

         # try each algorithm successively
         if (($xml = gzuncompress($rawdata))) {
            $compressmode = "zlib";
         } else if (($xml = $pfToolbox->gzdecode($rawdata))) {
            $compressmode = "gzip";
         } else if (($xml = gzinflate (substr($rawdata, 2)))) {
            // accept deflate for OCS agent 2.0 compatibility,
            // but use zlib for answer
            if (strstr($xml, "<QUERY>PROLOG</QUERY>")
                    AND !strstr($xml, "<TOKEN>")) {
               $compressmode = "zlib";
            } else {
               $compressmode = "deflate";
            }
         } else {
            $xml = $rawdata;
            $compressmode = 'none';
         }
      }
      $agent->restoreDebug();

      // check if we are in ssl only mode
      $ssl = $config->getValue('ssl_only');
      if (
         $ssl == "1"
            AND
         (!isset($_SERVER["HTTPS"]) OR $_SERVER["HTTPS"] != "on")
      ) {
         if ($output == 'glpi') {
            Session::addMessageAfterRedirect('SSL REQUIRED BY SERVER', false, ERROR);
         } else {
            $communication->setMessage("<?xml version='1.0' encoding='UTF-8'?>
<REPLY>
   <ERROR>SSL REQUIRED BY SERVER</ERROR>
</REPLY>");
            $communication->sendMessage($compressmode);
         }
         return;
      }

      PluginFusioninventoryConfig::logIfExtradebug(
         'pluginFusioninventory-dial' . uniqid(),
         $xml
      );

      // Check XML integrity
      $pxml = '';
      if (($pxml = @simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA))) {

      } else if (($pxml = @simplexml_load_string(utf8_encode($xml),
                                                'SimpleXMLElement',
                                                LIBXML_NOCDATA))) {
         $xml = utf8_encode($xml);
      } else {
         $xml = preg_replace ('/<FOLDER>.*?<\/SOURCE>/', '', $xml);
         $pxml = @simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

         if (!$pxml) {
            if ($output == 'glpi') {
               Session::addMessageAfterRedirect('XML not well formed!', false, ERROR);
            } else {
               $communication->setMessage("<?xml version='1.0' encoding='UTF-8'?>
<REPLY>
   <ERROR>XML not well formed!</ERROR>
</REPLY>");
               $communication->sendMessage($compressmode);
            }
            return;
         }
      }

      $_SESSION['plugin_fusioninventory_compressmode'] = $compressmode;

      // Convert XML into PHP array
      $arrayinventory = PluginFusioninventoryFormatconvert::XMLtoArray($pxml);
      unset($pxml);
      $deviceid = '';
      if (isset($arrayinventory['DEVICEID'])) {
         $deviceid = $arrayinventory['DEVICEID'];
      }

      $agent = new PluginFusioninventoryAgent();
      $agents_id = $agent->importToken($arrayinventory);
      $_SESSION['plugin_fusioninventory_agents_id'] = $agents_id;

      if (!$communication->import($arrayinventory)) {

         if ($deviceid != '') {

            $communication->setMessage("<?xml version='1.0' encoding='UTF-8'?>
<REPLY>
</REPLY>");

            $a_agent = $agent->InfosByKey($deviceid);

            // Get taskjob in waiting
            $communication->getTaskAgent($a_agent['id']);
            // ******** Send XML

            $communication->addInventory($a_agent['id']);
            $communication->addProlog();
            $communication->sendMessage($compressmode);
         }
      } else {
         if ($output == 'glpi') {
            Session::addMessageAfterRedirect('XML has been imported succesfully!');
         } else {
            $communication->setMessage("<?xml version='1.0' encoding='UTF-8'?>
<REPLY>
</REPLY>");
            $communication->sendMessage($compressmode);
         }
      }
   }
}

?>
