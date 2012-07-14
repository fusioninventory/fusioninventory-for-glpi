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
   @co-author 
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

class PluginFusinvsnmpNetworkPort extends CommonDBTM {
   private $glpi_type = "NetworkEquipment"; // NetworkEquipment, Printer...
   private $portDB = array();
   private $portModif = array();
   private $plugin_fusinvsnmp_networkports_id = 0;
   private $cdp=false; // true if CDP=1
   private $portMacs=array();  // MAC addresses
   private $portIps=array();   // IP addresses
   private $portVlans=array(); // number and name for each vlan

   
   
   function __construct($p_type=NULL) {
      if ($p_type!=NULL) {
         $this->glpi_type = $p_type;
      }
   }



   /**
    * Load an optionnaly existing port
    *
    *@return nothing
    **/
   function loadNetworkport($networkports_id) {
      global $DB;

      $networkport = new NetworkPort();
      $networkport->getFromDB($networkports_id);
      $this->portDB = $networkport->fields;
      
      $a_fusports = $this->find("`networkports_id`='".$networkports_id."'", "", 1);
      if (count($a_fusports) > 0) {
         $a_fusport = current($a_fusports);
         foreach ($a_fusport as $key=>$value) {
            if ($key == 'id') {
               $this->plugin_fusinvsnmp_networkports_id = $value;
            } else {
               $this->portDB[$key] = $value;
            }
         }
      }
   }


  
    /**
    * Connect this port to another one in DB
    *
    *@param $destination_port id of destination port
    *@return nothing
    **/
   function connectDB($destination_port='') {
      global $DB;

      // Clean ports connected on themself
      $queryd = "DELETE FROM `glpi_networkports_networkports`
         WHERE `networkports_id_1` = `networkports_id_2`";
      $DB->query($queryd);
      
      $queryVerif = "SELECT *
                     FROM `glpi_networkports_networkports`
                     WHERE (`networkports_id_1` = '".$this->getValue('id')."' OR `networkports_id_1` = '".$destination_port."')
                           AND (`networkports_id_2` = '".$this->getValue('id')."' OR `networkports_id_2` = '".$destination_port."');";

      if ($resultVerif=$DB->query($queryVerif)) {
         if ($DB->numrows($resultVerif) == "0") { // no existing connection between those 2 ports
            $this->disconnectDB($this->getValue('id')); // disconnect this port
            $this->disconnectDB($destination_port);     // disconnect destination port
            $nn = new NetworkPort_NetworkPort();
            $nn->add(array('networkports_id_1'=> $this->getValue('id'),
                               'networkports_id_2' => $destination_port)); //connect those 2 ports

         }
      }
   }


   
   /**
    * Disconnect a port in DB
    *
    *@param $p_port Port id to disconnect
    *@return nothing
    **/
   function disconnectDB($p_port) {
      if ($p_port=='') {
         return;
      }
      $nn = new NetworkPort_NetworkPort();

      $contact_id = $nn->getOppositeContact($p_port);
      if ($contact_id AND $nn->getFromDBForNetworkPort($contact_id)) {
         $nn->delete($nn->fields,1);
      }
      if ($nn->getFromDBForNetworkPort($p_port)) {
         $nn->delete($nn->fields,1);
      }
   }


   
   /**
    * Add vlan
    *
    *@param $p_number Vlan number
    *@param $p_name Vlan name
    *@return nothing
    **/
   function addVlan($p_number, $p_name) {
      $this->portVlans[$p_number] = $p_name;
   }


   
   /**
    * Add MAC address
    *
    *@param $p_mac MAC address
    *@return nothing
    **/
   function addMac($p_mac) {
      if (is_array($p_mac)) {
         if (isset($p_mac['sysmac'])) {
            $this->portMacs[$p_mac['sysmac']] = $p_mac;
         } else {
            $this->portMacs[$p_mac['mac']] = $p_mac;
         }
      } else {
         $this->portMacs[$p_mac] = $p_mac;
      }
   }

   

   /**
    * Add IP address
    *
    *@param $p_ip array with IP address...
    *@return nothing
    **/
   function addIp($p_ip) {
      $this->portIps[]=$p_ip;
   }



   /**
    * Get MAC addresses to connect
    *
    *@return array of MAC addresses
    **/
   function getMacsToConnect() {
      return $this->portMacs;
   }

   

   /**
    *
    */
   function deleteMacToConnect($mac) {
      foreach ($this->portMacs as $num=>$macaddress) {
         if ($mac == $macaddress) {
            unset($this->portMacs[$num]);
         }
      }
    }



   /**
    * Set CDP
    *
    *@return nothing
    **/
   function setCDP() {
      $this->cdp=true;
   }



   static function getUniqueObjectfieldsByportID($id) {
      global $DB;

      $array = array();
      $query = "SELECT *
                FROM `glpi_networkports`
                WHERE `id`='".$id."';";
      if ($result=$DB->query($query)) {
         $data = $DB->fetch_array($result);
         $array["items_id"] = $data["items_id"];
         $array["itemtype"] = $data["itemtype"];
      }
      switch($array["itemtype"]) {
         case NETWORKING_TYPE:
            $query = "SELECT *
                      FROM `glpi_networkequipments`
                      WHERE `id`='".$array["itemtype"]."'
                      LIMIT 0,1;";
            if ($result=$DB->query($query)) {
               $data = $DB->fetch_array($result);
               $array["name"] = $data["name"];
            }
            break;
      }
      return($array);
   }
   
   
   
   function setValue($name, $value) {
      if (!(isset($this->portDB[$name])
              AND $this->portDB[$name] == $value)) {
         $this->portModif[$name] = $value;
      }
   }
   
   
   
   function getValue($name) {
      if (isset($this->portModif[$name])) {
         return $this->portModif[$name];
      } else if (isset($this->portDB[$name])) {
         return $this->portDB[$name];
      }
      return '';
   }
   
   
   
   function getNetworkPorts_id() {
      if (isset($this->portDB['id'])) {
         return $this->portDB['id'];
      } else if (isset($this->portModif['id'])) {
         return $this->portModif['id'];
      }
      return 0;
   }
   
   
   
   function savePort($itemtype, $items_id) {

      $networkPort = new NetworkPort();
      if (!isset($this->portDB['id'])
              OR $this->portDB['id'] < 1) {
         $this->portModif['itemtype'] = $itemtype;
         $this->portModif['items_id'] = $items_id;
         $newID = $networkPort->add($this->portModif);
         $this->portModif['id'] = $newID;
         $this->portDB['id'] = $newID;
      } else {
         $this->portModif['id'] = $this->portDB['id'];
         $networkPort->update($this->portModif);
      }
      // Update this table fusinvsnmpnetworkport
      $this->portModif['networkports_id'] = $this->portModif['id'];
      unset($this->portModif['id']);
      if ($this->plugin_fusinvsnmp_networkports_id == '0') {
         $this->add($this->portModif);
      } else {
         $this->portModif['id'] = $this->plugin_fusinvsnmp_networkports_id;
         $this->update($this->portModif);
      }
      
      // ** save VLAN
      $vlan = new Vlan();
      $vlanfound = array();
      foreach ($this->portVlans as $number=>$name) {
         $a_vlans = $vlan->find("`tag`='".$number."' AND `name`='".$name."'");
         if (count($a_vlans) > 0) {
            $a_vlan = current($a_vlans);
            $vlanfound[$a_vlan['id']] = $a_vlan['id'];
         } else {
            $input = array();
            $input['tag'] = $number;
            $input['name'] = $name;
            $newID = $vlan->add($input);
            $vlanfound[$newID] = $newID;
         }
      }
      $networkPort_Vlan = new NetworkPort_Vlan();
      $vlanDB = $networkPort_Vlan->getVlansForNetworkPort($this->portModif['networkports_id']);
      foreach ($vlanDB as $vlans_id) {
         if (!isset($vlanfound[$vlans_id])) {
            $networkPort_Vlan->unassignVlan($this->portModif['networkports_id'], $vlans_id);
            PluginFusinvsnmpNetworkPortLog::networkport_addLog($this->portModif['networkports_id'], '', 'vmvlan');
         }
      }
      foreach ($vlanfound as $vlans_id) {
         if (!isset($vlanDB[$vlans_id])) {
            $networkPort_Vlan->assignVlan($this->portModif['networkports_id'], $vlans_id);
            PluginFusinvsnmpNetworkPortLog::networkport_addLog($this->portModif['networkports_id'], $number." [".$name."]", 'vmvlan');
         }
      }
   }
   
   
   
   function connectPorts() {      
      $wire = new NetworkPort_NetworkPort();
      $networkPort = new NetworkPort();
      
      $networkports_id = $this->portModif['networkports_id'];
      $portID = 0;
      if ($this->cdp) { // DCP, get device
         $pfSNMP = new PluginFusinvsnmpSNMP();
         $a_cdp = current($this->portIps);
         if (isset($a_cdp['ip'])) {
            $param = array();
            $param['ifdescr']    = '';
            $param['sysdescr']   = '';
            $param['sysname']    = '';
            $param['model']      = '';
            foreach ($a_cdp as $key=>$value) {
               $param[$key] = $value;
            }
            $portID = $pfSNMP->getPortIDfromDeviceIP($a_cdp['ip'], 
                                                     $param['ifdescr'], 
                                                     $param['sysdescr'], 
                                                     $param['sysname'], 
                                                     $param['model']);
            

         } else {
            $a_cdp = current($this->portMacs);
            if (isset($a_cdp['sysmac'])) {
               $ifnumber = $a_cdp['ifnumber'];
               $portID = $pfSNMP->getPortIDfromSysmacandPortnumber($a_cdp['sysmac'], 
                                                                   $ifnumber,
                                                                   $a_cdp);     
            }
         }
         if ($portID
                 AND $portID > 0) {
            $contact_id = $wire->getOppositeContact($networkports_id);
            if (!($contact_id
                    AND $contact_id == $portID)) {
               $this->disconnectDB($networkports_id);
               $this->disconnectDB($portID);
               $wire->add(array('networkports_id_1'=> $networkports_id,
                               'networkports_id_2' => $portID));
            }   
         }         
      } else {
         $count = count($this->portMacs);
         if ($this->getValue('trunk') != '1') {
            if ($count == '2') {
               // detect if phone IP is one of the 2 devices
               $phonecase = 0;
               $macNotPhone_id = 0;
               $macNotPhone = '';
               $phonePort_id = 0;
               foreach ($this->portMacs as $ifmac) {
                  $a_ports = $networkPort->find("`mac`='".$ifmac."'","", 1);
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
                  $opposite_id = false;
                  if ($opposite_id == $wire->getOppositeContact($portLink_id)) {
                     if ($opposite_id != $macNotPhone_id) {
                        $this->disconnectDB($portLink_id); // disconnect this port
                        $this->disconnectDB($macNotPhone_id);     // disconnect destination port
                     }
                  }
                  if (!isset($macNotPhone_id)) {
                     // Create unknown ports
                     $PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
                     $unknown_infos = array();
                     $unknown_infos["name"] = '';
                     if (isset($_SESSION["plugin_fusinvinventory_entity"])) {
                        $input['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
                     }
                     $newID=$PluginFusioninventoryUnknownDevice->add($unknown_infos);
                     // Add networking_port
                     $port_add = array();
                     $port_add["items_id"] = $newID;
                     $port_add["itemtype"] = 'PluginFusioninventoryUnknownDevice';
                     $port_add['mac'] = $macNotPhone;
                     $macNotPhone_id = $networkPort->add($port_add);
                  }
                  $wire->add(array('networkports_id_1'=> $portLink_id,
                                   'networkports_id_2' => $macNotPhone_id));
               } else {
                  $pfiud = new PluginFusioninventoryUnknownDevice;
                  $pfiud->hubNetwork($this);
               }
            } else if ($count > 1) { // MultipleMac
               $pfiud = new PluginFusioninventoryUnknownDevice;
               $pfiud->hubNetwork($this);
            } else { // One mac on port
               foreach ($this->portMacs as $ifmac) { //Only 1 time
                  $a_ports = $networkPort->find("`mac`='".$ifmac."'","", 1);
                  if (count($a_ports) > 0) {
                     $a_port = current($a_ports);
                     $hub = 0;
                     $id = $networkPort->getContact($a_port['id']);
                     $pfUnknownDevice = new PluginFusioninventoryUnknownDevice();
                     if ($id AND $networkPort->getFromDB($id)) {
                        if ($networkPort->fields['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
                           $pfUnknownDevice->getFromDB($networkPort->fields['items_id']);
                           if ($pfUnknownDevice->fields['hub'] == '1') {
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
                           if ($networkPort->fields['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
                              // 1. Hub connected to this switch port
                              $pfUnknownDevice->connectPortToHub(array($a_port), $networkPort->fields['items_id']);
                           } else {
                              // 2. direct connection
                              $directconnect = 1;                              
                           }                        
                        }
                        if ($directconnect == '1') {
                           $this->disconnectDB($networkports_id); // disconnect this port
                           $this->disconnectDB($a_port['id']);     // disconnect destination port
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
                              // 1.The hub where this device is connected is yet connected to this switch port
                           
                              // => Do nothing
                           } else {
                              // 2. The hub where this device is connected to is not connected to this switch port
                              if ($ddirect['itemtype'] == 'PluginFusioninventoryUnknownDevice') {
                                 // b. We have a hub connected to the switch port
                                 $pfUnknownDevice->connectPortToHub(array($a_port), $ddirect['items_id']);
                              } else {
                                 // a. We have a direct connexion to another device (on the switch port)
                                 $directconnect = 1;                              
                              }
                           }
                        }
                        if ($directconnect == '1') {
                           $this->disconnectDB($networkports_id); // disconnect this port
                           $this->disconnectDB($a_port['id']);     // disconnect destination port
                           $wire->add(array('networkports_id_1'=> $networkports_id,
                                            'networkports_id_2' => $a_port['id']));
                        }                        
                     } else if ($id) {
                        // Yet connected                        
                     } else {
                        // Not connected
                        $this->disconnectDB($networkports_id); // disconnect this port
                        $wire->add(array('networkports_id_1'=> $networkports_id,
                                         'networkports_id_2' => $a_port['id']));
                     }
                  } else {
                     // Create unknown device
                     $pluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
                     $input = array();
                     $input['name'] = '';
                     if (isset($_SESSION["plugin_fusinvinventory_entity"])) {
                        $input['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
                     }
                     $newID = $pluginFusioninventoryUnknownDevice->add($input);
                     $input['itemtype'] = "PluginFusioninventoryUnknownDevice";
                     $input['items_id'] = $newID;
                     $input['mac'] = $ifmac;
                     $newPortID = $networkPort->add($input);
                     $this->disconnectDB($networkports_id); // disconnect this port
                     $wire->add(array('networkports_id_1'=> $networkports_id,
                                      'networkports_id_2' => $newPortID));
                  }
               }
            }
         }
      }
   }
   


   /**
    * Get index of port object
    *
    *@param $p_mac MAC address
    *@param $p_ip='' IP address
    *@return Index of port object in ports array or '' if not found
    **/
   function getPortIdWithLogicialNumber($p_ifnumber, $items_id) {
      
      $networkPort= new NetworkPort();
      $a_ports = $networkPort->find("`logical_number`='".$p_ifnumber."'
         AND `itemtype`='NetworkEquipment'
         AND`items_id`='".$items_id."'", "", 1);
      
      if (count($a_ports) > 0) {
         $a_port = current($a_ports);
         return $a_port['id'];
      }
      return false;
   }
   
}

?>