<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
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
	function getPortIDfromDeviceIP($IP, $ifDescr) {
		global $DB;

      $PluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
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
            if ($PluginFusioninventoryUnknownDevice->convertUnknownToUnknownNetwork($data['items_id'])) {
               // Add port
               $input = array();
               $input['items_id'] = $data['items_id'];
               $input['itemtype'] = 'PluginFusioninventoryUnknownDevice';
               $input['ip'] = $IP;
               $input['name'] = $ifDescr;
               $PortID = $NetworkPort->add($input);
               return $PortID;
            }
         }
         // Add unknown device
         $input = array();
         $input['ip'] = $IP;
         $unkonwn_id = $PluginFusioninventoryUnknownDevice->add($input);
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



   function getPortIDfromSysmacandPortnumber($sysmac, $ifnumber) {
      global $DB;

      $PortID = '';
      $queryPort = "SELECT *
         FROM `glpi_plugin_fusinvsnmp_networkports`
         LEFT JOIN `glpi_networkports`
            ON `glpi_plugin_fusinvsnmp_networkports`.`networkports_id`=
               `glpi_networkports`.`id`
         WHERE `glpi_networkports`.`mac`='".$sysmac."'
            AND `glpi_networkports`.`itemtype`='NetworkEquipment'
            AND `logical_number`='".$ifnumber."'";
      $resultPort = $DB->query($queryPort);
      $dataPort = $DB->fetch_assoc($resultPort);
      if ($DB->numrows($resultPort) == "1") {
         $PortID = $dataPort['networkports_id'];
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
                WHERE `mac` IN ('".$p_mac."',
                                  '".strtoupper($p_mac)."')
                      AND `id`!='".$p_fromPortID."';"; // do not get the link port
		$result = $DB->query($query);
		$data = $DB->fetch_assoc($result);
		return($data["id"]);
	}


   
   static function auth_dropdown($selected="") {
      global $DB;

      $PluginFusinvsnmpConfigSecurity = new PluginFusinvsnmpConfigSecurity();
      $config = new PluginFusioninventoryConfig();

      if ($config->getValue($_SESSION["plugin_fusinvsnmp_moduleid"], "storagesnmpauth") == "file") {
         echo $PluginFusinvsnmpConfigSecurity->selectbox($selected);
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