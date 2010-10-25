<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
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
class PluginFusinvsnmpCommunicationSNMPQuery extends PluginFusinvsnmpCommunicationSNMP {
//   private $sxml, $deviceId, $ptd, $type='', $logFile;

   /**
    * Add SNMPQUERY string to XML code
    *
    *@return nothing
    **/
   function addQuery($pxml, $task=0) {
      $ptmi    = new PluginFusioninventorySNMPModel;
      $ptsnmpa = new PluginFusinvsnmpConfigSecurity;
      $pta     = new PluginFusioninventoryAgent;
      $ptap    = new PluginFusioninventoryAgentProcess;
      $ptrip   = new PluginFusioninventoryIPRange;
      $ptt     = new PluginFusioninventoryTask;

      $agent = $pta->InfosByKey($pxml->DEVICEID);
      $count_range = $ptrip->Counter($agent["id"], "query");
      $count_range += $ptt->Counter($agent["id"], "SNMPQUERY");
      if ($task == "1") {
         $tasks = $ptt->ListTask($agent["id"], "SNMPQUERY");
         foreach ($tasks as $task_id=>$taskInfos) {
            file_put_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/query.log".rand(), $agent["id"]);
            if ($tasks[$task_id]["param"] == 'PluginFusioninventoryAgent') {
               $task = "0";
            }
         }
         if ($task == "1") {
            $agent["core_query"] = 1;
            $agent["threads_query"] = 1;
         }
      }

      // Get total number of devices to query
      $ranges = $ptrip->ListRange($agent["id"], "query");
      $modelslistused = array();
      foreach ($ranges as $range_id=>$rangeInfos) {
         $modelslistused = $this->addDevice($sxml_option, 'networking', $ranges[$range_id]["ifaddr_start"],
                     $ranges[$range_id]["ifaddr_end"], $ranges[$range_id]["entities_id"], $modelslistused,0);
         $modelslistused = $this->addDevice($sxml_option, 'printer', $ranges[$range_id]["ifaddr_start"],
                     $ranges[$range_id]["ifaddr_end"], $ranges[$range_id]["entities_id"], $modelslistused,0);
      }


      if ((($count_range > 0) AND ($agent["lock"] == 0) AND (!empty($modelslistused))) OR ($task == "1")) {
         $a_input = array();
         if ($_SESSION['glpi_plugin_fusioninventory_addagentprocess'] == '0') {
            $this->addProcessNumber($ptap->addProcess($pxml));
            $_SESSION['glpi_plugin_fusioninventory_addagentprocess'] = '1';
         }
         $a_input['query_core'] = $agent["core_query"];
         $a_input['query_threads'] = $agent["threads_query"];
         $ptap->updateProcess($this->sxml->PROCESSNUMBER, $a_input);

         $sxml_option = $this->sxml->addChild('OPTION');
            $sxml_option->addChild('NAME', 'SNMPQUERY');
            $sxml_param = $sxml_option->addChild('PARAM');
               $sxml_param->addAttribute('CORE_QUERY', $agent["core_query"]);
               $sxml_param->addAttribute('THREADS_QUERY', $agent["threads_query"]);
               $sxml_param->addAttribute('PID', $this->sxml->PROCESSNUMBER);


               if ($task == "1") {
                  foreach ($tasks as $task_id=>$taskInfos) {
                     // TODO : envoyer une plage avec juste cette ip ***
                     switch ($tasks[$task_id]['itemtype']) {

                        case NETWORKING_TYPE:
                           $modelslistused = $this->addDevice($sxml_option, 'networking', 0,
                                 0, "-1", $modelslistused, 1, $tasks[$task_id]['items_id']);
                           break;

                        case PRINTER_TYPE:
                           $modelslistused = $this->addDevice($sxml_option, 'printer', 0,
                                 0, "-1", $modelslistused, 1, $tasks[$task_id]['items_id']);
                           break;
                        
                     }


                     //
                     //
//                     $modelslistused = $this->addDevice($sxml_option, 'networking', $ranges[$range_id]["ifaddr_start"],
//                                 $ranges[$range_id]["ifaddr_end"], $ranges[$range_id]["entities_id"], $modelslistused);
                  }
               } else {
                  $ranges = $ptrip->ListRange($agent["id"], "query");
                  $modelslistused = array();
                  foreach ($ranges as $range_id=>$rangeInfos) {
                     $modelslistused = $this->addDevice($sxml_option, 'networking', $ranges[$range_id]["ifaddr_start"],
                                 $ranges[$range_id]["ifaddr_end"], $ranges[$range_id]["entities_id"], $modelslistused);
                     $modelslistused = $this->addDevice($sxml_option, 'printer', $ranges[$range_id]["ifaddr_start"],
                                 $ranges[$range_id]["ifaddr_end"], $ranges[$range_id]["entities_id"], $modelslistused);
                  }
               }

            $snmpauthlist=$ptsnmpa->find();
            if (count($snmpauthlist)){
               foreach ($snmpauthlist as $snmpauth){
                  $this->addAuth($sxml_option, $snmpauth['id']);
               }
            }

            $modelslist=$ptmi->find();
            if (count($modelslist)){
               foreach ($modelslist as $model){
                  if (isset($modelslistused[$model['id']])) {
                     $this->addModel($sxml_option, $model['id']);
                  }
               }
            }
      }
   }

   /**
    * Add MODEL string to XML node
    *
    *@param $p_sxml_node XML node to complete
    *@param $p_id Model id
    *@return nothing
    **/
   function addModel($p_sxml_node, $p_id) {
      $models = new PluginFusioninventorySNMPModel;
      $mib_networking = new PluginFusioninventorySNMPModelMib;

      $models->getFromDB($p_id);
      $sxml_model = $p_sxml_node->addChild('MODEL');
         $sxml_model->addAttribute('ID', $p_id);
         $sxml_model->addAttribute('NAME', $models->fields['name']);
         $mib_networking->oidList($sxml_model,$p_id);
   }

   /**
    * Add GET string to XML node
    *
    *@param $p_sxml_node XML node to complete
    *@param $p_object Value of OBJECT attribute
    *@param $p_oid Value of OID attribute
    *@param $p_link Value of LINK attribute
    *@param $p_vlan Value of VLAN attribute
    *@return nothing
    **/
   function addGet($p_sxml_node, $p_object, $p_oid, $p_link, $p_vlan) {
      $sxml_get = $p_sxml_node->addChild('GET');
         $sxml_get->addAttribute('OBJECT', $p_object);
         $sxml_get->addAttribute('OID', $p_oid);
         $sxml_get->addAttribute('VLAN', $p_vlan);
         $sxml_get->addAttribute('LINK', $p_link);
   }

   /**
    * Add WALK string to XML node
    *
    *@param $p_sxml_node XML node to complete
    *@param $p_object Value of OBJECT attribute
    *@param $p_oid Value of OID attribute
    *@param $p_link Value of LINK attribute
    *@param $p_vlan Value of VLAN attribute
    *@return nothing
    **/
   function addWalk($p_sxml_node, $p_object, $p_oid, $p_link, $p_vlan) {
      $sxml_walk = $p_sxml_node->addChild('WALK');
         $sxml_walk->addAttribute('OBJECT', $p_object);
         $sxml_walk->addAttribute('OID', $p_oid);
         $sxml_walk->addAttribute('VLAN', $p_vlan);
         $sxml_walk->addAttribute('LINK', $p_link);
   }

   /**
    * Add INFO string to XML node
    *
    *@param $p_sxml_node XML node to complete
    *@param $p_id Value of ID attribute
    *@param $p_ip Value of IP attribute
    *@param $p_authsnmp_id Value of AUTHSNMP_ID attribute
    *@param $p_model_id Value of MODELSNMP_ID attribute
    *@param $p_type device type
    *@return nothing
    **/
   function addInfo($p_sxml_node, $p_id, $p_ip, $p_authsnmp_id, $p_model_id, $p_type) {
      $sxml_device = $p_sxml_node->addChild('DEVICE');
         $sxml_device->addAttribute('TYPE', $p_type);
         $sxml_device->addAttribute('ID', $p_id);
         $sxml_device->addAttribute('IP', $p_ip);
         $sxml_device->addAttribute('AUTHSNMP_ID', $p_authsnmp_id);
         $sxml_device->addAttribute('MODELSNMP_ID', $p_model_id);
   }

   /**
    * Add DEVICE string to XML node
    *
    *@param $p_sxml_node XML node to complete
    *@param $p_type Type of device
    *@param $p_ipstart Start ip of range
    *@param $p_ipend End ip of range
    *@param $p_entity Entity of device
    *@return true (device added) / false (unknown type of device)
    **/
   function addDevice($p_sxml_node, $p_type, $p_ipstart, $p_ipend, $p_entity, $modelslistused, $addingdevice=1, $devide_id=0) {
      global $DB;

      $type='';
      switch ($p_type) {
         
         case "networking":
            $type='NETWORKING';
            $query = "SELECT `glpi_networkequipments`.`id` AS `gID`,
                             `glpi_networkequipments`.`ip` AS `gnifaddr`,
                             `plugin_fusioninventory_snmpauths_id`, `plugin_fusinvsnmp_models_id`
                      FROM `glpi_networkequipments`
                      LEFT JOIN `glpi_plugin_fusinvsnmp_networkequipments`
                           ON `networkequipments_id`=`glpi_networkequipments`.`id`
                      INNER join `glpi_plugin_fusinvsnmp_models`
                           ON `plugin_fusinvsnmp_models_id`=`glpi_plugin_fusinvsnmp_models`.`id`
                      WHERE `glpi_networkequipments`.`is_deleted`='0'
                           AND `plugin_fusinvsnmp_models_id`!='0'
                           AND `plugin_fusioninventory_snmpauths_id`!='0'";
             if ($p_entity != '-1') {
               $query .= "AND `glpi_networkequipments`.`entities_id`='".$p_entity."' ";
             }
             if ($p_ipstart == '0') {
               $query .= " AND `glpi_networkequipments`.`id`='".$devide_id."'";
             } else {
               $query .= " AND inet_aton(`ip`)
                               BETWEEN inet_aton('".$p_ipstart."')
                               AND inet_aton('".$p_ipend."') ";
             }

            break;
         
         case "printer":
            $type='PRINTER';
            $query = "SELECT `glpi_printers`.`id` AS `gID`,
                             `glpi_networkports`.`ip` AS `gnifaddr`,
                             `plugin_fusioninventory_snmpauths_id`, `plugin_fusinvsnmp_models_id`
                      FROM `glpi_printers`
                      LEFT JOIN `glpi_plugin_fusinvsnmp_printers`
                              ON `printers_id`=`glpi_printers`.`id`
                      LEFT JOIN `glpi_networkports`
                              ON `items_id`=`glpi_printers`.`id`
                                 AND `itemtype`='".PRINTER_TYPE."'
                      INNER join `glpi_plugin_fusinvsnmp_models`
                           ON `plugin_fusinvsnmp_models_id`=`glpi_plugin_fusinvsnmp_models`.`id`
                      WHERE `glpi_printers`.`is_deleted`=0
                            AND `plugin_fusinvsnmp_models_id`!='0'
                            AND `plugin_fusioninventory_snmpauths_id`!='0'";
             if ($p_entity != '-1') {
               $query .= "AND `glpi_printers`.`entities_id`='".$p_entity."' ";
             }
             if ($p_ipstart == '0') {
               $query .= " AND `glpi_printers`.`id`='".$devide_id."'";
             } else {
               $query .= " AND inet_aton(`ip`)
                               BETWEEN inet_aton('".$p_ipstart."')
                               AND inet_aton('".$p_ipend."') ";
             }


            break;
         
         default: // type non géré
            return $modelslistused;
      }
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if ($addingdevice == '1') {
            $this->addInfo($p_sxml_node,
                           $data['gID'],
                           $data['gnifaddr'],
                           $data['plugin_fusioninventory_snmpauths_id'],
                           $data['plugin_fusinvsnmp_models_id'],
                           $type);
         }
         $modelslistused[$data['plugin_fusinvsnmp_models_id']] = 1;
      }
      return $modelslistused;
   }

   /**
    * Import data
    *
    *@param $p_DEVICEID XML code to import
    *@param $p_CONTENT XML code to import
    *@return "" (import ok) / error string (import ko)
    **/
   function import($p_DEVICEID, $p_CONTENT, $p_xml) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->import().');
      $this->setXML($p_CONTENT);
      $errors = '';

      if (isset($p_CONTENT->PROCESSNUMBER)) {
         $_SESSION['glpi_plugin_fusioninventory_processnumber'] = $p_CONTENT->PROCESSNUMBER;
      }
      $errors.=$this->importContent($p_CONTENT);
      $result=true;
      if ($errors != '') {
         if (isset($_SESSION['glpi_plugin_fusioninventory_processnumber'])) {
            $result=true;
            $ptap = new PluginFusioninventoryAgentProcess;
            $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                                 array('comment' => $errors));

         } else {
            // It's PROLOG
            $result=false;
         }
      }
      return $result;
   }

   /**
    * Import CONTENT
    *@param $p_content CONTENT code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importContent($p_content) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importContent().');
      $ptap = new PluginFusioninventoryAgentProcess;
      $pta  = new PluginFusioninventoryAgent;
      
      $errors='';
      $nbDevices = 0;

      foreach ($p_content->children() as $child) {
         PluginFusioninventoryCommunication::addLog($child->getName());
         switch ($child->getName()) {
            case 'DEVICE' :
               $errors.=$this->importDevice($child);
               $nbDevices++;
               break;

            case 'AGENT' :
               if (isset($this->sxml->CONTENT->AGENT->START)) {
                  $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                                       array('start_time_query' => date("Y-m-d H:i:s")));
               } else if (isset($this->sxml->CONTENT->AGENT->END)) {
                  $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                                       array('end_time_query' => date("Y-m-d H:i:s")));
               } else if (isset($this->sxml->CONTENT->AGENT->EXIT)) {
                  $ptap->endProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                                       date("Y-m-d H:i:s"));
               }
               if (isset($this->sxml->CONTENT->AGENT->AGENTVERSION)) {
                  $agent = $pta->InfosByKey($this->sxml->DEVICEID);
                  $agent['fusioninventory_agent_version'] = $this->sxml->CONTENT->AGENT->AGENTVERSION;
                  $agent['last_agent_update'] = date("Y-m-d H:i:s");
                  //$p_xml = gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);
                  $pta->update($agent);
               }
               break;

            case 'PROCESSNUMBER' :
               break;
            
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' CONTENT : '.$child->getName()."\n";
         }
      }
      return $errors;
   }

   /**
    * Import DEVICE
    *@param $p_device DEVICE code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importDevice($p_device) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importDevice().');
      $ptap = new PluginFusioninventoryAgentProcess;
      $ptae = new PluginFusioninventoryAgentProcessError;

      $errors=''; $this->deviceId='';
      switch ($p_device->INFO->TYPE) {
         case 'PRINTER':
            $this->type = PRINTER_TYPE;
            break;
         case 'NETWORKING':
            $this->type = NETWORKING_TYPE;
            break;
         default:
            $errors.=$LANG['plugin_fusioninventory']["errors"][22].' TYPE : '
                              .$p_device->INFO->TYPE."\n";
      }
      if (isset($p_device->ERROR)) {
         $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                              array('query_nb_error' => '1'));
         $a_input = array();
         $a_input['id'] = $p_device->ERROR->ID;
         if ($p_device->ERROR->TYPE=='NETWORKING') {
            $a_input['TYPE'] = NETWORKING_TYPE;
         } elseif ($p_device->ERROR->TYPE=='PRINTER') {
            $a_input['TYPE'] = PRINTER_TYPE;
         }
         $a_input['MESSAGE'] = $p_device->ERROR->MESSAGE;
         $a_input['agent_type'] = 'SNMPQUERY';
         $ptae->addError($a_input);
      } else {
         $ptap->updateProcess($this->sxml->CONTENT->PROCESSNUMBER, array('query_nb_query' => '1'));
         $errors.=$this->importInfo($p_device->INFO, $p_device);
         if ($this->deviceId!='') {
            foreach ($p_device->children() as $child) {
               switch ($child->getName()) {
                  case 'INFO' : // already managed
                     break;
                  case 'PORTS' :
                     $errors.=$this->importPorts($child);
                     break;
                  case 'CARTRIDGES' :
                     if ($this->type == PRINTER_TYPE) {
                        $errors.=$this->importCartridges($child);
                        break;
                     }
                  case 'PAGECOUNTERS' :
                     if ($this->type == PRINTER_TYPE) {
                        $errors.=$this->importPageCounters($child);
                        break;
                     }
                  default :
                     $errors.=$LANG['plugin_fusioninventory']["errors"][22].' DEVICE : '
                              .$child->getName()."\n";
               }
            }
            if ($errors=='') {
               $this->ptd->updateDB();
            } else {
               $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                     array('query_nb_error' => '1'));
               $a_input = array();
               $a_input['id'] = $p_device->ERROR->ID;
               if ($p_device->ERROR->TYPE=='NETWORKING') {
                  $a_input['TYPE'] = NETWORKING_TYPE;
               } elseif ($p_device->ERROR->TYPE=='PRINTER') {
                  $a_input['TYPE'] = PRINTER_TYPE;
               }
               $a_input['MESSAGE'] = $errors;
               $a_input['agent_type'] = 'SNMPQUERY';
               $ptae->addError($a_input);
            }
         } else {
            $ptap->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
                  array('query_nb_error' => '1'));
            $a_input = array();
            $a_input['id'] = $p_device->ERROR->ID;
            if ($p_device->ERROR->TYPE=='NETWORKING') {
               $a_input['TYPE'] = NETWORKING_TYPE;
            } elseif ($p_device->ERROR->TYPE=='PRINTER') {
               $a_input['TYPE'] = PRINTER_TYPE;
            }
            $a_input['MESSAGE'] = $errors;
            $a_input['agent_type'] = 'SNMPQUERY';
            $ptae->addError($a_input);
         }
      }

      return $errors;
   }

   /**
    * Import INFO
    *@param $p_info INFO code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importInfo($p_info, $p_device) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importInfo().');
      $errors='';
      $criteria['serial']  = trim($p_info->SERIAL);
      $criteria['name']    = $p_info->NAME;
      $criteria['macaddr'] = $p_info->MAC; //TODO get mac in PORT for printer
      $error_criteria = 0;
      if ($p_info->TYPE=='NETWORKING') {
         $this->deviceId = PluginFusioninventoryDiscovery::criteria($criteria, NETWORKING_TYPE);
         if ($this->deviceId != '') {
            $errors.=$this->importInfoNetworking($p_info);
         } else {
            $errors.=$LANG['plugin_fusioninventory']["errors"][23].'<br/>
                     type : '.$p_info->TYPE.'<br/>
                     id : '.$p_info->ID.'<br/>
                     serial : '.trim($p_info->SERIAL).'<br/>
                     name : '.$p_info->NAME.'<br/>
                     macaddress : '.$p_info->MAC.'\n';
            $error_criteria = 1;
         }
      } elseif ($p_info->TYPE=='PRINTER') {
         //TODO Get MAC address in port
         foreach ($p_device->children() as $child) {
            switch ($child->getName()) {
               case 'PORTS' :
                  foreach ($child->children() as $child_port) {
                     switch ($child_port->getName()) {
                        case 'PORT' :
                           $criteria['macaddr'] = $child_port->MAC;
                           if ($this->deviceId == '') {
                              $this->deviceId = PluginFusioninventoryDiscovery::criteria($criteria, PRINTER_TYPE);
                           }
                           break;
                     }
                  }
                  break;
            }
         }

         //$this->deviceId = PluginFusioninventoryDiscovery::criteria($criteria, PRINTER_TYPE);
         if ($this->deviceId != '') {
            $errors.=$this->importInfoPrinter($p_info);
         } else {
            if ($error_criteria == 0) {
               $errors.=$LANG['plugin_fusioninventory']["errors"][23].'<br/>
                        type : '.$p_info->TYPE.'<br/>
                        id : '.$p_info->ID.'<br/>
                        serial : '.trim($p_info->SERIAL).'<br/>
                        name : '.$p_info->NAME.'<br/>
                        macaddress : '.$p_info->MAC.'\n';
            }
         }
      }
      if (!empty($errors)) {
         $pfiae = new PluginFusioninventoryAgentProcessError;

         $a_input = array();
         $a_input['id'] = $p_info->ID;
         if ($p_info->TYPE=='NETWORKING') {
            $a_input['TYPE'] = NETWORKING_TYPE;
         } elseif ($p_info->TYPE=='PRINTER') {
            $a_input['TYPE'] = PRINTER_TYPE;
         }
         $a_input['MESSAGE'] = $errors;
         $a_input['agent_type'] = 'SNMPQUERY';
         $pfiae->addError($a_input);
      }

      return $errors;
   }

   /**
    * Import INFO:Networking
    *@param $p_info INFO code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importInfoNetworking($p_info) {
      global $LANG;

      $errors='';
      $this->ptd = new PluginFusioninventoryNetworkEquipment;
      $this->ptd->load($this->deviceId);

      foreach ($p_info->children() as $child)
      {
         switch ($child->getName()) {
            case 'ID' : // already managed
               break;
            case 'TYPE' : // already managed
               break;
            case 'COMMENTS' :
               $this->ptd->setValue('comment', $p_info->COMMENTS);
               break;
            case 'CPU' :
               $this->ptd->setValue('cpu', $p_info->CPU);
               break;
            case 'FIRMWARE' :
               $this->ptd->setValue('firmware', $p_info->FIRMWARE);
               break;
            case 'MAC' :
               $this->ptd->setValue('mac', $p_info->MAC);
               break;
            case 'MEMORY' :
               $this->ptd->setValue('memory', $p_info->MEMORY);
               break;
            case 'MODEL' :
               $this->ptd->setValue('model', $p_info->MODEL);
               break;
            case 'LOCATION' :
               $this->ptd->setValue('location', $p_info->LOCATION);
               break;
            case 'NAME' :
               $this->ptd->setValue('name', $p_info->NAME);
               break;
            case 'RAM' :
               $this->ptd->setValue('ram', $p_info->RAM);
               break;
            case 'SERIAL' :
               $this->ptd->setValue('serial', $p_info->SERIAL);
               break;
            case 'UPTIME' :
               $this->ptd->setValue('uptime', $p_info->UPTIME);
               break;
            case 'IPS' :
               $errors.=$this->importIps($child);
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' INFO : '.$child->getName()."\n";
         }
      }
      return $errors;
   }

   /**
    * Import INFO:Printer
    *@param $p_info INFO code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importInfoPrinter($p_info) {
      global $LANG;

      $errors='';
      $this->ptd = new PluginFusioninventoryPrinter;
      $this->ptd->load($this->deviceId);
      foreach ($p_info->children() as $child) {
         switch ($child->getName()) {
            case 'ID' : // already managed
               break;
            case 'TYPE' : // already managed
               break;
            case 'COMMENTS' :
               $this->ptd->setValue('comment', $p_info->COMMENTS);
               break;
            case 'MEMORY' :
               $this->ptd->setValue('memory', $p_info->MEMORY);
               break;
            case 'MODEL' :
               $this->ptd->setValue('model', $p_info->MODEL);
               break;
            case 'NAME' :
               $this->ptd->setValue('name', $p_info->NAME);
               break;
            case 'SERIAL' :
               $this->ptd->setValue('serial', $p_info->SERIAL);
               break;
            case 'OTHERSERIAL' :
               $this->ptd->setValue('otherserial', $p_info->OTHERSERIAL);
               break;
            case 'LOCATION' :
               $this->ptd->setValue('location', $p_info->LOCATION);
               break;
            case 'CONTACT' :
               $this->ptd->setValue('contact', $p_info->CONTACT);
               break;
            case 'MANUFACTURER' :
               $this->ptd->setValue('manufacturer', $p_info->MANUFACTURER); // TODO : regrouper tout ces cases
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' INFO : '.$child->getName()."\n";
         }
      }

      return $errors;
   }

   /**
    * Import IPS
    *@param $p_ips IPS code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importIps($p_ips) {
      global $LANG;

      $errors='';
      $pti = new PluginFusioninventoryNetworkEquipmentIp;
      foreach ($p_ips->children() as $name=>$child) {
         switch ($child->getName()) {
            case 'IP' :
               if ($child != "127.0.0.1") {
                  $ifaddrIndex = $this->ptd->getIfaddrIndex($child);
                  if (is_int($ifaddrIndex)) {
                     $oldIfaddr = $this->ptd->getIfaddr($ifaddrIndex);
                     $pti->load($oldIfaddr->getValue('id'));
                  } else {
                     $pti->load();
                  }
                  $pti->setValue('ip', $child);
                  $this->ptd->addIfaddr(clone $pti, $ifaddrIndex);
               }
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' IPS : '.$child->getName()."\n";
         }
      }
      $this->ptd->saveIfaddrs();
      return $errors;
   }

   /**
    * Import PORTS
    *@param $p_ports PORTS code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importPorts($p_ports) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importPorts().');
      $errors='';
      foreach ($p_ports->children() as $name=>$child)
      {
         switch ($child->getName()) {
            case 'PORT' :
               if ($this->type == PRINTER_TYPE) {
                  $errors.=$this->importPortPrinter($child);
               } elseif ($this->type == NETWORKING_TYPE) {
                  $errors.=$this->importPortNetworking($child);
               }
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' PORTS : '.$child->getName()."\n";
         }
      }
      return $errors;
   }

   /**
    * Import PORT Networking
    *@param $p_port PORT code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importPortNetworking($p_port) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importPortNetworking().');
      $errors='';
//      $ptp = new PluginFusioninventoryNetworkPort(NETWORKING_TYPE);
      $ptp = new PluginFusioninventoryNetworkPort(NETWORKING_TYPE, $this->logFile);
      $ifType = $p_port->IFTYPE;
      if ( $ptp->isReal($ifType) ) { // not virtual port
         $portIndex = $this->ptd->getPortIndex($p_port->IFNUMBER, $this->getConnectionIP($p_port));
         if (is_int($portIndex)) {
            $oldPort = $this->ptd->getPort($portIndex);
            $ptp->load($oldPort->getValue('id'));
         } else {
            $ptp->addDB($this->deviceId, TRUE);
         }
         foreach ($p_port->children() as $name=>$child) {
            switch ($name) {
               case 'CONNECTIONS' :
                  $errors.=$this->importConnections($child, $ptp);
                  break;
               case 'VLANS' :
                  $errors.=$this->importVlans($child, $ptp);
                  break;
               case 'IFNAME' :
                  PluginFusioninventoryNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue('name', $child);
                  break;
               case 'MAC' :
                  PluginFusioninventoryNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue('mac', $child);
                  break;
               case 'IFNUMBER' :
                  PluginFusioninventoryNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue('logical_number', $child);
                  break;
               case 'IFTYPE' : // already managed
                  break;
               case 'TRUNK' :
                  if (!$ptp->getNoTrunk()) {
                     PluginFusioninventoryNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                     $ptp->setValue('vlanTrunkPortDynamicStatus', $p_port->$name);
                  }
                  break;

               case 'IFDESCR' :
               case 'IFINERRORS' :
               case 'IFINOCTETS' :
               case 'IFINTERNALSTATUS' :
               case 'IFLASTCHANGE' :
               case 'IFMTU' :
               case 'IFOUTERRORS' :
               case 'IFOUTOCTETS' :
               case 'IFSPEED' :
               case 'IFSTATUS' :
                  PluginFusioninventoryNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue(strtolower($name), $p_port->$name);
                  break;
               default :
                  $errors.=$LANG['plugin_fusioninventory']["errors"][22].' PORT : '.$name."\n";
            }
         }
         $this->ptd->addPort($ptp, $portIndex);
      } else { // virtual port : do not import but delete if exists
         if ( is_numeric($ptp->getValue('id')) ) $ptp->deleteDB();
      }
      return $errors;
   }

   /**
    * Import PORT Printer
    *@param $p_port PORT code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importPortPrinter($p_port) {
      global $LANG;

      $errors='';
      $ptp = new PluginFusioninventoryNetworkPort(PRINTER_TYPE);
      $ifType = $p_port->IFTYPE;
      if ( $ptp->isReal($ifType) ) { // not virtual port
         $portIndex = $this->ptd->getPortIndex($p_port->MAC, $p_port->IP);
         if (is_int($portIndex)) {
            $oldPort = $this->ptd->getPort($portIndex);
            $ptp->load($oldPort->getValue('id'));
         } else {
            $ptp->addDB($this->deviceId, TRUE);
         }
         foreach ($p_port->children() as $name=>$child) {
            switch ($name) {
               case 'IFNAME' :
                  PluginFusioninventoryNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue('name', $child);
                  break;
               case 'MAC' :
                  PluginFusioninventoryNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue('mac', $child);
                  break;
               case 'IP' :
                  PluginFusioninventoryNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue('ip', $child);
                  break;
               case 'IFNUMBER' :
                  PluginFusioninventoryNetworkPortLog::networkport_addLog($ptp->getValue('id'), $child, strtolower($name));
                  $ptp->setValue('logical_number', $child);
                  break;
               case 'IFTYPE' : // already managed
                  break;
               default :
                  $errors.=$LANG['plugin_fusioninventory']["errors"][22].' PORT : '.$name."\n";
            }
         }
         $this->ptd->addPort($ptp, $portIndex);
      }
      return $errors;
   }

   /**
    * Import CARTRIDGES
    *@param $p_cartridges CARTRIDGES code to import
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importCartridges($p_cartridges) {
      global $LANG;

      $errors='';
      foreach ($p_cartridges->children() as $name=>$child)
      {
         switch ($name) {
            case 'TONERBLACK' :
            case 'TONERBLACK2' :
            case 'TONERCYAN' :
            case 'TONERMAGENTA' :
            case 'TONERYELLOW' :
            case 'WASTETONER' :
            case 'CARTRIDGEBLACK' :
            case 'CARTRIDGEBLACKPHOTO' :
            case 'CARTRIDGECYAN' :
            case 'CARTRIDGECYANLIGHT' :
            case 'CARTRIDGEMAGENTA' :
            case 'CARTRIDGEMAGENTALIGHT' :
            case 'CARTRIDGEYELLOW' :
            case 'MAINTENANCEKIT' :
            case 'DRUMBLACK' :
            case 'DRUMCYAN' :
            case 'DRUMMAGENTA' :
            case 'DRUMYELLOW' :
               $ptc = new PluginFusioninventoryPrinter_Cartridge();
               $cartridgeIndex = $this->ptd->getCartridgeIndex($name);
               if (is_int($cartridgeIndex)) {
                  $oldCartridge = $this->ptd->getCartridge($cartridgeIndex); //TODO ???
                  $ptc->load($oldCartridge->getValue('id'));
               } else {
                  $ptc->addCommon(TRUE); //TODO ???
                  $ptc->setValue('printers_id', $this->deviceId);
               }
               $ptc->setValue('object_name', $name);
               $ptc->setValue('state', $child, $ptc, 0);
               $this->ptd->addCartridge($ptc, $cartridgeIndex);
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' CARTRIDGES : '.$name."\n";
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

      $errors='';
      foreach ($p_pagecounters->children() as $name=>$child)
      {
         switch ($child->getName()) {
            case 'TOTAL' :
               $errors.=$this->ptd->addPageCounter('pages_total', $child);
               break;
            case 'BLACK' :
               $errors.=$this->ptd->addPageCounter('pages_n_b', $child);
               break;
            case 'COLOR' :
               $errors.=$this->ptd->addPageCounter('pages_color', $child);
               break;
            case 'RECTOVERSO' :
               $errors.=$this->ptd->addPageCounter('pages_recto_verso', $child);
               break;
            case 'SCANNED' :
               $errors.=$this->ptd->addPageCounter('scanned', $child);
               break;
            case 'PRINTTOTAL' :
               $errors.=$this->ptd->addPageCounter('pages_total_print', $child);
               break;
            case 'PRINTBLACK' :
               $errors.=$this->ptd->addPageCounter('pages_n_b_print', $child);
               break;
            case 'PRINTCOLOR' :
               $errors.=$this->ptd->addPageCounter('pages_color_print', $child);
               break;
            case 'COPYTOTAL' :
               $errors.=$this->ptd->addPageCounter('pages_total_copy', $child);
               break;
            case 'COPYBLACK' :
               $errors.=$this->ptd->addPageCounter('pages_n_b_copy', $child);
               break;
            case 'COPYCOLOR' :
               $errors.=$this->ptd->addPageCounter('pages_color_copy', $child);
               break;
            case 'FAXTOTAL' :
               $errors.=$this->ptd->addPageCounter('pages_total_fax', $child);
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' PAGECOUNTERS : '.$name."\n";
         }
      }
      return $errors;
   }

   /**
    * Import CONNECTIONS
    *@param $p_connections CONNECTIONS code to import
    *@param $p_oPort Port object to connect
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importConnections($p_connections, $p_oPort) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importConnections().');
      $errors='';
      if (isset($p_connections->CDP)) {
         $cdp = $p_connections->CDP;
         if ($cdp==1) {
            $p_oPort->setCDP();
         } else {
            $errors.=$LANG['plugin_fusioninventory']["errors"][22].' CONNECTIONS : CDP='.$cdp."\n";
         }
      } else {
         $cdp = 0;
      }
      $count = 0;
      foreach ($p_connections->children() as $name=>$child) {
         switch ($child->getName()) {
            case 'CDP' : // already managed
               break;
            case 'CONNECTION' :
               $count++;
               $errors.=$this->importConnection($child, $p_oPort, $cdp);
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' CONNECTIONS : '
                        .$child->getName()."\n";
         }
      }
      if ($p_oPort->getValue('trunk')!=1) {
         if ($count > 1) { // MultipleMac
            $p_oPort->setNoTrunk();
            $pfiud = new PluginFusioninventoryUnknownDevice;
            $pfiud->hubNetwork($p_oPort);
         } else {
            if (!$p_oPort->getNoTrunk()) {
               $p_oPort->setValue('trunk', 0);
            }
         }
//      } else {
//         if ($p_oPort->getValue('trunk') == '-1') {
//            $p_oPort->setValue('trunk', '0');
//         }
      }
      return $errors;
   }

   /**
    * Import CONNECTION
    *@param $p_connection CONNECTION code to import
    *@param $p_oPort Port object to connect
    *@param $p_cdp CDP value (1 or <>1)
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importConnection($p_connection, $p_oPort, $p_cdp) {
      global $LANG;

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusinvsnmpCommunicationSNMPQuery->importConnection().');
      $errors='';
      $portID=''; $mac=''; $ip='';
      $ptsnmp= new PluginFusioninventorySNMP;
      if ($p_cdp==1) {
         $ifdescr='';
         foreach ($p_connection->children() as $name=>$child) {
            switch ($child->getName()) {
               case 'IP' :
                  $ip=$child;
                  $p_oPort->addIp($ip);
                  break;
               case 'IFDESCR' :
                  $ifdescr=$child;
                  break;
               default :
                  $errors.=$LANG['plugin_fusioninventory']["errors"][22].' CONNECTION (CDP='.$p_cdp.') : '
                           .$child->getName()."\n";
            }
         }
         $portID=$ptsnmp->getPortIDfromDeviceIP($ip, $ifdescr);
      } else {
         foreach ($p_connection->children() as $name=>$child) {
            switch ($child->getName()) {
               case 'MAC' :
                  $mac=$child;
                  $portID=$ptsnmp->getPortIDfromDeviceMAC($child, $p_oPort->getValue('id'));
                  $p_oPort->addMac($mac);
                  break;
               case 'IP' ://TODO : si ip ajouter une tache de decouverte sur l'ip pour recup autre info // utile seulement si mac inconnu dans glpi
                  $ip=$child;
                  $p_oPort->addIp($ip);
                  break;
               default :
                  $errors.=$LANG['plugin_fusioninventory']["errors"][22].' CONNECTION (CDP='.$p_cdp.') : '
                           .$child->getName()."\n";
            }            
         }
      }
      if ($portID != '') {
         $p_oPort->addConnection($portID);
         if ($ip != '') $p_oPort->setValue('ip', $ip);
      } else {
         $p_oPort->addUnknownConnection($mac, $ip);
         //TODO : si ip ajouter une tache de decouverte sur l'ip pour recup autre info
      }
      return $errors;
   }

   /**
    * Import VLANS
    *@param $p_vlans VLANS code to import
    *@param $p_oPort Port object to connect
    *
    *@return errors string to be alimented if import ko / '' if ok
    **/
   function importVlans($p_vlans, $p_oPort) {
      global $LANG;

      $errors='';
      foreach ($p_vlans->children() as $name=>$child)
      {
         switch ($child->getName()) {
            case 'VLAN' :
               $errors.=$this->importVlan($child, $p_oPort);
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' VLANS : '.$child->getName()."\n";
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
   function importVlan($p_vlan, $p_oPort) {
      global $LANG;

      $errors='';
      $number=''; $name='';
      foreach ($p_vlan->children() as $child) {
         switch ($child->getName()) {
            case 'NUMBER' :
               $number=$child;
               break;
            case 'NAME' :
               $name=$child;
               break;
            default :
               $errors.=$LANG['plugin_fusioninventory']["errors"][22].' VLAN : '.$child->getName()."\n";
         }
      }
      $p_oPort->addVlan($number, $name);
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
            case 'CONNECTIONS' :
               foreach ($connectionsChild->children() as $connectionName=>$connectionChild) {
                  switch ($connectionName) {
                     case 'CONNECTION' :
                        foreach ($connectionChild->children() as $ipName=>$ipChild) {
                           switch ($ipName) {
                              case 'IP' :
                                 if ($ipChild != '') return $ipChild;
                           }
                        }
                  }
               }
         }
      }
      return '';
   }

//   /**
//    * Get printer MAC address
//    *
//    *@param $p_port PORT code to import
//    *@return first connection IP or ''
//    **/
//   function getPrinterMac() {
//      $ports = $this->sxml->CONTENT->DEVICE->PORTS;
//      foreach ($ports->children() as $portName=>$portChild) {
//         switch ($portName) {
//            case 'PORT' :
//               foreach ($portChild->children() as $macName=>$macChild) {
//                  switch ($macName) {
//                     case 'MAC' :
//                        if ($macChild != '') return $macChild;
//                  }
//               }
//         }
//      }
//      return '';
//   }
}

?>
