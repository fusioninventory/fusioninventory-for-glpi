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
   @co-author
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

class PluginFusioninventoryNetworkPort extends CommonDBTM {
   private $portDB = array();
   private $portModif = array();
   private $plugin_fusinvsnmp_networkports_id = 0;
   private $cdp=FALSE; // TRUE if CDP=1
   private $portMacs=array();  // MAC addresses
   private $portIps=array();   // IP addresses
   private $portVlans=array(); // number and name for each vlan


   
   function getSearchOptions() {

      $tab                     = array();
      $tab['common']           = __('Characteristics');

      $tab[1]['table']         = 'glpi_networkports';
      $tab[1]['field']         = 'name';
      $tab[1]['name']          = __('Name');
      $tab[1]['type']          = 'text';
      $tab[1]['massiveaction'] = FALSE;

//      $tab[2]['table']         = $this->getTable();
//      $tab[2]['field']         = 'id';
//      $tab[2]['name']          = __('ID');
//      $tab[2]['massiveaction'] = FALSE;
//      $tab[2]['datatype']      = 'number';

      $tab[3]['table']         = $this->getTable();
      $tab[3]['field']         = 'ifmtu';
      $tab[3]['name']          = __('MTU', 'fusioninventory');

      $tab[5]['table']         = $this->getTable();
      $tab[5]['field']         = 'ifspeed ';
      $tab[5]['name']          = __('Speed');

      $tab[6]['table']         = $this->getTable();
      $tab[6]['field']         = 'ifinternalstatus';
      $tab[6]['name']          = __('Internal status', 'fusioninventory');

      $tab[7]['table']         = $this->getTable();
      $tab[7]['field']         = 'iflastchange';
      $tab[7]['name']          = __('Last change', 'fusioninventory');

      $tab[8]['table']  = $this->getTable();
      $tab[8]['field']  = 'ifinoctets';
      $tab[8]['name']   = __('Number of bytes received / Number of bytes sent', 'fusioninventory');

      $tab[9]['table']         = $this->getTable();
      $tab[9]['field']         = 'ifinerrors';
      $tab[9]['name']          = __('Number of input errors / Number of errors in reception', 'fusioninventory');

      $tab[10]['table']         = $this->getTable();
      $tab[10]['field']         = 'portduplex';
      $tab[10]['name']          = __('Duplex', 'fusioninventory');

      $tab[11]['table']         = $this->getTable();
      $tab[11]['field']         = 'mac';
      $tab[11]['name']          = __('Internal MAC address', 'fusioninventory');

      $tab[12]['table']         = $this->getTable();
      $tab[12]['field']         = 'vlan';
      $tab[12]['name']          = __('VLAN');

      $tab[13]['table']         = $this->getTable();
      $tab[13]['field']         = 'connectedto';
      $tab[13]['name']          = __('Connected to');

      $tab[14]['table']         = $this->getTable();
      $tab[14]['field']         = 'ifconnectionstatus';
      $tab[14]['name']          = __('Connection');
     
      return $tab;
   }
   

   
   /**
    * Load an optionnaly existing port
    *
    *@return nothing
    **/
   function loadNetworkport($networkports_id) {

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
                     WHERE (`networkports_id_1` = '".$this->getValue('id')."' 
                              OR `networkports_id_1` = '".$destination_port."')
                           AND (`networkports_id_2` = '".$this->getValue('id')."' 
                              OR `networkports_id_2` = '".$destination_port."');";

      if (($resultVerif = $DB->query($queryVerif))) {
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
         $nn->delete($nn->fields, 1);
      }
      if ($nn->getFromDBForNetworkPort($p_port)) {
         $nn->delete($nn->fields, 1);
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
      $this->cdp=TRUE;
   }



   static function getUniqueObjectfieldsByportID($id) {
      global $DB;

      $array = array();
      $query = "SELECT *
                FROM `glpi_networkports`
                WHERE `id`='".$id."';";
      if (($result = $DB->query($query))) {
         $data = $DB->fetch_array($result);
         $array["items_id"] = $data["items_id"];
         $array["itemtype"] = $data["itemtype"];
      }
      switch($array["itemtype"]) {
         case NETWORKING_TYPE:
            $query = "SELECT *
                      FROM `glpi_networkequipments`
                      WHERE `id`='".$array["itemtype"]."'
                      LIMIT 0, 1;";
            if (($result = $DB->query($query))) {
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
      return FALSE;
   }

   
   
   function getPortIDfromDeviceIP($IP, $ifDescr, $sysdescr, $sysname, $model) {
      global $DB;

      $pfUnknownDevice = new PluginFusioninventoryUnknownDevice();

      $NetworkPort = new NetworkPort();

      $PortID = "";
      $query = "SELECT *
                FROM `glpi_plugin_fusioninventory_networkequipmentips`
                WHERE `ip`='".$IP."';";
      $result = $DB->query($query);
      if ($DB->numrows($result) == "1") {
         $data = $DB->fetch_assoc($result);

         $queryPort = "SELECT *
                       FROM `glpi_plugin_fusioninventory_networkports`
                       LEFT JOIN `glpi_networkports`
                          ON `glpi_plugin_fusioninventory_networkports`.`networkports_id`=
                             `glpi_networkports`.`id`
                       WHERE (`ifdescr`='".$ifDescr."'
                                OR `glpi_networkports`.`name`='".$ifDescr."')
                             AND `glpi_networkports`.`items_id`='".$data["networkequipments_id"]."'
                             AND `glpi_networkports`.`itemtype`='NetworkEquipment'";
         $resultPort = $DB->query($queryPort);
         $dataPort = $DB->fetch_assoc($resultPort);
         if ($DB->numrows($resultPort) == "0") {
            // Search in other devices
            $queryPort = "SELECT *
                          FROM `glpi_networkports`
                          WHERE `ip`='".$IP."'
                          ORDER BY `itemtype`
                          LIMIT 0, 1";
            $resultPort = $DB->query($queryPort);
            $dataPort = $DB->fetch_assoc($resultPort);
            if (isset($dataPort['id'])) {
               $PortID = $dataPort["id"];
            }
         } else {
            $PortID = $dataPort['networkports_id'];
         }
      }

      // Detect IP Phone
      if ($PortID == "") {
         if (strstr($model, "Phone")
               || $model == '') {
            $queryPort = "SELECT glpi_networkports.*
                           FROM `glpi_phones`
                              LEFT JOIN `glpi_networkports`
                                 ON `glpi_phones`.`id`=`glpi_networkports`.`items_id`
                          WHERE `ip`='".$IP."'
                                AND `glpi_networkports`.`itemtype`='Phone'
                                AND `glpi_phones`.`name`='".$sysname."'
                          LIMIT 1";
            $resultPort = $DB->query($queryPort);
            if ($DB->numrows($resultPort) == 1) {
               $dataPort = $DB->fetch_assoc($resultPort);
               if (isset($dataPort['id'])) {
                  $PortID = $dataPort["id"];
               }
            }
         }
      }

      if ($PortID == "") {
         $query = "SELECT * FROM `glpi_plugin_fusioninventory_unknowndevices`
            WHERE `ip`='".$IP."'
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == "1") {
            $data = $DB->fetch_assoc($result);
            // Search port and add if required
            $query1 = "SELECT *
                FROM `glpi_networkports`
                WHERE `itemtype`='PluginFusioninventoryUnknownDevice'
                   AND `items_id`='".$data['id']."'
                   AND `name`='".$ifDescr."'
                LIMIT 1";
            $result1 = $DB->query($query1);
            if ($DB->numrows($result1) == "1") {
               $data1 = $DB->fetch_assoc($result1);
               $PortID = $data1['id'];
            } else {
               // Add port
               $input = array();
               $input['items_id'] = $data['id'];
               $input['itemtype'] = 'PluginFusioninventoryUnknownDevice';
               $input['ip'] = $IP;
               $input['name'] = $ifDescr;
               $PortID = $NetworkPort->add($input);
            }
            // Update unknown device
            $input = array();
            $input['id'] = $data['id'];
            $input['ip'] = $IP;
            if (strstr($model, "Phone")) {
               $input['item_type'] = 'Phone';
            }
            if ($sysname != '') {
               $input['name'] = $sysname;
            }
            if ($sysdescr != '') {
               $input['sysdescr'] = $sysdescr;
            }
            $pfUnknownDevice->update($input);
            return $PortID;
         }

         $query = "SELECT `glpi_networkports`.* FROM `glpi_networkports`
             LEFT JOIN `glpi_networknames`
                 ON `glpi_networknames`.`items_id`=`glpi_networkports`.`id`
                    AND `glpi_networknames`.`itemtype`='NetworkPort'
            LEFT JOIN `glpi_ipaddresses`
                 ON `glpi_ipaddresses`.`items_id`=`glpi_networknames`.`id`
                    AND `glpi_ipaddresses`.`itemtype`='NetworkName'

             WHERE `glpi_networkports`.`itemtype`='PluginFusioninventoryUnknownDevice'
               AND `glpi_ipaddresses`.`name`='".$IP."'
             LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == "1") {
            $data = $DB->fetch_assoc($result);
            if ($pfUnknownDevice->convertUnknownToUnknownNetwork($data['items_id'])) {
               // Add port
               $input = array();
               $input['items_id'] = $data['items_id'];
               $input['itemtype'] = 'PluginFusioninventoryUnknownDevice';
               $input['ip'] = $IP;
               $input['name'] = $ifDescr;
               $PortID = $NetworkPort->add($input);
               // Update unknown device
               $input = array();
               $input['id'] = $data['id'];
               $input['ip'] = $IP;
               if (strstr($model, "Phone")) {
                  $input['item_type'] = 'Phone';
               }
               if ($sysname != '') {
                  $input['name'] = $sysname;
               }
               if ($sysdescr != '') {
                  $input['sysdescr'] = $sysdescr;
               }
               $pfUnknownDevice->update($input);
               return $PortID;
            }
         }
         // Add unknown device
         $input = array();
         $input['ip'] = $IP;
         if (strstr($model, "Phone")) {
            $input['item_type'] = 'Phone';
         }
         if ($sysname != '') {
            $input['name'] = $sysname;
         }
         if ($model != '') {
            $input['comment'] = $model;
         }
         if (isset($_SESSION["plugin_fusinvinventory_entity"])) {
            $input['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
         }
         if ($sysdescr != '') {
            $input['sysdescr'] = $sysdescr;
         }
         $unkonwn_id = $pfUnknownDevice->add($input);
         // Add port
         $input = array();
         $input['items_id'] = $unkonwn_id;
         $input['itemtype'] = 'PluginFusioninventoryUnknownDevice';
         $input['ip'] = $IP;
         $input['name'] = $ifDescr;
         $PortID = $NetworkPort->add($input);
         return($PortID);
      }
      return($PortID);
   }
   
   

   
   function getPortIDfromSysmacandPortnumber($sysmac, $ifnumber, $params = array()) {
      global $DB;

      $PortID = '';
      $queryPort = "SELECT *
         FROM `glpi_plugin_fusioninventory_networkports`
         LEFT JOIN `glpi_networkports`
            ON `glpi_plugin_fusioninventory_networkports`.`networkports_id`=
               `glpi_networkports`.`id`
         WHERE `glpi_networkports`.`mac`='".$sysmac."'
            AND `glpi_networkports`.`itemtype`='NetworkEquipment'
            AND `logical_number`='".$ifnumber."'
         LIMIT 1";
      $resultPort = $DB->query($queryPort);
      $dataPort = $DB->fetch_assoc($resultPort);
      if ($DB->numrows($resultPort) == "1") {
         $PortID = $dataPort['networkports_id'];
      }

      if ($PortID == '') {
         // case where mac is of switch and not of the port (like Procurve)
         $queryPort = "SELECT *
            FROM `glpi_plugin_fusioninventory_networkports`
            LEFT JOIN `glpi_networkports`
               ON `glpi_plugin_fusioninventory_networkports`.`networkports_id`=
                  `glpi_networkports`.`id`
            LEFT JOIN `glpi_networkequipments`
               ON `glpi_networkports`.`items_id`=
                  `glpi_networkequipments`.`id`
            WHERE `glpi_networkequipments`.`mac`='".$sysmac."'
               AND `glpi_networkports`.`itemtype`='NetworkEquipment'
               AND `logical_number`='".$ifnumber."'
            LIMIT 1";
         $resultPort = $DB->query($queryPort);
         $dataPort = $DB->fetch_assoc($resultPort);
         if ($DB->numrows($resultPort) == "1") {
            $PortID = $dataPort['networkports_id'];
         }
      }

      if ($PortID == "") {
         $NetworkPort = new NetworkPort();
         $PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();

         $query = "SELECT * FROM `glpi_plugin_fusioninventory_unknowndevices`
            WHERE `mac`='".$sysmac."'
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == "1") {
            $data = $DB->fetch_assoc($result);
            // Search port and add if required
            $query1 = "SELECT *
                FROM `glpi_networkports`
                WHERE `itemtype`='PluginFusioninventoryUnknownDevice'
                   AND `items_id`='".$data['id']."'
                   AND `logical_number`='".$ifnumber."'
                LIMIT 1";
            $result1 = $DB->query($query1);
            if ($DB->numrows($result1) == "1") {
               $data1 = $DB->fetch_assoc($result1);
               $PortID = $data1['id'];
            } else {
               // Add port
               $input = array();
               $input['items_id'] = $data['id'];
               $input['itemtype'] = 'PluginFusioninventoryUnknownDevice';
               $input['mac'] = $sysmac;
               $input['logical_number'] = $ifnumber;
               if (isset($params['ifdescr'])) {
                  $input['name'] = $params['ifdescr'];
               }
               $PortID = $NetworkPort->add($input);
            }
            // Update unknown device
            $input = array();
            $input['id'] = $data['id'];
            $input['ip'] = $sysmac;
            // Add SNMP informations of unknown device
            if (isset($params['sysdescr'])) {
               $input['sysdescr'] = $params['sysdescr'];
            }
            $PluginFusioninventoryUnknownDevice->update($input);
            return $PortID;
         }

         $query = "SELECT *
             FROM `glpi_networkports`
             WHERE `itemtype`='PluginFusioninventoryUnknownDevice'
               AND `mac`='".$sysmac."'
             LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == "1") {
            $data = $DB->fetch_assoc($result);
            if ($PluginFusioninventoryUnknownDevice->convertUnknownToUnknownNetwork(
                    $data['items_id'])) {
               // Add port
               $input = array();
               $input['items_id'] = $data['items_id'];
               $input['itemtype'] = 'PluginFusioninventoryUnknownDevice';
               $input['mac'] = $sysmac;
               if (isset($params['ifdescr'])) {
                  $input['name'] = $params['ifdescr'];
               }
               $PortID = $NetworkPort->add($input);
               // Update unknown device
               $input = array();
               $input['id'] = $data['id'];
               $input['mac'] = $sysmac;
               if (isset($params['sysname'])) {
                  $input['name'] = $params['sysname'];
               }
               if (isset($params['sysdescr'])) {
                  $input['sysdescr'] = $params['sysdescr'];
               }
               $PluginFusioninventoryUnknownDevice->update($input);
               return $PortID;
            }
         }
         // Add unknown device
         $input = array();
         $input['mac'] = $sysmac;
         if (isset($params['sysname'])) {
            $input['name'] = $params['sysname'];
         }
         if (isset($_SESSION["plugin_fusinvinventory_entity"])) {
            $input['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
         }
         if (isset($params['sysdescr'])) {
            $input['sysdescr'] = $params['sysdescr'];
         }
         $unkonwn_id = $PluginFusioninventoryUnknownDevice->add($input);
         // Add port
         $input = array();
         $input['items_id'] = $unkonwn_id;
         $input['itemtype'] = 'PluginFusioninventoryUnknownDevice';
         $input['mac'] = $sysmac;
         if (isset($params['ifdescr'])) {
            $input['name'] = $params['ifdescr'];
         }
         $PortID = $NetworkPort->add($input);
         return($PortID);
      }
      return($PortID);
   }

   
   
   function isPorthasPhone() {
      $isPhone = FALSE;
      
      $networkPort = new NetworkPort();
      
      foreach ($this->portMacs as $ifmac) {
         $a_ports = $networkPort->find("`mac`='".$ifmac."'", "", 1);
         if (count($a_ports) > 0) {
            $a_port = current($a_ports);
            if ($a_port['itemtype'] == 'Phone') {
               $isPhone = TRUE;
               return $isPhone;
            }
         }
      }
      return $isPhone;
   }   
}

?>
