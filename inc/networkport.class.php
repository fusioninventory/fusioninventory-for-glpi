<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2016 FusionInventory team
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
      $tab[5]['field']         = 'ifspeed';
      $tab[5]['name']          = __('Speed');

      $tab[6]['table']         = $this->getTable();
      $tab[6]['field']         = 'ifinternalstatus';
      $tab[6]['name']          = __('Internal status', 'fusioninventory');

      $tab[7]['table']         = $this->getTable();
      $tab[7]['field']         = 'iflastchange';
      $tab[7]['name']          = __('Last change', 'fusioninventory');

      $tab[8]['table']         = $this->getTable();
      $tab[8]['field']         = 'ifinoctets';
      $tab[8]['name']          = __('Number of bytes received / Number of bytes sent', 'fusioninventory');

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

      $tab[15]['table']         = $this->getTable();
      $tab[15]['field']         = 'lastup';
      $tab[15]['name']          = __('Port not connected since', 'fusioninventory');

      $tab[16]['table']         = $this->getTable();
      $tab[16]['field']         = 'ifalias';
      $tab[16]['name']          = __('Alias', 'fusioninventory');

      $tab[17]['table']          = 'glpi_netpoints';
      $tab[17]['field']          = 'name';
      $tab[17]['name']           = _n('Network outlet', 'Network outlets', 1);

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



   /*
    * Search networkport with ip and ifdescr
    */
   function getPortIDfromDeviceIP($IP, $ifDescr, $sysdescr, $sysname, $model) {
      global $DB;

      $pfUnmanaged      = new PluginFusioninventoryUnmanaged();
      $NetworkPort      = new NetworkPort();
      $networkName      = new NetworkName();
      $iPAddress        = new IPAddress();

      $PortID = "";

      // search port have ifdescr + ip (in most cases not find it)
      $queryPort = "SELECT `glpi_networkports`.`id`
                    FROM `glpi_plugin_fusioninventory_networkports`
                    LEFT JOIN `glpi_networkports`
                       ON `glpi_plugin_fusioninventory_networkports`.`networkports_id`=
                          `glpi_networkports`.`id`
                    LEFT JOIN `glpi_networknames`
                       ON `glpi_networknames`.`items_id`=`glpi_networkports`.`id`
                          AND `glpi_networknames`.`itemtype`='NetworkPort'
                    LEFT JOIN `glpi_ipaddresses`
                       ON `glpi_ipaddresses`.`items_id`=`glpi_networknames`.`id`
                          AND `glpi_ipaddresses`.`itemtype`='NetworkName'

                    WHERE (`ifdescr`='".$ifDescr."'
                             OR `glpi_networkports`.`name`='".$ifDescr."')
                          AND `glpi_networkports`.`itemtype`='NetworkEquipment'
                          AND `glpi_ipaddresses`.`name`='".$IP."'";
      $resultPort = $DB->query($queryPort);
      if ($DB->numrows($resultPort) == 0) {
         // Search a management port of networkequipment have this IP
         $queryManagement = "SELECT `glpi_networkports`.`itemtype`,
                        `glpi_networkports`.`items_id` FROM `glpi_networkports`
                       LEFT JOIN `glpi_networknames`
                          ON `glpi_networknames`.`items_id`=`glpi_networkports`.`id`
                             AND `glpi_networknames`.`itemtype`='NetworkPort'
                       LEFT JOIN `glpi_ipaddresses`
                          ON `glpi_ipaddresses`.`items_id`=`glpi_networknames`.`id`
                             AND `glpi_ipaddresses`.`itemtype`='NetworkName'

                       WHERE `glpi_ipaddresses`.`name`='".$IP."'
                          AND `instantiation_type`='NetworkPortAggregate'
                       LIMIT 1";
         $resultManagement = $DB->query($queryManagement);
         if ($DB->numrows($resultManagement) == 1) {
            $dataManagement = $DB->fetch_assoc($resultManagement);
            // Seach a port have this ifdescr for this same networkequipment
            $queryPort = "SELECT `glpi_networkports`.`id`
                          FROM `glpi_plugin_fusioninventory_networkports`
                          LEFT JOIN `glpi_networkports`
                             ON `glpi_plugin_fusioninventory_networkports`.`networkports_id`=
                                `glpi_networkports`.`id`
                          WHERE `glpi_networkports`.`itemtype`='".$dataManagement['itemtype']."'
                              AND `glpi_networkports`.`items_id`='".$dataManagement['items_id']."'
                              AND (`ifdescr`='".$ifDescr."'
                                   OR `glpi_networkports`.`name`='".$ifDescr."')
                          LIMIT 1";
            $resultPort = $DB->query($queryPort);
            if ($DB->numrows($resultPort) == 1) {
               $dataPort = $DB->fetch_assoc($resultPort);
               $PortID = $dataPort["id"];
            }
         }
      } else {
         $dataPort = $DB->fetch_assoc($resultPort);
         $PortID = $dataPort['id'];
      }

      // Detect IP Phone
      if ($PortID == "") {
         if (strstr($model, "Phone")
               || $model == '') {
            $queryPort = "SELECT glpi_networkports.* FROM `glpi_phones`
                          LEFT JOIN `glpi_networkports`
                             ON `glpi_phones`.`id`=`glpi_networkports`.`items_id`
                          LEFT JOIN `glpi_networknames`
                             ON `glpi_networknames`.`items_id`=`glpi_networkports`.`id`
                                AND `glpi_networknames`.`itemtype`='NetworkPort'
                          LEFT JOIN `glpi_ipaddresses`
                             ON `glpi_ipaddresses`.`items_id`=`glpi_networknames`.`id`
                                AND `glpi_ipaddresses`.`itemtype`='NetworkName'

                          WHERE `glpi_ipaddresses`.`name`='".$IP."'
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
         $query = "SELECT * FROM `glpi_plugin_fusioninventory_unmanageds`
            WHERE `ip`='".$IP."'
            LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == "1") {
            $data = $DB->fetch_assoc($result);
            // Search port and add if required
            $query1 = "SELECT *
                FROM `glpi_networkports`
                WHERE `itemtype`='PluginFusioninventoryUnmanaged'
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
               $input['itemtype'] = 'PluginFusioninventoryUnmanaged';
               $input['ip'] = $IP;
               $input['name'] = $ifDescr;
               $input['instantiation_type'] = 'NetworkPortEthernet';
               $PortID = $NetworkPort->add($input);
            }
            // Update unmanaged device
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
            $pfUnmanaged->update($input);
            return $PortID;
         }

         $query0 = "SELECT `glpi_networkports`.* FROM `glpi_networkports`
             LEFT JOIN `glpi_networknames`
                 ON `glpi_networknames`.`items_id`=`glpi_networkports`.`id`
                    AND `glpi_networknames`.`itemtype`='NetworkPort'
            LEFT JOIN `glpi_ipaddresses`
                 ON `glpi_ipaddresses`.`items_id`=`glpi_networknames`.`id`
                    AND `glpi_ipaddresses`.`itemtype`='NetworkName'

             WHERE `glpi_networkports`.`itemtype`='PluginFusioninventoryUnmanaged'
               AND `glpi_ipaddresses`.`name`='".$IP."'
             LIMIT 1";
         $result0 = $DB->query($query0);
         if ($DB->numrows($result0) == 1) {
            $data0 = $DB->fetch_assoc($result0);
            // Search port and add if required
            $query1 = "SELECT *
                FROM `glpi_networkports`
                WHERE `itemtype`='PluginFusioninventoryUnmanaged'
                   AND `items_id`='".$data0['items_id']."'
                   AND `name`='".$ifDescr."'
                LIMIT 1";
            $result1 = $DB->query($query1);
            if ($DB->numrows($result1) == "1") {
               $data1 = $DB->fetch_assoc($result1);
               $PortID = $data1['id'];
            } else {
               // Add port
               $input = array();
               $input['items_id'] = $data0['items_id'];
               $input['itemtype'] = 'PluginFusioninventoryUnmanaged';
               $input['name'] = $ifDescr;
               $input['instantiation_type'] = 'NetworkPortEthernet';
               $PortID = $NetworkPort->add($input);

               $input = array();
               $input['itemtype'] = 'NetworkPort';
               $input['items_id'] = $PortID;
               $networknames_id = $networkName->add($input);

               $input = array();
               $input['itemtype'] = 'NetworkName';
               $input['items_id'] = $networknames_id;
               $input['name'] = $IP;
               $iPAddress->add($input);
            }
            return $PortID;
         }
         // Add unmanaged device
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
         if (isset($_SESSION["plugin_fusioninventory_entity"])) {
            $input['entities_id'] = $_SESSION["plugin_fusioninventory_entity"];
         }
         if ($sysdescr != '') {
            $input['sysdescr'] = $sysdescr;
         }
         $unkonwn_id = $pfUnmanaged->add($input);
         // Add port
         $input = array();
         $input['items_id'] = $unkonwn_id;
         $input['itemtype'] = 'PluginFusioninventoryUnmanaged';
         $input['name'] = $ifDescr;
         $input['instantiation_type'] = 'NetworkPortEthernet';
         $PortID = $NetworkPort->add($input);

         $input = array();
         $input['itemtype'] = 'NetworkPort';
         $input['items_id'] = $PortID;
         $networknames_id = $networkName->add($input);

         $input = array();
         $input['itemtype'] = 'NetworkName';
         $input['items_id'] = $networknames_id;
         $input['name'] = $IP;
         $iPAddress->add($input);

         return($PortID);
      }
      return($PortID);
   }



   /*
    * Used to find port of a device tell LLDP
    */
   function getPortIDfromSysmacandPortnumber($sysmac, $ifnumber, $params = array()) {
      global $DB;

      $PortID = '';
      $queryPort = "SELECT *
         FROM `glpi_networkports`
         WHERE `mac`='".$sysmac."'
            AND `itemtype`='NetworkEquipment'
            AND `logical_number`='".$ifnumber."'
         LIMIT 1";
      $resultPort = $DB->query($queryPort);
      if ($DB->numrows($resultPort) == "1") {
         $dataPort = $DB->fetch_assoc($resultPort);
         $PortID = $dataPort['id'];
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
            WHERE `glpi_networkports`.`mac`='".$sysmac."'
               AND `glpi_networkports`.`itemtype`='NetworkEquipment'
               AND `logical_number`='".$ifnumber."'
            LIMIT 1";
         $resultPort = $DB->query($queryPort);
         $dataPort = $DB->fetch_assoc($resultPort);
         if ($DB->numrows($resultPort) == "1") {
            $PortID = $dataPort['networkports_id'];
         }
      }

      // In case of mac is mac of switch, not a port
      if ($PortID == '') {
         $queryPort = "SELECT *
            FROM `glpi_networkports`
            WHERE `logical_number`='".$ifnumber."'
               AND `itemtype`='NetworkEquipment'
               AND `items_id` IN
               (SELECT `items_id`
                FROM `glpi_networkports`
                WHERE `instantiation_type`='NetworkPortAggregate'
                  AND `mac`='".$sysmac."')
            LIMIT 1";
         $resultPort = $DB->query($queryPort);
         $dataPort = $DB->fetch_assoc($resultPort);
         if (isset($dataPort['id'])) {
            $PortID = $dataPort['id'];
         }
      }

      if ($PortID == "") {
         $NetworkPort = new NetworkPort();
         $PluginFusioninventoryUnmanaged = new PluginFusioninventoryUnmanaged();

         $query = "SELECT *
             FROM `glpi_networkports`
             WHERE `itemtype`='PluginFusioninventoryUnmanaged'
               AND `mac`='".$sysmac."'
             LIMIT 1";
         $result = $DB->query($query);
         if ($DB->numrows($result) == "1") {
            $data = $DB->fetch_assoc($result);
            return $data['id'];
         }
         // Add unmanaged device because not find device
         $input = array();
         $input['mac'] = $sysmac;
         if (isset($params['sysname'])) {
            $input['name'] = $params['sysname'];
         }
         if (isset($_SESSION["plugin_fusioninventory_entity"])) {
            $input['entities_id'] = $_SESSION["plugin_fusioninventory_entity"];
         }
         if (isset($params['sysdescr'])) {
            $input['sysdescr'] = $params['sysdescr'];
         }
         $unkonwn_id = $PluginFusioninventoryUnmanaged->add($input);
         // Add port
         $input = array();
         $input['items_id'] = $unkonwn_id;
         $input['itemtype'] = 'PluginFusioninventoryUnmanaged';
         $input['mac'] = $sysmac;
         if (isset($params['ifdescr'])) {
            $input['name'] = $params['ifdescr'];
         }
         $input['instantiation_type'] = 'NetworkPortEthernet';
         $PortID = $NetworkPort->add($input);
         return($PortID);
      }

      return($PortID);
   }



   /**
    * Function used to detect if port has multiple mac connected
    */
   static function isPortHasMultipleMac($networkports_id) {
      $nw = new NetworkPort_NetworkPort();
      $networkPort = new NetworkPort();

      $is_multiple = FALSE;
      $opposite_port = $nw->getOppositeContact($networkports_id);
      if ($opposite_port != ""
              && $opposite_port!= 0) {
         $networkPort->getFromDB($opposite_port);
         if ($networkPort->fields["itemtype"] == 'PluginFusioninventoryUnmanaged') {
            $pfUnmanaged = new PluginFusioninventoryUnmanaged();
            if ($pfUnmanaged->getFromDB($networkPort->fields['items_id'])) {
               if ($pfUnmanaged->fields['hub'] == 1) {
                  $is_multiple = TRUE;
               }
            }
         }
      }
      return $is_multiple;
   }
}

?>
