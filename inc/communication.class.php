<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file: management of communication with ocsinventoryng agents
// ----------------------------------------------------------------------
/**
 * The datas are XML encoded and compressed with Zlib.
 * XML rules :
 * - XML tags in uppercase
 **/

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
//         $sxml_option = $this->sxml->addChild('OPTION');
//            $sxml_option->addChild('NAME', 'DOWNLOAD');
//            $sxml_param = $sxml_option->addChild('PARAM');
//               $sxml_param->addAttribute('FRAG_LATENCY', '10');
//               $sxml_param->addAttribute('PERIOD_LATENCY', '10');
//               $sxml_param->addAttribute('TIMEOUT', '30');
//               $sxml_param->addAttribute('ON', '1');
//               $sxml_param->addAttribute('TYPE', 'CONF');
//               $sxml_param->addAttribute('CYCLE_LATENCY', '60');
//               $sxml_param->addAttribute('PERIOD_LENGTH', '10');
         $this->sxml->addChild('PROLOG_FREQ', '24'); // a recup dans base config --> pas trouvé
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
    * Check connection string
    *
    *@param &$errors errors string to be alimented if connection ko
    *@return true (connection ok) / false (connection ko)
    **/
   function connectionOK(&$errors='') {
      // TODO : gérer l'encodage, la version
      // pas gérer le REQUEST (tjs pareil)
      $get=$this->get();
      $errors='';
      $sxml_prolog = @simplexml_load_string($get,'SimpleXMLElement', LIBXML_NOCDATA); // @ to avoid xml warnings


      if ($sxml_prolog->DEVICEID=='') {
         $errors.="DEVICEID invalide\n";
      }
      if ($sxml_prolog->QUERY!='PROLOG') {
         $errors.="QUERY invalide\n";
      }
      $result=false;
      if ($errors=='') {
         $result=true;
      }
      return $result;
   }

   /**
    * Import data
    *
    *@param $p_xml XML code to import
    *@param &$p_errors errors string to be alimented if import ko
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

      $xmltag = $this->sxml->QUERY;
      $agent = $PluginFusioninventoryAgent->InfosByKey($this->sxml->DEVICEID);
      if ($xmltag == "PROLOG") {
         return false;
      }
      if (!$PluginFusioninventoryAgentmodule->getAgentsCanDo($xmltag, $agent['id'])) {
         return true;
      }
      
      if (isset($_SESSION['glpi_plugin_fusioninventory']['xmltags']["$xmltag"])) {
         $moduleClass = $_SESSION['glpi_plugin_fusioninventory']['xmltags']["$xmltag"];

         $moduleCommunication = new $moduleClass;
         $errors.=$moduleCommunication->import($this->sxml->DEVICEID, $this->sxml->CONTENT, $p_xml);
      } else {
         $errors.=$LANG['plugin_fusioninventory']['errors'][22].' QUERY : *'.$xmltag."*\n";
      }
      $result=true;
      if ($errors != '') {
         if (isset($_SESSION['glpi_plugin_fusioninventory_processnumber'])) {
            $result=true;
//            $ptap = new PluginFusioninventoryAgentProcess;
//            $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
//                                 array('comment' => $errors));
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

   function addWakeonlan($pxml) {
      $pta = new PluginFusioninventoryAgent;
      $ptt = new PluginFusioninventoryTask;
      $np  = new NetworkPort;

      $agent = $pta->InfosByKey($pxml->DEVICEID);

      $sxml_option = $this->sxml->addChild('OPTION');
         $sxml_option->addChild('NAME', 'WAKEONLAN');

      $tasks = $ptt->ListTask($agent["id"], "WAKEONLAN");
      foreach ($tasks as $taskInfos) {
         if ($taskInfos['itemtype'] == COMPUTER_TYPE) {
            $a_portsList = $np->find('items_id='.$taskInfos['items_id'].' AND itemtype="'.COMPUTER_TYPE.'"');
            foreach ($a_portsList as $data) {
               if ($data['ip'] != "127.0.0.1") {
                  $sxml_param = $sxml_option->addChild('PARAM');
                  $sxml_param->addAttribute('MAC', $data['mac']);
                  $sxml_param->addAttribute('IP', $data['ip']);
               }
            }
         }
      }
   }

   function noSSL() {
      $this->sxml->addAttribute('RESPONSE', "ERROR : SSL REQUIRED BY SERVER");
      $this->setXML($this->getXML());
      echo $this->getSend();
   }

   /**
    * Add logs
    *
    *@param $p_logs logs to write
    *@return nothing (write text in log file)
    **/
   static function addLog($p_logs) {
      global $CFG_GLPI;
      if ($_SESSION['glpi_use_mode']==DEBUG_MODE) {
         file_put_contents(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/communication.log',
                           "\n".time().' : '.$p_logs,
                           FILE_APPEND);
      }
   }


   function getTaskAgent($agent_id) {

      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;
      $moduleRun = $PluginFusioninventoryTaskjobstatus->getTaskjobsAgent($agent_id);
      foreach ($moduleRun as $itemtype => $array) {
         $array_tmp = current($array);
         $className = $array_tmp['className'];
         $class = new $className();
         $this->sxml = $class->Run($array_tmp['itemtype'], $array);
      }
   }


   function addProlog() {
      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');
      $this->sxml->addChild('PROLOG_FREQ', $PluginFusioninventoryConfig->getValue($plugins_id, "inventory_frequence"));
   }



   // Put in INVENTORY plugin
   function addInventory($items_id) {
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
      if ($PluginFusioninventoryAgentmodule->getAgentsCanDo('INVENTORY', $items_id)) {
         $this->sxml->addChild('RESPONSE', "SEND");
      }
   }

}

?>