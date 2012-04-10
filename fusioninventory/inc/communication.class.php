<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Vincent Mazzoni
   @co-author David Durieux
   @copyright Copyright (c) 2010-2012 FusionInventory team
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
         $message,'SimpleXMLElement', 
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
    * Import data
    *
    * @param $p_xml XML code to import
    * 
    * @return true (import ok) / false (import ko)
    **/
   function import($p_xml) {
      global $LANG;
      
      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $pfAgent = new PluginFusioninventoryAgent();

      PluginFusioninventoryToolbox::logIfExtradebug(
         'pluginFusioninventory-communication',
         'Function import().'
      );
      // TODO : gérer l'encodage, la version
      // Do not manage <REQUEST> element (always the same)
      
      $_SESSION["plugin_fusioninventory_disablelocks"] = 1;
      $this->message = $p_xml;
      $errors = '';

      $xmltag = (string)$this->message->QUERY;
      $agent = $pfAgent->InfosByKey($this->message->DEVICEID);
      if ($xmltag == "PROLOG") {
         return false;
      }
      if (!isset($agent['id'])) {
         return true;
      }

      if (isset($this->message->CONTENT->MODULEVERSION)) {
         $pfAgent->setAgentVersions($agent['id'], $xmltag, (string)$this->message->CONTENT->MODULEVERSION);
      } else if (isset($this->message->CONTENT->VERSIONCLIENT)) {
         $version = str_replace("FusionInventory-Agent_", "", (string)$this->message->CONTENT->VERSIONCLIENT);
         $pfAgent->setAgentVersions($agent['id'], $xmltag, $version);
      }

      if (!$pfAgentmodule->getAgentCanDo($xmltag, $agent['id'])) {
         return true;
      }

      if (isset($_SESSION['glpi_plugin_fusioninventory']['xmltags']["$xmltag"])) {
         $moduleClass = $_SESSION['glpi_plugin_fusioninventory']['xmltags']["$xmltag"];
         $moduleCommunication = new $moduleClass();
         $errors.=$moduleCommunication->import($this->message->DEVICEID, 
                 $this->message->CONTENT, 
                 $p_xml);
      } else {
         $errors.=$LANG['plugin_fusioninventory']['errors'][22].' QUERY : *'.$xmltag."*\n";
      }
      $result=true;
      if ($errors != '') {
         echo $errors;
         if (isset($_SESSION['glpi_plugin_fusioninventory_processnumber'])) {
            $result=true;
         } else {
            // It's PROLOG
            $result=false;
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

      $pfTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $moduleRun = $pfTaskjobstatus->getTaskjobsAgent($agent_id);
      foreach ($moduleRun as $className => $array) {
         if (class_exists($className)) {
            if ($className != "PluginFusioninventoryInventoryComputerESX") {
               $class = new $className();
               $sxml_temp = $class->run($array);
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
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
      $this->message->addChild('PROLOG_FREQ', $pfConfig->getValue($plugins_id, "inventory_frequence", ''));
   }



   /**
    * order to agent to do inventory if module inventory is activated for this agent
    *
    * @param $items_id interger Id of this agent
    *
    **/
   function addInventory($items_id) {
      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      if ($pfAgentmodule->getAgentCanDo('INVENTORY', $items_id)) {
         $this->message->addChild('RESPONSE', "SEND");
      }
   }
}

?>
