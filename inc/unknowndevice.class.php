<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

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
 ------------------------------------------------------------------------
*/

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginFusionInventoryUnknownDevice extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_fusioninventory_unknown_device";
		$this->type = PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
	}



	function showForm($target, $ID = '') {
		global $DB,$CFG_GLPI,$LANG;

		plugin_fusioninventory_checkRight("snmp_networking","r");

		if ($ID!='') {
			$this->getFromDB($ID);
      } else {
			$this->getEmpty();
      }
      $this->showTabs($ID, "",$_SESSION['glpi_tab']);
		echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";

		echo "<table  class='tab_cadre_fixe'>";

		echo "<tr><th colspan='4'>";
		echo $LANG['plugin_fusioninventory']["menu"][4];
		echo " :</th></tr>";

		$datestring = $LANG["common"][26].": ";
		$date = convDateTime($this->fields["date_mod"]);
		echo "<tr>";
		echo "<th align='center' width='450' colspan='2'>";
		echo $LANG["common"][2]." ".$this->fields["ID"];
		echo "</th>";
	
		echo "<th align='center' colspan='2'>";
		echo $datestring.$date;
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG["common"][16] . " :</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' value='" . $this->fields["name"] . "' size='35'/>";
		echo "</td>";

      if (isMultiEntitiesMode()) {
         echo "<td align='center'>" . $LANG['entity'][0] . " : </td>";
         echo "</td>";
         echo "<td align='center'>";
         dropdownvalue("glpi_entities",'FK_entities', $this->fields["FK_entities"]);
         echo "</td>";
         echo "</tr>";
         echo "</tr>";
      }

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["unknown"][0] . " :</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='dnsname' value='" . $this->fields["dnsname"] . "' size='35'/>";
		echo "</td>";

      echo "<td align='center'>" . $LANG['common'][18] . " : </td>";
      echo "</td>";
      echo "<td align='center'>";
		echo "<input type='text' name='contact' value='" . $this->fields["contact"] . "' size='35'/>";
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['common'][17] . " :</td>";
		echo "<td align='center'>";
			$type_list[] = COMPUTER_TYPE;
			$type_list[] = NETWORKING_TYPE;
			$type_list[] = PRINTER_TYPE;
			$type_list[] = PERIPHERAL_TYPE;
			$type_list[] = PHONE_TYPE;

         // GENERIC OBJECT : Search types in generic object
         $plugin = new Plugin;
         if ($plugin->isActivated('genericobject')) {
            if (TableExists("glpi_plugin_genericobject_types")) {
               $query = "SELECT * FROM `glpi_plugin_genericobject_types`
                  WHERE `status`='1' ";
               if ($result=$DB->query($query)) {
                  while ($data=$DB->fetch_array($result)) {
                     $type_list[] = $data['device_type'];
                  }
               }
            }
         }
         // END GENERIC OBJECT
			dropdownDeviceTypes('type',$this->fields["type"],$type_list);
		echo "</td>";

      echo "<td align='center'>" . $LANG['setup'][89] . " : </td>";
      echo "</td>";
      echo "<td align='center'>";
      dropdownValue("glpi_dropdown_domain", "domain", $this->fields["domain"]);
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['common'][15] . " :</td>";
		echo "<td align='center'>";
      dropdownValue("glpi_dropdown_locations", "location", $this->fields["location"]);
		echo "</td>";

      echo "<td align='center'>" . $LANG['common'][19] . " : </td>";
      echo "</td>";
      echo "<td align='center'>";
		echo "<input type='text' name='serial' value='" . $this->fields["serial"] . "' size='35'/>";
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["unknown"][2] . " :</td>";
		echo "<td align='center'>";
      dropdownYesNo("accepted", $this->fields["accepted"]);
		echo "</td>";

      echo "<td align='center'>" . $LANG['common'][20] . " : </td>";
      echo "</td>";
      echo "<td align='center'>";
		echo "<input type='text' name='otherserial' value='" . $this->fields["otherserial"] . "' size='35'/>";
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["functionalities"][3] . " :</td>";
		echo "<td align='center'>";
      dropdownYesNo("snmp", $this->fields["snmp"]);
		echo "</td>";

      if ($this->fields["snmp"] == "1") {
         echo "<td align='center'>" . $LANG['plugin_fusioninventory']["model_info"][4] . " : </td>";
         echo "</td>";
         echo "<td align='center'>";
         dropdownValue("glpi_plugin_fusioninventory_model_infos", "FK_model_infos", $this->fields["FK_model_infos"]);
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>" . $LANG['plugin_fusioninventory']["model_info"][3] . " :</td>";
         echo "<td align='center'>";
         dropdownValue("glpi_plugin_fusioninventory_snmp_connection", "FK_snmp_connection", $this->fields["FK_snmp_connection"]);
         echo "</td>";
      }

      echo "<td align='center'>" . $LANG['common'][25] . " : </td>";
      echo "</td>";
      echo "<td align='middle'>";
      echo "<textarea  cols='50' rows='5' name='comments' >".$this->fields["comments"]."</textarea>";
      echo "</td>";
		echo "</tr>";


      echo "<tr>";
      echo "<td class='tab_bg_2' align='center' colspan='2'>\n";
      echo "<input type='hidden' name='ID' value=$ID>";
      echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit'>";
      echo "</td>\n";

      echo "<td class='tab_bg_2' colspan='2'  align='center'>\n";
      echo "<input type='hidden' name='ID' value=$ID>";
      echo "<div class='center'>";
      if (!$this->fields["deleted"]){
         echo "<input type='submit' name='delete' value=\"".$LANG['buttons'][6]."\" class='submit'>";
         }else {
         echo "<input type='submit' name='restore' value=\"".$LANG['buttons'][21]."\" class='submit'>";

         echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='purge' value=\"".$LANG['buttons'][22]."\" class='submit'>";
      }
      echo "</div>";
      echo "</td>";

		echo "</table></form></div>";
	}



	function updateFromOldVersion_unknown_mac() {
		global $DB,$LANG;

		$snmp_queries = new PluginFusionInventorySNMP;
		$np=new Netport;

		$query = "SELECT DISTINCT `unknow_mac`,`unknown_ip`,`port`,`end_FK_processes`
                FROM `glpi_plugin_fusioninventory_unknown_mac`
                WHERE `end_FK_processes`=(
                      SELECT MAX(`end_FK_processes`)
                      FROM `glpi_plugin_fusioninventory_unknown_mac`); ";

		if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
				$name_unknown = plugin_fusioninventory_search_name_ocs_servers($data["unknow_mac"]);
				// Add unknown device
				if ($name_unknown == $data["unknown_ip"]) {
					$unknown_infos["name"] = '';
            } else {
					$unknown_infos["name"] = $name_unknown;
            }
				$newID=$this->add($unknown_infos);
				unset($unknown_infos);
				// Add networking_port
				$port_add["on_device"] = $newID;
				$port_add["device_type"] = PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN;
				$port_add["ifaddr"] = $data["unknown_ip"];
				$port_add['ifmac'] = $data["unknow_mac"];
				$port_ID = $np->add($port_add);
				unset($port_add);

				// Connection between ports (wire table in DB)
				$snmp_queries->PortsConnection($data["port"], $port_ID,$data["end_FK_processes"]);
			}
		}
	}



   function CleanOrphelinsConnections() {
      global $DB;

      $query = "SELECT `glpi_networking_ports`.`ID`
                FROM `glpi_networking_ports`
                     LEFT JOIN `glpi_plugin_fusioninventory_unknown_device`
                               ON `on_device`=`glpi_plugin_fusioninventory_unknown_device`.`ID`
                     WHERE `device_type`=".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."
                           AND `glpi_plugin_fusioninventory_unknown_device`.`ID` IS NULL;";
      if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            $unknown_infos["name"] = '';
            $newID=$this->add($unknown_infos);
            
            $query_update = "UPDATE `glpi_networking_ports`
                             SET `on_device`='".$newID."'
                             WHERE `ID`='".$data["ID"]."';";
				$DB->query($query_update);
         }
      }
      
   }



	function FusionUnknownKnownDevice() {
		global $DB;

		$query = "SELECT *
                FROM `glpi_networking_ports`
                WHERE `ifmac` != ''
                      AND `ifmac` != '00:00:00:00:00:00'
                      AND `device_type`=".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."
                GROUP BY `ifmac`
                HAVING COUNT(*)>0;";
		if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
				// $data = ID of unknown device
				$query_known = "SELECT *
                            FROM `glpi_networking_ports`
                            WHERE `ifmac` IN ('".$data["ifmac"]."','".strtoupper($data["ifmac"])."',
                                              '".strtolower($data["ifmac"])."')
                                  AND `device_type`!=".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."
                            LIMIT 0,1;";
				$result_known=$DB->query($query_known);
            if ($DB->numrows($result_known) > 0) {
               $data_known=$DB->fetch_array($result_known);

               $query_update = "UPDATE `glpi_networking_ports`
                                SET `on_device`='".$data_known["on_device"]."',
                                    `device_type='".$data_known["device_type"]."',
                                    `logical_number`='".$data_known["logical_number"]."',
                                    `name`='".$data_known["name"]."',
                                    `ifaddr`='".$data_known["ifaddr"]."',
                                    `iface`='".$data_known["iface"]."',
                                    `netpoint`='".$data_known["netpoint"]."',
                                    `netmask`='".$data_known["netmask"]."',
                                    `gateway`='".$data_known["gateway"]."',
                                    `subnet`='".$data_known["subnet"]."'
                                  WHERE `ID`='".$data["ID"]."';";
               $DB->query($query_update);

               // Delete old networking port
               $this->deleteFromDB($data_known["ID"],1);

               // Delete unknown device
               $this->deleteFromDB($data["on_device"],1);

               // Modify OCS link of this networking port
               $query = "SELECT *
                         FROM `glpi_ocs_link`
                         WHERE `glpi_id`='".$data_known["on_device"]."';";
               $result = $DB->query($query);
               if ($DB->numrows($result) == 1) {
                  $line = $DB->fetch_assoc($result);

                  $import_ip = importArrayFromDB($line["import_ip"]);
                  $ip_port = $import_ip[$data_known["ID"]];
                  unset($import_ip[$data_known["ID"]]);
                  $import_ip[$data["ID"]] = $ip_port;

                  $query_update = "UPDATE `glpi_ocs_link`
                                   SET `import_ip`='" . exportArrayToDB($import_ip) . "'
                                   WHERE `glpi_id`='".$line["ID"]."';";
                  $DB->query($query_update);
               }
            }
			}
		}
	}
}

?>