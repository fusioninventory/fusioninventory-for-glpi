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
class PluginFusioninventoryInventoryNetworkEquipmentLib extends PluginFusioninventoryInventoryCommon {

   public $data_device = [];
   public $found_ports = [];


   /**
    * Function to update NetworkEquipment
    *
    * @global object $DB
    * @param array $a_inventory data fron agent inventory
    * @param integer $items_id id of the networkequipment
    * @param boolean $no_history notice if changes must be logged or not
    */
   function updateNetworkEquipment($a_inventory, $items_id, $no_history = false) {
      global $DB;

      $networkEquipment   = new NetworkEquipment();
      $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();

      $networkEquipment->getFromDB($items_id);

      if (!isset($_SESSION['glpiactiveentities_string'])) {
         $_SESSION['glpiactiveentities_string'] = "'" . $networkEquipment->fields['entities_id'] . "'";
      }
      if (!isset($_SESSION['glpiactiveentities'])) {
         $_SESSION['glpiactiveentities'] = [$networkEquipment->fields['entities_id']];
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

      $input['id']       = $items_id;
      $input['itemtype'] = 'NetworkEquipment';

      //Add defaut status if there's one defined in the configuration
      //If we're here it's because we've manually injected an snmpinventory xml file
      $input = PluginFusioninventoryToolbox::addDefaultStateIfNeeded('snmp', $input);

      //Add ips to the rule criteria array
      $input['ip'] = $a_inventory['internalport'];

      //Add the location if needed (play rule locations engine)
      $input = PluginFusioninventoryToolbox::addLocation($input);

      // Manage inventory number
      if ($networkEquipment->fields['otherserial'] == ''
         && (!isset($input['otherserial'])
            || $input['otherserial'] == '')) {

         $input['otherserial'] = PluginFusioninventoryToolbox::setInventoryNumber(
            'NetworkEquipment', '', $networkEquipment->fields['entities_id']);
      }

      $networkEquipment->update($input, !$no_history);

      $this->internalPorts($a_inventory['internalport'],
                           $items_id,
                           $mac,
                           'Internal');

      // * NetworkEquipment fusion (ext)
      $db_networkequipment = [];

      $params = [
         'FROM'  => getTableForItemType("PluginFusioninventoryNetworkEquipment"),
         'WHERE' => ['networkequipments_id' => $items_id]
      ];
      $iterator = $DB->request($params);
      while ($data = $iterator->next()) {
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
         $input                = $a_inventory['PluginFusioninventoryNetworkEquipment'];
         $input['id']          = $idtmp;
         $pfNetworkEquipment->update($input);
      }

      // * Ports
      $this->importPorts('NetworkEquipment', $a_inventory, $items_id, $no_history);

      //Import firmwares
      $this->importFirmwares('NetworkEquipment', $a_inventory, $items_id, $no_history);

      //Import simcards
      $this->importSimcards('NetworkEquipment', $a_inventory, $items_id, $no_history);

      Plugin::doHook("fusioninventory_inventory",
      ['inventory_data' => $a_inventory,
       'networkequipments_id'   => $items_id,
       'no_history'     => $no_history
      ]);
   }


   /**
    * Import internal ports (so internal IP, management IP)
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
                    ['itemtype'           => 'NetworkEquipment',
                     'items_id'           => $networkequipments_id,
                     'instantiation_type' => 'NetworkPortAggregate',
                     'logical_number'     => 0],
                    [], 1));
      $a_ips_DB = [];
      if (isset($a_networkPortAggregates['id'])) {
         $a_networkPortAggregates['mac'] = $mac;
         $networkPort->update($a_networkPortAggregates);

         $networkports_id = $a_networkPortAggregates['id'];
      } else {
         $input = [];
         $input['itemtype'] = 'NetworkEquipment';
         $input['items_id'] = $networkequipments_id;
         $input['instantiation_type'] = 'NetworkPortAggregate';
         $input['name'] = 'general';
         $input['mac'] = $mac;
         $networkports_id = $networkPort->add($input);
      }
      // Get networkname
      $a_networknames_find = current($networkName->find(
            ['items_id' => $networkports_id,
             'itemtype' => 'NetworkPort'],
            [], 1));
      if (isset($a_networknames_find['id'])) {
         $networknames_id = $a_networknames_find['id'];
         $a_networknames_find['name'] = $networkname_name;
         $networkName->update($a_networknames_find);
      } else {
         $input = [];
         $input['items_id'] = $networkports_id;
         $input['itemtype'] = 'NetworkPort';
         $input['name']     = $networkname_name;
         $networknames_id   = $networkName->add($input);
      }
      $a_ips_fromDB = $iPAddress->find(
            ['itemtype' => 'NetworkName',
             'items_id' => $networknames_id]);
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
      if (count($a_ips) || count($a_ips_DB)) {
         if (count($a_ips_DB) != 0 && count($a_ips) != 0) {
            // Delete IPs in DB
            foreach ($a_ips_DB as $idtmp => $ip) {
               $iPAddress->delete(['id'=>$idtmp]);
            }
         }
         if (count($a_ips) != 0) {
            foreach ($a_ips as $ip) {
               if ($ip != '127.0.0.1') {
                  $input = [];
                  $input['entities_id'] = 0;
                  $input['itemtype'] = 'NetworkName';
                  $input['items_id'] = $networknames_id;
                  $input['name'] = $ip;
                  $iPAddress->add($input);

                  // Search in unmanaged device if device with IP (LLDP) is yet added, in this case,
                  // we get id of this unmanaged device
                  $a_unmanageds = $pfUnmanaged->find(['ip' => $ip], [], 1);
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
   function importPorts($itemtype, $a_inventory, $items_id, $no_history = false) {
      //TODO : try to report this code in PluginFusioninventoryInventoryCommon::importPorts
      $pfNetworkporttype = new PluginFusioninventoryNetworkporttype();
      $networkPort       = new NetworkPort();
      $pfNetworkPort     = new PluginFusioninventoryNetworkPort();
      $networkports_id   = 0;
      $pfArrayPortInfos  = [];
      foreach ($a_inventory['networkport'] as $a_port) {

         $ifType = $a_port['iftype'];
         if ($pfNetworkporttype->isImportType($ifType)
                 || isset($a_inventory['aggregate'][$a_port['logical_number']])
                 || $ifType == '') {
            $a_ports_DB = current($networkPort->find(
                  ['itemtype'       => 'NetworkEquipment',
                   'items_id'       => $items_id,
                   'logical_number' => $a_port['logical_number']],
                  [], 1));
            if (!isset($a_ports_DB['id'])) {
               // Add port because not exists
               if (isset($a_inventory['aggregate'])
                       && isset($a_inventory['aggregate'][$a_port['logical_number']])) {
                  $a_port['instantiation_type'] = 'NetworkPortAggregate';
               } else {
                  $a_port['instantiation_type'] = 'NetworkPortEthernet';
               }
               $a_port['items_id'] = $items_id;
               $a_port['itemtype'] = 'NetworkEquipment';
               $networkports_id = $networkPort->add($a_port, [], $no_history);
               $a_pfnetworkport_DB = current($pfNetworkPort->find(
                       ['networkports_id' => $networkports_id], [], 1));
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
                       ['networkports_id' => $networkports_id], [], 1));
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
                  ['itemtype'       => 'NetworkEquipment',
                   'items_id'       => $items_id,
                   'logical_number' => $a_port['logical_number']],
                  [], 1);
            if (count($a_ports_DB) > 0) {
               $networkPort->delete(current($a_ports_DB));
            }
         }
      }

   }


   /**
    * Import LLDP connexions
    *
    * List of fields we have :
    *   - ifdescr
    *   - logical_number
    *   - sysdescr
    *   - model
    *   - ip
    *   - mac
    *   - name
    *
    * @param array $a_lldp
    * @param integer $networkports_id
    */
   function importConnectionLLDP($a_lldp, $networkports_id) {

      $this->found_ports = [];
      $pfNetworkPort = new PluginFusioninventoryNetworkPort();
      $this->data_device = $a_lldp;
      // Prepare data to import rule
      $input_crit = [];
      if (!empty($a_lldp['ifdescr'])) {
         $input_crit['ifdescr'] = $a_lldp['ifdescr'];
      }
      if (!empty($a_lldp['logical_number'])) {
         $input_crit['ifnumber'] = $a_lldp['logical_number'];
      }
      /* not coded in rules
      if (!empty($a_lldp['sysdescr'])) {
         $input_crit['sysdescr'] = $a_lldp['sysdescr'];
      }*/
      if (!empty($a_lldp['mac'])) {
         $input_crit['mac'] = [$a_lldp['mac']];
      }
      if (!empty($a_lldp['name'])) {
         $input_crit['name'] = $a_lldp['name'];
      }
      if (!empty($a_lldp['model'])) {
         $input_crit['model'] = $a_lldp['model'];
      }
      if (!empty($a_lldp['ip'])) {
         $input_crit['ip'] = [$a_lldp['ip']];
      }

      // Entity?
      $rule = new PluginFusioninventoryInventoryRuleImportCollection();
      $_SESSION['plugin_fusinvsnmp_datacriteria'] = serialize($input_crit);

      // * Reload rules (required for unit tests)
      $rule->getCollectionPart();
      $data = $rule->processAllRules($input_crit, [], ['class'=>$this]);
      PluginFusioninventoryToolbox::logIfExtradebug("pluginFusioninventory-rules",
                                                   $data);
      $rule->getFromDB($data['_ruleid']);

      if (count($this->found_ports)) {
         $port_id = current(current($this->found_ports));

         // We connect the 2 ports
         $wire = new NetworkPort_NetworkPort();
         $contact_id = $wire->getOppositeContact($networkports_id);
         if (!($contact_id
                 AND $contact_id == $port_id)) {
            $pfNetworkPort->disconnectDB($networkports_id);
            $pfNetworkPort->disconnectDB($port_id);

            $wire->add(['networkports_id_1'=> $networkports_id,
                             'networkports_id_2' => $port_id]);
         }
      }
   }


   /**
    * Import connection with MAC address
    *
    * @param array $a_portconnection
    * @param integer $networkports_id
    */
   function importConnectionMac($a_portconnection, $networkports_id) {

      $this->found_ports = [];
      $wire = new NetworkPort_NetworkPort();
      $networkPort = new NetworkPort();
      $pfNetworkPort = new PluginFusioninventoryNetworkPort();
      $pfUnmanaged = new PluginFusioninventoryUnmanaged();
      $rule = new PluginFusioninventoryInventoryRuleImportCollection();

      // Pass all MAC addresses in the import rules
      foreach ($a_portconnection as $ifmac) {
         $this->data_device = ['mac' => $ifmac];
         $_SESSION['plugin_fusinvsnmp_datacriteria'] = serialize(['mac' => $ifmac]);
         $data = $rule->processAllRules(['mac' => $ifmac], [], ['class'=>$this]);
      }
      $list_all_ports_found = [];
      foreach ($this->found_ports as $itemtype => $ids) {
         foreach ($ids as $items_id) {
            $list_all_ports_found[] = $items_id;
         }
      }

      if (count($list_all_ports_found) == 0) {
         return;
      }

      $pfNetworkPort->loadNetworkport($networkports_id);
      if ($pfNetworkPort->getValue('trunk') == '1'
            && count($list_all_ports_found) > 1) {
         return;
      }

      // Try detect phone + computer on this port
      if (count($list_all_ports_found) == 2) {
         foreach ($this->found_ports as $itemtype => $ids) {
            $phonecase = 0;
            $macNotPhone_id = 0;
            $phonePort_id = 0;
            if ($itemtype == "phone") {
               // Connect phone on switch port and other (computer..) in this phone
               foreach ($ids as $items_id) {
                  $phonePort_id = current($items_id);
                  $phonecase++;
               }
            } else {
               foreach ($ids as $items_id) {
                  $macNotPhone_id = $items_id;
               }
            }
         }
         if ($phonecase == 1) {
            $wire->add(['networkports_id_1'=> $networkports_id,
                             'networkports_id_2' => $phonePort_id]);
            $networkPort->getFromDB($phonePort_id);
            $portLink_id = 0;
            if ($networkPort->fields['name'] == 'Link') {
               $portLink_id = $networkPort->fields['id'];
            } else {
               // Perhaps the phone as another port named 'Link'
               $Phone = new Phone();
               $Phone->getFromDB($networkPort->fields['items_id']);
               $a_portsPhone = $networkPort->find(
                     ['items_id' => $networkPort->fields['items_id'],
                      'itemtype' => 'Phone',
                      'name'     => 'Link'],
                     [], 1);
               $portLink_id = 0;
               if (count($a_portsPhone) == '1') {
                  $a_portPhone = current($a_portsPhone);
                  $portLink_id = $a_portPhone['id'];
               } else {
                  // Create Port Link
                  $input = [];
                  $input['name'] = 'Link';
                  $input['itemtype'] = 'Phone';
                  $input['items_id'] = $Phone->fields['id'];
                  $input['entities_id'] = $Phone->fields['entities_id'];
                  $portLink_id = $networkPort->add($input);
               }
            }
            $opposite_id = false;
            if ($opposite_id == $wire->getOppositeContact($portLink_id)) {
               if ($opposite_id != $macNotPhone_id) {
                  $pfNetworkPort->disconnectDB($portLink_id); // disconnect this port
                  $pfNetworkPort->disconnectDB($macNotPhone_id); // disconnect destination port
               }
            }
            $wire->add([
               'networkports_id_1'=> $portLink_id,
               'networkports_id_2' => $macNotPhone_id
            ]);
            return;
         }
      }
      if (count($list_all_ports_found) > 1) { // MultipleMac
         // If we have minimum 1 device 'NetworkEquipment', we not manage these MAC addresses
         if (isset($this->found_ports['NetworkEquipment'])) {
            return;
         }
         // TODO update this function to pass the ports id found
         $pfUnmanaged->hubNetwork($pfNetworkPort, $list_all_ports_found);
      } else { // One mac on port
         $networkPort->getFromDB(current($list_all_ports_found));
         $id = $wire->getOppositeContact($networkPort->fields['id']);
         if ($id && $id == $networkports_id) {
            // yet connected
            return;
         }
         $pfNetworkPort->disconnectDB($networkports_id); // disconnect this port
         $pfNetworkPort->disconnectDB($networkPort->fields['id']); // disconnect destination port

         $wire->add([
            'networkports_id_1'=> $networkports_id,
            'networkports_id_2' => $networkPort->fields['id']
         ]);
         return;
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

      $db_vlans = [];
      $query = "SELECT `glpi_networkports_vlans`.`id`, `glpi_vlans`.`name`, `glpi_vlans`.`tag`, `glpi_networkports_vlans`.`tagged`
                FROM `glpi_networkports_vlans`
                LEFT JOIN `glpi_vlans`
                  ON `vlans_id`=`glpi_vlans`.`id`
                WHERE `networkports_id` = '$networkports_id'";
      foreach ($DB->request($query) as $data) {
         $db_vlans[$data['id']] = $data;
      }

      if (count($db_vlans) == 0) {
         foreach ($a_vlans as $a_vlan) {
            $this->addVlan($a_vlan, $networkports_id);
         }
      } else {
         foreach ($a_vlans as $key => $arrays) {
            foreach ($db_vlans as $keydb => $arraydb) {
               if ($arrays['name'] == $arraydb['name'] && $arrays['tag'] == $arraydb['tag'] && $arrays['tagged'] == $arraydb['tagged']) {
                  unset($a_vlans[$key]);
                  unset($db_vlans[$keydb]);
                  break;
               }
            }
         }

         if (count($a_vlans) || count($db_vlans)) {
            if (count($db_vlans) != 0) {
               // Delete vlan in DB
               foreach (array_keys($db_vlans) as $id) {
                  $networkPort_Vlan->delete(['id'=>$id]);
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

      $db_vlans = $vlan->find(['tag' => $a_vlan['tag'], 'name' => $a_vlan['name']], [], 1);
      $vlans_id = 0;
      if (count($db_vlans) > 0) {
         $db_vlan = current($db_vlans);
         $vlans_id = $db_vlan['id'];
      } else {
         $input = [];
         $input['tag'] = $a_vlan['tag'];
         $input['name'] = $a_vlan['name'];
         $vlans_id = $vlan->add($input);
      }

      $input = [];
      $input['networkports_id'] = $networkports_id;
      $input['vlans_id'] = $vlans_id;
      $input['tagged'] = $a_vlan['tagged'];
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

      $a_aggregates = $networkPortAggregate->find(['networkports_id' => $networkports_id], [], 1);

      $input = [];
      if (count($a_aggregates) == 1) {
         $input = current($a_aggregates);
      } else {
         $input['networkports_id'] = $networkports_id;
         $input['networkports_id_list'] = exportArrayToDB([]);
         $input['id'] = $networkPortAggregate->add($input);
      }
      $a_ports_db_tmp = [];
      foreach ($a_ports as $logical_number) {
         $a_networkports_DB = current($networkPort->find(
               ['itemtype'           => 'NetworkEquipment',
                'items_id'           => $networkequipments_id,
                'instantiation_type' => 'NetworkPortEthernet',
                'logical_number'     => $logical_number],
               [], 1));
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


   /**
    * After rule engine passed, update task (log) and create item if required
    *
    * @param integer $items_id id of the item (0 = not exist in database)
    * @param string $itemtype
    */
   function rulepassed($items_id, $itemtype, $ports_id = 0) {
      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-rules",
         "Rule passed : ".$items_id.", ".$itemtype."\n"
      );
      $NetworkPort = new NetworkPort();
      if ($itemtype == "") {
         $itemtype = "PluginFusioninventoryUnmanaged";
      }
      $class = new $itemtype;
      $dbu   = new DbUtils();
      if ($items_id == "0") {
         // create the device
         $input = $this->data_device;
         if (!isset($input['name'])
               && isset($input['mac'])) {
            $manufacturer = PluginFusioninventoryInventoryExternalDB::getManufacturerWithMAC($input['mac']);
            $manufacturer = Toolbox::addslashes_deep($manufacturer);
            $manufacturer = Toolbox::clean_cross_side_scripting_deep($manufacturer);
            $input['name'] = $manufacturer;
         }
         $items_id = $class->add($input);

         if (isset($_SESSION['plugin_fusioninventory_rules_id'])) {
            $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
            $inputrulelog = [];
            $inputrulelog['date'] = date('Y-m-d H:i:s');
            $inputrulelog['rules_id'] = $_SESSION['plugin_fusioninventory_rules_id'];
            if (isset($_SESSION['plugin_fusioninventory_agents_id'])) {
               $inputrulelog['plugin_fusioninventory_agents_id'] = $_SESSION['plugin_fusioninventory_agents_id'];
            }
            $inputrulelog['items_id'] = $items_id;
            $inputrulelog['itemtype'] = $itemtype;
            $inputrulelog['method'] = 'networkinventory';
            $inputrulelog['criteria'] = $dbu->exportArrayToDB(Toolbox::addslashes_deep(unserialize($_SESSION['plugin_fusinvsnmp_datacriteria'])));
            $pfRulematchedlog->add($inputrulelog);
            $pfRulematchedlog->cleanOlddata($items_id, $itemtype);
            unset($_SESSION['plugin_fusioninventory_rules_id']);
         }

         // Create the network port
         $input = [
            'items_id' => $items_id,
            'itemtype' => $itemtype
         ];
         if (isset($this->data_device['ip'])) {
            $input['_create_children'] = 1;
            $input['NetworkName_name'] = '';
            $input['NetworkName_fqdns_id'] = 0;
            $input['NetworkName__ipaddresses'] = [
               '-1' => $this->data_device['ip']
            ];
         }
         if (isset($this->data_device['ifdescr'])
               && !empty($this->data_device['ifdescr'])) {
            $input['name'] = $this->data_device['ifdescr'];
         }
         if (!isset($input['name'])
               && isset($this->data_device['mac'])) {
            $manufacturer = PluginFusioninventoryInventoryExternalDB::getManufacturerWithMAC($this->data_device['mac']);
            $manufacturer = Toolbox::addslashes_deep($manufacturer);
            $manufacturer = Toolbox::clean_cross_side_scripting_deep($manufacturer);
            $input['name'] = $manufacturer;
         }
         if (isset($this->data_device['mac'])
               && !empty($this->data_device['mac'])) {
            $input['mac'] = $this->data_device['mac'];
         }
         if (count($input) > 2) {
            // so have network elements
            $input['instantiation_type'] = 'NetworkPortEthernet';
            $portID = $NetworkPort->add($input);
            if (!isset($this->found_ports[$itemtype])) {
               $this->found_ports[$itemtype] = [];
            }
            $this->found_ports[$itemtype][$portID] = $portID;
         }
      } else {
         if ($ports_id > 0) {
            if (!isset($this->found_ports[$itemtype])) {
               $this->found_ports[$itemtype] = [];
            }
            $this->found_ports[$itemtype][$ports_id] = $ports_id;
         } else {
            // Add port
            $input = [];
            $input['items_id'] = $items_id;
            $input['itemtype'] = $itemtype;
            if (isset($this->data_device['ifdescr'])
                  && !empty($this->data_device['ifdescr'])) {
               $input['name'] = $this->data_device['ifdescr'];
            }
            if (isset($this->data_device['mac'])
                  && !empty($this->data_device['mac'])) {
               $input['mac'] = $this->data_device['mac'];
            }
            $input['instantiation_type'] = 'NetworkPortEthernet';
            if (isset($this->data_device['ip'])
                  && !empty($this->data_device['ip'])) {
               $input['_create_children'] = 1;
               $input['NetworkName_name'] = '';
               $input['NetworkName_fqdns_id'] = 0;
               $input['NetworkName__ipaddresses'] = [
                  '-1' => $this->data_device['ip']
               ];
            }
            $ports_id = $NetworkPort->add($input);
            if (!isset($this->found_ports['PluginFusioninventoryUnmanaged'])) {
               $this->found_ports['PluginFusioninventoryUnmanaged'] = [];
            }
            $this->found_ports['PluginFusioninventoryUnmanaged'][$ports_id] = $ports_id;
         }
      }
   }

   /**
    * Get method name linked to this class
    *
    * @return string
    */
   static function getMethod() {
      return PluginFusioninventoryCommunicationNetworkInventory::getMethod();
   }

}
