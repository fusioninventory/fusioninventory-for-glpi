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

class PluginFusinvsnmpCommunicationSNMPQuery {
//   private $sxml, $deviceId, $ptd, $type='', $logFile;
   private $sxml, $ptd, $agent, $unknownDeviceCDP;
   private $a_ports = array();

   /**
    * Import data
    *
    *@param $p_DEVICEID XML code to import
    *@param $p_CONTENT XML code to import
    *@return "" (import ok) / error string (import ko)
    **/
   function import($p_DEVICEID, $p_CONTENT, $p_xml) {

      //$_SESSION['SOURCEXML'] = $p_xml;

      $result = false;

      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationSNMPQuery->import().'
      );

      $pfAgent = new PluginFusioninventoryAgent();
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();

      $this->agent = $pfAgent->InfosByKey($p_DEVICEID);

      $this->sxml = $p_xml;
      $errors = '';

      $_SESSION['glpi_plugin_fusioninventory_processnumber'] = $p_CONTENT->PROCESSNUMBER;
      if ($pfTaskjobstate->getFromDB($p_CONTENT->PROCESSNUMBER)) {
         if ($pfTaskjobstate->fields['state'] != "3") {
            $pfTaskjobstate->changeStatus($p_CONTENT->PROCESSNUMBER, 2);
            if ((!isset($p_CONTENT->AGENT->START)) AND (!isset($p_CONTENT->AGENT->END))) {
               $nb_devices = 0;
               $segs=$p_CONTENT->xpath('//DEVICE');
               $nb_devices = count($segs);
                  
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = $p_CONTENT->PROCESSNUMBER;
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'] = $this->agent['id'];
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'] = 'PluginFusioninventoryAgent';
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'] = '6';
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = $nb_devices.' ==fusinvsnmp::1==';
               $this->addtaskjoblog();

            }
            $errors.=$this->importContent($p_CONTENT);
            $result=true;
            if (isset($p_CONTENT->AGENT->END)) {
               $pfTaskjobstate->changeStatusFinish($p_CONTENT->PROCESSNUMBER,
                                                         $this->agent['id'],
                                                         'PluginFusioninventoryAgent');
            }
            if (isset($p_CONTENT->AGENT->START)) {
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = $p_CONTENT->PROCESSNUMBER;
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'] = $this->agent['id'];
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'] = 'PluginFusioninventoryAgent';
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'] = '6';
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '==fusinvsnmp::6==';
               $this->addtaskjoblog();
            }
         }
      } else {
         $errors.=$this->importContent($p_CONTENT);
      }
      return $errors;
   }


   
   /**
    * Import the content (where have all devices)
    *@param $p_content CONTENT code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importContent($p_content) {
      global $LANG;

      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationSNMPQuery->importContent().'
      );
      $pfAgent = new PluginFusioninventoryAgent();
      
      $errors='';
      $nbDevices = 0;

      foreach ($p_content->children() as $child) {
         PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
            'fusioninventorycommunication',
            $child->getName()
         );
         switch ($child->getName()) {
            
            case 'DEVICE' :
               if (isset($child->ERROR)) {
                  $itemtype = "";
                  if ((string)$child->ERROR->TYPE == "NETWORKING") {
                     $itemtype = "NetworkEquipment";
                  } else if ((string)$child->ERROR->TYPE == "PRINTER") {
                     $itemtype = "Printer";
                  }
                  $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '[==fusinvsnmp::7==] '.(string)$child->ERROR->MESSAGE.' [['.$itemtype.'::'.$child->ERROR->ID.']]';
                  $this->addtaskjoblog();
               } else if (!isset($child->INFO->COMMENTS)
                       AND !isset($child->INFO->NAME)
                       AND !isset($child->INFO->SERIAL)) {
                  $itemtype = "";
                  if ((string)$child->TYPE == "NETWORKING") {
                     $itemtype = "NetworkEquipment";
                  } else if ((string)$child->TYPE == "PRINTER") {
                     $itemtype = "Printer";
                  }
                  $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '[==fusinvsnmp::7==] No informations [['.$itemtype.'::'.$child->ID.']]';
                  $this->addtaskjoblog();
               } else {
                  if (count($child) > 0) {
                     $errors .= $this->sendCriteria($this->sxml->DEVICEID, $child);
                     $nbDevices++;
                  }
               }
               break;

            case 'AGENT' :
               if (isset($this->sxml->CONTENT->AGENT->AGENTVERSION)) {
                  $agent = $pfAgent->InfosByKey($this->sxml->DEVICEID);
                  $agent['fusioninventory_agent_version'] = $this->sxml->CONTENT->AGENT->AGENTVERSION;
                  $agent['last_agent_update'] = date("Y-m-d H:i:s");
                  $pfAgent->update($agent);
               }
               break;

            case 'PROCESSNUMBER' :
               break;

            case 'MODULEVERSION' :
               break;
            
            default :
               $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '[==fusinvsnmp::7==] '.$LANG['plugin_fusioninventory']['errors'][22].' CONTENT : '.$child->getName();
               $this->addtaskjoblog();
         }
      }
      return $errors;
   }



   /**
    * Import one device
    *
    * @global type $LANG
    * @param type $itemtype
    * @param type $items_id
    * 
    * @return errors string to be alimented if import ko / '' if ok 
    */
   function importDevice($itemtype, $items_id) {
      global $LANG;

      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationSNMPQuery->importDevice().'
      );

      $p_xml = simplexml_load_string($_SESSION['SOURCE_XMLDEVICE'],'SimpleXMLElement', LIBXML_NOCDATA);

      // Write XML file
      if (isset($p_xml)) {
         $folder = substr($items_id,0,-1);
         if (empty($folder)) {
            $folder = '0';
         }
         if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusinvsnmp/".$itemtype."/".$folder)) {
            mkdir(GLPI_PLUGIN_DOC_DIR."/fusinvsnmp/".$itemtype."/".$folder, 0777, true);
         }
         $fileopen = fopen(GLPI_PLUGIN_DOC_DIR."/fusinvsnmp/".$itemtype."/".$folder."/".$items_id, 'w');
         fwrite($fileopen, $p_xml->asXML());
         fclose($fileopen);
       }

      $errors='';
      $this->deviceId=$items_id;
      switch ($itemtype) {
         
         case 'Printer':
            $this->type = 'Printer';
            break;
         
         case 'NetworkEquipment':
            $this->type = 'NetworkEquipment';
            break;
         
         default:
            $errors.=$LANG['plugin_fusioninventory']['errors'][22].' TYPE : '
                              .$p_xml->INFO->TYPE."\n";
      }
      if (!isset($p_xml->ERROR)) {
         $errors.=$this->importInfo($itemtype, $items_id);
         if ($this->deviceId!='') {
            foreach ($p_xml->children() as $child) {
               switch ($child->getName()) {
                  
                  case 'INFO': // already managed
                     break;
                  
                  case 'PORTS':
                     $errors.=$this->importPorts($child);
                     break;
                  
                  case 'CARTRIDGES':
                     if ($this->type == 'Printer') {
                        $errors.=$this->importCartridges($child);
                        break;
                     }
                  
                  case 'PAGECOUNTERS':
                     if ($this->type == 'Printer') {
                        $errors.=$this->importPageCounters($child);
                        break;
                     }
                  
                  default:
                     $errors.=$LANG['plugin_fusioninventory']['errors'][22].' DEVICE : '
                              .$child->getName()."\n";
               }
            }
            $this->ptd->updateDB();
         }
      }
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
   function importInfo($itemtype, $items_id) {

      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationSNMPQuery->importInfo().'
      );
      $errors='';
      $xml = simplexml_load_string($_SESSION['SOURCE_XMLDEVICE'],'SimpleXMLElement', LIBXML_NOCDATA);
      if ($itemtype == 'NetworkEquipment') {
         $errors.=$this->importInfoNetworking($xml->INFO);
      } elseif ($itemtype == 'Printer') {
         $errors.=$this->importInfoPrinter($xml->INFO);
      }
      return $errors;
   }


   
   /**
    * Import INFO:Networking
    * 
    * @param $p_info INFO code to import
    *
    * @return errors string to be alimented if import ko / '' if ok
    **/
   function importInfoNetworking($p_info) {
      global $LANG;
      
      $errors='';
      $this->ptd = new PluginFusinvsnmpNetworkEquipment();
      $this->ptd->load($this->deviceId);

      $_SESSION["plugin_fusinvinventory_entity"] = $this->ptd->getValue('entities_id');
      if (!isset($_SESSION['glpiactiveentities_string'])) {
         $_SESSION['glpiactiveentities_string'] = "'".$this->ptd->getValue('entities_id')."'";
      }

      $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_networkequipments', $this->ptd->getValue('id'));

      foreach ($p_info->children() as $child) {
         switch ($child->getName()) {
            
            case 'ID': // already managed
               break;
            
            case 'TYPE': // already managed
               break;
            
            case 'COMMENTS':
               $this->ptd->setValue('sysdescr', $p_info->COMMENTS[0]);
               break;
            
            case 'CPU':
               $this->ptd->setValue('cpu', $p_info->CPU[0]);
               break;
            
            case 'FIRMWARE':
               if (!in_array('networkequipmentfirmwares_id', $a_lockable)) {
                  $firmware = (string)$p_info->FIRMWARE;
                  if (strstr($firmware, "CW_VERSION")
                          OR strstr($firmware, "CW_INTERIM_VERSION")) {
                     $explode = explode("$", $firmware);
                     if (isset($explode[1])) {
                        $firmware = $explode[1];
                     }
                  }
                  $NetworkEquipmentFirmware = new NetworkEquipmentFirmware();
                  $this->ptd->setValue('networkequipmentfirmwares_id', $NetworkEquipmentFirmware->import(array('name' => $firmware)));
               }
               break;
               
            case 'MAC':
               if (!in_array('mac', $a_lockable)) {
                  $this->ptd->setValue('mac', $p_info->MAC[0]);
               }
               break;
            
            case 'MEMORY':
               if (!in_array('memory', $a_lockable)) {
                  $this->ptd->setValue('memory', $p_info->MEMORY[0]);
               }
               break;
               
            case 'MODEL':
               $NetworkEquipmentModel = new NetworkEquipmentModel();
               if (!in_array('networkequipmentmodels_id', $a_lockable)) {
                  $networkequipmentmodels_id = $NetworkEquipmentModel->import(array('name'=>(string)$p_info->MODEL));
                  $this->ptd->setValue('networkequipmentmodels_id', $networkequipmentmodels_id);
               }
               break;
               
            case 'LOCATION':
               if (!in_array('locations_id', $a_lockable)) {
                  $Location = new Location();
                  $this->ptd->setValue('locations_id', $Location->import(array('name' => (string)$p_info->LOCATION,
                                                                    'entities_id' => $this->ptd->getValue('entities_id'))));
               }
               break;
               
            case 'NAME':
               if (!in_array('name', $a_lockable)) {
                  $this->ptd->setValue('name', $p_info->NAME[0]);
               }
               break;
               
            case 'RAM':
               $this->ptd->setValue('ram', $p_info->RAM[0]);
               break;
            
            case 'SERIAL':
               if (!in_array('serial', $a_lockable)) {
                  $this->ptd->setValue('serial', $p_info->SERIAL[0]);
               }
               break;
               
            case 'UPTIME':
               $this->ptd->setValue('uptime', $p_info->UPTIME[0]);
               break;
            
            case 'IPS':
               $errors.=$this->importIps($child, $this->ptd->getValue('id'));
               break;

            case 'MANUFACTURER':
               if (!in_array('manufacturers_id', $a_lockable)) {
                  $this->ptd->setValue('manufacturers_id', 
                                        Dropdown::importExternal('Manufacturer',
                                                                 (string)$p_info->MANUFACTURER,
                                                                 $_SESSION["plugin_fusinvinventory_entity"]));
               }
               break;

            default:
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' INFO : '.$child->getName()."\n";
               
         }
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
      global $LANG;

      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationSNMPQuery->importInfoPrinter().'
      );

      $errors='';
      $this->ptd = new PluginFusinvsnmpPrinter();
      $this->ptd->load($this->deviceId);

      $_SESSION["plugin_fusinvinventory_entity"] = $this->ptd->getValue('entities_id');
      if (!isset($_SESSION['glpiactiveentities_string'])) {
         $_SESSION['glpiactiveentities_string'] = "'".$this->ptd->getValue('entities_id')."'";
      }
      
      $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_printers', $this->ptd->getValue('id'));

      foreach ($p_info->children() as $child) {
         switch ($child->getName()) {
            
            case 'ID': // already managed
               break;
            
            case 'TYPE': // already managed
               break;
            
            case 'COMMENTS':
               $this->ptd->setValue('sysdescr', (string)$p_info->COMMENTS);
               break;
            
            case 'MEMORY':
               $this->ptd->setValue('memory_size', (string)$p_info->MEMORY);
               break;
            
            case 'MODEL':
               if (!in_array('printermodels_id', $a_lockable)) {
                  $PrinterModel = new PrinterModel();
                  $printermodels_id = $PrinterModel->import(array('name'=>(string)$p_info->MODEL));
                  $this->ptd->setValue('printermodels_id', $printermodels_id);
               }
               break;
               
            case 'NAME':
               if (!in_array('name', $a_lockable)) {
                  $this->ptd->setValue('name', (string)$p_info->NAME);
               }
               break;
               
            case 'SERIAL':
               if (!in_array('serial', $a_lockable)) {
                  $this->ptd->setValue('serial', (string)$p_info->SERIAL);
               }
               break;
               
            case 'OTHERSERIAL':
               if (!in_array('otherserial', $a_lockable)) {
                  $otherserial = (string)$p_info->OTHERSERIAL;
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
                  $this->ptd->setValue('locations_id', $Location->import(array('name' => (string)$p_info->LOCATION,
                                                                           'entities_id' => $this->ptd->getValue('entities_id'))));
               }
               break;
               
            case 'CONTACT':
               if (!in_array('contact', $a_lockable)) {
                  $this->ptd->setValue('contact', (string)$p_info->CONTACT);
               }
               break;
               
            case 'MANUFACTURER':
               if (!in_array('manufacturers_id', $a_lockable)) {
                  $Manufacturer = new Manufacturer();
                  $this->ptd->setValue('manufacturers_id', $Manufacturer->import(array('name' => (string)$p_info->MANUFACTURER)));
               }
               break;
               
            default:
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' INFO : '.$child->getName()."\n";
         
         }
      }
      return $errors;
   }



   /**
    * Import IPs
    * 
    * @param $p_ips IPs code to import
    * @param $networkequipments_id id of network equipment
    *
    * @return errors string to be alimented if import ko / '' if ok
    **/
   function importIps($p_ips, $networkequipments_id) {
      global $LANG;

      $errors='';
      $pfNetworkEquipmentIP = new PluginFusinvsnmpNetworkEquipmentIP();
      $pfUnknownDevice = new PluginFusioninventoryUnknownDevice();
      
      $pfNetworkEquipmentIP->loadIPs($networkequipments_id);
      
      foreach ($p_ips->children() as $child) {
         switch ($child->getName()) {
            
            case 'IP':
               if ((string)$child != "127.0.0.1") {
                  $pfNetworkEquipmentIP->setIP((string)$child);
                  // Search in unknown device if device with IP (CDP) is yet added, in this case,
                  // we get id of this unknown device
                  $a_unknown = $pfUnknownDevice->find("`ip`='".(string)$child."'", "", 1);
                  if (count($a_unknown) > 0) {
                     $datas= current($a_unknown);
                     $this->unknownDeviceCDP = $datas['id'];
                  }
               }
               break;
               
            default:
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' IPs : '.$child->getName()."\n";
               
         }
      }
      $pfNetworkEquipmentIP->saveIPs($networkequipments_id);
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
      global $LANG;

      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationSNMPQuery->importPorts().'
      );
      $errors='';
      foreach ($p_ports->children() as $child) {
         switch ($child->getName()) {
            
            case 'PORT':
               if ($this->type == "Printer") {
                  $errors.=$this->importPortPrinter($child);
               } elseif ($this->type == "NetworkEquipment") {
                  $errors.=$this->importPortNetworking($child);
               }
               break;
               
            default:
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' PORTS : '.$child->getName()."\n";
               
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
    * Import PORT Networking
    * 
    * @param $p_port PORT code to import
    *
    * @return errors string to be alimented if import ko / '' if ok
    **/
   function importPortNetworking($p_port) {
      global $LANG;
      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationSNMPQuery->importPortNetworking().'
      );
      $errors='';
      $pfNetworkPort = new PluginFusinvsnmpNetworkPort("NetworkEquipment");
      $pfNetworkporttype = new PluginFusinvsnmpNetworkporttype();
      $ifType = $p_port->IFTYPE;
      // not virtual port
      if ($pfNetworkporttype->isImportType($ifType)) {
         // Get port of unknown device CDP if exist
         $portloaded = 0;
         $portIndex  = 0;
         if (!empty($this->unknownDeviceCDP)) {
            $NetworkPort = new NetworkPort();
            $a_unknownPorts = $NetworkPort->find("`itemtype`='PluginFusioninventoryUnknownDevice'
                                                   AND `items_id`='".$this->unknownDeviceCDP."'", 
                                                   '',
                                                   1);
            if (count($a_unknownPorts) > 0) {
               $dataport = current($a_unknownPorts);
               if ((isset($p_port->IFNAME))
                       AND ($p_port->IFNAME == $dataport['name'])) {

                  // get this port and put in this switch
                  $dataport['itemtype'] = 'NetworkEquipment';
                  $dataport['items_id'] = $this->ptd->getValue('id');
                  $NetworkPort->update($dataport);
                  $pfNetworkPort->loadNetworkport($dataport['id']);
                  $portloaded = 1;
                  $portIndex = $p_port->IFNUMBER;
               }
            }
            $nbelements = countElementsInTable($NetworkPort->getTable(), 
                    "`itemtype`='PluginFusioninventoryUnknownDevice'
                        AND `items_id`='".$this->unknownDeviceCDP."'");
            if ($nbelements == '0') {
               $pfUnknownDevice = new PluginFusioninventoryUnknownDevice();
               $pfUnknownDevice->delete(array('id'=>$this->unknownDeviceCDP), 1);
               $this->unknownDeviceCDP = 0;
            }
         }
         if ($portloaded == '0') {
            $oldport = false;
            $oldport = $pfNetworkPort->getPortIdWithLogicialNumber($p_port->IFNUMBER, $this->deviceId);
            if ($oldport) {
               $pfNetworkPort->loadNetworkport($oldport);
            }
         }

         $pfNetworkPort->setValue('entities_id', $this->ptd->fields['entities_id']);
         $trunk = 0;
         foreach ($p_port->children() as $name=>$child) {
            switch ($name) {
               
               case 'CONNECTIONS':
                  $errors.=$this->importConnections($child, $pfNetworkPort);
                  break;
               
               case 'VLANS':
                  $errors.=$this->importVlans($child, $pfNetworkPort);
                  break;
               
               case 'IFNAME':
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($pfNetworkPort->getNetworkPorts_id(), $child, strtolower($name));
                  if ((string)$child != '') {
                     $pfNetworkPort->setValue('name', (string)$child);
                  }
                  break;
               
               case 'MAC':
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($pfNetworkPort->getNetworkPorts_id(), $child, strtolower($name));
                  if (!strstr($child, '00:00:00:00:00:00')) {
                     $pfNetworkPort->setValue('mac', (string)$child);
                  }
                  break;
                  
               case 'IFNUMBER':
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($pfNetworkPort->getNetworkPorts_id(), $child, strtolower($name));
                  $pfNetworkPort->setValue('logical_number', (string)$child);
                  break;
               
               case 'IFTYPE': // already managed
                  break;
               
               case 'TRUNK':
                  if ((string)$child == '1') {
                     PluginFusinvsnmpNetworkPortLog::networkport_addLog($pfNetworkPort->getNetworkPorts_id(), $child, strtolower($name));
                     $pfNetworkPort->setValue('trunk', 1);
                     $trunk = 1;
                  }
                  break;

               case 'IFDESCR':
                  if (!isset($p_port->IFNAME)
                          OR (string)$p_port->IFNAME == '') {
                     $pfNetworkPort->setValue('name', (string)$p_port->IFDESCR);
                  }
                  $pfNetworkPort->setValue(strtolower($name), (string)$p_port->$name);
                  break;
                  
               case 'IFINERRORS':
               case 'IFINOCTETS':
               case 'IFINTERNALSTATUS':
               case 'IFLASTCHANGE':
               case 'IFMTU':
               case 'IFOUTERRORS':
               case 'IFOUTOCTETS':
               case 'IFSPEED':
               case 'IFSTATUS':
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($pfNetworkPort->getNetworkPorts_id(), $child, strtolower($name));
                  $pfNetworkPort->setValue(strtolower($name), (string)$p_port->$name);
                  break;
               
               default:
                  $errors.=$LANG['plugin_fusioninventory']['errors'][22].' PORT : '.$name."\n";
            }
         }
         if ($trunk == "0") {
            if ($pfNetworkPort->getValue('trunk') == '1') {
               PluginFusinvsnmpNetworkPortLog::networkport_addLog($pfNetworkPort->getNetworkPorts_id(), '0', 'trunk');
               $pfNetworkPort->setValue('trunk', 0);
            }
         }
         $pfNetworkPort->savePort("NetworkEquipment", $this->deviceId);
         $this->a_ports[$pfNetworkPort->getValue("networkports_id")] = $pfNetworkPort->getValue("networkports_id");
         $pfNetworkPort->connectPorts();
      } else { // virtual port : do not import but delete if exists
         $oldport = false;
         $oldport = $pfNetworkPort->getPortIdWithLogicialNumber($p_port->IFNUMBER, $this->deviceId);
         if ($oldport) {
            $NetworkPort = new NetworkPort();
            $NetworkPort->delete(array('id' => $oldport));
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
      global $LANG;

      $errors='';
      $pfNetworkPort = new PluginFusinvsnmpNetworkPort('Printer');
      $networkPort = new NetworkPort();
      $pfNetworkporttype = new PluginFusinvsnmpNetworkporttype();
      $ifType = $p_port->IFTYPE;
      $portDB = $networkPort->getEmpty();
      $portModif = array();
      if ($pfNetworkporttype->isImportType($ifType)) { // not virtual port
         $a_ports = $networkPort->find("`itemtype`='Printer'
                                          AND `items_id`='".$this->deviceId."'
                                          AND `mac`='".(string)$p_port->MAC."'",
                                       "",
                                       1);
         if (count($a_ports) == '0'
                 AND $p_port->IP != '') {
            $a_ports = $networkPort->find("`itemtype`='Printer'
                                             AND `items_id`='".$this->deviceId."'
                                             AND `ip`='".(string)$p_port->IP."'",
                                          "",
                                          1);
         }
         if (count($a_ports) > 0) {
            $portDB = current($a_ports);
         }
         if ($portDB['entities_id'] != $this->ptd->fields['entities_id']) {
            $portModif['entities_id'] = $this->ptd->fields['entities_id'];
         }
         foreach ($p_port->children() as $name=>$child) {
            switch ($name) {
               
               case 'IFNAME':
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($portDB['id'], $child, strtolower($name));
                  if ($portDB['name'] != (string)$child) {
                     $portModif['name'] = (string)$child;
                  }
                  break;
               
               case 'MAC':
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($portDB['id'], $child, strtolower($name));
                  if ($portDB['mac'] != (string)$child) {
                     $portModif['mac'] = (string)$child;
                  }
                  break;
               
               case 'IP':
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($portDB['id'], $child, strtolower($name));
                  if ($portDB['ip'] != (string)$child) {
                     $portModif['ip'] = (string)$child;
                  }
                  break;
               
               case 'IFNUMBER':
                  PluginFusinvsnmpNetworkPortLog::networkport_addLog($portDB['id'], $child, strtolower($name));
                  if ($portDB['logical_number'] != (string)$child) {
                     $portModif['logical_number'] = (string)$child;
                  }
                  break;
               
               case 'IFTYPE': // already managed
                  break;
               
               default:
                  $errors.=$LANG['plugin_fusioninventory']['errors'][22].' PORT : '.$name."\n";
            }
         }
         // Update
         if ($portDB['id'] > 0) {
            $portModif['id'] = $portDB['id'];
            $networkPort->update($portModif);
            $this->a_ports[$portDB['id']] = $portDB['id'];
         } else {
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
      global $LANG;

      $pfMapping = new PluginFusioninventoryMapping();
      $errors='';
      foreach ($p_cartridges->children() as $name=>$child) {
         $plugin_fusioninventory_mappings = $pfMapping->get("Printer", strtolower($name));
         if ($plugin_fusioninventory_mappings) {
            $pfPrinterCartridge = new PluginFusinvsnmpPrinterCartridge();
            $a_cartridges = $pfPrinterCartridge->find("`printers_id`='".$this->deviceId."'
               AND `plugin_fusioninventory_mappings_id`='".$plugin_fusioninventory_mappings['id']."'",
               "", 1);
            if (!is_numeric((string)$child)) {
               $child = 0;
            }
            if (count($a_cartridges) > 0) {
               // Udpate
               $a_cartridge = current($a_cartridges);
               $input = array();
               $input['id'] = $a_cartridge['id'];
               $input['state'] = (string)$child;
               $pfPrinterCartridge->update($input);
            } else {
               // Add 
               $input = array();
               $input['printers_id'] = $this->deviceId;
               $input['plugin_fusioninventory_mappings_id'] = $plugin_fusioninventory_mappings['id'];
               $input['state'] = (string)$child;
               $pfPrinterCartridge->add($input);               
            }            
         } else {
            $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] = '[==fusinvsnmp::7==] '.$LANG['plugin_fusioninventory']['errors'][22].' CARTRIDGES : '.$name;
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
      global $LANG;

      $pfPrinterLog = new PluginFusinvsnmpPrinterLog();
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
      foreach ($p_pagecounters->children() as $name=>$child) {
         $childname = $child->getName();
         
         if ((string)$child == '') {
            $child = 0;
         }
         switch ($childname) {
            
            case 'TOTAL':
               $input['pages_total'] = (string)$child;
               break;
            
            case 'BLACK':
               $input['pages_n_b'] = (string)$child;
               break;
            
            case 'COLOR':
               $input['pages_color'] = (string)$child;
               break;
            
            case 'RECTOVERSO':
               $input['pages_recto_verso'] = (string)$child;
               break;
            
            case 'SCANNED':
               $input['scanned'] = (string)$child;
               break;
            
            case 'PRINTTOTAL':
               $input['pages_total_print'] = (string)$child;
               break;
            
            case 'PRINTBLACK':
               $input['pages_n_b_print'] = (string)$child;
               break;
            
            case 'PRINTCOLOR':
               $input['pages_color_print'] = (string)$child;
               break;
            
            case 'COPYTOTAL':
               $input['pages_total_copy'] = (string)$child;
               break;
            
            case 'COPYBLACK':
               $input['pages_n_b_copy'] = (string)$child;
               break;
            
            case 'COPYCOLOR':
               $input['pages_color_copy'] = (string)$child;
               break;
            
            case 'FAXTOTAL':
               $input['pages_total_fax'] = (string)$child;
               break;
            
            default:
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' PAGECOUNTERS : '.$name."\n";
               
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
      global $LANG;

      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationSNMPQuery->importConnections().'
      );
      $errors='';
      $cdp = 0;
      if (isset($p_connections->CDP)) {
         $cdp = $p_connections->CDP;
         if ($cdp==1) {
            $pfNetworkPort->setCDP();
         } else {
            $errors.=$LANG['plugin_fusioninventory']['errors'][22].' CONNECTIONS : CDP='.$cdp."\n";
         }
      }
      $count = 0;
      $a_macsFound = array();
      foreach ($p_connections->children() as $child) {
         switch ($child->getName()) {
            
            case 'CDP': // already managed
               if ($pfNetworkPort->getValue('trunk') != '1') {
                  $pfNetworkPort->setValue('trunk', 0);
               }
               break;
            
            case 'CONNECTION':
               $continue = 1;
               if (isset($child->MAC)) {
                  if (isset($a_macsFound[(string)$child->MAC])) {
                     $continue = 0;
                  } else if (count($child) > 20) {
                     $continue = 0;
                  } else {
                     $a_macsFound[(string)$child->MAC] = 1;
                  }
                  
                  if (count($child) > 1
                          AND $pfNetworkPort->getValue('trunk') != '1') {
                     
                     $pfNetworkPort->setValue('trunk', -1);
                  } else if ($pfNetworkPort->getValue('trunk') != '1') {
                     $pfNetworkPort->setValue('trunk', 0);
                  }
               }
               if ($continue == '1') {
                  $count++;
                  $errors.=$this->importConnection($child, $pfNetworkPort, $cdp);
               }
               break;
               
            default:
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' CONNECTIONS : '
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
      global $LANG;

      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationSNMPQuery->importConnection().'
      );

      $errors  = '';
      if ($p_cdp==1) {
         $a_ip = array();
         foreach ($p_connection->children() as $child) {
            switch ($child->getName()) {
               
               case 'IP':
               case 'IFDESCR':
               case 'SYSMAC': // LLDP Nortel
               case 'IFNUMBER': // LLDP Nortel
               case 'SYSDESCR': // CDP or LLDP
               case 'SYSNAME': // CDP or LLDP
               case 'MODEL': // CDP or LLDP
                  $a_ip[strtolower($child->getName())] = (string)$child;
                  break;

               default:
                  $errors.=$LANG['plugin_fusioninventory']['errors'][22].' CONNECTION (CDP='.$p_cdp.') : '
                           .$child->getName()."\n";
                  
            }             
         }
         if (isset($a_ip['ip'])) {
            $pfNetworkPort->addIp($a_ip);
         } else if (isset($a_ip['sysmac'])
                 AND isset($a_ip['ifnumber'])) {
            $pfNetworkPort->addMac($a_ip);
         }
      } else {
         foreach ($p_connection->children() as $child) {

            switch ($child->getName()) {
               
               case 'MAC':
                  $pfNetworkPort->addMac(strval($child));
                  break;
               
               case 'IP':
                  $pfNetworkPort->addIP(strval($child));
                  break;
               
               default:
                  $errors.=$LANG['plugin_fusioninventory']['errors'][22].' CONNECTION (CDP='.$p_cdp.') : '
                           .$child->getName()."\n";
                  
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
      global $LANG;

      $errors='';
      foreach ($p_vlans->children() as $child) {
         switch ($child->getName()) {
            
            case 'VLAN' :
               $errors.=$this->importVlan($child, $pfNetworkPort);
               break;
            
            default :
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' VLANS : '.$child->getName()."\n";
               
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
      global $LANG;

      $errors='';
      $number=''; 
      $name='';
      foreach ($p_vlan->children() as $child) {
         switch ($child->getName()) {
            
            case 'NUMBER':
               $number=(string)$child;
               break;
            
            case 'NAME':
               $name=(string)$child;
               break;
            
            default:
               $errors.=$LANG['plugin_fusioninventory']['errors'][22].' VLAN : '.$child->getName()."\n";
               
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
   function sendCriteria($p_DEVICEID, $p_CONTENT) {

      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationSNMPQuery->sendCriteria().'
      );

      $errors = '';
      
      // Manual blacklist
       if ((isset($p_CONTENT->INFO->SERIAL)) AND ($p_CONTENT->INFO->SERIAL == 'null')) {
          unset($p_CONTENT->INFO->SERIAL);
       }
       // End manual blacklist

       $_SESSION['SOURCE_XMLDEVICE'] = $p_CONTENT->asXML();

       $input = array();

      // Global criterias

         if ((isset($p_CONTENT->INFO->SERIAL)) AND (!empty($p_CONTENT->INFO->SERIAL))) {
            $input['serial'] = (string)$p_CONTENT->INFO->SERIAL;
         }
         if ($p_CONTENT->INFO->TYPE=='NETWORKING') {
            $input['itemtype'] = "NetworkEquipment";
            if ((isset($p_CONTENT->INFO->MAC)) AND (!empty($p_CONTENT->INFO->MAC))) {
               $input['mac'][] = (string)$p_CONTENT->INFO->MAC;
            }
         } else if ($p_CONTENT->INFO->TYPE=='PRINTER') {
            $input['itemtype'] = "Printer";
            if (isset($p_CONTENT->PORTS)) {
               foreach($p_CONTENT->PORTS->children() as $port) {
                  if ((isset($port->MAC)) AND (!empty($port->MAC))) {
                     $input['mac'][] = (string)$port->MAC;
                  }
                  if ((isset($port->MAC)) AND (!empty($port->IP))) {
                     $input['ip'][] = (string)$port->IP;
                  }
               }
            }
         }
         if ((isset($p_CONTENT->INFO->MODEL)) AND (!empty($p_CONTENT->INFO->MODEL))) {
            $input['model'] = (string)$p_CONTENT->INFO->MODEL;
         }
         if ((isset($p_CONTENT->INFO->NAME)) AND (!empty($p_CONTENT->INFO->NAME))) {
            $input['name'] = (string)$p_CONTENT->INFO->NAME;
         }

      $_SESSION['plugin_fusinvsnmp_datacriteria'] = serialize($input);
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "PluginFusinvsnmpCommunicationSNMPQuery";
      $rule = new PluginFusioninventoryRuleImportEquipmentCollection();
      $data = array();
      PluginFusioninventoryLogger::logIfExtradebug(
         "pluginFusioninventory-rules",
         "Input data : ".print_r($input, true)
      );
      $data = $rule->processAllRules($input, array());
      PluginFusioninventoryLogger::logIfExtradebug(
         "pluginFusioninventory-rules",
         print_r($data, true)
      );
      if (isset($data['action'])
              AND ($data['action'] == PluginFusioninventoryRuleImportEquipment::LINK_RESULT_DENIED)) {

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
         $inputdb['method'] = 'netinventory';
         $pFusioninventoryIgnoredimportdevice->add($inputdb);
         unset($_SESSION['plugin_fusioninventory_rules_id']);
      }
      if (isset($data['_no_rule_matches']) AND ($data['_no_rule_matches'] == '1')) {
         if (isset($input['itemtype'])
              AND isset($data['action'])
              AND ($data['action'] == PluginFusioninventoryRuleImportEquipment::LINK_RESULT_CREATE)) {

            $errors .= $this->rulepassed(0, $input['itemtype']);
         } else if (isset($input['itemtype'])
              AND !isset($data['action'])) {
            $id_xml = (string)$p_CONTENT->INFO->ID;
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

      $xml = simplexml_load_string($_SESSION['SOURCE_XMLDEVICE'],'SimpleXMLElement', LIBXML_NOCDATA);

      $errors = '';
      $class = new $itemtype;
      if ($items_id == "0") {
         $input = array();
         $input['date_mod'] = date("Y-m-d H:i:s");
         if ($class->getFromDB((string)$xml->INFO->ID)) {
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
         if ((isset($xml->INFO->NAME)) AND (!empty($xml->INFO->NAME))) {
            $input['name'] = (string)$xml->INFO->NAME;
         }
         if ((isset($xml->INFO->SERIAL)) AND (!empty($xml->INFO->SERIAL))) {
            $input['serial'] = (string)$xml->INFO->SERIAL;
         }
         if ((isset($xml->INFO->OTHERSERIAL)) AND (!empty($xml->INFO->OTHERSERIAL))) {
            $input['otherserial'] = (string)$xml->INFO->OTHERSERIAL;
         }
         if ($xml->INFO->TYPE=='NETWORKING') {
            $input['itemtype'] = "NetworkEquipment";
         } else if ($xml->INFO->TYPE=='PRINTER') {
            $input['itemtype'] = "Printer";
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
         $errors .= $this->importDevice($itemtype, $items_id);
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