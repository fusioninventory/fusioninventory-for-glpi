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
 * This file is used to manage the update of information into network
 * equipment in GLPI.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the update of information into network equipment in GLPI.
 */
class PluginFusioninventoryInventoryNetworkEquipmentLib extends CommonDBTM {


   /**
    * Function to update NetworkEquipment
    *
    * @global object $DB
    * @param array $a_inventory data fron agent inventory
    * @param integer $items_id id of the networkequipment
    */
   function updateNetworkEquipment($a_inventory, $items_id) {
      global $DB;

      $networkEquipment = new NetworkEquipment();
      $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();

      $networkEquipment->getFromDB($items_id);

      if (!isset($_SESSION['glpiactiveentities_string'])) {
         $_SESSION['glpiactiveentities_string'] = $networkEquipment->fields['entities_id'];
      }
      if (!isset($_SESSION['glpiactiveentities'])) {
         $_SESSION['glpiactiveentities'] = array($networkEquipment->fields['entities_id']);
      }
      if (!isset($_SESSION['glpiactive_entity'])) {
         $_SESSION['glpiactive_entity'] = $networkEquipment->fields['entities_id'];
      }

      // * NetworkEquipment
      $db_networkequipment =  $networkEquipment->fields;

      $a_lockable = PluginFusioninventoryLock::getLockFields('glpi_networkequipments', $items_id);

      $a_ret = PluginFusioninventoryToolbox::checkLock($a_inventory['NetworkEquipment'],
                                                       $db_networkequipment, $a_lockable);
      $a_inventory['NetworkEquipment'] = $a_ret[0];

      $mac = $a_inventory['NetworkEquipment']['mac'];
      unset($a_inventory['NetworkEquipment']['mac']);

      $input = $a_inventory['NetworkEquipment'];

      $input['id'] = $items_id;

      //Add defaut status if there's one defined in the configuration
      //If we're here it's because we've manually injected an snmpinventory xml file
      $input = PluginFusioninventoryToolbox::addDefaultStateIfNeeded('snmp', $input);

      $networkEquipment->update($input);

      $this->internalPorts($a_inventory['internalport'],
                           $items_id,
                           $mac,
                           'Internal');


      // * NetworkEquipment fusion (ext)
         $db_networkequipment = array();
         $query = "SELECT *
            FROM `".  getTableForItemType("PluginFusioninventoryNetworkEquipment")."`
            WHERE `networkequipments_id` = '$items_id'";
         $result = $DB->query($query);
         while ($data = $DB->fetch_assoc($result)) {
            foreach ($data as $key=>$value) {
               $db_networkequipment[$key] = Toolbox::addslashes_deep($value);
            }
         }
         if (count($db_networkequipment) == '0') { // Add
            $a_inventory['PluginFusioninventoryNetworkEquipment']['networkequipments_id'] =
               $items_id;
            $pfNetworkEquipment->add($a_inventory['PluginFusioninventoryNetworkEquipment']);
         } else { // Update
            $idtmp = $db_networkequipment['id'];
            unset($db_networkequipment['id']);
            unset($db_networkequipment['networkequipments_id']);
            unset($db_networkequipment['plugin_fusioninventory_configsecurities_id']);

            $a_ret = PluginFusioninventoryToolbox::checkLock(
                        $a_inventory['PluginFusioninventoryNetworkEquipment'],
                        $db_networkequipment);
            $a_inventory['PluginFusioninventoryNetworkEquipment'] = $a_ret[0];
            $input = $a_inventory['PluginFusioninventoryNetworkEquipment'];
            $input['id'] = $idtmp;
            $pfNetworkEquipment->update($input);
         }

      // * Ports
         $this->importPorts($a_inventory, $items_id);

   }



   /**
    * IMport internal ports (so internal IP, management IP)
    *
    * @param array $a_ips
    * @param integer $networkequipments_id
    * @param string $mac
    * @param string $networkname_name
    */
   function internalPorts($a_ips, $networkequipments_id, $mac, $networkname_name) {

      $networkPort = new NetworkPort();
      $iPAddress = new IPAddress();
      $pfUnmanaged = new PluginFusioninventoryUnmanaged();
      $networkName = new NetworkName();

      // Get agregated ports
      $a_networkPortAggregates = current($networkPort->find(
                    "`itemtype`='NetworkEquipment'
                       AND `items_id`='".$networkequipments_id."'
                       AND `instantiation_type`='NetworkPortAggregate'
                       AND `logical_number` = '0'", '', 1));
      $a_ips_DB = array();
      if (isset($a_networkPortAggregates['id'])) {
         $a_networkPortAggregates['mac'] = $mac;
         $networkPort->update($a_networkPortAggregates);

         $networkports_id = $a_networkPortAggregates['id'];
      } else {
         $input = array();
         $input['itemtype'] = 'NetworkEquipment';
         $input['items_id'] = $networkequipments_id;
         $input['instantiation_type'] = 'NetworkPortAggregate';
         $input['name'] = 'general';
         $input['mac'] = $mac;
         $networkports_id = $networkPort->add($input);
      }
      // Get networkname
      $a_networknames_find = current($networkName->find("`items_id`='".$networkports_id."'
                                                         AND `itemtype`='NetworkPort'", "", 1));
      if (isset($a_networknames_find['id'])) {
         $networknames_id = $a_networknames_find['id'];
         $a_networknames_find['name'] = $networkname_name;
         $networkName->update($a_networknames_find);
      } else {
         $input = array();
         $input['items_id'] = $networkports_id;
         $input['itemtype'] = 'NetworkPort';
         $input['name']     = $networkname_name;
         $networknames_id   = $networkName->add($input);
      }
      $a_ips_fromDB = $iPAddress->find("`itemtype`='NetworkName'
                                    AND `items_id`='".$networknames_id."'");
      foreach ($a_ips_fromDB as $data) {
         $a_ips_DB[$data['id']] = $data['name'];
      }

      foreach ($a_ips as $key => $ip) {
         foreach ($a_ips_DB as $keydb => $ipdb) {
            if ($ip == $ipdb) {
               unset($a_ips[$key]);
               unset($a_ips_DB[$keydb]);
               break;
            }
         }
      }
      if (count($a_ips) == 0
         AND count($a_ips_DB) == 0) {
         // Nothing to do
      } else {
         if (count($a_ips_DB) != 0 && count($a_ips) != 0) {
            // Delete IPs in DB
            foreach ($a_ips_DB as $idtmp => $ip) {
               $iPAddress->delete(array('id'=>$idtmp));
            }
         }
         if (count($a_ips) != 0) {
            foreach ($a_ips as $ip) {
               if ($ip != '127.0.0.1') {
                  $input = array();
                  $input['entities_id'] = 0;
                  $input['itemtype'] = 'NetworkName';
                  $input['items_id'] = $networknames_id;
                  $input['name'] = $ip;
                  $iPAddress->add($input);

                  // Search in unmanaged device if device with IP (LLDP) is yet added, in this case,
                  // we get id of this unmanaged device
                  $a_unmanageds = $pfUnmanaged->find("`ip`='".$ip."'", "", 1);
                  if (count($a_unmanageds) > 0) {
                     $datas= current($a_unmanageds);
                     $this->unmanagedCDP = $datas['id'];
                  }
               }
            }
         }
      }
   }



   /**
    * Import ports
    *
    * @param array $a_inventory
    * @param integer $items_id
    */
   function importPorts($a_inventory, $items_id) {

      $pfNetworkporttype = new PluginFusioninventoryNetworkporttype();
      $networkPort = new NetworkPort();
      $pfNetworkPort = new PluginFusioninventoryNetworkPort();

      $networkports_id = 0;
      foreach ($a_inventory['networkport'] as $a_port) {
         $ifType = $a_port['iftype'];
         if ($pfNetworkporttype->isImportType($ifType)
                 || isset($a_inventory['aggregate'][$a_port['logical_number']])
                 || $ifType == '') {
            $a_ports_DB = current($networkPort->find(
                       "`itemtype`='NetworkEquipment'
                          AND `items_id`='".$items_id."'
                          AND `logical_number` = '".$a_port['logical_number']."'", '', 1));
            if (!isset($a_ports_DB['id'])) {
               // Add port
               if (isset($a_inventory['aggregate'])
                       && isset($a_inventory['aggregate'][$a_port['logical_number']])) {
                  $a_port['instantiation_type'] = 'NetworkPortAggregate';
               } else {
                  $a_port['instantiation_type'] = 'NetworkPortEthernet';
               }
               $a_port['items_id'] = $items_id;
               $a_port['itemtype'] = 'NetworkEquipment';
               $networkports_id = $networkPort->add($a_port);
               unset($a_port['id']);
               $a_pfnetworkport_DB = current($pfNetworkPort->find(
                       "`networkports_id`='".$networkports_id."'", '', 1));
               $a_port['id'] = $a_pfnetworkport_DB['id'];
               $a_port['lastup'] = date('Y-m-d H:i:s');
               $pfNetworkPort->update($a_port);
            } else {
               // Update port
               $networkports_id = $a_ports_DB['id'];
               $a_port['id'] = $a_ports_DB['id'];
               $networkPort->update($a_port);
               unset($a_port['id']);

               // Check if pfnetworkport exist.
               $a_pfnetworkport_DB = current($pfNetworkPort->find(
                       "`networkports_id`='".$networkports_id."'", '', 1));
               $a_port['networkports_id'] = $networkports_id;
               if (isset($a_pfnetworkport_DB['id'])) {
                  $a_port['id'] = $a_pfnetworkport_DB['id'];
                  if ($a_port['ifstatus'] == 0
                          && $a_pfnetworkport_DB['ifstatus'] == 1) {
                     $a_port['lastup'] = date('Y-m-d H:i:s');
                  }
                  $pfNetworkPort->update($a_port);
               } else {
                  $a_port['networkports_id'] = $networkports_id;
                  $a_port['lastup'] = date('Y-m-d H:i:s');
                  $pfNetworkPort->add($a_port);
               }
            }

            // Connections
            if (isset($a_inventory['connection-lldp'][$a_port['logical_number']])) {
               $this->importConnectionLLDP(
                          $a_inventory['connection-lldp'][$a_port['logical_number']],
                          $networkports_id);
            } else if (isset($a_inventory['connection-mac'][$a_port['logical_number']])) {
               $this->importConnectionMac(
                          $a_inventory['connection-mac'][$a_port['logical_number']],
                          $networkports_id);
            }

            // Vlan
            if (isset($a_inventory['vlans'][$a_port['logical_number']])) {
               $this->importPortVlan($a_inventory['vlans'][$a_port['logical_number']],
                                     $networkports_id);
            }

            // Aggegation
            if (isset($a_inventory['aggregate'])
                    && isset($a_inventory['aggregate'][$a_port['logical_number']])) {
               $this->importPortAggregate($a_inventory['aggregate'][$a_port['logical_number']],
                                          $networkports_id, $items_id);
            }

         } else {
            // Delete the port
            $a_ports_DB = $networkPort->find(
                       "`itemtype`='NetworkEquipment'
                          AND `items_id`='".$items_id."'
                          AND `logical_number` = '".$a_port['logical_number']."'", '', 1);
            if (count($a_ports_DB) > 0) {
               $networkPort->delete(current($a_ports_DB));
            }
         }
      }

   }



   /**
    * Import LLDP connexions
    *
    * @param array $a_lldp
    * @param integer $networkports_id
    */
   function importConnectionLLDP($a_lldp, $networkports_id) {

      $pfNetworkPort = new PluginFusioninventoryNetworkPort();

      if ($a_lldp['ip'] == ''
              && $a_lldp['name'] == ''
              && $a_lldp['mac'] == '') {
         return;
      }

      $portID = FALSE;
      if ($a_lldp['ip'] != '') {
         $portID = $pfNetworkPort->getPortIDfromDeviceIP($a_lldp['ip'],
                                                         $a_lldp['ifdescr'],
                                                         $a_lldp['sysdescr'],
                                                         $a_lldp['name'],
                                                         $a_lldp['model']);
      } else {
         if ($a_lldp['mac'] != '') {
            $portID = $pfNetworkPort->getPortIDfromSysmacandPortnumber($a_lldp['mac'],
                                                                       $a_lldp['logical_number'],
                                                                       $a_lldp);
         }
      }

      if ($portID
              && $portID > 0) {
         $wire = new NetworkPort_NetworkPort();
         $contact_id = $wire->getOppositeContact($networkports_id);
         if (!($contact_id
                 AND $contact_id == $portID)) {
            $pfNetworkPort->disconnectDB($networkports_id);
            $pfNetworkPort->disconnectDB($portID);
            $wire->add(array('networkports_id_1'=> $networkports_id,
                             'networkports_id_2' => $portID));
         }
      }
   }



   /**
    * Import connexion with MAC address
    *
    * @param array $a_portconnection
    * @param integer $networkports_id
    */
   function importConnectionMac($a_portconnection, $networkports_id) {

      $wire = new NetworkPort_NetworkPort();
      $networkPort = new NetworkPort();
      $pfNetworkPort = new PluginFusioninventoryNetworkPort();
      $pfUnmanaged = new PluginFusioninventoryUnmanaged();

      $a_snmpports = current($pfNetworkPort->find("`networkports_id`='".$networkports_id."'",
                                                  "",
                                                  1));
      $pfNetworkPort->getFromDB($a_snmpports['id']);

      $count = count($a_portconnection);
      $pfNetworkPort->loadNetworkport($networkports_id);
      if ($pfNetworkPort->getValue('trunk') != '1') {
         if ($count == '2') {
            // detect if phone IP is one of the 2 devices
            $phonecase = 0;
            $macNotPhone_id = 0;
            $macNotPhone = '';
            $phonePort_id = 0;
            foreach ($a_portconnection as $ifmac) {
               $a_ports = $networkPort->find("`mac`='".$ifmac."'", "", 1);
               $a_port = current($a_ports);
               if ($a_port['itemtype'] == 'Phone') {
                  // Connect phone on switch port and other (computer..) in this phone
                  $phonePort_id = $a_port['id'];
                  $phonecase++;
               } else {
                  $macNotPhone_id = $a_port['id'];
                  $macNotPhone = $ifmac;
               }
            }
            if ($phonecase == '1') {
               $wire->add(array('networkports_id_1'=> $networkports_id,
                                'networkports_id_2' => $phonePort_id));
               $networkPort->getFromDB($phonePort_id);
               $Phone = new Phone();
               $Phone->getFromDB($networkPort->fields['items_id']);
               $a_portsPhone = $networkPort->find("`items_id`='".$networkPort->fields['items_id']."'
                                                AND `itemtype`='Phone'
                                                AND `name`='Link'", '', 1);
               $portLink_id = 0;
               if (count($a_portsPhone) == '1') {
                  $a_portPhone = current($a_portsPhone);
                  $portLink_id = $a_portPhone['id'];
               } else {
                  // Create Port Link
                  $input = array();
                  $input['name'] = 'Link';
                  $input['itemtype'] = 'Phone';
                  $input['items_id'] = $Phone->fields['id'];
                  $input['entities_id'] = $Phone->fields['entities_id'];
                  $portLink_id = $networkPort->add($input);
               }
               $opposite_id = FALSE;
               if ($opposite_id == $wire->getOppositeContact($portLink_id)) {
                  if ($opposite_id != $macNotPhone_id) {
                     $pfNetworkPort->disconnectDB($portLink_id); // disconnect this port
                     $pfNetworkPort->disconnectDB($macNotPhone_id); // disconnect destination port
                  }
               }
               if (!isset($macNotPhone_id)) {
                  // Create unmanaged ports
                  $unmanagedn_infos = array();
                  $unmanagedn_infos["name"] = '';
                  if (isset($_SESSION["plugin_fusioninventory_entity"])) {
                     $input['entities_id'] = $_SESSION["plugin_fusioninventory_entity"];
                  }
                  $newID = $pfUnmanaged->add($unmanagedn_infos);
                  // Add networking_port
                  $port_add = array();
                  $port_add["items_id"] = $newID;
                  $port_add["itemtype"] = 'PluginFusioninventoryUnmanaged';
                  $port_add['mac'] = $macNotPhone;
                  $port_add['instantiation_type'] = "NetworkPortEthernet";
                  $macNotPhone_id = $networkPort->add($port_add);
               }
               $wire->add(array('networkports_id_1'=> $portLink_id,
                                'networkports_id_2' => $macNotPhone_id));
            } else {
               $pfUnmanaged->hubNetwork($pfNetworkPort, $a_portconnection);
            }
         } else if ($count > 1) { // MultipleMac
            $pfUnmanaged->hubNetwork($pfNetworkPort, $a_portconnection);
         } else { // One mac on port
            foreach ($a_portconnection as $ifmac) { //Only 1 time
               $a_ports = $networkPort->find("`mac`='".$ifmac."' AND `logical_number`='1'", "", 1);
               if (count($a_ports) == 0) {
                  $a_ports = $networkPort->find("`mac`='".$ifmac."'", "", 1);
               }
               if (count($a_ports) > 0) {
                  $a_port = current($a_ports);
                  $hub = 0;
                  $id = $networkPort->getContact($a_port['id']);
                  if ($id AND $networkPort->getFromDB($id)) {
                     if ($networkPort->fields['itemtype'] == 'PluginFusioninventoryUnmanaged') {
                        $pfUnmanaged->getFromDB($networkPort->fields['items_id']);
                        if ($pfUnmanaged->fields['hub'] == '1') {
                           $hub = 1;
                        }
                     }
                  }
                  $direct_id = $networkPort->getContact($networkports_id);
                  if ($id AND $id != $networkports_id
                          AND $hub == '0') {

                     $directconnect = 0;
                     if (!$direct_id) {
                        $directconnect = 1;
                     } else {
                        $networkPort->getFromDB($direct_id);
                        if ($networkPort->fields['itemtype'] == 'PluginFusioninventoryUnmanaged') {
                           // 1. Hub connected to this switch port
                           $pfUnmanaged->connectPortToHub(array($a_port),
                                                              $networkPort->fields['items_id']);
                        } else {
                           // 2. direct connection
                           $directconnect = 1;
                        }
                     }
                     if ($directconnect == '1') {
                        $pfNetworkPort->disconnectDB($networkports_id); // disconnect this port
                        $pfNetworkPort->disconnectDB($a_port['id']); // disconnect destination port
                        $wire->add(array('networkports_id_1'=> $networkports_id,
                                         'networkports_id_2' => $a_port['id']));
                     }
                  } else if ($id and $hub == '1') {
                     $directconnect = 0;
                     if (!$direct_id) {
                        $directconnect = 1;
                     } else {
                        $networkPort->getFromDB($direct_id);
                        $ddirect = $networkPort->fields;
                        $networkPort->getFromDB($id);
                        if ($ddirect['items_id'] == $networkPort->fields['items_id']
                                AND $ddirect['itemtype'] == $networkPort->fields['itemtype']) {
                           // 1.The hub where this device is connected is yet connected
                           // to this switch port

                           // => Do nothing
                        } else {
                           // 2. The hub where this device is connected to is not connected
                           // to this switch port
                           if ($ddirect['itemtype'] == 'PluginFusioninventoryUnmanaged') {
                              // b. We have a hub connected to the switch port
                              $pfUnmanaged->connectPortToHub(array($a_port),
                                                                 $ddirect['items_id']);
                           } else {
                              // a. We have a direct connexion to another device
                              // (on the switch port)
                              $directconnect = 1;
                           }
                        }
                     }
                     if ($directconnect == '1') {
                        $pfNetworkPort->disconnectDB($networkports_id); // disconnect this port
                        $pfNetworkPort->disconnectDB($a_port['id']); // disconnect destination port
                        $wire->add(array('networkports_id_1'=> $networkports_id,
                                         'networkports_id_2' => $a_port['id']));
                     }
                  } else if ($id) {
                     // Yet connected
                  } else {
                     // Not connected
                     $pfNetworkPort->disconnectDB($networkports_id); // disconnect this port
                     $wire->add(array('networkports_id_1'=> $networkports_id,
                                      'networkports_id_2' => $a_port['id']));
                  }
               } else {
                  // Create unmanaged device
                  $pfUnmanaged = new PluginFusioninventoryUnmanaged();
                  $input = array();
                  $manufacturer =
                     PluginFusioninventoryInventoryExternalDB::getManufacturerWithMAC($ifmac);
                  $manufacturer = Toolbox::addslashes_deep($manufacturer);
                  $manufacturer = Toolbox::clean_cross_side_scripting_deep($manufacturer);
                  $input['name'] = $manufacturer;
                  if (isset($_SESSION["plugin_fusioninventory_entity"])) {
                     $input['entities_id'] = $_SESSION["plugin_fusioninventory_entity"];
                  }
                  $newID = $pfUnmanaged->add($input);
                  $input['itemtype'] = "PluginFusioninventoryUnmanaged";
                  $input['items_id'] = $newID;
                  $input['mac'] = $ifmac;
                  $input['instantiation_type'] = "NetworkPortEthernet";
                  $newPortID = $networkPort->add($input);
                  $pfNetworkPort->disconnectDB($networkports_id); // disconnect this port
                  $wire->add(array('networkports_id_1'=> $networkports_id,
                                   'networkports_id_2' => $newPortID));
               }
            }
         }
      }
   }



   /**
    * Import VLANs
    *
    * @global object $DB
    * @param array $a_vlans
    * @param integer $networkports_id
    */
   function importPortVlan($a_vlans, $networkports_id) {
      global $DB;

      $networkPort_Vlan = new NetworkPort_Vlan();

      $db_vlans = array();
      $query = "SELECT `glpi_networkports_vlans`.`id`, `glpi_vlans`.`name`, `glpi_vlans`.`tag`
                FROM `glpi_networkports_vlans`
                LEFT JOIN `glpi_vlans`
                  ON `vlans_id`=`glpi_vlans`.`id`
                WHERE `networkports_id` = '$networkports_id'";
      foreach ($DB->request($query) as $data) {
         $db_vlans[$data['name']."$$$$".$data['tag']] = $data['id'];
      }

      if (count($db_vlans) == 0) {
         foreach ($a_vlans as $a_vlan) {
            $this->addVlan($a_vlan, $networkports_id);
         }
      } else {
         foreach ($a_vlans as $key => $arrays) {
            foreach ($db_vlans as $keydb => $arraydb) {
               if ($arrays['name']."$$$$".$arrays['tag'] == $keydb) {
                  unset($a_vlans[$key]);
                  unset($db_vlans[$keydb]);
                  break;
               }
            }
         }

         if (count($a_vlans) == 0
            AND count($db_vlans) == 0) {
            // Nothing to do
         } else {
            if (count($db_vlans) != 0) {
               // Delete vlan in DB
               foreach ($db_vlans as $id) {
                  $networkPort_Vlan->delete(array('id'=>$id));
               }
            }
            if (count($a_vlans) != 0) {
               foreach ($a_vlans as $a_vlan) {
                  $this->addVlan($a_vlan, $networkports_id);
               }
            }
         }
      }
   }



   /**
    * Add VLAN if not exist
    *
    * @param array $a_vlan
    * @param integer $networkports_id
    */
   function addVlan($a_vlan, $networkports_id) {

      $networkPort_Vlan = new NetworkPort_Vlan();
      $vlan = new Vlan();

      $db_vlans = $vlan->find("`tag`='".$a_vlan['tag']."' AND `name`='".$a_vlan['name']."'",
                             "", 1);
      $vlans_id = 0;
      if (count($db_vlans) > 0) {
         $db_vlan = current($db_vlans);
         $vlans_id = $db_vlan['id'];
      } else {
         $input = array();
         $input['tag'] = $a_vlan['tag'];
         $input['name'] = $a_vlan['name'];
         $vlans_id = $vlan->add($input);
      }

      $input = array();
      $input['networkports_id'] = $networkports_id;
      $input['vlans_id'] = $vlans_id;
      $networkPort_Vlan->add($input);
   }



   /**
    * Import aggregate ports
    *
    * @param array $a_ports
    * @param integer $networkports_id
    * @param integer $networkequipments_id
    */
   function importPortAggregate($a_ports, $networkports_id, $networkequipments_id) {

      $networkPort = new NetworkPort();
      $networkPortAggregate = new NetworkPortAggregate();

      $a_aggregates = $networkPortAggregate->find("`networkports_id`='".$networkports_id."'", "", 1);

      $input = array();
      if (count($a_aggregates) == 1) {
         $input = current($a_aggregates);
      } else {
         $input['networkports_id'] = $networkports_id;
         $input['networkports_id_list'] = exportArrayToDB(array());
         $input['id'] = $networkPortAggregate->add($input);
      }
      $a_ports_db_tmp = array();
      foreach ($a_ports as $logical_number) {
         $a_networkports_DB = current($networkPort->find(
                    "`itemtype`='NetworkEquipment'
                       AND `items_id`='".$networkequipments_id."'
                       AND `instantiation_type`='NetworkPortEthernet'
                       AND `logical_number` = '".$logical_number."'", '', 1));
         if (!isset($a_networkports_DB['id'])) {
            // Add port
            $a_port['instantiation_type'] = 'NetworkPortEthernet';
            $a_port['items_id'] = $networkequipments_id;
            $a_port['itemtype'] = 'NetworkEquipment';
            $a_port['logical_number'] = $logical_number;
            $networkports_id = $networkPort->add($a_port);
         } else {
            $networkports_id = $a_networkports_DB['id'];
         }
         $a_ports_db_tmp[] = $networkports_id;
      }
      $input['networkports_id_list'] = $a_ports_db_tmp;
      $networkPortAggregate->update($input);
   }
}

?>
