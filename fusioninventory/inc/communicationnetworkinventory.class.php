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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

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
   die("Sorry. You can't access this file directly");
}

class PluginFusioninventoryCommunicationNetworkInventory {
//   private $sxml, $deviceId, $ptd, $type='', $logFile;
   private $sxml, $ptd, $logFile, $agent, $unknownDeviceCDP, $arrayinventory;
   private $a_ports = array();



   function __construct() {
      if (PluginFusioninventoryConfig::isExtradebugActive()) {
         $this->logFile = GLPI_LOG_DIR.'/fusioninventorycommunication.log';
      }
   }


   
   static function canCreate() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "networkequipment", "w");
   }

   

   static function canView() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "networkequipment", "r");
   }
   
   

   /**
    * Import data
    *
    *@param $p_DEVICEID XML code to import
    *@param $p_CONTENT XML code to import
    *@return "" (import ok) / error string (import ko)
    **/
   function import($p_DEVICEID, $a_CONTENT, $arrayinventory) {

      //$_SESSION['SOURCEXML'] = $p_xml;

      $result = false;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkInventory->import().');

      $pfAgent = new PluginFusioninventoryAgent();
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();

      $this->agent = $pfAgent->InfosByKey($p_DEVICEID);

      $this->arrayinventory = $arrayinventory;
      $errors = '';

      $_SESSION['glpi_plugin_fusioninventory_processnumber'] = $a_CONTENT['PROCESSNUMBER'];
//      if ($pfTaskjobstate->getFromDB($a_CONTENT['PROCESSNUMBER'])) {
//         if ($pfTaskjobstate->fields['state'] != "3") {
//            $pfTaskjobstate->changeStatus($a_CONTENT['PROCESSNUMBER'], 2);
            if ((!isset($a_CONTENT['AGENT']['START'])) AND (!isset($a_CONTENT['AGENT']['END']))) {
               $nb_devices = 0;
               if (isset($a_CONTENT['DEVICE'])) {
                  if (is_int(key($a_CONTENT['DEVICE']))) {
                     $nb_devices = count($a_CONTENT['DEVICE']);
                  } else {
                     $nb_devices = 1;
                  }
               }

               $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = $a_CONTENT['PROCESSNUMBER'];
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'] = $this->agent['id'];
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'] = 'PluginFusioninventoryAgent';
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'] = '6';
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = $nb_devices.' ==fusinvsnmp::1==';
               $this->addtaskjoblog();

            }
            $errors.=$this->importContent($a_CONTENT);
            $result=true;
            if (isset($p_CONTENT->AGENT->END)) {
               $pfTaskjobstate->changeStatusFinish($p_CONTENT->PROCESSNUMBER,
                                                         $this->agent['id'],
                                                         'PluginFusioninventoryAgent');
            }
            if (isset($a_CONTENT['AGENT']['START'])) {
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = $a_CONTENT['PROCESSNUMBER'];
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'] = $this->agent['id'];
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'] = 'PluginFusioninventoryAgent';
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'] = '6';
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '==fusinvsnmp::6==';
               $this->addtaskjoblog();
            }
//         }
//      } else {
//         $errors.=$this->importContent($p_CONTENT);
//      }
      return $errors;
   }



   /**
    * Import the content (where have all devices)
    *@param $p_content CONTENT code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
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
               $a_devices = array();
               if (is_int(key($child))) {
                  $a_devices = $child;
               } else {
                  $a_devices[] = $child;
               }
               foreach ($a_devices as $dchild) {
                  if ($dchild['INFO']['TYPE'] == "NETWORKING") {
                     $a_inventory = PluginFusioninventoryFormatconvert::networkequipmentInventoryTransformation($dchild);
                  } else if ($dchild['INFO']['TYPE'] == "PRINTER") {
                     $a_inventory = PluginFusioninventoryFormatconvert::printerInventoryTransformation($dchild);
                  }
                  if (isset($dchild['ERROR'])) {
                     $itemtype = "";
                     if ($dchild['ERROR']['TYPE'] == "NETWORKING") {
                        $itemtype = "NetworkEquipment";
                     } else if ($dchild['ERROR']['TYPE'] == "PRINTER") {
                        $itemtype = "Printer";
                     }
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '[==fusinvsnmp::7==] '.$dchild['ERROR']['MESSAGE'].' [['.$itemtype.'::'.$dchild['ERROR']['ID'].']]';
                     $this->addtaskjoblog();
                  } else if ($a_inventory['PluginFusioninventory'.$a_inventory['itemtype']]['sysdescr'] == ''
                              && $a_inventory[$a_inventory['itemtype']]['name'] == ''
                              && $a_inventory[$a_inventory['itemtype']]['serial'] == '') {
                         
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '[==fusinvsnmp::7==] No informations [['.$itemtype.'::'.$dchild['ID'].']]';
                     $this->addtaskjoblog();
                  } else {
                     if (count($a_inventory) > 0) {
                        $errors .= $this->sendCriteria($this->arrayinventory['DEVICEID'], $a_inventory);
                        $nbDevices++;
                     }
                  }
               }
               break;

            case 'AGENT' :
               if (isset($this->arrayinventory['CONTENT']['AGENT']['AGENTVERSION'])) {
                  $agent = $pfAgent->InfosByKey($this->arrayinventory['DEVICEID']);
                  $agent['fusioninventory_agent_version'] = $this->arrayinventory['CONTENT']['AGENT']['AGENTVERSION'];
                  $agent['last_agent_update'] = date("Y-m-d H:i:s");
                  $pfAgent->update($agent);
               }
               break;

            case 'PROCESSNUMBER' :
               break;

            case 'MODULEVERSION' :
               break;

            default :
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '[==fusinvsnmp::7==] '.__('Unattended element in', 'fusioninventory').' CONTENT : '.$childname;
               $this->addtaskjoblog();
         }
      }
      return $errors;
   }



   /**
    * Import one device
    *
    * @param type $itemtype
    * @param type $items_id
    *
    * @return errors string to be alimented if import ko / '' if ok
    */
   function importDevice($itemtype, $items_id, $a_inventory) {
      
      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkInventory->importDevice().');

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();
      $a_inventory = $pfFormatconvert->replaceids($a_inventory);
      
      // Write XML file
      if (count($a_inventory) > 0) {
         $folder = substr($items_id,0,-1);
         if (empty($folder)) {
            $folder = '0';
         }
         if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory/".$itemtype."/".$folder)) {
            mkdir(GLPI_PLUGIN_DOC_DIR."/fusioninventory/".$itemtype."/".$folder, 0777, true);
         }
         $fileopen = fopen(GLPI_PLUGIN_DOC_DIR."/fusioninventory/".$itemtype."/".$folder."/".$items_id, 'w');
         fwrite($fileopen, print_r($a_inventory, true));
         fclose($fileopen);
       }

      $errors='';
      $this->deviceId=$items_id;
      switch ($itemtype) {

         case 'Printer':
            $this->type = 'Printer';
            break;

         case 'NetworkEquipment':
            $pfInventoryNetworkEquipmentLib = new PluginFusioninventoryInventoryNetworkEquipmentLib();
            $pfInventoryNetworkEquipmentLib->updateNetworkEquipment($a_inventory, $items_id);
            break;

         default:
            $errors.=__('Unattended element in', 'fusioninventory').' TYPE : '
                              .$a_inventory['itemtype']."\n";
      }
      return;
//      if (!isset($arraydevice['ERROR'])) {
         $errors.=$this->importInfo($itemtype, $items_id, $a_inventory);
         if ($this->deviceId!='') {
            foreach ($arraydevice as $childname=>$child) {
               switch ($childname) {
// TODO Continue rewrite network 0.84
                  
                  
                  case 'networkport':
                     $errors.=$this->importPorts($child);
                     break;

                  case 'CARTRIDGES':
                     if ($this->type == 'Printer') {
                        $a_cartridges = array();
                        if (is_int(key($child))) {
                           $a_cartridges = $child;
                        } else {
                           $a_cartridges[] = $child;
                        }
                        $errors .= $this->importCartridges($a_cartridges);
                        break;
                     }

                  case 'PAGECOUNTERS':
                     if ($this->type == 'Printer') {
                        $a_pagecounters = array();
                        if (is_int(key($child))) {
                           $a_pagecounters = $child;
                        } else {
                           $a_pagecounters[] = $child;
                        }
                        $errors.=$this->importPageCounters($a_pagecounters);
                        break;
                     }

                  default:
                     $errors.=__('Unattended element in', 'fusioninventory').' DEVICE : '
                              .$childname."\n";
               }
            }
//            $this->ptd->updateDB();
         }
//      }
      if (!empty($errors)) {
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '[==fusinvsnmp::7==] '.$errors.' [['.$itemtype.'::'.$items_id.']]';
         $this->addtaskjoblog();
      }
      return $errors;
   }



   /**
    * Import INFO (Node info of the device
    *
    * @param type $itemtype
    * @param type $items_id
    *
    * @return errors string to be alimented if import ko / '' if ok
    */
   function importInfo($itemtype, $items_id, $a_inventory) {

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkInventory->importInfo().');
      $errors='';
      if ($itemtype == 'NetworkEquipment') {
      } elseif ($itemtype == 'Printer') {
         $errors.=$this->importInfoPrinter($a_inventory);
      }
      return $errors;
   }



   /**
    * Import INFO:Printer
    *
    * @param $p_info INFO code to import
    *
    * @return errors string to be alimented if import ko / '' if ok
    **/
   function importInfoPrinter($p_info) {

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkInventory->importInfoPrinter().');

      $errors='';
      $this->ptd = new PluginFusioninventoryPrinter();
      $this->ptd->load($this->deviceId);

      $_SESSION["plugin_fusinvinventory_entity"] = $this->ptd->getValue('entities_id');
      if (!isset($_SESSION['glpiactiveentities_string'])) {
         $_SESSION['glpiactiveentities_string'] = "'".$this->ptd->getValue('entities_id')."'";
      }
      
      $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_printers', $this->ptd->getValue('id'));

      foreach ($p_info as $childname=>$value) {
         switch ($childname) {

            case 'ID': // already managed
               break;

            case 'TYPE': // already managed
               break;

            case 'COMMENTS':
               $this->ptd->setValue('sysdescr', $value);
               break;

            case 'MEMORY':
               $this->ptd->setValue('memory_size', $value);
               break;

            case 'MODEL':
               if (!in_array('printermodels_id', $a_lockable)) {
                  $PrinterModel = new PrinterModel();
                  $printermodels_id = $PrinterModel->import(array('name'=>$value));
                  $this->ptd->setValue('printermodels_id', $printermodels_id);
               }
               break;

            case 'NAME':
               if (!in_array('name', $a_lockable)) {
                  $this->ptd->setValue('name', $value);
               }
               break;

            case 'SERIAL':
               if (!in_array('serial', $a_lockable)) {
                  $this->ptd->setValue('serial', $value);
               }
               break;

            case 'OTHERSERIAL':
               if (!in_array('otherserial', $a_lockable)) {
                  $otherserial = $value;
                  if (strstr($otherserial, "chr(hex")) {
                     $otherserial = str_replace("chr(hex(", "", $otherserial);
                     $otherserial = str_replace("))", "", $otherserial);
                     $otherserial = substr($otherserial, 4);
                     $otherserial = $this->hexToStr($otherserial);
                  }
                  $this->ptd->setValue('otherserial', $otherserial);
               }
               break;

            case 'LOCATION':
               if (!in_array('locations_id', $a_lockable)) {
                  $Location = new Location();
                  $this->ptd->setValue('locations_id', $Location->import(array('name' => $value,
                                                                           'entities_id' => $this->ptd->getValue('entities_id'))));
               }
               break;

            case 'CONTACT':
               if (!in_array('contact', $a_lockable)) {
                  $this->ptd->setValue('contact', $value);
               }
               break;

            case 'MANUFACTURER':
               if (!in_array('manufacturers_id', $a_lockable)) {
                  $Manufacturer = new Manufacturer();
                  $this->ptd->setValue('manufacturers_id', $Manufacturer->import(array('name' => $value)));
               }
               break;

            default:
               $errors.=__('Unattended element in', 'fusioninventory').' INFO : '.$childname."\n";

         }
      }
      return $errors;
   }






   /**
    * Import PORTS
    *
    * @param $p_ports PORTS code to import
    *
    * @return errors string to be alimented if import ko / '' if ok
    **/
   function importPorts($p_ports) {

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkInventory->importPorts().');
      $errors='';
      if (isset($p_ports['PORT'])) {
         $a_ports = array();
         if (is_int(key($p_ports['PORT']))) {
            $a_ports = $p_ports['PORT'];
         } else {
            $a_ports[] = $p_ports['PORT'];
         }
         if ($this->type == "Printer") {
            foreach ($a_ports as $a_port) {
               $errors .= $this->importPortPrinter($a_port);
            }
         }
      }
      // Remove ports may not in XML and must be deleted in GLPI DB
      $networkPort = new NetworkPort();
      $a_portsDB = $networkPort->find("`itemtype` = '".$this->type."'
                                       AND `items_id`='".$this->deviceId."'");
      foreach ($a_portsDB as $data) {
         if (!isset($this->a_ports[$data['id']])) {
            $networkPort->delete($data);
         }
      }
      return $errors;
   }



   /**
    * Import PORT Printer
    *
    * @param $p_port PORT code to import
    *
    * @return errors string to be alimented if import ko / '' if ok
    **/
   function importPortPrinter($p_port) {

      $errors='';
      $networkPort = new NetworkPort();
      $pfNetworkporttype = new PluginFusioninventoryNetworkporttype();
      $ifType = $p_port['IFTYPE'];
      $portDB = $networkPort->getEmpty();
      $portModif = array();
      if ($pfNetworkporttype->isImportType($ifType)) { // not virtual port
         $a_ports = $networkPort->find("`itemtype`='Printer'
                                          AND `items_id`='".$this->deviceId."'
                                          AND `mac`='".$p_port['MAC']."'",
                                       "",
                                       1);
         if (count($a_ports) == '0'
                 AND $p_port['IP'] != '') {
            $a_ports = $networkPort->find("`itemtype`='Printer'
                                             AND `items_id`='".$this->deviceId."'
                                             AND `ip`='".$p_port['IP']."'",
                                          "",
                                          1);
         }
         if (count($a_ports) > 0) {
            $portDB = current($a_ports);
         }
         if ($portDB['entities_id'] != $this->ptd->fields['entities_id']) {
            $portModif['entities_id'] = $this->ptd->fields['entities_id'];
         }
         foreach ($p_port as $name=>$child) {
            switch ($name) {

               case 'IFNAME':
//                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($portDB['id'], $child, strtolower($name));
                  if ($portDB['name'] != (string)$child) {
                     $portModif['name'] = (string)$child;
                  }
                  break;

               case 'MAC':
//                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($portDB['id'], $child, strtolower($name));
                  if ($portDB['mac'] != (string)$child) {
                     $portModif['mac'] = (string)$child;
                  }
                  break;

               case 'IP':
//                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($portDB['id'], $child, strtolower($name));
                  if ($portDB['ip'] != (string)$child) {
                     $portModif['ip'] = (string)$child;
                  }
                  break;

               case 'IFNUMBER':
//                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($portDB['id'], $child, strtolower($name));
                  if ($portDB['logical_number'] != (string)$child) {
                     $portModif['logical_number'] = (string)$child;
                  }
                  break;

               case 'IFTYPE': // already managed
                  break;

               default:
                  $errors.=__('Unattended element in', 'fusioninventory').' PORT : '.$name."\n";
            }
         }
         // Update
         if (count($portDB) > 0 && $portDB['id'] > 0) {
            $portModif['id'] = $portDB['id'];
            $networkPort->update($portModif);
            $this->a_ports[$portDB['id']] = $portDB['id'];
         } else {
            $portModif['items_id'] = $this->deviceId;
            $portModif['itemtype'] = 'Printer';
            $newID = $networkPort->add($portModif);
            $this->a_ports[$newID] = $newID;
         }
      }
      return $errors;
   }



   /**
    * Import CARTRIDGES
    *
    * @param $p_cartridges CARTRIDGES code to import
    *
    * @return errors string to be alimented if import ko / '' if ok
    **/
   function importCartridges($p_cartridges) {

      $pfMapping = new PluginFusioninventoryMapping();
      $errors='';
      foreach ($p_cartridges as $name=>$child) {
         $plugin_fusioninventory_mappings = $pfMapping->get("Printer", strtolower($name));
         if ($plugin_fusioninventory_mappings) {
            $pfPrinterCartridge = new PluginFusioninventoryPrinterCartridge();
            $a_cartridges = $pfPrinterCartridge->find("`printers_id`='".$this->deviceId."'
               AND `plugin_fusioninventory_mappings_id`='".$plugin_fusioninventory_mappings['id']."'",
               "", 1);
            if (!is_numeric($child)) {
               $child = 0;
            }
            if (count($a_cartridges) > 0) {
               // Udpate
               $a_cartridge = current($a_cartridges);
               $input = array();
               $input['id'] = $a_cartridge['id'];
               $input['state'] = $child;
               $pfPrinterCartridge->update($input);
            } else {
               // Add
               $input = array();
               $input['printers_id'] = $this->deviceId;
               $input['plugin_fusioninventory_mappings_id'] = $plugin_fusioninventory_mappings['id'];
               $input['state'] = $child;
               $pfPrinterCartridge->add($input);
            }
         } else {
            $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '[==fusinvsnmp::7==] '.__('Unattended element in', 'fusioninventory').' CARTRIDGES : '.$name;
            $this->addtaskjoblog();
         }
      }
      return $errors;
   }



   /**
    * Import PAGECOUNTERS
    *@param $p_pagecounters PAGECOUNTERS code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importPageCounters($p_pagecounters) {

      $pfPrinterLog = new PluginFusioninventoryPrinterLog();
      //See if have an entry today
      $a_entires = $pfPrinterLog->find("`printers_id`='".$this->deviceId."'
         AND LEFT(`date`, 10)='".date("Y-m-d")."'", "", 1);
      if (count($a_entires) > 0) {
         return '';
      }
      $input = array();
      $input['printers_id'] = $this->deviceId;
      $input['date'] = date("Y-m-d H:i:s");

      $errors='';
      foreach ($p_pagecounters as $childname=>$child) {

         if ((string)$child == '') {
            $child = 0;
         }
         switch ($childname) {

            case 'TOTAL':
               $input['pages_total'] = $child;
               break;

            case 'BLACK':
               $input['pages_n_b'] = $child;
               break;

            case 'COLOR':
               $input['pages_color'] = $child;
               break;

            case 'RECTOVERSO':
               $input['pages_recto_verso'] = $child;
               break;

            case 'SCANNED':
               $input['scanned'] = $child;
               break;

            case 'PRINTTOTAL':
               $input['pages_total_print'] = $child;
               break;

            case 'PRINTBLACK':
               $input['pages_n_b_print'] = $child;
               break;

            case 'PRINTCOLOR':
               $input['pages_color_print'] = $child;
               break;

            case 'COPYTOTAL':
               $input['pages_total_copy'] = $child;
               break;

            case 'COPYBLACK':
               $input['pages_n_b_copy'] = $child;
               break;

            case 'COPYCOLOR':
               $input['pages_color_copy'] = $child;
               break;

            case 'FAXTOTAL':
               $input['pages_total_fax'] = $child;
               break;

            default:
               $errors.=__('Unattended element in', 'fusioninventory').' PAGECOUNTERS : '.$childname."\n";

         }
      }
      $pfPrinterLog->add($input);
      return $errors;
   }



   /**
    * Import CONNECTIONS
    *@param $p_connections CONNECTIONS code to import
    *@param $pfNetworkPort Port object to connect
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importConnections($p_connections, $pfNetworkPort) {

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkInventory->importConnections().');
      $errors='';
      $cdp = 0;
      if (isset($p_connections->CDP)) {
         $cdp = $p_connections->CDP;
         if ($cdp==1) {
            $pfNetworkPort->setCDP();
         } else {
            $errors.=__('Unattended element in', 'fusioninventory').' CONNECTIONS : CDP='.$cdp."\n";
         }
      }
      $count = 0;
      $a_macsFound = array();
      foreach ($p_connections as $childname=>$child) {
         switch ($childname) {

            case 'CDP': // already managed
               if ($pfNetworkPort->getValue('trunk') != '1') {
                  $pfNetworkPort->setValue('trunk', 0);
               }
               break;

            case 'CONNECTION':
               $continue = 1;
               if (isset($child['MAC'])) {
                  if (isset($a_macsFound[$child['MAC']])) {
                     $continue = 0;
                  } else if (count($child) > 20) {
                     $continue = 0;
                  } else {
                     $a_macsFound[$child['MAC']] = 1;
                  }
               }
               if ($continue == '1') {
                  $count++;
                  $errors.=$this->importConnection($child, $pfNetworkPort, $cdp);
               }
               if (isset($child->MAC)) {                  
                  $a_macs = array();
                  foreach ($child->children() as $child) {
                     if ($child->getName() == 'MAC') {
                        $a_macs[strval($child)] = strval($child);
                     }
                  }
                  if ($pfNetworkPort->isPorthasPhone()) {
                     if ($pfNetworkPort->getValue('trunk') != '1') {
                        $pfNetworkPort->setValue('trunk', 0);
                     }
                  } else {
                     if (count($a_macs) > 1
                             AND $pfNetworkPort->getValue('trunk') != '1') {

                        $pfNetworkPort->setValue('trunk', -1);
                     } else if ($pfNetworkPort->getValue('trunk') != '1') {
                        $pfNetworkPort->setValue('trunk', 0);
                     }
                  }
               }
               break;

            default:
               $errors.=__('Unattended element in', 'fusioninventory').' CONNECTIONS : '
                        .$child->getName()."\n";
         }
      }
      return $errors;
   }



   /**
    * Import CONNECTION
    *
    * @param $p_connection CONNECTION code to import
    * @param $pfNetworkPort Port object to connect
    * @param $p_cdp CDP value (1 or <>1)
    *
    * @return errors string to be alimented if import ko / '' if ok
    **/
   function  importConnection($p_connection, $pfNetworkPort, $p_cdp) {

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkInventory->importConnection().');

      $errors  = '';
      if ($p_cdp==1) {
         $a_ip = array();
         foreach ($p_connection as $childname=>$child) {
            switch ($childname) {

               case 'IP':
               case 'IFDESCR':
               case 'SYSMAC': // LLDP Nortel
               case 'IFNUMBER': // LLDP Nortel
               case 'SYSDESCR': // CDP or LLDP
               case 'SYSNAME': // CDP or LLDP
               case 'MODEL': // CDP or LLDP
                  $a_ip[strtolower($childname)] = $child;
                  break;

               default:
                  $errors.=__('Unattended element in', 'fusioninventory').' CONNECTION (CDP='.$p_cdp.') : '
                           .$childname."\n";

            }
         }
         if (isset($a_ip['ip'])) {
            $pfNetworkPort->addIp($a_ip);
         } else if (isset($a_ip['sysmac'])
                 AND isset($a_ip['ifnumber'])) {
            $pfNetworkPort->addMac($a_ip);
         }
      } else {
         foreach ($p_connection as $childname=>$child) {

            switch ($childname) {

               case 'MAC':
                  $pfNetworkPort->addMac(strval($child));
                  break;

               case 'IP':
                  $pfNetworkPort->addIP(strval($child));
                  break;

               default:
                  $errors.=__('Unattended element in', 'fusioninventory').' CONNECTION (CDP='.$p_cdp.') : '
                           .$childname."\n";

            }
         }
      }
      return $errors;
   }



   /**
    * Import VLANS
    *@param $p_vlans VLANS code to import
    *@param $pfNetworkPort Port object to connect
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importVlans($p_vlans, $pfNetworkPort) {

      $errors='';
      foreach ($p_vlans as $childname=>$child) {
         switch ($childname) {

            case 'VLAN' :
               $errors.=$this->importVlan($child, $pfNetworkPort);
               break;

            default :
               $errors.=__('Unattended element in', 'fusioninventory').' VLANS : '.$child->getName()."\n";

         }
      }
      return $errors;
   }



   /**
    * Import VLAN
    *@param $p_vlan VLAN code to import
    *@param $p_oPort Port object to connect
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importVlan($p_vlan, $pfNetworkPort) {

      $errors='';
      $number='';
      $name='';
      foreach ($p_vlan as $childname=>$child) {
         switch ($childname) {

            case 'NUMBER':
               $number=$child;
               break;

            case 'NAME':
               $name=$child;
               break;

            default:
               $errors.=__('Unattended element in', 'fusioninventory').' VLAN : '.$childname."\n";

         }
      }
      $pfNetworkPort->addVlan($number, $name);
      return $errors;
   }



   /**
    * Get connection IP
    *
    *@param $p_port PORT code to import
    *@return first connection IP or ''
    **/
   function getConnectionIP($p_port) {
      foreach ($p_port->children() as $connectionsName=>$connectionsChild) {
         switch ($connectionsName) {

            case 'CONNECTIONS':
               foreach ($connectionsChild->children() as $connectionName=>$connectionChild) {

                  switch ($connectionName) {

                     case 'CONNECTION':
                        foreach ($connectionChild->children() as $ipName=>$ipChild) {

                           switch ($ipName) {

                              case 'IP':
                                 if ($ipChild != '') {
                                    return $ipChild;
                                 }
                                 break;

                           }
                        }
                        break;

                  }
               }
               break;

         }
      }
      return '';
   }



   /**
    * Send XML of SNMP device to rules
    *
    * @param varchar $p_DEVICEID
    * @param simplexml $p_CONTENT
    *
    * @return type
    */
   function sendCriteria($p_DEVICEID, $a_inventory) {

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkInventory->sendCriteria().');

      $errors = '';

      // Manual blacklist
       if ($a_inventory[$a_inventory['itemtype']]['serial'] == 'null') {
          $a_inventory[$a_inventory['itemtype']]['serial'] = '';
       }
       // End manual blacklist

       $_SESSION['SOURCE_XMLDEVICE'] = $a_inventory;
       $input = array();

      // Global criterias

         if (!empty($a_inventory[$a_inventory['itemtype']]['serial'])) {
            $input['serial'] = $a_inventory[$a_inventory['itemtype']]['serial'];
         }
         if ($a_inventory['itemtype'] == 'NetworkEquipment') {
            if (!empty($a_inventory[$a_inventory['itemtype']]['mac'])) {
               $input['mac'][] = $a_inventory[$a_inventory['itemtype']]['mac'];
            }
         } else if ($a_inventory['itemtype'] == 'Printer') {
            $input['itemtype'] = "Printer";
            if (isset($a_inventory['networkport'])) {
               $a_ports = array();
               if (is_int(key($a_inventory['networkport']))) {
                  $a_ports = $a_inventory['networkport'];
               } else {
                  $a_ports[] = $a_inventory['networkport'];
               }
               foreach($a_ports as $port) {
                  if (!empty($port['mac'])) {
                     $input['mac'][] = $port['mac'];
                  }
                  if (!empty($port['ip'])) {
                     $input['ip'][] = $port['ip'];
                  }
               }
            }
         }
         if (!empty($a_inventory['networkequipmentmodels_id'])) {
            $input['model'] = $a_inventory['networkequipmentmodels_id'];
         }
         if (!empty($a_inventory['name'])) {
            $input['name'] = $a_inventory['name'];
         }

      $_SESSION['plugin_fusinvsnmp_datacriteria'] = serialize($input);
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "PluginFusioninventoryCommunicationNetworkInventory";
      $rule = new PluginFusioninventoryInventoryRuleImportCollection();
      $data = array();
      PluginFusioninventoryConfig::logIfExtradebug("pluginFusioninventory-rules",
                                                   "Input data : ".print_r($input, true));
Toolbox::logInFile("K", 'crit'.print_r($input, true));
      $data = $rule->processAllRules($input, array());
      PluginFusioninventoryConfig::logIfExtradebug("pluginFusioninventory-rules",
                                                   $data);
      if (isset($data['action'])
              AND ($data['action'] == PluginFusioninventoryInventoryRuleImport::LINK_RESULT_DENIED)) {

         $a_text = '';
         foreach ($input as $key=>$data) {
            if (is_array($data)) {
               $a_text[] = "[".$key."]:".implode(",", $data);
            } else {
               $a_text[] = "[".$key."]:".$data;
            }
         }
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '==fusioninventory::3== '.implode(",", $a_text);
         $this->addtaskjoblog();

         $pFusioninventoryIgnoredimportdevice = new PluginFusioninventoryIgnoredimportdevice();
         $inputdb = array();
         $inputdb['name'] = $input['name'];
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
         $pFusioninventoryIgnoredimportdevice->add($inputdb);
         unset($_SESSION['plugin_fusioninventory_rules_id']);
      }
      if (isset($data['_no_rule_matches']) AND ($data['_no_rule_matches'] == '1')) {
         if (isset($input['itemtype'])
              AND isset($data['action'])
              AND ($data['action'] == PluginFusioninventoryInventoryRuleImport::LINK_RESULT_CREATE)) {

            $errors .= $this->rulepassed(0, $input['itemtype']);
         } else if (isset($input['itemtype'])
              AND !isset($data['action'])) {
            $id_xml = $a_inventory['id'];
            $classname = $input['itemtype'];
            $class = new $classname;
            if ($class->getFromDB($id_xml)) {
               $errors .= $this->rulepassed($id_xml, $input['itemtype']);
            } else {
               $errors .= $this->rulepassed(0, $input['itemtype']);
            }
         } else {
            $errors .= $this->rulepassed(0, "PluginFusioninventoryUnknownDevice");
         }
      }
      return $errors;
   }



   /**
    * After rules import device
    *
    * @param integer $items_id id of the device in GLPI DB (0 = created, other = merge)
    * @param varchar $itemtype itemtype of the device
    *
    * @return type
    */
   function rulepassed($items_id, $itemtype) {
      
      PluginFusioninventoryLogger::logIfExtradebug(
         "pluginFusioninventory-rules",
         "Rule passed : ".$items_id.", ".$itemtype."\n"
      );
      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationSNMPQuery->rulepassed().'
      );

      PluginFusioninventoryConfig::logIfExtradebug("pluginFusioninventory-rules",
                                                   "Rule passed : ".$items_id.", ".$itemtype."\n");
      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkInventory->rulepassed().');

      $a_inventory = array();
      $a_inventory = $_SESSION['SOURCE_XMLDEVICE'];
      
      $errors = '';
      $class = new $itemtype;
      if ($items_id == "0") {
         $input = array();
         $input['date_mod'] = date("Y-m-d H:i:s");
         if ($class->getFromDB($a_inventory['id'])) {
            $input['entities_id'] = $class->fields['entities_id'];
         } else {
            $input['entities_id'] = 0;
         }
         if (!isset($_SESSION['glpiactiveentities_string'])) {
            $_SESSION['glpiactiveentities_string'] = "'".$input['entities_id']."'";
         }
         $items_id = $class->add($input);
         if (isset($_SESSION['plugin_fusioninventory_rules_id'])) {
            $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
            $inputrulelog = array();
            $inputrulelog['date'] = date('Y-m-d H:i:s');
            $inputrulelog['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
            if (isset($_SESSION['plugin_fusioninventory_agents_id'])) {
               $inputrulelog['plugin_fusioninventory_agents_id'] = $_SESSION['plugin_fusioninventory_agents_id'];
            }
            $inputrulelog['items_id'] = $items_id;
            $inputrulelog['itemtype'] = $itemtype;
            $inputrulelog['method'] = 'snmpinventory';
            $pfRulematchedlog->add($inputrulelog);
            $pfRulematchedlog->cleanOlddata($items_id, $itemtype);
            unset($_SESSION['plugin_fusioninventory_rules_id']);
         }
      }
      if ($itemtype == "PluginFusioninventoryUnknownDevice") {
         $class->getFromDB($items_id);
         $input = array();
         $input['id'] = $class->fields['id'];
         if (!empty($a_inventory[$a_inventory['itemtype']]['name'])) {
            $input['name'] = $a_inventory[$a_inventory['itemtype']]['name'];
         }
         if (!empty($a_inventory[$a_inventory['itemtype']]['serial'])) {
            $input['serial'] = $a_inventory[$a_inventory['itemtype']]['serial'];
         }
         if (!empty($a_inventory[$a_inventory['itemtype']]['otherserial'])) {
            $input['otherserial'] = $a_inventory[$a_inventory['itemtype']]['otherserial'];
         }
         if (!empty($a_inventory['itemtype'])) {
            $input['itemtype'] = $a_inventory['itemtype'];
         }
         // TODO : add import ports
         PluginFusioninventoryUnknownDevice::writeXML($items_id, $_SESSION['SOURCE_XMLDEVICE']);
         $class->update($input);
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] =
            '[==fusinvsnmp::7==] ==fusinvsnmp::5== Update '.PluginFusioninventoryUnknownDevice::getTypeName().' [[PluginFusioninventoryUnknownDevice::'.$items_id.']]';
         $this->addtaskjoblog();
      } else {
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] =
               '[==fusinvsnmp::7==] Update '.$class->getTypeName().' [['.$itemtype.'::'.$items_id.']]';
         $this->addtaskjoblog();
         $errors .= $this->importDevice($itemtype, $items_id, $a_inventory);
      }
      return $errors;
   }



   /**
    * Used to add log in the task
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
    * Convert hexa format into string
    *
    * @param $hex
    * @return string
    */
   function hexToStr($hex) {
       $string='';
       for ($i=0; $i < strlen($hex)-1; $i+=2) {
           $string .= chr(hexdec($hex[$i].$hex[$i+1]));
       }
       return $string;
   }


   static function getMethod() {
      return 'snmpinventory';
   }

}

?>