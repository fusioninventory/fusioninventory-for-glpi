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
   @author    David Durieux
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

class PluginFusinvsnmpSNMP extends CommonDBTM {

   /**
    * Description
    *
    * @param
    * @param
    *
    * @return
    *
   **/
   function update_network_infos($id, $plugin_fusinvsnmp_models_id, $plugin_fusinvsnmp_configsecurities_id, $sysdescr) {
      global $DB;
      
      $query = "SELECT *
                FROM `glpi_plugin_fusinvsnmp_networkequipments`
                WHERE `networkequipments_id`='".$id."';";
      $result = $DB->query($query);
      if ($DB->numrows($result) == "0") {
         $queryInsert = "INSERT INTO `glpi_plugin_fusinvsnmp_networkequipments`(`networkequipments_id`)
                         VALUES('".$id."');";

         $DB->query($queryInsert);
      }      
      if (empty($plugin_fusinvsnmp_configsecurities_id)) {
         $plugin_fusinvsnmp_configsecurities_id = 0;
      }
      $query = "UPDATE `glpi_plugin_fusinvsnmp_networkequipments`
                SET `plugin_fusinvsnmp_models_id`='".$plugin_fusinvsnmp_models_id."',
                    `plugin_fusinvsnmp_configsecurities_id`='".$plugin_fusinvsnmp_configsecurities_id."',
                    `sysdescr`='".$sysdescr."'
                WHERE `networkequipments_id`='".$id."';";
   
      $DB->query($query);
   }
   


   /**
    * Description
    *
    * @param $IP value ip of the device
    * @param $ifDescr value description/name of the port
    *
    * @return
    *
   **/
   function getPortIDfromDeviceIP($IP, $ifDescr, $sysdescr, $sysname, $model) {
      global $DB;

      $pfUnknownDevice = new PluginFusioninventoryUnknownDevice();
      $pfsnmpUnknownDevice = new PluginFusinvsnmpUnknownDevice();
      
      $NetworkPort = new NetworkPort();

      $PortID = "";
      $query = "SELECT *
                FROM `glpi_plugin_fusinvsnmp_networkequipmentips`
                WHERE `ip`='".$IP."';";
      $result = $DB->query($query);
      if ($DB->numrows($result) == "1") {
         $data = $DB->fetch_assoc($result);

         $queryPort = "SELECT *
                       FROM `glpi_plugin_fusinvsnmp_networkports`
                            LEFT JOIN `glpi_networkports`
                                      ON `glpi_plugin_fusinvsnmp_networkports`.`networkports_id`=
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
                          LIMIT 0,1";
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
         if (strstr($model, "Phone")) {
            $queryPort = "SELECT glpi_networkports.*
                           FROM `glpi_phones`
                              LEFT JOIN `glpi_networkports`
                                 ON `glpi_phones`.`id`=`glpi_networkports`.`items_id`
                          WHERE `ip`='".$IP."'
                                AND `glpi_networkports`.`itemtype`='Phone'
                                AND `glpi_phones`.`name`='".$sysname."'
                          LIMIT 1";
            $resultPort = $DB->query($queryPort);
            $dataPort = $DB->fetch_assoc($resultPort);
            if (isset($dataPort['id'])) {
               $PortID = $dataPort["id"];
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
            $pfUnknownDevice->update($input);
            // Add SNMP informations of unknown device
            if ($sysdescr != '') {
               $a_list = $pfsnmpUnknownDevice->find("plugin_fusioninventory_unknowndevices_id='".$data['id']."'"); 
               $input = array();               
               $input['sysdescr'] = $sysdescr;
               if (count($a_list == '0')) {
                  $input['plugin_fusioninventory_unknowndevices_id'] = $data['id'];
                  $pfsnmpUnknownDevice->add($input);
               } else {
                  $snmpunknow = current($a_list);
                  $input['id'] = $snmpunknow['id'];
                  $pfsnmpUnknownDevice->update($input);
               }
            }
            return $PortID;
         }

         $query = "SELECT *
             FROM `glpi_networkports`
             WHERE `itemtype`='PluginFusioninventoryUnknownDevice'
               AND`ip`='".$IP."'
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
               $pfUnknownDevice->update($input);
               // Add SNMP informations of unknown device
               if ($sysdescr != '') {
                  $a_list = $pfsnmpUnknownDevice->find("plugin_fusioninventory_unknowndevices_id='".$data['id']."'"); 
                  $input = array();               
                  $input['sysdescr'] = $sysdescr;
                  if (count($a_list == '0')) {
                     $input['plugin_fusioninventory_unknowndevices_id'] = $data['id'];
                     $pfsnmpUnknownDevice->add($input);
                  } else {
                     $snmpunknow = current($a_list);
                     $input['id'] = $snmpunknow['id'];
                     $pfsnmpUnknownDevice->update($input);
                  }
               }
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
         if (isset($_SESSION["plugin_fusinvinventory_entity"])) {
            $input['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
         }
         $unkonwn_id = $pfUnknownDevice->add($input);
         // Add port
         $input = array();
         $input['items_id'] = $unkonwn_id;
         $input['itemtype'] = 'PluginFusioninventoryUnknownDevice';
         $input['ip'] = $IP;
         $input['name'] = $ifDescr;
         $PortID = $NetworkPort->add($input);
         // Add SNMP informations of unknown device
         if ($sysdescr != '') {
            $input = array();
            $input['plugin_fusioninventory_unknowndevices_id'] = $unkonwn_id;
            $input['sysdescr'] = $sysdescr;
            $pfsnmpUnknownDevice->add($input);
         }
         return($PortID);
      }
      return($PortID);
   }



   function getPortIDfromSysmacandPortnumber($sysmac, $ifnumber, $params = array()) {
      global $DB;

      $PortID = '';
      $queryPort = "SELECT *
         FROM `glpi_plugin_fusinvsnmp_networkports`
         LEFT JOIN `glpi_networkports`
            ON `glpi_plugin_fusinvsnmp_networkports`.`networkports_id`=
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
            FROM `glpi_plugin_fusinvsnmp_networkports`
            LEFT JOIN `glpi_networkports`
               ON `glpi_plugin_fusinvsnmp_networkports`.`networkports_id`=
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
         $pluginFusinvsnmpUnknownDevice = new PluginFusinvsnmpUnknownDevice();
         
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
            $PluginFusioninventoryUnknownDevice->update($input);
            // Add SNMP informations of unknown device
            if (isset($params['sysdescr'])) {
               $a_list = $pluginFusinvsnmpUnknownDevice->find("plugin_fusioninventory_unknowndevices_id='".$data['id']."'"); 
               $input = array();               
               $input['sysdescr'] = $params['sysdescr'];
               if (count($a_list == '0')) {
                  $input['plugin_fusioninventory_unknowndevices_id'] = $data['id'];
                  $pluginFusinvsnmpUnknownDevice->add($input);
               } else {
                  $snmpunknow = current($a_list);
                  $input['id'] = $snmpunknow['id'];
                  $pluginFusinvsnmpUnknownDevice->update($input);
               }
            }
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
            if ($PluginFusioninventoryUnknownDevice->convertUnknownToUnknownNetwork($data['items_id'])) {
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
               $PluginFusioninventoryUnknownDevice->update($input);
               // Add SNMP informations of unknown device
               if (isset($params['sysdescr'])) {
                  $a_list = $pluginFusinvsnmpUnknownDevice->find("plugin_fusioninventory_unknowndevices_id='".$data['id']."'"); 
                  $input = array();               
                  $input['sysdescr'] = $params['sysdescr'];
                  if (count($a_list == '0')) {
                     $input['plugin_fusioninventory_unknowndevices_id'] = $data['id'];
                     $pluginFusinvsnmpUnknownDevice->add($input);
                  } else {
                     $snmpunknow = current($a_list);
                     $input['id'] = $snmpunknow['id'];
                     $pluginFusinvsnmpUnknownDevice->update($input);
                  }
               }
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
         // Add SNMP informations of unknown device
         if (isset($params['sysdescr'])) {
            $input = array();
            $input['plugin_fusioninventory_unknowndevices_id'] = $unkonwn_id;
            $input['sysdescr'] = $params['sysdescr'];
            $pluginFusinvsnmpUnknownDevice->add($input);
         }
         return($PortID);
      }
      
      
      return($PortID);
      
   }
   


   /**
    * Get port id from device MAC address
    *
    * @param $p_mac MAC address
    * @param $p_fromPortID Link port id
    *
    * @return Port id
   **/
   function getPortIDfromDeviceMAC($p_mac, $p_fromPortID) {
      global $DB;

      $query = "SELECT id
                FROM `glpi_networkports`
                WHERE (`mac` = '".$p_mac."' OR
                                  `mac` = '".strtoupper($p_mac)."')
                      AND `id`!='".$p_fromPortID."'
                LIMI 1"; // do not get the link port
      $result = $DB->query($query);
      if ($DB->numrows($result) > 0) {
         $data = $DB->fetch_assoc($result);
         return($data["id"]);
      }
      return false;
   }


   
   static function auth_dropdown($selected="") {

      $pfConfigSecurity = new PluginFusinvsnmpConfigSecurity();
      $config = new PluginFusioninventoryConfig();

      if ($config->getValue($_SESSION["plugin_fusinvsnmp_moduleid"], "storagesnmpauth") == "file") {
         echo $pfConfigSecurity->selectbox($selected);
      } else  if ($config->getValue($_SESSION["plugin_fusinvsnmp_moduleid"], "storagesnmpauth") == "DB") {
         Dropdown::show("PluginFusinvsnmpConfigSecurity",
                        array('name' => "plugin_fusinvsnmp_configsecurities_id",
                              'value' => $selected,
                              'comment' => false));
      }
   }


   
   static function hex_to_string($value) {
      if (strstr($value, "0x0115")) {
         $hex = str_replace("0x0115","",$value);
         $string='';
         for ($i=0; $i < strlen($hex)-1; $i+=2) {
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
         }
         $value = $string;
      }
      if (strstr($value, "0x")) {
         $hex = str_replace("0x","",$value);
         $string='';
         for ($i=0; $i < strlen($hex)-1; $i+=2) {
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
         }
         $value = $string;
      }
      return $value;
   }
}

?>