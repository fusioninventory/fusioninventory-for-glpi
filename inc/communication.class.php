<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: Vincent MAZZONI
   Co-authors of file: David DURIEUX
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Class to communicate with agents using XML
 **/
class PluginFusioninventoryCommunication {
   private $deviceId, $ptd, $type='';
   protected $sxml;

   
   function __construct() {
      $this->sxml = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><REPLY></REPLY>");
         PluginFusioninventoryCommunication::addLog('New PluginFusioninventoryCommunication object.');
   }


   
   /**
    * Get readable XML code (add carriage returns)
    *
    *@return readable XML code
    **/
   function getXML() {
      return $this->formatXmlString();
   }


   
   /**
    * Set XML code
    *
    *@param $p_xml XML code
    *@return nothing
    **/
   function setXML($p_xml) {
      $this->sxml = @simplexml_load_string($p_xml,'SimpleXMLElement', LIBXML_NOCDATA); // @ to avoid xml warnings
   }


   
   /**
    * Get XML code
    *
    *@return XML code
    **/
   function get() {
      if ($GLOBALS["HTTP_RAW_POST_DATA"] == '') {
         return '';
      } else {
         return gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);
      }
   }


   
   /**
    * Get data ready to be send (gzcompressed)
    * 
    *@return data ready to be send
    **/
   function getSend() {
      return gzcompress($this->sxml->asXML());
   }


  
   /**
    * Import data
    *
    *@param $p_xml XML code to import
    *@param &$p_errors errors string to be alimented if import ko
    * 
    *@return true (import ok) / false (import ko)
    **/
   function import($p_xml, &$p_errors='') {
      global $LANG;

      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();

      PluginFusioninventoryCommunication::addLog('Function import().');
      // TODO : gérer l'encodage, la version
      // Do not manage <REQUEST> element (always the same)
      $this->setXML($p_xml);
      $errors = '';

      $xmltag = (string)$this->sxml->QUERY;
      $agent = $PluginFusioninventoryAgent->InfosByKey($this->sxml->DEVICEID);
      if ($xmltag == "PROLOG") {
         return false;
      }
      if (!isset($agent['id'])) {
         return true;
      }
      if (isset($this->sxml->CONTENT->MODULEVERSION)) {
         $PluginFusioninventoryAgent->setAgentVersions($agent['id'], $xmltag, (string)$this->sxml->CONTENT->MODULEVERSION);
      } else if (isset($this->sxml->CONTENT->VERSIONCLIENT)) {
         $version = str_replace("FusionInventory-Agent_", "", (string)$this->sxml->CONTENT->VERSIONCLIENT);
         $PluginFusioninventoryAgent->setAgentVersions($agent['id'], $xmltag, $version);
      }

      if (!$PluginFusioninventoryAgentmodule->getAgentsCanDo($xmltag, $agent['id'])) {
         return true;
      }
      
      if (isset($_SESSION['glpi_plugin_fusioninventory']['xmltags']["$xmltag"])) {
         $moduleClass = $_SESSION['glpi_plugin_fusioninventory']['xmltags']["$xmltag"];

         $moduleCommunication = new $moduleClass();
         $errors.=$moduleCommunication->import($this->sxml->DEVICEID, $this->sxml->CONTENT, $p_xml);
      } else {
         $errors.=$LANG['plugin_fusioninventory']['errors'][22].' QUERY : *'.$xmltag."*\n";
      }
      $result=true;
      if ($errors != '') {
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
    * Add indent in XML to have nice XML format
    *
    *@return XML
    **/
   function formatXmlString() {
      $xml = str_replace("><", ">\n<", $this->sxml->asXML());
      $token      = strtok($xml, "\n");
      $result     = '';
      $pad        = 0;
      $matches    = array();
      $indent     = 0;

      while ($token !== false) {
         // 1. open and closing tags on same line - no change
         if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
            $indent=0;
         // 2. closing tag - outdent now
         elseif (preg_match('/^<\/\w/', $token, $matches)) :
            $pad = $pad-3;
         // 3. opening tag - don't pad this one, only subsequent tags
         elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
            $indent=3;
         else :
            $indent = 0;
         endif;

         $line    = str_pad($token, strlen($token)+$pad, '  ', STR_PAD_LEFT);
         $result .= $line . "\n";
         $token   = strtok("\n");
         $pad    += $indent;
      }
      $this->setXML($result);
      return $this->sxml->asXML();
   }



   /**
    * Return error to agent because SSL is required
    *
   **/
   function noSSL() {
      $this->sxml->addAttribute('RESPONSE', "ERROR : SSL REQUIRED BY SERVER");
      $this->setXML($this->getXML());
      echo $this->getSend();
   }


   
   /**
    * Return an empty answer to agent if nothing to import
    *
   **/
   function emptyAnswer() {
      $this->setXML($this->getXML());
      echo $this->getSend();
   }


   
   /**
    * Add logs
    *
    *@param $p_logs logs to write
    * 
    *@return nothing (write text in log file)
    **/
   static function addLog($p_logs) {
      global $CFG_GLPI;
      if ($_SESSION['glpi_use_mode']==DEBUG_MODE) {
         file_put_contents(GLPI_LOG_DIR.'/fusioninventorycommunication.log',
                           "\n".time().' : '.$p_logs,
                           FILE_APPEND);
      }
   }

   

   /**
    * Get all tasks prepared for this agent
    *
    *@param $agent_id interger id of agent
    *
    **/
   function getTaskAgent($agent_id) {

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $moduleRun = $PluginFusioninventoryTaskjobstatus->getTaskjobsAgent($agent_id);
      foreach ($moduleRun as $className => $array) {
         $class = new $className();
         $this->sxml = $class->Run($array);
      }
   }

   

   /**
    * Set prolog for agent
    *
    **/
   function addProlog() {
      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
      $this->sxml->addChild('PROLOG_FREQ', $PluginFusioninventoryConfig->getValue($plugins_id, "inventory_frequence"));
   }



   /**
    * order to agent to do inventory if module inventory is activated for this agent
    *
    *@param $items_id interger Id of this agent
    *
    **/
   function addInventory($items_id) {
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      if ($PluginFusioninventoryAgentmodule->getAgentsCanDo('INVENTORY', $items_id)) {
         $this->sxml->addChild('RESPONSE', "SEND");
      }
   }
}

?>