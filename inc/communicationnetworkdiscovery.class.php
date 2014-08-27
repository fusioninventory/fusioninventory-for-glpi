<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
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

class PluginFusioninventoryCommunicationNetworkDiscovery {


   /**
    * Import data
    *
    * @param $p_DEVICEID XML code to import
    * @param $p_CONTENT XML code to import
    * @param $p_xml value XML code to import
    *
    * @return "" (import ok) / error string (import ko)
    *
    **/
   function import($p_DEVICEID, $a_CONTENT, $arrayinventory) {
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfAgent = new PluginFusioninventoryAgent();

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkDiscovery->import().');

      $errors = '';
      $a_agent = $pfAgent->InfosByKey($p_DEVICEID);
      if (isset($a_CONTENT['PROCESSNUMBER'])) {
         $_SESSION['glpi_plugin_fusioninventory_processnumber'] = $a_CONTENT['PROCESSNUMBER'];
         if ($pfTaskjobstate->getFromDB($a_CONTENT['PROCESSNUMBER'])) {
            if ($pfTaskjobstate->fields['state'] != "3") {
               $pfTaskjobstate->changeStatus($a_CONTENT['PROCESSNUMBER'], 2);
               if ((!isset($a_CONTENT['AGENT']['START']))
                       AND (!isset($a_CONTENT['AGENT']['END']))) {
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
                  $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'] = $a_agent['id'];
                  $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'] =
                                 'PluginFusioninventoryAgent';
                  $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'] = '6';
                  $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] =
                                 $nb_devices.' ==devicesfound==';
                  $this->addtaskjoblog();
               }
            }
         }
      }

      if ($pfTaskjobstate->getFromDB($a_CONTENT['PROCESSNUMBER'])) {
         if ($pfTaskjobstate->fields['state'] != "3") {
            $pfImportExport = new PluginFusioninventorySnmpmodelImportExport();
            $errors.=$pfImportExport->import_netdiscovery($a_CONTENT, $p_DEVICEID);
            if (isset($a_CONTENT['AGENT']['END'])) {
               if ((isset($a_CONTENT['DICO'])) AND ($a_CONTENT['DICO'] == "REQUEST")) {
                  $pfAgent->getFromDB($pfTaskjobstate->fields["plugin_fusioninventory_agents_id"]);
                  $input = array();
                  $input['id'] = $pfAgent->fields['id'];
                  $input["senddico"] = "1";
                  $pfAgent->update($input);

                  $pfTaskjobstate->changeStatusFinish($a_CONTENT['PROCESSNUMBER'],
                                                      $a_agent['id'],
                                                      'PluginFusioninventoryAgent',
                                                      '1',
                                                      '==diconotuptodate==');
               } else {
                  $messages = array(
                      'Total Found'   => 0,
                      'Created' => 0,
                      'Updated' => 0
                  );
                  $messages['Updated'] = countElementsInTable('glpi_plugin_fusioninventory_taskjoblogs',
                                       "`plugin_fusioninventory_taskjobstates_id`='".$a_CONTENT['PROCESSNUMBER']."' "
                          . " AND `comment` LIKE '%==updatetheitem==%'");
                  $messages['Created'] = countElementsInTable('glpi_plugin_fusioninventory_taskjoblogs',
                                       "`plugin_fusioninventory_taskjobstates_id`='".$a_CONTENT['PROCESSNUMBER']."' "
                          . " AND `comment` LIKE '%==addtheitem==%'");

                  $messages['Total Found'] = $messages['Updated'] + $messages['Created'];

                  $message = 'Total Found:'.$messages['Total Found'].' Created:'.$messages['Created'].' Updated:'.$messages['Updated'];

                  $pfTaskjobstate->changeStatusFinish($a_CONTENT['PROCESSNUMBER'],
                                                      $a_agent['id'],
                                                      'PluginFusioninventoryAgent',
                                                      '0',
                                                      $message);
               }
            }
         }
      }
      return $errors;
   }



   /**
    * Prepare data and send them to rule engine
    *
    * @param type $p_xml simpleXML object
    */
   function sendCriteria($arrayinventory) {

      PluginFusioninventoryCommunication::addLog(
              'Function PluginFusioninventoryCommunicationNetworkDiscovery->sendCriteria().');

      if ((isset($arrayinventory['MAC']))
              && ($arrayinventory['MAC'] == "00:00:00:00:00:00")) {
         unset($arrayinventory['MAC']);
      }

      $_SESSION['SOURCE_XMLDEVICE'] = $arrayinventory;

      $input = array();

      // Global criterias

      if ((isset($arrayinventory['SERIAL']))
              && (!empty($arrayinventory['SERIAL']))) {
         $input['serial'] = $arrayinventory['SERIAL'];
      }
      if ((isset($arrayinventory['MAC']))
              && (!empty($arrayinventory['MAC']))) {
         $input['mac'][] = $arrayinventory['MAC'];
      }
      if ((isset($arrayinventory['IP']))
              && (!empty($arrayinventory['IP']))) {
         $input['ip'][] = $arrayinventory['IP'];
      }
      if ((isset($arrayinventory['MODELSNMP']))
              && (!empty($arrayinventory['MODELSNMP']))) {
         $input['model'] = $arrayinventory['MODELSNMP'];
      }
      if ((isset($arrayinventory['SNMPHOSTNAME']))
              && (!empty($arrayinventory['SNMPHOSTNAME']))) {
         $input['name'] = $arrayinventory['SNMPHOSTNAME'];
      } else if ((isset($arrayinventory['NETBIOSNAME']))
              && (!empty($arrayinventory['NETBIOSNAME']))) {
         $input['name'] = $arrayinventory['NETBIOSNAME'];
      } else if ((isset($arrayinventory['DNSHOSTNAME']))
              && (!empty($arrayinventory['DNSHOSTNAME']))) {
         if (strpos($arrayinventory['DNSHOSTNAME'],'.') !== false) {
            $splitname = explode('.', $arrayinventory['DNSHOSTNAME']);
            $input['name'] = $splitname[0];
            if (!isset($arrayinventory['WORKGROUP'])) {
               unset($splitname[0]);
               $arrayinventory['WORKGROUP'] = implode('.', $splitname);
               $_SESSION['SOURCE_XMLDEVICE'] = $arrayinventory;
            }
         } else {
            $input['name'] = $arrayinventory['DNSHOSTNAME'];
         }
      }

      if (!isset($arrayinventory['ENTITY'])) {
         $arrayinventory['ENTITY'] = 0;
      }
      $input['entities_id'] = $arrayinventory['ENTITY'];
      if (isset($arrayinventory['TYPE'])) {
         switch ($arrayinventory['TYPE']) {

            case '1':
            case 'COMPUTER':
               $input['itemtype'] = "Computer";
               // Computer

                break;

            case '2':
            case 'NETWORKING':
               $input['itemtype'] = "NetworkEquipment";
                break;

            case '3':
            case 'PRINTER':
               $input['itemtype'] = "Printer";
                break;

         }
      }

      $_SESSION['plugin_fusinvsnmp_datacriteria'] = serialize($input);
      $_SESSION['plugin_fusioninventory_classrulepassed'] =
                     "PluginFusioninventoryCommunicationNetworkDiscovery";
      $rule = new PluginFusioninventoryInventoryRuleImportCollection();
      $data = $rule->processAllRules($input, array());
      PluginFusioninventoryConfig::logIfExtradebug("pluginFusioninventory-rules",
                                                   $data);

      if (isset($data['action'])
             && ($data['action'] == PluginFusioninventoryInventoryRuleImport::LINK_RESULT_DENIED)) {

         $a_text = '';
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

         $pfIgnoredimport = new PluginFusioninventoryIgnoredimportdevice();
         $inputdb = array();
         if (isset($input['name'])) {
            $inputdb['name'] = $input['name'];
         }
         $inputdb['date'] = date("Y-m-d H:i:s");
         if (isset($input['itemtype'])) {
            $inputdb['itemtype'] = $input['itemtype'];
         }
         if (isset($input['serial'])) {
            $input['serial'] = $input['serial'];
         }
         if (isset($input['ip'])) {
            $inputdb['ip'] = exportArrayToDB($input['ip']);
         }
         if (isset($input['mac'])) {
            $inputdb['mac'] = exportArrayToDB($input['mac']);
         }
         if (isset($input['uuid'])) {
            $inputdb['uuid'] = $input['uuid'];
         }
         $inputdb['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
         $inputdb['method'] = 'netdiscovery';
         $pfIgnoredimport->add($inputdb);
         unset($_SESSION['plugin_fusioninventory_rules_id']);
      }
      if (isset($data['_no_rule_matches']) AND ($data['_no_rule_matches'] == '1')) {
         if (!isset($_SESSION['glpiactiveentities_string'])) {
            $_SESSION['glpiactiveentities_string'] = "'".$input['entities_id']."'";
         }
         if (isset($input['itemtype'])
             && isset($data['action'])
             && ($data['action'] == PluginFusioninventoryInventoryRuleImport::LINK_RESULT_CREATE)) {

            $this->rulepassed(0, $input['itemtype'], $input['entities_id']);
         } else if (isset($input['itemtype'])
                AND !isset($data['action'])) {
            $this->rulepassed(0, $input['itemtype'], $input['entities_id']);
         } else {
            $this->rulepassed(0, "PluginFusioninventoryUnknownDevice", $input['entities_id']);
         }
      }
   }



   /**
    * After rule engine passed, update task (log) and create item if required
    *
    * @param type $items_id
    * @param type $itemtype
    * @param type $entities_id
    */
   function rulepassed($items_id, $itemtype, $entities_id=0) {

      PluginFusioninventoryLogger::logIfExtradebug(
         "pluginFusioninventory-rules",
         "Rule passed : ".$items_id.", ".$itemtype."\n"
      );
      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationNetDiscovery->rulepassed().'
      );

      if (!isset($_SESSION['glpiactiveentities_string'])) {
         $_SESSION['glpiactiveentities_string'] = "'".$entities_id."'";
      }

      $_SESSION['glpiactive_entity'] = $entities_id;

      $item = new $itemtype();
      if ($items_id == "0") {
         $input = array();
         $input['date_mod'] = date("Y-m-d H:i:s");
         $input['entities_id'] = $entities_id;
         $items_id = $item->add($input);
         if (isset($_SESSION['plugin_fusioninventory_rules_id'])) {
            $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
            $inputrulelog = array();
            $inputrulelog['date'] = date('Y-m-d H:i:s');
            $inputrulelog['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
            if (isset($_SESSION['plugin_fusioninventory_agents_id'])) {
               $inputrulelog['plugin_fusioninventory_agents_id'] =
                              $_SESSION['plugin_fusioninventory_agents_id'];
            }
            $inputrulelog['items_id'] = $items_id;
            $inputrulelog['itemtype'] = $itemtype;
            $inputrulelog['method'] = 'netdiscovery';
            $pfRulematchedlog->add($inputrulelog);
            $pfRulematchedlog->cleanOlddata($items_id, $itemtype);
            unset($_SESSION['plugin_fusioninventory_rules_id']);
         }
         if (!isset($_SESSION['glpiactiveentities_string'])) {
            $_SESSION['glpiactiveentities_string'] = "'".$entities_id."'";
         }
         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] =
               '[==detail==] ==addtheitem== '.$item->getTypeName().
               ' [['.$itemtype.'::'.$items_id.']]';
         $this->addtaskjoblog();
      } else {

         $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment'] =
               '[==detail==] ==updatetheitem== '.$item->getTypeName().
               ' [['.$itemtype.'::'.$items_id.']]';
         $this->addtaskjoblog();
      }
      $item->getFromDB($items_id);
      $this->importDevice($item);
   }



   /**
    * Import discovered device (add / update data in GLPI DB)
    *
    * @param object $item
    */
   function importDevice($item) {

      PluginFusioninventoryLogger::logIfExtradebugAndDebugMode(
         'fusioninventorycommunication',
         'Function PluginFusinvsnmpCommunicationNetDiscovery->importDevice().'
      );

      $arrayinventory = $_SESSION['SOURCE_XMLDEVICE'];
      $input = array();
      $input['id'] = $item->getID();

      $a_lockable = PluginFusioninventoryLock::getLockFields(getTableForItemType($item->getType()),
                                                             $item->getID());

      if (!in_array('name', $a_lockable)) {
         if (isset($arrayinventory['SNMPHOSTNAME'])
                 && !empty($arrayinventory['SNMPHOSTNAME'])) {
            $input['name'] = $arrayinventory['SNMPHOSTNAME'];
         } else if (isset($arrayinventory['NETBIOSNAME'])
                 && !empty($arrayinventory['NETBIOSNAME'])) {
            $input['name'] = $arrayinventory['NETBIOSNAME'];
         } else if (isset($arrayinventory['DNSHOSTNAME'])
                 &&!empty($arrayinventory['DNSHOSTNAME'])) {
            $input['name'] = $arrayinventory['DNSHOSTNAME'];
         }
      }
      if (!in_array('serial', $a_lockable)) {
         if (isset($arrayinventory['SERIAL'])) {
            if (trim($arrayinventory['SERIAL']) != '') {
               $input['serial'] = trim($arrayinventory['SERIAL']);
            }
         }
      }
      if (isset($input['name'])
              && $input['name'] == '') {
         unset($input['name']);
      }
      if (isset($input['serial'])
              && $input['serial'] == '') {
         unset($input['serial']);
      }

      if (isset($arrayinventory['ENTITY']) AND !empty($arrayinventory['ENTITY'])) {
         $input['entities_id'] = $arrayinventory['ENTITY'];
         if (!isset($_SESSION['glpiactiveentities_string'])) {
            $_SESSION['glpiactiveentities_string'] = "'".$arrayinventory['ENTITY']."'";
         }
      }
      if (!isset($_SESSION['glpiactiveentities_string'])) {
         $_SESSION['glpiactiveentities_string'] = "'".$item->fields['entities_id']."'";
      }

      switch ($item->getType()) {

         case 'Computer':
            // If computer is update with Agent, don't update it
            if (Dropdown::getDropdownName("glpi_autoupdatesystems",
                                          $item->fields['autoupdatesystems_id'])
                    != 'FusionInventory') {
               if (isset($arrayinventory['WORKGROUP'])) {
                  $domain = new Domain();
                  if (!in_array('domains_id', $a_lockable)) {
                     $input['domains_id'] = $domain->import(
                               array('name'=>$arrayinventory['WORKGROUP'])
                             );
                  }
               }
               $item->update($input);
               //Manage IP and Mac address
               $NetworkPort = new NetworkPort();
               $a_nports = current($NetworkPort->find(
                       "`itemtype`='Computer' AND `items_id`='".$item->getID()."'
                           AND `instantiation_type`='NetworkPortEthernet'",
                       "",
                       1));
               $networkports_id = 0;
               if (isset($a_nports['id'])) {
                  if (isset($arrayinventory['MAC'])
                          && !empty($arrayinventory['MAC'])) {
                     $input = array();
                     $input['id'] = $a_nports['id'];
                     $input['mac'] = $arrayinventory['MAC'];
                     $NetworkPort->update($input);
                  }
                  $networkports_id = $a_nports['id'];
               } else {
                  $input = array();
                  $input['itemtype'] = 'Computer';
                  $input['items_id'] = $item->getID();
                  $input['instantiation_type'] = 'NetworkPortEthernet';
                  $input['name'] = "management";
                  if (isset($arrayinventory['MAC']) AND !empty($arrayinventory['MAC'])) {
                     $input['mac'] = $arrayinventory['MAC'];
                  }
                  $networkports_id = $NetworkPort->add($input);
               }

               $networkName = new NetworkName();
               $a_networknames = current($networkName->find(
                       "`itemtype`='NetworkPort' AND `items_id`='".$networkports_id."'",
                       "",
                       1));
               $networknames_id = 0;
               if (isset($a_networknames['id'])) {
                  $networknames_id = $a_networknames['id'];
               } else {
                  $input = array();
                  $input['itemtype'] = 'NetworkPort';
                  $input['items_id'] = $networkports_id;
                  $networknames_id = $networkName->add($input);
               }
               if (isset($arrayinventory['IP'])) {
                  $iPAddress = new IPAddress();
                  $a_ipaddresses = $iPAddress->find("`itemtype`='NetworkName'
                                       AND `items_id`='".$networknames_id."'");
                  if (count($a_ipaddresses) == 0) {
                     $input = array();
                     $input['itemtype'] = 'NetworkName';
                     $input['items_id'] = $networknames_id;
                     $input['name'] = $arrayinventory['IP'];
                     $iPAddress->add($input);
                  } else {
                     $a_ipaddresse = current($a_ipaddresses);
                     if ($a_ipaddresse['name'] != $arrayinventory['IP']) {
                        $input = array();
                        $input['id'] = $a_ipaddresse['id'];
                        $input['name'] = $arrayinventory['IP'];
                        $iPAddress->update($input);
                     }
                  }
               }
            }
            break;

         case 'PluginFusioninventoryUnknownDevice':
            // Write XML file
            if (isset($_SESSION['SOURCE_XMLDEVICE'])) {
               PluginFusioninventoryToolbox::writeXML($item->getID(),
                                                      serialize($_SESSION['SOURCE_XMLDEVICE']),
                                                      'PluginFusioninventoryUnknownDevice'
                                                      );
            }


            if (!in_array('contact', $a_lockable)
                    && isset($arrayinventory['USERSESSION'])) {
               $input['contact'] = $arrayinventory['USERSESSION'];
            }
            if (!in_array('domain', $a_lockable)) {
               if (isset($arrayinventory['WORKGROUP'])
                       && !empty($arrayinventory['WORKGROUP'])) {
               $input['domain'] = Dropdown::importExternal("Domain",
                                       $arrayinventory['WORKGROUP'], $arrayinventory['ENTITY']);
               }
            }
            if (!empty($arrayinventory['TYPE'])) {
               switch ($arrayinventory['TYPE']) {

                  case '1':
                  case 'COMPUTER':
                     $input['item_type'] = 'Computer';
                     break;

                  case '2':
                  case 'NETWORKING':
                     $input['item_type'] = 'NetworkEquipment';
                     break;

                  case '3':
                  case 'PRINTER':
                     $input['item_type'] = 'Printer';
                     break;

               }
            }
            $input['plugin_fusioninventory_agents_id'] =
                           $_SESSION['glpi_plugin_fusioninventory_agentid'];
            if (isset($arrayinventory['DESCRIPTION'])
                    AND !empty($arrayinventory['DESCRIPTION'])) {
               $input['sysdescr'] = $arrayinventory['DESCRIPTION'];
            }
            if (isset($arrayinventory['MODELSNMP']) AND !empty($arrayinventory['MODELSNMP'])) {
               $pfModel = new PluginFusioninventorySnmpmodel();
               $model_id = $pfModel->getModelByKey($arrayinventory['MODELSNMP']);
               if (($model_id == '0')
                       && (isset($arrayinventory['DESCRIPTION']))
                       && (!empty($arrayinventory['DESCRIPTION']))) {
                  $model_id = $pfModel->getModelBySysdescr($arrayinventory['DESCRIPTION']);
               }
               if ($model_id != '0') {
                  $input['plugin_fusioninventory_snmpmodels_id'] = $model_id;
               }
            }
            if (isset($arrayinventory['AUTHSNMP']) AND !empty($arrayinventory['AUTHSNMP'])) {
               $input['plugin_fusioninventory_configsecurities_id'] = $arrayinventory['AUTHSNMP'];
            }
            $item->update($input);

            //Manage IP and Mac address
            $NetworkPort = new NetworkPort();
            $a_nports = current($NetworkPort->find(
                    "`itemtype`='PluginFusioninventoryUnknownDevice' AND `items_id`='".$item->getID()."'
                        AND `instantiation_type`='NetworkPortEthernet'",
                    "",
                    1));
            $networkports_id = 0;
            if (isset($a_nports['id'])) {
               if (isset($arrayinventory['MAC'])
                       && !empty($arrayinventory['MAC'])) {
                  $input = array();
                  $input['id'] = $a_nports['id'];
                  $input['mac'] = $arrayinventory['MAC'];
                  $NetworkPort->update($input);
               }
               $networkports_id = $a_nports['id'];
            } else {
               $input = array();
               $input['itemtype'] = 'PluginFusioninventoryUnknownDevice';
               $input['items_id'] = $item->getID();
               $input['instantiation_type'] = 'NetworkPortEthernet';
               $input['name'] = "management";
               if (isset($arrayinventory['MAC']) AND !empty($arrayinventory['MAC'])) {
                  $input['mac'] = $arrayinventory['MAC'];
               }
               $networkports_id = $NetworkPort->add($input);
            }

            $networkName = new NetworkName();
            $a_networknames = current($networkName->find(
                    "`itemtype`='NetworkPort' AND `items_id`='".$networkports_id."'",
                    "",
                    1));
            $networknames_id = 0;
            if (isset($a_networknames['id'])) {
               $networknames_id = $a_networknames['id'];
            } else {
               $input = array();
               $input['itemtype'] = 'NetworkPort';
               $input['items_id'] = $networkports_id;
               $networknames_id = $networkName->add($input);
            }
            if (isset($arrayinventory['IP'])) {
               $iPAddress = new IPAddress();
               $a_ipaddresses = $iPAddress->find("`itemtype`='NetworkName'
                                    AND `items_id`='".$networknames_id."'");
               if (count($a_ipaddresses) == 0) {
                  $input = array();
                  $input['itemtype'] = 'NetworkName';
                  $input['items_id'] = $networknames_id;
                  $input['name'] = $arrayinventory['IP'];
                  $iPAddress->add($input);
               } else {
                  $a_ipaddresse = current($a_ipaddresses);
                  if ($a_ipaddresse['name'] != $arrayinventory['IP']) {
                     $input = array();
                     $input['id'] = $a_ipaddresse['id'];
                     $input['name'] = $arrayinventory['IP'];
                     $iPAddress->update($input);
                  }
               }
            }
            break;

         case 'NetworkEquipment':
            $item->update($input);

            $NetworkPort = new NetworkPort();
            $a_npAggregates = current($NetworkPort->find(
                    "`itemtype`='NetworkEquipment' AND `items_id`='".$item->getID()."'".
                       " AND `instantiation_type`='NetworkPortAggregate'",
                    "",
                    1));
            $networkports_id = 0;
            if (isset($a_npAggregates['id'])) {
               if (isset($arrayinventory['MAC']) AND !empty($arrayinventory['MAC'])) {
                  $input = array();
                  $input['id'] = $a_npAggregates['id'];
                  $input['mac'] = $arrayinventory['MAC'];
                  $NetworkPort->update($input);
               }
               $networkports_id = $a_npAggregates['id'];
            } else {
               $input = array();
               $input['itemtype'] = 'NetworkEquipment';
               $input['items_id'] = $item->getID();
               $input['instantiation_type'] = 'NetworkPortAggregate';
               $input['name'] = "management";
               if (isset($arrayinventory['MAC'])
                       && !empty($arrayinventory['MAC'])) {
                  $input['mac'] = $arrayinventory['MAC'];
               }
               $networkports_id = $NetworkPort->add($input);
            }

            $networkName = new NetworkName();
            $a_networknames = current($networkName->find(
                    "`itemtype`='NetworkPort' AND `items_id`='".$networkports_id."'",
                    "",
                    1));
            $networknames_id = 0;
            if (isset($a_networknames['id'])) {
               $networknames_id = $a_networknames['id'];
            } else {
               $input = array();
               $input['itemtype'] = 'NetworkPort';
               $input['items_id'] = $networkports_id;
               $networknames_id = $networkName->add($input);
            }
            if (isset($arrayinventory['IP'])) {
               $iPAddress = new IPAddress();
               $a_ipaddresses = $iPAddress->find("`itemtype`='NetworkName'
                                    AND `items_id`='".$networknames_id."'");
               if (count($a_ipaddresses) == 0) {
                  $input = array();
                  $input['itemtype'] = 'NetworkName';
                  $input['items_id'] = $networknames_id;
                  $input['name'] = $arrayinventory['IP'];
                  $iPAddress->add($input);
               } else {
                  $a_ipaddresse = current($a_ipaddresses);
                  if ($a_ipaddresse['name'] != $arrayinventory['IP']) {
                     $input = array();
                     $input['id'] = $a_ipaddresse['id'];
                     $input['name'] = $arrayinventory['IP'];
                     $iPAddress->update($input);
                  }
               }
            }

            // Update SNMP informations
            $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();
            $a_snmpnetworkequipments = $pfNetworkEquipment->find(
                       "`networkequipments_id`='".$item->getID()."'"
                    );
            $input = array();
            if (count($a_snmpnetworkequipments) > 0) {
               $addItem = FALSE;
               $a_snmpnetworkequipment = current($a_snmpnetworkequipments);
               $input['id'] = $a_snmpnetworkequipment['id'];
            } else {
               $input['networkequipments_id'] = $item->getID();
               $id = $pfNetworkEquipment->add($input);
               $pfNetworkEquipment->getFromDB($id);
               $input['id'] = $pfNetworkEquipment->fields['id'];
            }
            // Write XML file
            if (isset($_SESSION['SOURCE_XMLDEVICE'])) {
               PluginFusioninventoryToolbox::writeXML($item->getID(),
                                          serialize($_SESSION['SOURCE_XMLDEVICE']),
                                          "NetworkEquipment");
            }
            if (isset($arrayinventory['DESCRIPTION'])
                    && !empty($arrayinventory['DESCRIPTION'])) {
               $input['sysdescr'] = $arrayinventory['DESCRIPTION'];
            }
            $pfModel = new PluginFusioninventorySnmpmodel();
            if (isset($arrayinventory['MODELSNMP'])
                    && !empty($arrayinventory['MODELSNMP'])) {
               $model_id = $pfModel->getModelByKey($arrayinventory['MODELSNMP']);
               if ($model_id > 0) {
                  $input['plugin_fusioninventory_snmpmodels_id'] = $model_id;
               }
            }
            if (isset($arrayinventory['AUTHSNMP'])
                    && !empty($arrayinventory['AUTHSNMP'])) {
               $input['plugin_fusioninventory_configsecurities_id'] = $arrayinventory['AUTHSNMP'];
            }
            $pfNetworkEquipment->update($input);
            break;

         case 'Printer':
            $input['have_ethernet'] = '1';
            $item->update($input);

            //Manage IP and Mac address
            $NetworkPort = new NetworkPort();
            $a_nports = current($NetworkPort->find(
                    "`itemtype`='Printer' AND `items_id`='".$item->getID()."'
                        AND `instantiation_type`='NetworkPortEthernet'",
                    "",
                    1));
            $networkports_id = 0;
            if (isset($a_nports['id'])) {
               if (isset($arrayinventory['MAC'])
                       && !empty($arrayinventory['MAC'])) {
                  $input = array();
                  $input['id'] = $a_nports['id'];
                  $input['mac'] = $arrayinventory['MAC'];
                  $NetworkPort->update($input);
               }
               $networkports_id = $a_nports['id'];
            } else {
               $input = array();
               $input['itemtype'] = 'Printer';
               $input['items_id'] = $item->getID();
               $input['instantiation_type'] = 'NetworkPortEthernet';
               $input['name'] = "management";
               if (isset($arrayinventory['MAC']) AND !empty($arrayinventory['MAC'])) {
                  $input['mac'] = $arrayinventory['MAC'];
               }
               $networkports_id = $NetworkPort->add($input);
            }

            $networkName = new NetworkName();
            $a_networknames = current($networkName->find(
                    "`itemtype`='NetworkPort' AND `items_id`='".$networkports_id."'",
                    "",
                    1));
            $networknames_id = 0;
            if (isset($a_networknames['id'])) {
               $networknames_id = $a_networknames['id'];
            } else {
               $input = array();
               $input['itemtype'] = 'NetworkPort';
               $input['items_id'] = $networkports_id;
               $networknames_id = $networkName->add($input);
            }
            if (isset($arrayinventory['IP'])) {
               $iPAddress = new IPAddress();
               $a_ipaddresses = $iPAddress->find("`itemtype`='NetworkName'
                                    AND `items_id`='".$networknames_id."'");
               if (count($a_ipaddresses) == 0) {
                  $input = array();
                  $input['itemtype'] = 'NetworkName';
                  $input['items_id'] = $networknames_id;
                  $input['name'] = $arrayinventory['IP'];
                  $iPAddress->add($input);
               } else {
                  $a_ipaddresse = current($a_ipaddresses);
                  if ($a_ipaddresse['name'] != $arrayinventory['IP']) {
                     $input = array();
                     $input['id'] = $a_ipaddresse['id'];
                     $input['name'] = $arrayinventory['IP'];
                     $iPAddress->update($input);
                  }
               }
            }

            // Update SNMP informations
            $pfPrinter = new PluginFusioninventoryPrinter();
            $a_snmpprinters = $pfPrinter->find("`printers_id`='".$item->getID()."'");
            $input = array();
            if (count($a_snmpprinters) > 0) {
               $addItem = FALSE;
               $a_snmpprinter = current($a_snmpprinters);
               $input['id'] = $a_snmpprinter['id'];
            } else {
               $input['printers_id'] = $item->getID();
               $id = $pfPrinter->add($input);
               $pfPrinter->getFromDB($id);
               $input['id'] = $pfPrinter->fields['id'];
            }
            // Write XML file
            if (isset($_SESSION['SOURCE_XMLDEVICE'])) {
               PluginFusioninventoryToolbox::writeXML($item->getID(),
                                          serialize($_SESSION['SOURCE_XMLDEVICE']),
                                          "Printer");
            }
            $input['sysdescr'] = $arrayinventory['DESCRIPTION'];
            if (isset($arrayinventory['MODELSNMP'])
                    && !empty($arrayinventory['MODELSNMP'])) {
               $pfModel = new PluginFusioninventorySnmpmodel();
               $model_id = $pfModel->getModelByKey($arrayinventory['MODELSNMP']);
               if ($model_id != '0') {
                  $input['plugin_fusioninventory_snmpmodels_id'] = $model_id;
               }
            }
            $input['plugin_fusioninventory_configsecurities_id'] = $arrayinventory['AUTHSNMP'];
            $pfPrinter->update($input);
            break;

      }
   }



   /**
    * Used to add log in the task
    */
   function addtaskjoblog() {

      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $pfTaskjoblog->addTaskjoblog(
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['state'],
                     $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment']);
   }



   static function getMethod() {
      return 'netdiscovery';
   }

}

?>
