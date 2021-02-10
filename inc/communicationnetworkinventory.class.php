<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the communication for the network inventory
 * with the agents.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Vincent Mazzoni
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * Manage the communication of network inventory feature with the agents.
 */
class PluginFusioninventoryCommunicationNetworkInventory {

   /**
    * Define protected variables
    *
    * @var null
    */
   private $logFile, $agent, $arrayinventory;

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_networkequipment';


   /**
    * __contruct function where fill logFile if extradebug enabled
    */
   function __construct() {
      if (PluginFusioninventoryConfig::isExtradebugActive()) {
         $this->logFile = GLPI_LOG_DIR.'/fusioninventorycommunication.log';
      }
   }


   /**
    * Import data, so get data from agent to put in GLPI
    *
    * @param string $p_DEVICEID device_id of the agent
    * @param array $a_CONTENT
    * @param array $arrayinventory
    */
   function import($p_DEVICEID, $a_CONTENT, $arrayinventory) {

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkInventory->import().');

      $pfAgent = new PluginFusioninventoryAgent();
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();

      $this->agent = $pfAgent->infoByKey($p_DEVICEID);
      $this->arrayinventory = $arrayinventory;

      if (!isset($a_CONTENT['PROCESSNUMBER'])) {
         $a_CONTENT['PROCESSNUMBER'] = 1;
      }
      $_SESSION['glpi_plugin_fusioninventory_processnumber'] = $a_CONTENT['PROCESSNUMBER'];
      if ((!isset($a_CONTENT['AGENT']['START'])) AND (!isset($a_CONTENT['AGENT']['END']))) {
          $nb_devices = 0;
         if (isset($a_CONTENT['DEVICE'])) {
            if (is_int(key($a_CONTENT['DEVICE']))) {
               $nb_devices = count($a_CONTENT['DEVICE']);
            } else {
               $nb_devices = 1;
            }
         }

          $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] =
              $a_CONTENT['PROCESSNUMBER'];
          $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'] = $this->agent['id'];
          $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'] = 'PluginFusioninventoryAgent';
          $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'] = '6';
          $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = $nb_devices.
              ' ==devicesqueried==';
          $this->addtaskjoblog();
      }

      $this->importContent($a_CONTENT);

      if (isset($a_CONTENT['AGENT']['END'])) {
         $cnt = countElementsInTable('glpi_plugin_fusioninventory_taskjoblogs',
            [
               'plugin_fusioninventory_taskjobstates_id' => $a_CONTENT['PROCESSNUMBER'],
               'comment'                                 => ["LIKE", '%[==detail==] Update %'],
            ]);

          $pfTaskjobstate->changeStatusFinish(
                  $a_CONTENT['PROCESSNUMBER'],
                  $this->agent['id'],
                  'PluginFusioninventoryAgent',
                  '0',
                  'Total updated:'.$cnt);
      }
      if (isset($a_CONTENT['AGENT']['START'])) {
          $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] =
              $a_CONTENT['PROCESSNUMBER'];
          $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'] = $this->agent['id'];
          $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'] = 'PluginFusioninventoryAgent';
          $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'] = '6';
          $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '==inventorystarted==';
          $this->addtaskjoblog();
      }
   }


   /**
    * Import the content (where have all devices)
    *
    * @param array $arrayinventory
    * @return string errors or empty string
    */
   function importContent($arrayinventory) {

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkInventory->importContent().');
      $pfAgent = new PluginFusioninventoryAgent();

      $errors='';
      $nbDevices = 0;
      foreach ($arrayinventory as $childname=>$child) {
         PluginFusioninventoryCommunication::addLog($childname);
         switch ($childname) {

            case 'DEVICE' :
               $a_devices = [];
               if (is_int(key($child))) {
                  $a_devices = $child;
               } else {
                  $a_devices[] = $child;
               }
               $xml_num = 0;
               foreach ($a_devices as $dchild) {
                  $_SESSION['plugin_fusioninventory_xmlnum'] = $xml_num;
                  $a_inventory = [];
                  if (isset($dchild['INFO'])) {
                     if ($dchild['INFO']['TYPE'] == "NETWORKING" || $dchild['INFO']['TYPE'] == "STORAGE") {
                        $a_inventory = PluginFusioninventoryFormatconvert::networkequipmentInventoryTransformation($dchild);
                     } else if ($dchild['INFO']['TYPE'] == "PRINTER") {
                        $a_inventory = PluginFusioninventoryFormatconvert::printerInventoryTransformation($dchild);
                     }
                  }
                  if (isset($dchild['ERROR'])) {
                     $itemtype = "";
                     if ($dchild['ERROR']['TYPE'] == "NETWORKING" || $dchild['ERROR']['TYPE'] == "STORAGE") {
                        $itemtype = "NetworkEquipment";
                     } else if ($dchild['ERROR']['TYPE'] == "PRINTER") {
                        $itemtype = "Printer";
                     }
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '[==detail==] '.
                             $dchild['ERROR']['MESSAGE'].' [['.$itemtype.'::'.
                             $dchild['ERROR']['ID'].']]';
                     $this->addtaskjoblog();
                  } else if ($a_inventory['PluginFusioninventory'.$a_inventory['itemtype']]['sysdescr'] == ''
                              && $a_inventory[$a_inventory['itemtype']]['name'] == ''
                              && $a_inventory[$a_inventory['itemtype']]['serial'] == '') {

                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] =
                              '[==detail==] No informations [['.$a_inventory['itemtype'].'::'.$dchild['INFO']['ID'].']]';
                     $this->addtaskjoblog();
                  } else {
                     if (count($a_inventory) > 0) {
                        if ($a_inventory['itemtype'] == 'NetworkEquipment') {
                           // Detect if the device is a stacked switch
                           $staked = $this->is_stacked_switch($a_inventory);
                           if ($staked) {
                              $devices = $this->split_stacked_switch($a_inventory);
                              foreach ($devices as $device) {
                                 $this->sendCriteria($device);
                              }
                              // So we had create the devices of the stack, no need continue to import the global stack
                              return $errors;
                           }
                           $wirelesscontroller = $this->is_wireless_controller($a_inventory);
                           if ($wirelesscontroller) {
                              $accesspoints = $this->get_wireless_controller_access_points($a_inventory);
                              foreach ($accesspoints as $device) {
                                 $this->sendCriteria($device);
                              }
                              // we continue to manage / import / update the wireless controller
                           }
                        }
                        $errors .= $this->sendCriteria($a_inventory);
                        $nbDevices++;
                     }
                  }
                  $xml_num++;
               }
               break;

            case 'AGENT' :
               if (isset($this->arrayinventory['CONTENT']['AGENT']['AGENTVERSION'])) {
                  $agent = $pfAgent->infoByKey($this->arrayinventory['DEVICEID']);
                  $agent['fusioninventory_agent_version'] =
                                       $this->arrayinventory['CONTENT']['AGENT']['AGENTVERSION'];
                  $agent['last_agent_update'] = date("Y-m-d H:i:s");
                  $pfAgent->update($agent);
               }
               break;

            case 'PROCESSNUMBER' :
               break;

            case 'MODULEVERSION' :
               break;

            default :
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '[==detail==] '.
                           __('Unattended element in', 'fusioninventory').' CONTENT : '.$childname;
               $this->addtaskjoblog();
         }
      }
      return $errors;
   }


   /**
    * import process of one device
    *
    * @global SimpleXMLElement $PLUGIN_FUSIONINVENTORY_XML
    * @param string $itemtype
    * @param integer $items_id
    * @param array $a_inventory
    * @param boolean $no_history notice if changes must be logged or not
    * @return string errors or empty string
    */
   function importDevice($itemtype, $items_id, $a_inventory, $no_history) {
      global $PLUGIN_FUSIONINVENTORY_XML;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkInventory->importDevice().');

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();
      $a_inventory = $pfFormatconvert->replaceids($a_inventory, $itemtype, $items_id);

      // Write XML file
      if (count($a_inventory) > 0
              AND isset($_SESSION['plugin_fusioninventory_xmlnum'])) {
         $xml = $PLUGIN_FUSIONINVENTORY_XML->CONTENT->DEVICE[$_SESSION['plugin_fusioninventory_xmlnum']]->asXML();
         PluginFusioninventoryToolbox::writeXML(
                 $items_id,
                 $xml,
                 $itemtype);
      }

      $errors='';
      $this->deviceId=$items_id;

      $serialized = gzcompress(serialize($a_inventory));

      if (isset($a_inventory['name'])
              && $a_inventory['name'] == '') {
         unset($a_inventory['name']);
      }
      if (isset($a_inventory['serial'])
              && $a_inventory['serial'] == '') {
         unset($a_inventory['serial']);
      }

      switch ($itemtype) {

         case 'Printer':
            $pfiPrinterLib = new PluginFusioninventoryInventoryPrinterLib();
            $a_inventory['PluginFusioninventoryPrinter']['serialized_inventory'] =
                        Toolbox::addslashes_deep($serialized);
            $pfiPrinterLib->updatePrinter($a_inventory, $items_id, $no_history);
            break;

         case 'NetworkEquipment':
            $pfiNetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
            $a_inventory['PluginFusioninventoryNetworkEquipment']['serialized_inventory'] =
                        Toolbox::addslashes_deep($serialized);
            $pfiNetworkEquipmentLib->updateNetworkEquipment($a_inventory, $items_id, $no_history);
            break;

         default:
            return __('Unattended element in', 'fusioninventory').' TYPE : '.$a_inventory['itemtype']."\n";
      }
      return '';
   }


   /**
    * Send inventory information to import rules
    *
    * @param array $a_inventory
    * @return string errors or empty string
    */
   function sendCriteria($a_inventory) {

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkInventory->sendCriteria().');

      $errors = '';

      // Manual blacklist
      if ($a_inventory[$a_inventory['itemtype']]['serial'] == 'null') {
         $a_inventory[$a_inventory['itemtype']]['serial'] = '';
      }
      // End manual blacklist

      $_SESSION['SOURCE_XMLDEVICE'] = $a_inventory;
      $input = [];

      // Global criterias

      if (!empty($a_inventory[$a_inventory['itemtype']]['serial'])) {
         $input['serial'] = $a_inventory[$a_inventory['itemtype']]['serial'];
      }
      if ($a_inventory['itemtype'] == 'NetworkEquipment') {
         if (!empty($a_inventory[$a_inventory['itemtype']]['mac'])) {
            $input['mac'][] = $a_inventory[$a_inventory['itemtype']]['mac'];
         }
         $input['itemtype'] = "NetworkEquipment";
      } else if ($a_inventory['itemtype'] == 'Printer') {
         $input['itemtype'] = "Printer";
         if (isset($a_inventory['networkport'])) {
            $a_ports = [];
            if (is_int(key($a_inventory['networkport']))) {
               $a_ports = $a_inventory['networkport'];
            } else {
               $a_ports[] = $a_inventory['networkport'];
            }
            foreach ($a_ports as $port) {
               if (!empty($port['mac'])) {
                  $input['mac'][] = $port['mac'];
               }
               if (!empty($port['ip'])) {
                  $input['ip'][] = $port['ip'];
               }
            }
         }
      }
      if (!empty($a_inventory[$a_inventory['itemtype']]['networkequipmentmodels_id'])) {
         $input['model'] = $a_inventory[$a_inventory['itemtype']]['networkequipmentmodels_id'];
      }
      if (!empty($a_inventory[$a_inventory['itemtype']]['name'])) {
         $input['name'] = $a_inventory[$a_inventory['itemtype']]['name'];
      }

      $_SESSION['plugin_fusinvsnmp_datacriteria'] = serialize($input);
      $_SESSION['plugin_fusioninventory_classrulepassed'] =
                                 "PluginFusioninventoryCommunicationNetworkInventory";
      $rule = new PluginFusioninventoryInventoryRuleImportCollection();
      PluginFusioninventoryConfig::logIfExtradebug("pluginFusioninventory-rules",
                                                   "Input data : ".print_r($input, true));
      $data = $rule->processAllRules($input, []);
      PluginFusioninventoryConfig::logIfExtradebug("pluginFusioninventory-rules",
                                                   $data);
      if (isset($data['action'])
             && ($data['action'] == PluginFusioninventoryInventoryRuleImport::LINK_RESULT_DENIED)) {

         $a_text = [];
         foreach ($input as $key=>$data) {
            if (is_array($data)) {
               $a_text[] = "[".$key."]:".implode(", ", $data);
            } else {
               $a_text[] = "[".$key."]:".$data;
            }
         }
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '==importdenied== '.
                 implode(", ", $a_text);
         $this->addtaskjoblog();

         $pfIgnoredimportdevice = new PluginFusioninventoryIgnoredimportdevice();
         $inputdb = [];
         if (isset($input['name'])) {
            $inputdb['name'] = $input['name'];
         }
         $inputdb['date'] = date("Y-m-d H:i:s");
         $inputdb['itemtype'] = $input['itemtype'];
         if (isset($input['serial'])) {
            $input['serialnumber'] = $input['serial'];
         }
         if (isset($input['ip'])) {
            $inputdb['ip'] = exportArrayToDB($input['ip']);
         }
         if (isset($input['mac'])) {
            $inputdb['mac'] = exportArrayToDB($input['mac']);
         }
         $inputdb['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
         $inputdb['method'] = 'networkinventory';
         $pfIgnoredimportdevice->add($inputdb);
         unset($_SESSION['plugin_fusioninventory_rules_id']);
      }
      if (isset($data['_no_rule_matches']) AND ($data['_no_rule_matches'] == '1')) {
         if (isset($input['itemtype'])
             && isset($data['action'])
             && ($data['action'] == PluginFusioninventoryInventoryRuleImport::LINK_RESULT_CREATE)) {

            $errors .= $this->rulepassed(0, $input['itemtype']);
         } else if (isset($input['itemtype'])
              AND !isset($data['action'])) {
            $id_xml = $a_inventory[$a_inventory['itemtype']]['id'];
            $classname = $input['itemtype'];
            $class = new $classname;
            if ($class->getFromDB($id_xml)) {
               $errors .= $this->rulepassed($id_xml, $input['itemtype']);
            } else {
               $errors .= $this->rulepassed(0, $input['itemtype']);
            }
         } else {
            $errors .= $this->rulepassed(0, "PluginFusioninventoryUnmanaged");
         }
      }
      return $errors;
   }


   /**
    * After rules import device
    *
    * @param integer $items_id id of the device in GLPI DB (0 = created,
    *                          other = merge)
    * @param string $itemtype itemtype of the device
    * @return string errors or empty string
    */
   function rulepassed($items_id, $itemtype, $ports_id = 0) {

      $no_history = false;

      PluginFusioninventoryLogger::logIfExtradebug(
         "pluginFusioninventory-rules",
         "Rule passed : ".$items_id.", ".$itemtype."\n"
      );
      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationSNMPQuery->rulepassed().'
      );

      $_SESSION["plugin_fusioninventory_entity"] = 0;

      PluginFusioninventoryConfig::logIfExtradebug("pluginFusioninventory-rules",
                                                   "Rule passed : ".$items_id.", ".$itemtype."\n");
      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkInventory->rulepassed().');

      $a_inventory = $_SESSION['SOURCE_XMLDEVICE'];

      $errors = '';
      $class = new $itemtype;
      if ($items_id == "0") {
         $input = [];
         $input['date_mod'] = date("Y-m-d H:i:s");
         if ($class->getFromDB($a_inventory[$a_inventory['itemtype']]['id'])) {
            $input['entities_id'] = $class->fields['entities_id'];
         } else {
            $input['entities_id'] = 0;
         }
         if (!isset($_SESSION['glpiactiveentities_string'])) {
            $_SESSION['glpiactiveentities_string'] = "'".$input['entities_id']."'";
         }
         $_SESSION["plugin_fusioninventory_entity"] = $input['entities_id'];

         //Add defaut status if there's one defined in the configuration
         $input      = PluginFusioninventoryToolbox::addDefaultStateIfNeeded('snmp', $input);
         $items_id   = $class->add($input);
         $no_history = true;
         if (isset($_SESSION['plugin_fusioninventory_rules_id'])) {
            $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
            $inputrulelog = [];
            $inputrulelog['date'] = date('Y-m-d H:i:s');
            $inputrulelog['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
            if (isset($_SESSION['plugin_fusioninventory_agents_id'])) {
               $inputrulelog['plugin_fusioninventory_agents_id'] =
                                                   $_SESSION['plugin_fusioninventory_agents_id'];
            }
            $inputrulelog['items_id'] = $items_id;
            $inputrulelog['itemtype'] = $itemtype;
            $inputrulelog['method'] = 'networkinventory';
            $inputrulelog['criteria'] = exportArrayToDB(unserialize($_SESSION['plugin_fusinvsnmp_datacriteria']));
            $pfRulematchedlog->add($inputrulelog);
            $pfRulematchedlog->cleanOlddata($items_id, $itemtype);
            unset($_SESSION['plugin_fusioninventory_rules_id']);
         }
      }
      if ($itemtype == "PluginFusioninventoryUnmanaged") {
         $class->getFromDB($items_id);
         $input = [];
         $input['id'] = $class->fields['id'];
         if (!empty($a_inventory[$a_inventory['itemtype']]['name'])) {
            $input['name'] = $a_inventory[$a_inventory['itemtype']]['name'];
         }
         if (!empty($a_inventory[$a_inventory['itemtype']]['serial'])) {
            $input['serial'] = $a_inventory[$a_inventory['itemtype']]['serial'];
         }
         if (!empty($a_inventory['itemtype'])) {
            $input['itemtype'] = $a_inventory['itemtype'];
         }
         // TODO : add import ports
         PluginFusioninventoryToolbox::writeXML($items_id,
                                                serialize($_SESSION['SOURCE_XMLDEVICE']),
                                                'PluginFusioninventoryUnmanaged');
         $class->update($input);
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] =
            '[==detail==] ==updatetheitem== Update '.
                 PluginFusioninventoryUnmanaged::getTypeName().
                 ' [[PluginFusioninventoryUnmanaged::'.$items_id.']]';
         $this->addtaskjoblog();
      } else {
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] =
               '[==detail==] Update '.$class->getTypeName().' [['.$itemtype.'::'.$items_id.']]';
         $this->addtaskjoblog();
         $errors .= $this->importDevice($itemtype, $items_id, $a_inventory, $no_history);
      }
      return $errors;
   }


   /**
    * Add log in the taskjob
    */
   function addtaskjoblog() {

      if (!isset($_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'])) {
         return;
      }

      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $pfTaskjoblog->addTaskjoblog(
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment']);
   }


   /**
    * Get method name linked to this class
    *
    * @return string
    */
   static function getMethod() {
      return 'networkinventory';
   }


   /**
    * Detect if the device is a stacked switch.
    * We use the level / dependencies of the components from root level
    *
    * case 1 :
    *   * type stack
    *     * type chassis with serial number (minimum of 2 chassis)
    *
    * case 2 :
    *   * type chassis with serial number (minimum of 2 chassis)
    *
    */
   function is_stacked_switch($a_inventory, $parent_index = 0) {
      if (count($a_inventory['components']) == 0) {
         return false;
      }
      $stack_chassis = 0;
      foreach ($a_inventory['components'] as $component) {
         if ($parent_index == 0
               && !isset($component['parent_index'])
               && $component['type'] == 'stack') {
            $stack_chassis += $this->is_stacked_switch($a_inventory, $component['index']);
         } else if ($component['type'] == 'chassis'
               && isset($component['serial'])) {
               $stack_chassis++;
         }
      }
      if ($stack_chassis >= 2) {
         return true;
      }
      return false;
   }


   /**
    * We split stacked switches to manage them individually
    *
    * the ports field ifname has the number of the switch, for example:
    *   - Gi1/0/1 (switch 1)
    *   - Gi2/0/1 (swicth 2)
    *
    * @param type $a_inventory
    */
   function split_stacked_switch($a_inventory) {
      $xml_devices = $this->get_stacked_switches_information($a_inventory);
      ksort($xml_devices);
      $devices = [];
      // split the switch
      // create new inventory for each switch
      $portswitchid = 1;
      $num = 0;
      foreach ($xml_devices as $xml_device) {
         $devices[$num] = [
            'NetworkEquipment'                      => $a_inventory['NetworkEquipment'],
            'itemtype'                              => 'NetworkEquipment',
            'PluginFusioninventoryNetworkEquipment' => $a_inventory['PluginFusioninventoryNetworkEquipment'],
            'firmwares'                             => $a_inventory['firmwares'],
            'internalport'                          => $a_inventory['internalport'],
            'networkport'                           => [],
            'connection-lldp'                       => $a_inventory['connection-lldp'],
            'connection-mac'                        => $a_inventory['connection-mac'],
            'components'                            => []
         ];

         // Overwrite couple of information
         $devices[$num]['firmwares'][0]['version'] = $xml_device['version'];
         $devices[$num]['NetworkEquipment']['serial'] = $xml_device['serial'];
         $devices[$num]['NetworkEquipment']['networkequipmentmodels_id'] = $xml_device['model'];
         $devices[$num]['NetworkEquipment']['name'] .= " - ".$xml_device['name'];

         // TODO: mettre les sous-composants ici

         // ports
         foreach ($a_inventory['networkport'] as $port) {
            $matches = [];
            preg_match('/([\w-]+)(\d+)\/(\d+)\/(\d+)/', $port['name'], $matches);
            if (count($matches) == 5) {
               if ($portswitchid != $matches[2]) {
                  continue;
               }
               $devices[$num]['networkport'][] = $port;
            } else {
               // Generic port, so add in all devices
               $devices[$num]['networkport'][] = $port;
            }
         }
         $num++;
         $portswitchid++;
      }
      return $devices;
   }


   function get_stacked_switches_information($a_inventory, $parent_index = 0) {
      if (count($a_inventory['components']) == 0) {
         return [];
      }
      $switches = [];
      foreach ($a_inventory['components'] as $component) {
         if ($parent_index == 0
               && (!isset($component['parent_index'])
                  || !empty($component['parent_index']))
               && $component['type'] == 'stack') {
            $switches += $this->get_stacked_switches_information($a_inventory, $component['index']);
         } else if ($component['type'] == 'chassis'
               && isset($component['serial'])) {
            $switches[$component['index']] = $component;
         }
      }
      return $switches;
   }


   function is_wireless_controller($a_inventory, $parent_index = 0) {
      if (count($a_inventory['components']) == 0) {
         return false;
      }
      $accesspoint = false;
      foreach ($a_inventory['components'] as $component) {
         if (!empty($component['ip'])
               && !empty($component['mac'])) {
            $accesspoint = true;
            return $accesspoint;
         }
      }
      return $accesspoint;
   }

   function get_wireless_controller_access_points($a_inventory) {
      $accesspoints = [];
      $num = 0;
      foreach ($a_inventory['components'] as $component) {
         if (!empty($component['ip'])
               && !empty($component['mac'])) {
            $accesspoints[$num] = [
               'NetworkEquipment' => [
                  'id'                        => 0,
                  'locations_id'              => '',
                  'mac'                       => $component['mac'],
                  'manufacturers_id'          => '',
                  'networkequipmentmodels_id' => $component['model'],
                  'name'                      => $component['name'],
                  'serial'                    => $component['serial'],
                  'memory'                    => 0,
                  'ram'                       => 0,
                  'is_dynamic'                => 1
               ],
               'itemtype'  => 'NetworkEquipment',
               'PluginFusioninventoryNetworkEquipment' => [
                  'last_fusioninventory_update' => $a_inventory['PluginFusioninventoryNetworkEquipment']['last_fusioninventory_update']
               ],
               'firmwares' => [
                  [
                     'description'            => $component['comment'],
                     'manufacturers_id'       => '',
                     'name'                   => $component['model'],
                     'devicefirmwaretypes_id' => 'device',
                     'version'                => $component['version']
                  ]
               ],
               'internalport' => [$component['ip']],
               'networkport'  => [],
               'components'   => []
            ];
            $num++;
         }
      }
      return $accesspoints;
   }
}
