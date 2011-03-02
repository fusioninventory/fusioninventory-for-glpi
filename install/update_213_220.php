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

// Update from 2.1.3 to 2.2.0
function update213to220() {
   global $DB, $LANG;

   echo "<strong>Update 2.1.3 to 2.2.0</strong><br/>";
   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";

   plugin_fusioninventory_displayMigrationMessage("220"); // Start

   plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]); // Updating schema

   plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Update DB");

   $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-2.2.0-update.sql";
   $DBf_handle = fopen($DB_file, "rt");
   $sql_query = fread($DBf_handle, filesize($DB_file));
   fclose($DBf_handle);
   foreach ( explode(";\n", "$sql_query") as $sql_line) {
      if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
      if (!empty($sql_line)) {
         plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Update DB : ".$sql_line);
         $DB->query($sql_line);
      }
   }

   ini_set("memory_limit", "-1");
   ini_set("max_execution_time", "0");

   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
   }

   $DB->query("UPDATE `glpi_plugin_tracker_config`
      SET `version` = '2.2.0'
      WHERE `ID`=1
      LIMIT 1 ;");
      

   // **** Migration network history connections
      plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Migration network history connections");

      $sql_connection = "SELECT * FROM `glpi_plugin_fusioninventory_snmp_history`
                        WHERE `Field`='0'
                        ORDER BY `FK_process` DESC, `date_mod` DESC;";
      $result_connection = $DB->query($sql_connection);
      while ($thread_connection = $DB->fetch_array($result_connection)) {
         $input = array();
         $input['process_number'] = $thread_connection['FK_process'];
         $input['date'] = $thread_connection['date_mod'];
         if (($thread_connection["old_device_ID"] != "0")
                 OR ($thread_connection["new_device_ID"] != "0")) {

            if ($thread_connection["old_device_ID"] != "0") {
               // disconnection
               $input['creation'] = '0';
            } else if ($thread_connection["new_device_ID"] != "0") {
               // connection
               $input['creation'] = '1';
            }
            $input['FK_port_source'] = $thread_connection["FK_ports"];
            $dataPort = array();
            if ($thread_connection["old_device_ID"] != "0") {
               $queryPort = "SELECT *
                             FROM `glpi_networkports`
                             WHERE `mac`='".$thread_connection['old_value']."'
                             LIMIT 0,1;";
               $resultPort = $DB->query($queryPort);
               $dataPort = $DB->fetch_assoc($resultPort);
            } else if ($thread_connection["new_device_ID"] != "0") {
               $queryPort = "SELECT *
                             FROM `glpi_networkports`
                             WHERE `mac`='".$thread_connection['new_value']."'
                             LIMIT 0,1;";
               $resultPort = $DB->query($queryPort);
               $dataPort = $DB->fetch_assoc($resultPort);
            }
            if (isset($dataPort['id'])) {
               $input['FK_port_destination'] = $dataPort['id'];
            } else {
               $input['FK_port_destination'] = 0;
            }

            $query_ins = "INSERT INTO `glpi_plugin_fusioninventory_snmp_history_connections`
               (`process_number`, `date`, `creation`, `FK_port_source`, `FK_port_destination`)
               VALUES ('".$input['process_number']."',
                       '".$input['date']."', 
                       '".$input['creation']."',
                       '".$input['FK_port_source']."', 
                       '".$input['FK_port_destination']."')";
            $DB->query($query_ins);
            $query_del = "DELETE FROM `glpi_plugin_fusioninventory_snmp_history`
               WHERE `ID`='".$thread_connection['ID']."'";
            $DB->query($query_del);
         }
      }

   // **** Clean DB
   // * Clean glpi_plugin_fusioninventory_networking_ports
   plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Clean glpi_plugin_fusioninventory_networking_ports");
	$query_select = "SELECT `glpi_plugin_fusioninventory_networking_ports`.`ID`
                    FROM `glpi_plugin_fusioninventory_networking_ports`
                          LEFT JOIN `glpi_networkports`
                                    ON `glpi_networkports`.`id` = `FK_networking_ports`
                          LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `glpi_networkports`.`items_id`
                    WHERE `glpi_networkequipments`.`id` IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusioninventory_networking_ports`
         WHERE`ID`='".$data["ID"]."'";
      $DB->query($query_del);
	}
	// * Clean glpi_plugin_fusioninventory_networking_ifaddr
   plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Clean glpi_plugin_fusioninventory_networking_ifaddr");
	$query_select = "SELECT `glpi_plugin_fusioninventory_networking_ifaddr`.`ID`
                    FROM `glpi_plugin_fusioninventory_networking_ifaddr`
                          LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `FK_networking`
                    WHERE `glpi_networkequipments`.`id` IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusioninventory_networking_ifaddr`
         WHERE`ID`='".$data["ID"]."'";
      $DB->query($query_del);
	}
	// * Clean glpi_plugin_fusioninventory_networking
	plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Clean glpi_plugin_fusioninventory_networking");
	$query_select = "SELECT `glpi_plugin_fusioninventory_networking`.`ID`
                    FROM `glpi_plugin_fusioninventory_networking`
                          LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `FK_networking`
                    WHERE `glpi_networkequipments`.`id` IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
       $query_del = "DELETE FROM `glpi_plugin_fusioninventory_networking`
         WHERE`ID`='".$data["ID"]."'";
      $DB->query($query_del);
	}
	// * Clean glpi_plugin_fusioninventory_printers
	plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Clean glpi_plugin_fusioninventory_printers");
	$query_select = "SELECT `glpi_plugin_fusioninventory_printers`.`ID`
                    FROM `glpi_plugin_fusioninventory_printers`
                          LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `FK_printers`
                    WHERE `glpi_printers`.`id` IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusioninventory_printers`
         WHERE`ID`='".$data["ID"]."'";
      $DB->query($query_del);
	}
	// * Clean glpi_plugin_fusioninventory_printers_cartridges
	plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Clean glpi_plugin_fusioninventory_printers_cartridges");
	$query_select = "SELECT `glpi_plugin_fusioninventory_printers_cartridges`.`ID`
                    FROM `glpi_plugin_fusioninventory_printers_cartridges`
                          LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `FK_printers`
                    WHERE `glpi_printers`.`id` IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusioninventory_printers_cartridges`
         WHERE`ID`='".$data["ID"]."'";
      $DB->query($query_del);
	}
	// * Clean glpi_plugin_fusioninventory_printers_history
	plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Clean glpi_plugin_fusioninventory_printers_history");
	$query_select = "SELECT `glpi_plugin_fusioninventory_printers_history`.`ID`
                    FROM `glpi_plugin_fusioninventory_printers_history`
                          LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `FK_printers`
                    WHERE `glpi_printers`.`id` IS NULL";
	$result=$DB->query($query_select);
	while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusioninventory_printers_history`
         WHERE`ID`='".$data["ID"]."'";
      $DB->query($query_del);
	}

   // **** Init config modules
   $query = "INSERT INTO `glpi_plugin_fusioninventory_config_modules`
               (`id`, `snmp`, `inventoryocs`, `netdiscovery`, `remotehttpagent`, `wol`)
             VALUES ('1', '0', '0', '0', '0', '0');";
   $DB->query($query);

   // **** CReation of folders
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
   }

   // **** Update right
   plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Update rights");
	$Profile=new Profile();
	$Profile->getFromDB($_SESSION['glpiactiveprofile']['id']);
	$name=$Profile->fields["name"];

   $query = "UPDATE `glpi_plugin_fusioninventory_profiles`
               SET `interface`='fusioninventory', `snmp_networking`='w',
                   `snmp_printers`='w', `snmp_models`='w',
                   `snmp_authentification`='w', `rangeip`='w',
                   `agents`='w', `remotecontrol`='w',
                   `agentsprocesses`='r', `unknowndevices`='w',
                   `reports`='r', `deviceinventory`='w',
                   `netdiscovery`='w', `snmp_query`='w',
                   `wol`='w', `configuration`='w'
               WHERE `name`='".$name."'";
	$DB->query($query);

   // **** Delete old agents
   plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Delete old agents");
	$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_agents`";
   $DB->query($query_delete);

   // **** Delete models
   plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Delete models");
	$query_delete = "DELETE FROM `glpi_plugin_fusioninventory_model_infos`";
   $DB->query($query_delete);

//   // **** Import models
//   $importexport = new PluginFusionInventoryImportExport;
//   include(GLPI_ROOT.'/inc/setup.function.php');
//   include(GLPI_ROOT.'/inc/rulesengine.function.php');
//   foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0,1);

   // **** Update ports history from lang traduction into field constant (MySQL fiel 'Field')
   plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Update ports history from lang traduction into field constant");
	update213to220_ConvertField();

   // **** Delete all values in glpi_plugin_fusioninventory_config_snmp_history
   plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Delete all values in glpi_plugin_fusioninventory_config_snmp_history");
	$rights = array();
   $rights['ifmtu'] = '-1';
   $rights['ifdescr'] = '-1';
   $rights['ifinerrors'] = '-1';
   $rights['ifinoctets'] = '-1';
   $rights['ifinternalstatus'] = '-1';
   $rights['iflastchange'] = '-1';
   $rights['ifName'] = '-1';
   $rights['ifouterrors'] = '-1';
   $rights['ifoutoctets'] = '-1';
   $rights['ifspeed'] = '-1';
   $rights['ifstatus'] = '-1';
   $rights['vlanTrunkPortDynamicStatus'] = '-1';
   $rights['portDuplex'] = '-1';
   $rights['ifIndex'] = '-1';
   $rights['ifPhysAddress'] = '-1';

   foreach ($rights as $field=>$value){
      $input = array();
      $input['field'] = $field;
      $input['days']  = $value;
      $query_ins = "INSERT INTO `glpi_plugin_fusioninventory_config_snmp_history`
         (`field`, `days`)
         VALUES ('".$input['field']."', '".$input['days']."')";
      $DB->query($query_ins);
   }


   // Fields to history
   $rights = array();
   $rights['ifmtu'] = '-1';
   $rights['ifdescr'] = '-1';
   $rights['ifinerrors'] = '-1';
   $rights['ifinoctets'] = '-1';
   $rights['ifinternalstatus'] = '-1';
   $rights['iflastchange'] = '-1';
   $rights['ifName'] = '-1';
   $rights['ifouterrors'] = '-1';
   $rights['ifoutoctets'] = '-1';
   $rights['ifspeed'] = '-1';
   $rights['ifstatus'] = '-1';
   $rights['vlanTrunkPortDynamicStatus'] = '-1';
   $rights['portDuplex'] = '-1';
   $rights['ifIndex'] = '-1';
   $rights['ifPhysAddress'] = '-1';

   $query = "SELECT *
             FROM `glpi_plugin_fusioninventory_config_snmp_history`;";
   if ($result=$DB->query($query)) {
      while ($data=$DB->fetch_array($result)) {
         $val = str_replace("2-", "", $data['field']);
         if (isset($rights[$val])) {
            $rights[$val] = '0';
         }
      }
   }

   $query = "TRUNCATE TABLE `glpi_plugin_fusioninventory_config_snmp_history`";
   $DB->query($query);

   // Add rights in DB
   foreach ($rights as $field=>$value){
      $input = array();
      $input['field'] = $field;
      $input['days']  = $value;
      $query_ins = "INSERT INTO `glpi_plugin_fusioninventory_config_snmp_history`
         (`field`, `days`)
         VALUES ('".$input['field']."', '".$input['days']."')";
      $DB->query($query_ins);
   }

   // **** Delete all ports present in fusion but deleted in glpi_networking
   plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Delete all ports present in fusion but deleted in glpi_networking");
	$query = "SELECT glpi_plugin_fusioninventory_networking_ports.ID AS fusinvID FROM `glpi_plugin_fusioninventory_networking_ports`
      LEFT JOIN `glpi_networkports` ON FK_networking_ports=glpi_networkports.id
      WHERE glpi_networkports.id IS NULL";
   if ($result=$DB->query($query)) {
      while ($data=$DB->fetch_array($result)) {
         $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking_ports`
            WHERE `ID`='".$data['fusinvID']."' ";
         $DB->query($query_delete);
      }
   }

   // **** Add IP of switch in table glpi_plugin_fusioninventory_networking_ifaddr if not present
   plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Add IP of switch in table glpi_plugin_fusioninventory_networking_ifaddr if not present");
	$query = "SELECT * FROM glpi_networkequipments";
   if ($result=$DB->query($query)) {
      while ($data=$DB->fetch_array($result)) {
         $query_ifaddr = "SELECT * FROM `glpi_plugin_fusioninventory_networking_ifaddr`
            WHERE `ifaddr`='".$data['ip']."' ";
         $result_ifaddr = $DB->query($query_ifaddr);
         if ($DB->numrows($result_ifaddr) == "0") {
            $query_add = "INSERT INTO `glpi_plugin_fusioninventory_networking_ifaddr`
               (`FK_networking`, `ifaddr`) VALUES ('".$data['id']."', '".$data['ip']."')";
            $DB->query($query_add);
         }
      }
   }

   plugin_fusioninventory_displayMigrationMessage("220"); // End

   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";

}


function update213to220_ConvertField() {
   global $LANG,$FUSIONINVENTORY_MAPPING,$FUSIONINVENTORY_MAPPING_DISCOVERY,$DB;

   // ----------------------------------------------------------------------
   //NETWORK MAPPING MAPPING
   // ----------------------------------------------------------------------
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['table'] = 'glpi_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['field'] = 'location';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['name'] = $LANG['plugin_fusioninventory']["mapping"][1];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['location']['dropdown'] = 'glpi_dropdown_locations';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['table'] = 'glpi_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['field'] = 'firmware';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['name'] = $LANG['plugin_fusioninventory']["mapping"][2];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware']['dropdown'] = 'glpi_dropdown_firmware';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware1']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware1']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware1']['name'] = $LANG['plugin_fusioninventory']["mapping"][2]." 1";
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware1']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware1']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware2']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware2']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware2']['name'] = $LANG['plugin_fusioninventory']["mapping"][2]." 2";
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware2']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['firmware2']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['table'] = 'glpi_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['field'] = 'contact';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['name'] = $LANG['plugin_fusioninventory']["mapping"][403];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['contact']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comments']['table'] = 'glpi_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comments']['field'] = 'comments';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comments']['name'] = $LANG['plugin_fusioninventory']["mapping"][404];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comments']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['comments']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['uptime']['table'] = 'glpi_plugin_fusioninventory_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['uptime']['field'] = 'uptime';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['uptime']['name'] = $LANG['plugin_fusioninventory']["mapping"][3];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['uptime']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['uptime']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpu']['table'] = 'glpi_plugin_fusioninventory_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpu']['field'] = 'cpu';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpu']['name'] = $LANG['plugin_fusioninventory']["mapping"][12];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpu']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpu']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpuuser']['table'] = 'glpi_plugin_fusioninventory_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpuuser']['field'] = 'cpu';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpuuser']['name'] = $LANG['plugin_fusioninventory']["mapping"][401];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpuuser']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpuuser']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpusystem']['table'] = 'glpi_plugin_fusioninventory_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpusystem']['field'] = 'cpu';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpusystem']['name'] = $LANG['plugin_fusioninventory']["mapping"][402];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpusystem']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cpusystem']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['table'] = 'glpi_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['field'] = 'serial';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['name'] = $LANG['plugin_fusioninventory']["mapping"][13];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['serial']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['table'] = 'glpi_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['field'] = 'otherserial';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['name'] = $LANG['plugin_fusioninventory']["mapping"][419];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['otherserial']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['table'] = 'glpi_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['field'] = 'name';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['name'] = $LANG['plugin_fusioninventory']["mapping"][20];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['name']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ram']['table'] = 'glpi_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ram']['field'] = 'ram';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ram']['name'] = $LANG['plugin_fusioninventory']["mapping"][21];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ram']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ram']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['memory']['table'] = 'glpi_plugin_fusioninventory_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['memory']['field'] = 'memory';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['memory']['name'] = $LANG['plugin_fusioninventory']["mapping"][22];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['memory']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['memory']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vtpVlanName']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vtpVlanName']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vtpVlanName']['name'] = $LANG['plugin_fusioninventory']["mapping"][19];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vtpVlanName']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vtpVlanName']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vmvlan']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vmvlan']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vmvlan']['name'] = $LANG['plugin_fusioninventory']["mapping"][430];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vmvlan']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vmvlan']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['table'] = 'glpi_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['field'] = 'model';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['name'] = $LANG['plugin_fusioninventory']["mapping"][17];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['entPhysicalModelName']['dropdown'] = 'glpi_dropdown_model_networking';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['macaddr']['table'] = 'glpi_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['macaddr']['field'] = 'ifmac';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['macaddr']['name'] = $LANG['plugin_fusioninventory']["mapping"][417];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['macaddr']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['macaddr']['dropdown'] = '';

   // Networking CDP (Walk)
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheAddress']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheAddress']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheAddress']['name'] = $LANG['plugin_fusioninventory']["mapping"][409];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheAddress']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheAddress']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheDevicePort']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheDevicePort']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheDevicePort']['name'] = $LANG['plugin_fusioninventory']["mapping"][410];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheDevicePort']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['cdpCacheDevicePort']['dropdown'] = '';

   // Networking LLDP
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpRemChassisId']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpRemChassisId']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpRemChassisId']['name'] = $LANG['plugin_fusioninventory']["mapping"][431];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpRemChassisId']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpRemChassisId']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpRemPortId']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpRemPortId']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpRemPortId']['name'] = $LANG['plugin_fusioninventory']["mapping"][432];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpRemPortId']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpRemPortId']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpLocChassisId']['table'] = 'glpi_networking';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpLocChassisId']['field'] = 'contact_num';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpLocChassisId']['name'] = $LANG['plugin_fusioninventory']["mapping"][432];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpLocChassisId']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['lldpLocChassisId']['dropdown'] = '';


   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vlanTrunkPortDynamicStatus']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vlanTrunkPortDynamicStatus']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vlanTrunkPortDynamicStatus']['name'] = $LANG['plugin_fusioninventory']["mapping"][411];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vlanTrunkPortDynamicStatus']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['vlanTrunkPortDynamicStatus']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbAddress']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbAddress']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbAddress']['name'] = $LANG['plugin_fusioninventory']["mapping"][412];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbAddress']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbAddress']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipNetToMediaPhysAddress']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipNetToMediaPhysAddress']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipNetToMediaPhysAddress']['name'] = $LANG['plugin_fusioninventory']["mapping"][413];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipNetToMediaPhysAddress']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipNetToMediaPhysAddress']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbPort']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbPort']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbPort']['name'] = $LANG['plugin_fusioninventory']["mapping"][414];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbPort']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dTpFdbPort']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dBasePortIfIndex']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dBasePortIfIndex']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dBasePortIfIndex']['name'] = $LANG['plugin_fusioninventory']["mapping"][415];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dBasePortIfIndex']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['dot1dBasePortIfIndex']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipAdEntAddr']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipAdEntAddr']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipAdEntAddr']['name'] = $LANG['plugin_fusioninventory']["mapping"][421];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipAdEntAddr']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ipAdEntAddr']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['PortVlanIndex']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['PortVlanIndex']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['PortVlanIndex']['name'] = $LANG['plugin_fusioninventory']["mapping"][422];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['PortVlanIndex']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['PortVlanIndex']['dropdown'] = '';

   // Networking Ports

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifIndex']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifIndex']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifIndex']['name'] = $LANG['plugin_fusioninventory']["mapping"][408];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifIndex']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifIndex']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifmtu']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifmtu']['field'] = 'ifmtu';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifmtu']['name'] = $LANG['plugin_fusioninventory']["mapping"][4];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifmtu']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifmtu']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifspeed']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifspeed']['field'] = 'ifspeed';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifspeed']['name'] = $LANG['plugin_fusioninventory']["mapping"][5];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifspeed']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifspeed']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['field'] = 'ifinternalstatus';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['name'] = $LANG['plugin_fusioninventory']["mapping"][6];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinternalstatus']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['iflastchange']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['iflastchange']['field'] = 'iflastchange';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['iflastchange']['name'] = $LANG['plugin_fusioninventory']["mapping"][7];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['iflastchange']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['iflastchange']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinoctets']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinoctets']['field'] = 'ifinoctets';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinoctets']['name'] = $LANG['plugin_fusioninventory']["mapping"][8];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinoctets']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinoctets']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifoutoctets']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifoutoctets']['field'] = 'ifoutoctets';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifoutoctets']['name'] = $LANG['plugin_fusioninventory']["mapping"][9];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifoutoctets']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifoutoctets']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinerrors']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinerrors']['field'] = 'ifinerrors';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinerrors']['name'] = $LANG['plugin_fusioninventory']["mapping"][10];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinerrors']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifinerrors']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifouterrors']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifouterrors']['field'] = 'ifouterrors';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifouterrors']['name'] = $LANG['plugin_fusioninventory']["mapping"][11];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifouterrors']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifouterrors']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifstatus']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifstatus']['field'] = 'ifstatus';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifstatus']['name'] = $LANG['plugin_fusioninventory']["mapping"][14];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifstatus']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifstatus']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['table'] = 'glpi_networking_ports';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['field'] = 'ifmac';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['name'] = $LANG['plugin_fusioninventory']["mapping"][15];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifPhysAddress']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifName']['table'] = 'glpi_networking_ports';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifName']['field'] = 'name';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifName']['name'] = $LANG['plugin_fusioninventory']["mapping"][16];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifName']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifName']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifType']['table'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifType']['field'] = '';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifType']['name'] = $LANG['plugin_fusioninventory']["mapping"][18];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifType']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifType']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifdescr']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifdescr']['field'] = 'ifdescr';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifdescr']['name'] = $LANG['plugin_fusioninventory']["mapping"][23];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifdescr']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['ifdescr']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['portDuplex']['table'] = 'glpi_plugin_fusioninventory_networking_ports';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['portDuplex']['field'] = 'portduplex';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['portDuplex']['name'] = $LANG['plugin_fusioninventory']["mapping"][33];
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['portDuplex']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE]['portDuplex']['dropdown'] = '';



   // Printers

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['model']['table'] = 'glpi_printers';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['model']['field'] = 'model';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['model']['name'] = $LANG['plugin_fusioninventory']["mapping"][25];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['model']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['model']['dropdown'] = 'glpi_dropdown_model_printers';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['enterprise']['table'] = 'glpi_printers';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['enterprise']['field'] = 'FK_glpi_enterprise';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['enterprise']['name'] = $LANG['plugin_fusioninventory']["mapping"][420];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['enterprise']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['enterprise']['dropdown'] = 'glpi_dropdown_manufacturer';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['serial']['table'] = 'glpi_printers';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['serial']['field'] = 'serial';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['serial']['name'] = $LANG['plugin_fusioninventory']["mapping"][27];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['serial']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['serial']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['contact']['table'] = 'glpi_printers';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['contact']['field'] = 'contact';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['contact']['name'] = $LANG['plugin_fusioninventory']["mapping"][405];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['contact']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['contact']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comments']['table'] = 'glpi_printers';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comments']['field'] = 'comments';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comments']['name'] = $LANG['plugin_fusioninventory']["mapping"][406];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comments']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['comments']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['name']['table'] = 'glpi_printers';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['name']['field'] = 'name';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['name']['name'] = $LANG['plugin_fusioninventory']["mapping"][24];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['name']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['name']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['otherserial']['table'] = 'glpi_printers';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['otherserial']['field'] = 'otherserial';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['otherserial']['name'] = $LANG['plugin_fusioninventory']["mapping"][418];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['otherserial']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['otherserial']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['memory']['table'] = 'glpi_printers';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['memory']['field'] = 'ramSize';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['memory']['name'] = $LANG['plugin_fusioninventory']["mapping"][26];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['memory']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['memory']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['table'] = 'glpi_printers';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['field'] = 'location';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['name'] = $LANG['plugin_fusioninventory']["mapping"][56];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['location']['dropdown'] = 'glpi_dropdown_locations';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['informations']['name'] = $LANG['plugin_fusioninventory']["mapping"][165];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['informations']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][165];

   // NEW CARTRIDGE
      // Black
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblack']['name'] = $LANG['plugin_fusioninventory']["mapping"][157];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblack']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][157];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblackmax']['name'] = $LANG['plugin_fusioninventory']["mapping"][166];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblackmax']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][166];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblackused']['name'] = $LANG['plugin_fusioninventory']["mapping"][167];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblackused']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][167];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblackremaining']['name'] = $LANG['plugin_fusioninventory']["mapping"][168];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblackremaining']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][168];

      // Black 2
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblack2']['name'] = $LANG['plugin_fusioninventory']["mapping"][157]." 2";
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblack2']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][157]." 2";
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblack2max']['name'] = $LANG['plugin_fusioninventory']["mapping"][166]." 2";
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblack2max']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][166]." 2";
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblack2used']['name'] = $LANG['plugin_fusioninventory']["mapping"][167]." 2";
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblack2used']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][167]." 2";
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblack2remaining']['name'] = $LANG['plugin_fusioninventory']["mapping"][168]." 2";
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonerblack2remaining']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][168]." 2";

      // Cyan
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonercyan']['name'] = $LANG['plugin_fusioninventory']["mapping"][158];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonercyan']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][158];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonercyanmax']['name'] = $LANG['plugin_fusioninventory']["mapping"][169];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonercyanmax']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][169];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonercyanused']['name'] = $LANG['plugin_fusioninventory']["mapping"][170];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonercyanused']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][170];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonercyanremaining']['name'] = $LANG['plugin_fusioninventory']["mapping"][171];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonercyanremaining']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][171];

      // Magenta
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonermagenta']['name'] = $LANG['plugin_fusioninventory']["mapping"][159];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonermagenta']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][159];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonermagentamax']['name'] = $LANG['plugin_fusioninventory']["mapping"][172];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonermagentamax']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][172];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonermagentaused']['name'] = $LANG['plugin_fusioninventory']["mapping"][173];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonermagentaused']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][173];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonermagentaremaining']['name'] = $LANG['plugin_fusioninventory']["mapping"][174];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['tonermagentaremaining']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][174];

      // Yellow
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['toneryellow']['name'] = $LANG['plugin_fusioninventory']["mapping"][160];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['toneryellow']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][160];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['toneryellowmax']['name'] = $LANG['plugin_fusioninventory']["mapping"][175];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['toneryellowmax']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][175];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['toneryellowused']['name'] = $LANG['plugin_fusioninventory']["mapping"][176];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['toneryellowused']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][176];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['toneryellowremaining']['name'] = $LANG['plugin_fusioninventory']["mapping"][177];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['toneryellowremaining']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][177];

      // Waste
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['wastetoner']['name'] = $LANG['plugin_fusioninventory']["mapping"][151];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['wastetoner']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][151];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['wastetonermax']['name'] = $LANG['plugin_fusioninventory']["mapping"][190];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['wastetonermax']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][190];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['wastetonerused']['name'] = $LANG['plugin_fusioninventory']["mapping"][191];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['wastetonerused']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][191];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['wastetonerremaining']['name'] = $LANG['plugin_fusioninventory']["mapping"][192];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['wastetonerremaining']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][192];



      // Cartridge black
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeblack']['name'] = $LANG['plugin_fusioninventory']["mapping"][134];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeblack']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][134];

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeblackphoto']['name'] = $LANG['plugin_fusioninventory']["mapping"][135];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeblackphoto']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][135];

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgecyan']['name'] = $LANG['plugin_fusioninventory']["mapping"][136];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgecyan']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][136];

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgecyanlight']['name'] = $LANG['plugin_fusioninventory']["mapping"][139];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgecyanlight']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][139];

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgemagenta']['name'] = $LANG['plugin_fusioninventory']["mapping"][138];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgemagenta']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][138];

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgemagentalight']['name'] = $LANG['plugin_fusioninventory']["mapping"][140];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgemagentalight']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][140];

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeyellow']['name'] = $LANG['plugin_fusioninventory']["mapping"][137];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgeyellow']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][137];

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgegrey']['name'] = $LANG['plugin_fusioninventory']["mapping"][196];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['cartridgegrey']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][196];

      // maintenance kit
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['maintenancekit']['name'] = $LANG['plugin_fusioninventory']["mapping"][156];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['maintenancekit']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][156];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['maintenancekitmax']['name'] = $LANG['plugin_fusioninventory']["mapping"][193];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['maintenancekitmax']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][193];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['maintenancekitused']['name'] = $LANG['plugin_fusioninventory']["mapping"][194];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['maintenancekitused']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][194];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['maintenancekitremaining']['name'] = $LANG['plugin_fusioninventory']["mapping"][195];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['maintenancekitremaining']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][195];


      // Drum Black
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumblack']['name'] = $LANG['plugin_fusioninventory']["mapping"][161];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumblack']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][161];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumblackmax']['name'] = $LANG['plugin_fusioninventory']["mapping"][178];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumblackmax']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][178];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumblackused']['name'] = $LANG['plugin_fusioninventory']["mapping"][179];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumblackused']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][179];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumblackremaining']['name'] = $LANG['plugin_fusioninventory']["mapping"][180];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumblackremaining']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][180];

      // Drum cyan
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumcyan']['name'] = $LANG['plugin_fusioninventory']["mapping"][162];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumcyan']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][162];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumcyanmax']['name'] = $LANG['plugin_fusioninventory']["mapping"][181];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumcyanmax']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][181];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumcyanused']['name'] = $LANG['plugin_fusioninventory']["mapping"][182];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumcyanused']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][182];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumcyanremaining']['name'] = $LANG['plugin_fusioninventory']["mapping"][183];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumcyanremaining']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][183];

      // Drum magenta
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drummagenta']['name'] = $LANG['plugin_fusioninventory']["mapping"][163];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drummagenta']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][163];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drummagentamax']['name'] = $LANG['plugin_fusioninventory']["mapping"][184];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drummagentamax']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][184];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drummagentaused']['name'] = $LANG['plugin_fusioninventory']["mapping"][185];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drummagentaused']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][185];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drummagentaremaining']['name'] = $LANG['plugin_fusioninventory']["mapping"][186];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drummagentaremaining']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][186];

      // Drum yellow
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumyellow']['name'] = $LANG['plugin_fusioninventory']["mapping"][164];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumyellow']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][164];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumyellowmax']['name'] = $LANG['plugin_fusioninventory']["mapping"][187];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumyellowmax']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][187];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumyellowused']['name'] = $LANG['plugin_fusioninventory']["mapping"][188];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumyellowused']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][188];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumyellowremaining']['name'] = $LANG['plugin_fusioninventory']["mapping"][189];
      $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['drumyellowremaining']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][189];


   // Printers : Counter pages

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['table'] = 'glpi_plugin_fusioninventory_printers_history';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['field'] = 'pages_total';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['name'] = $LANG['plugin_fusioninventory']["mapping"][28];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][128];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['table'] = 'glpi_plugin_fusioninventory_printers_history';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['field'] = 'pages_n_b';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['name'] = $LANG['plugin_fusioninventory']["mapping"][29];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][129];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['table'] = 'glpi_plugin_fusioninventory_printers_history';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['field'] = 'pages_color';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['name'] = $LANG['plugin_fusioninventory']["mapping"][30];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][130];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['table'] = 'glpi_plugin_fusioninventory_printers_history';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['field'] = 'pages_recto_verso';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['name'] = $LANG['plugin_fusioninventory']["mapping"][54];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][154];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['table'] = 'glpi_plugin_fusioninventory_printers_history';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['field'] = 'scanned';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['name'] = $LANG['plugin_fusioninventory']["mapping"][55];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][155];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_print']['table'] = 'glpi_plugin_fusioninventory_printers_history';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_print']['field'] = 'pages_total_print';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_print']['name'] = $LANG['plugin_fusioninventory']["mapping"][423];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_print']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1423];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_print']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_print']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_print']['table'] = 'glpi_plugin_fusioninventory_printers_history';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_print']['field'] = 'pages_n_b_print';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_print']['name'] = $LANG['plugin_fusioninventory']["mapping"][424];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_print']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1424];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_print']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_print']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_print']['table'] = 'glpi_plugin_fusioninventory_printers_history';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_print']['field'] = 'pages_color_print';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_print']['name'] = $LANG['plugin_fusioninventory']["mapping"][425];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_print']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1425];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_print']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_print']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_copy']['table'] = 'glpi_plugin_fusioninventory_printers_history';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_copy']['field'] = 'pages_total_copy';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_copy']['name'] = $LANG['plugin_fusioninventory']["mapping"][426];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_copy']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1426];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_copy']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_copy']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_copy']['table'] = 'glpi_plugin_fusioninventory_printers_history';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_copy']['field'] = 'pages_n_b_copy';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_copy']['name'] = $LANG['plugin_fusioninventory']["mapping"][427];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_copy']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1427];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_copy']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterblackpages_copy']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_copy']['table'] = 'glpi_plugin_fusioninventory_printers_history';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_copy']['field'] = 'pages_color_copy';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_copy']['name'] = $LANG['plugin_fusioninventory']["mapping"][428];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_copy']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1428];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_copy']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountercolorpages_copy']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_fax']['table'] = 'glpi_plugin_fusioninventory_printers_history';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_fax']['field'] = 'pages_total_fax';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_fax']['name'] = $LANG['plugin_fusioninventory']["mapping"][429];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_fax']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1429];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_fax']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecountertotalpages_fax']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterlargepages']['table'] = 'glpi_plugin_fusioninventory_printers_history';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterlargepages']['field'] = '';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterlargepages']['name'] = $LANG['plugin_fusioninventory']["mapping"][434];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterlargepages']['shortname'] = $LANG['plugin_fusioninventory']["mapping"][1434];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterlargepages']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['pagecounterlargepages']['dropdown'] = '';

   // Printers : Networking

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['table'] = 'glpi_networking_ports';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['field'] = 'ifmac';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['name'] = $LANG['plugin_fusioninventory']["mapping"][58];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifPhysAddress']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['table'] = 'glpi_networking_ports';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['field'] = 'name';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['name'] = $LANG['plugin_fusioninventory']["mapping"][57];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifName']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifaddr']['table'] = 'glpi_networking_ports';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifaddr']['field'] = 'ifaddr';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifaddr']['name'] = $LANG['plugin_fusioninventory']["mapping"][407];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifaddr']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifaddr']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifType']['table'] = '';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifType']['field'] = '';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifType']['name'] = $LANG['plugin_fusioninventory']["mapping"][97];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifType']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifType']['dropdown'] = '';

   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifIndex']['table'] = '';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifIndex']['field'] = '';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifIndex']['name'] = $LANG['plugin_fusioninventory']["mapping"][416];
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifIndex']['type'] = 'text';
   $FUSIONINVENTORY_MAPPING[PRINTER_TYPE]['ifIndex']['dropdown'] = '';



   // Computer :

   $FUSIONINVENTORY_MAPPING[COMPUTER_TYPE]['serial']['field'] = 'serial';
   $FUSIONINVENTORY_MAPPING[COMPUTER_TYPE]['serial']['name'] = $LANG['plugin_fusioninventory']["mapping"][13];

   $FUSIONINVENTORY_MAPPING[COMPUTER_TYPE]['ifPhysAddress']['field'] = 'ifmac';
   $FUSIONINVENTORY_MAPPING[COMPUTER_TYPE]['ifPhysAddress']['name'] = $LANG['plugin_fusioninventory']["mapping"][15];

   $FUSIONINVENTORY_MAPPING[COMPUTER_TYPE]['ifaddr']['field'] = 'ifaddr';
   $FUSIONINVENTORY_MAPPING[COMPUTER_TYPE]['ifaddr']['name'] = $LANG['plugin_fusioninventory']["mapping"][407];



   $constantsfield = array();
   foreach ($FUSIONINVENTORY_MAPPING[NETWORKING_TYPE] as $fieldtype=>$array) {
      $constantsfield[$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE][$fieldtype]['name']] = $fieldtype;
   }
   echo "<center><table align='center' width='500'>";
   echo "<tr>";
   echo "<td>";
   echo "Converting history port ...";
   echo "</td>";
   echo "</tr>";
   echo "<tr>";
   echo "<td>";
   createProgressBar("Update Ports history");
   $i = 0;
   $nb = 0;
   $query = "SELECT *
             FROM `glpi_plugin_fusioninventory_snmp_history`
             WHERE `Field` != '0';";
   if ($result=$DB->query($query)) {
      $nb = $DB->numrows($result);
      $i = 0;
      while ($data=$DB->fetch_array($result)) {
         $i++;
         if ($data['Field'] == 'trunk') {
            $data['Field'] = 'vlanTrunkPortDynamicStatus';
         }
         if (isset($constantsfield[$data['Field']])) {
            $data['Field'] = $constantsfield[$data['Field']];
            $query_update = "UPDATE `glpi_plugin_fusioninventory_snmp_history`
               SET `Field`='".$data['Field']."'
               WHERE `ID`='".$data['ID']."' ";
            $DB->query($query_update);
         } else {
            $query_update = "UPDATE `glpi_plugin_fusioninventory_snmp_history`
               SET `Field`='".$data['Field']."'
               WHERE `ID`='".$data['ID']."' ";
            $DB->query($query_update);
         }
         if (preg_match("/000$/", $i)) {
            changeProgressBarPosition($i, $nb, "$i / $nb");
         }
      }
   }
   changeProgressBarPosition($i, $nb, "$i / $nb");
   echo "</td>";
   echo "</tr>";
   echo "</table></center>";


   // Move connections from glpi_plugin_fusioninventory_snmp_history to glpi_plugin_fusioninventory_snmp_history_connections

   echo "<br/><center><table align='center' width='500'>";
   echo "<tr>";
   echo "<td>";
   echo "Moving creation connections history ...";
   echo "</td>";
   echo "</tr>";
   echo "<tr>";
   echo "<td>";
   createProgressBar("Move create connections");
   $query = "SELECT *
             FROM `glpi_plugin_fusioninventory_snmp_history`
             WHERE `Field` = '0'
               AND ((`old_value` NOT LIKE '%:%')
                     OR (`old_value` IS NULL))";
   if ($result=$DB->query($query)) {
      $nb = $DB->numrows($result);
      $i = 0;
      changeProgressBarPosition($i, $nb, "$i / $nb");
      while ($data=$DB->fetch_array($result)) {
         $i++;

         // Search port from mac address
         $query_port = "SELECT * FROM `glpi_networkports`
            WHERE `mac`='".$data['new_value']."' ";
         if ($result_port=$DB->query($query_port)) {
            if ($DB->numrows($result_port) == '1') {
               $input = array();
               $data_port = $DB->fetch_assoc($result_port);
               $input['FK_port_source'] = $data_port['id'];

               $query_port2 = "SELECT * FROM `glpi_networkports`
                  WHERE `items_id` = '".$data['new_device_ID']."'
                     AND `itemtype` = '".$data['new_device_type']."' ";
               if ($result_port2=$DB->query($query_port2)) {
                  if ($DB->numrows($result_port2) == '1') {
                     $data_port2 = $DB->fetch_assoc($result_port2);
                     $input['FK_port_destination'] = $data_port2['id'];

                     $input['date'] = $data['date_mod'];
                     $input['creation'] = 1;
                     $input['process_number'] = $data['FK_process'];
                     $query_ins = "INSERT INTO `glpi_plugin_fusioninventory_snmp_history_connections`
                        (`process_number`, `date`, `creation`, `FK_port_source`, `FK_port_destination`)
                        VALUES ('".$input['process_number']."',
                                '".$input['date']."',
                                '".$input['creation']."',
                                '".$input['FK_port_source']."',
                                '".$input['FK_port_destination']."')";
                     $DB->query($query_ins);
                  }
               }
            }
         }

         $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_snmp_history`
               WHERE `ID`='".$data['ID']."' ";
         $DB->query($query_delete);
         if (preg_match("/000$/", $i)) {
            changeProgressBarPosition($i, $nb, "$i / $nb");
         }
      }
      changeProgressBarPosition($i, $nb, "$i / $nb");
   }
   echo "</td>";
   echo "</tr>";
   echo "</table></center>";


   echo "<br/><center><table align='center' width='500'>";
   echo "<tr>";
   echo "<td>";
   echo "Moving deleted connections history ...";
   echo "</td>";
   echo "</tr>";
   echo "<tr>";
   echo "<td>";
   createProgressBar("Move delete connections");
   $query = "SELECT *
             FROM `glpi_plugin_fusioninventory_snmp_history`
             WHERE `Field` = '0'
               AND ((`new_value` NOT LIKE '%:%')
                     OR (`new_value` IS NULL))";
   if ($result=$DB->query($query)) {
      $nb = $DB->numrows($result);
      $i = 0;
      changeProgressBarPosition($i, $nb, "$i / $nb");
      while ($data=$DB->fetch_array($result)) {
         $i++;

         // Search port from mac address
         $query_port = "SELECT * FROM `glpi_networking_ports`
            WHERE `ifmac`='".$data['old_value']."' ";
         if ($result_port=$DB->query($query_port)) {
            if ($DB->numrows($result_port) == '1') {
               $input = array();
               $data_port = $DB->fetch_assoc($result_port);
               $input['FK_port_source'] = $data_port['ID'];

               $query_port2 = "SELECT * FROM `glpi_networking_ports`
                  WHERE `on_device` = '".$data['old_device_ID']."'
                     AND `device_type` = '".$data['old_device_type']."' ";
               if ($result_port2=$DB->query($query_port2)) {
                  if ($DB->numrows($result_port2) == '1') {
                     $data_port2 = $DB->fetch_assoc($result_port2);
                     $input['FK_port_destination'] = $data_port2['ID'];

                     $input['date'] = $data['date_mod'];
                     $input['creation'] = 1;
                     $input['process_number'] = $data['FK_process'];
                     if ($input['FK_port_source'] != $input['FK_port_destination']) {
                        $query_ins = "INSERT INTO `glpi_plugin_fusioninventory_snmp_history_connections`
                           (`process_number`, `date`, `creation`, `FK_port_source`, `FK_port_destination`)
                           VALUES ('".$input['process_number']."',
                                   '".$input['date']."',
                                   '".$input['creation']."',
                                   '".$input['FK_port_source']."',
                                   '".$input['FK_port_destination']."')";
                        $DB->query($query_ins);
                     }
                  }
               }
            }
         }

         $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_snmp_history`
               WHERE `ID`='".$data['ID']."' ";
         $DB->query($query_delete);
         if (preg_match("/000$/", $i)) {
            changeProgressBarPosition($i, $nb, "$i / $nb");
         }
      }
      changeProgressBarPosition($i, $nb, "$i / $nb");
   }
   echo "</td>";
   echo "</tr>";
   echo "</table></center>";
   
}

?>