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
 
   if (!isIndex("glpi_plugin_fusioninventory_snmp_history", "Field")) {
      $sql = "ALTER TABLE `glpi_plugin_fusioninventory_snmp_history`
         ADD INDEX ( `Field` )";
      $DB->query($sql);
   }


   // **** Migration network history connections
      plugin_fusioninventory_displayMigrationMessage("220", $LANG['update'][141]." - Migration network history connections");

      $query = "SELECT count(ID) FROM `glpi_plugin_fusioninventory_snmp_history`
                        WHERE `Field`='0'";
      $result = $DB->query($query);
      $datas = $DB->fetch_assoc($result);
      $nb = $datas['count(ID)'];

      echo "</td>";
      echo "</tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo "Move Connections history to another table...";
      createProgressBar("Move Connections history to another table");

      for ($i=0; $i < $nb; $i = $i + 500) {
         changeProgressBarPosition($i, $nb, "$i / $nb");
         $sql_connection = "SELECT * FROM `glpi_plugin_fusioninventory_snmp_history`
                           WHERE `Field`='0'
                           ORDER BY `FK_process` DESC, `date_mod` DESC
                           LIMIT 500";
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
                                LIMIT 1";
                  $resultPort = $DB->query($queryPort);
                  $dataPort = $DB->fetch_assoc($resultPort);
               } else if ($thread_connection["new_device_ID"] != "0") {
                  $queryPort = "SELECT *
                                FROM `glpi_networkports`
                                WHERE `mac`='".$thread_connection['new_value']."'
                                LIMIT 1";
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
//               $query_del = "DELETE FROM `glpi_plugin_fusioninventory_snmp_history`
//                  WHERE `ID`='".$thread_connection['ID']."'";
//               $DB->query($query_del);
            }
         }
      }
   $query_del = "DELETE FROM `glpi_plugin_fusioninventory_snmp_history`
      WHERE `Field`='0'
      AND (`old_device_ID`!='0' OR `new_device_ID`!='0')";
   $DB->query($query_del);
   changeProgressBarPosition($nb, $nb, "$nb / $nb");
   echo "</td>";
   echo "</tr>";
   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";
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

   $DB->query("UPDATE `glpi_plugin_fusioninventory_config`
      SET `version` = '2.2.0'
      WHERE `ID`=1
      LIMIT 1");

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
   $constantsfield = array();

   $constantsfield['reseaux > lieu'] = 'location';
   $constantsfield['networking > location'] = 'location';
   $constantsfield['Netzwerk > Standort'] = 'location';

   $constantsfield['réseaux > firmware'] = 'firmware';
   $constantsfield['networking > firmware'] = 'firmware';
   $constantsfield['Netzwerk > Firmware'] = 'firmware';
   
   $constantsfield['réseaux > firmware'] = 'firmware1';
   $constantsfield['networking > firmware'] = 'firmware1';
   $constantsfield['Netzwerk > Firmware'] = 'firmware1';

   $constantsfield['réseaux > firmware'] = 'firmware2';
   $constantsfield['networking > firmware'] = 'firmware2';
   $constantsfield['Netzwerk > Firmware'] = 'firmware2';

   $constantsfield['réseaux > contact'] = 'contact';
   $constantsfield['networking > contact'] = 'contact';
   $constantsfield['Netzwerk > Kontakt'] = 'contact';
   
   $constantsfield['réseaux > description'] = 'comments';
   $constantsfield['networking > comments'] = 'comments';
   $constantsfield['Netzwerk > Kommentar'] = 'comments';

   $constantsfield['réseaux > uptime'] = 'uptime';
   $constantsfield['networking > uptime'] = 'uptime';
   $constantsfield['Netzwerk > Uptime'] = 'uptime';

   $constantsfield['réseaux > utilisation du CPU'] = 'cpu';
   $constantsfield['networking > CPU usage'] = 'cpu';
   $constantsfield['Netzwerk > CPU Auslastung'] = 'cpu';

   $constantsfield['réseaux > CPU user'] = 'cpuuser';
   $constantsfield['networking > CPU usage (user)'] = 'cpuuser';
   $constantsfield['Netzwerk > CPU Benutzer'] = 'cpuuser';

   $constantsfield['réseaux > CPU système'] = 'cpusystem';
   $constantsfield['networking > CPU usage (system)'] = 'cpusystem';
   $constantsfield['Netzwerk > CPU System'] = 'cpusystem';

   $constantsfield['réseaux > numéro de série'] = 'serial';
   $constantsfield['networking > serial number'] = 'serial';
   $constantsfield['Netzwerk > Seriennummer'] = 'serial';

   $constantsfield['réseaux > numéro d\'inventaire'] = 'otherserial';
   $constantsfield['networking > Inventory number'] = 'otherserial';
   $constantsfield['Netzwerk > Inventarnummer'] = 'otherserial';

   $constantsfield['réseaux > nom'] = 'name';
   $constantsfield['networking > name'] = 'name';
   $constantsfield['Netzwerk > Name'] = 'name';

   $constantsfield['réseaux > mémoire totale'] = 'ram';
   $constantsfield['networking > total memory'] = 'ram';
   $constantsfield['Netzwerk > Gesamter Speicher'] = 'ram';

   $constantsfield['réseaux > mémoire libre'] = 'memory';
   $constantsfield['networking > free memory'] = 'memory';
   $constantsfield['Netzwerk > Freier Speicher'] = 'memory';

   $constantsfield['réseaux > VLAN'] = 'vtpVlanName';
   $constantsfield['networking > VLAN'] = 'vtpVlanName';
   $constantsfield['Netzwerk > VLAN'] = 'vtpVlanName';

   $constantsfield['réseaux > port > vlan'] = 'vmvlan';
   $constantsfield['networking > port > vlan'] = 'vmvlan';

   $constantsfield['réseaux > modèle'] = 'entPhysicalModelName';
   $constantsfield['networking > model'] = 'entPhysicalModelName';
   $constantsfield['Netzwerk > Modell'] = 'entPhysicalModelName';

   $constantsfield['réseaux > adresse MAC'] = 'macaddr';
   $constantsfield['networking > MAC address'] = 'macaddr';
   $constantsfield['Netzwerk > MAC Adresse'] = 'macaddr';

   $constantsfield['réseaux > Adresse CDP'] = 'cdpCacheAddress';
   $constantsfield['networking > CDP address'] = 'cdpCacheAddress';
   $constantsfield['Netzwerk > Adresse CDP'] = 'cdpCacheAddress';

   $constantsfield['réseaux > port CDP'] = 'cdpCacheDevicePort';
   $constantsfield['networking > CDP port'] = 'cdpCacheDevicePort';
   $constantsfield['Netzwerk > Port CDP'] = 'cdpCacheDevicePort';

   $constantsfield['réseaux > chassis id distant LLDP'] = 'lldpRemChassisId';
   $constantsfield['networking > remote chassis id LLDP'] = 'lldpRemChassisId';

   $constantsfield['réseaux > port distant LLDP'] = 'lldpRemPortId';
   $constantsfield['networking > remote port LLDP'] = 'lldpRemPortId';

   $constantsfield['réseaux > chassis id local LLDP'] = 'lldpLocChassisId';
   $constantsfield['networking > localchassis id LLDP'] = 'lldpLocChassisId';

   $constantsfield['réseaux > port > trunk/tagged'] = 'vlanTrunkPortDynamicStatus';
   $constantsfield['networking > port > trunk/tagged'] = 'vlanTrunkPortDynamicStatus';
   $constantsfield['Netzwerk > Port > trunk/tagged'] = 'vlanTrunkPortDynamicStatus';

   $constantsfield['trunk'] = 'vlanTrunkPortDynamicStatus';

   $constantsfield['réseaux > Adresses mac filtrées (dot1dTpFdbAddress)'] = 'dot1dTpFdbAddress';
   $constantsfield['networking > MAC address filters (dot1dTpFdbAddress)'] = 'dot1dTpFdbAddress';
   $constantsfield['Netzwerk > MAC Adressen Filter (dot1dTpFdbAddress)'] = 'dot1dTpFdbAddress';

   $constantsfield['réseaux > adresses physiques mémorisées (ipNetToMediaPhysAddress)'] = 'ipNetToMediaPhysAddress';
   $constantsfield['networking > Physical addresses in memory (ipNetToMediaPhysAddress)'] = 'ipNetToMediaPhysAddress';
   $constantsfield['Netzwerk > Physikalische Adressen im Speicher (ipNetToMediaPhysAddress)'] = 'ipNetToMediaPhysAddress';

   $constantsfield['réseaux > instances de ports (dot1dTpFdbPort)'] = 'dot1dTpFdbPort';
   $constantsfield['networking > Port instances (dot1dTpFdbPort)'] = 'dot1dTpFdbPort';
   $constantsfield['Netzwerk > Instanzen des Ports (dot1dTpFdbPort)'] = 'dot1dTpFdbPort';

   $constantsfield['réseaux > numéro de ports associé ID du port (dot1dBasePortIfIndex)'] = 'dot1dBasePortIfIndex';
   $constantsfield['networking > Port number associated with port ID (dot1dBasePortIfIndex)'] = 'dot1dBasePortIfIndex';
   $constantsfield['Netzwerk > Verkn&uuml;pfung der Portnummerierung mit der ID des Ports (dot1dBasePortIfIndex)'] = 'dot1dBasePortIfIndex';

   $constantsfield['réseaux > addresses IP'] = 'ipAdEntAddr';
   $constantsfield['networking > IP addresses'] = 'ipAdEntAddr';
   $constantsfield['Netzwerk > IP Adressen'] = 'ipAdEntAddr';

   $constantsfield['réseaux > portVlanIndex'] = 'PortVlanIndex';
   $constantsfield['networking > portVlanIndex'] = 'PortVlanIndex';
   $constantsfield['Netzwerk > portVlanIndex'] = 'PortVlanIndex';

   $constantsfield['réseaux > port > numéro index'] = 'ifIndex';
   $constantsfield['networking > port > index number'] = 'ifIndex';
   $constantsfield['Netzwerk > Port > Nummerischer Index'] = 'ifIndex';

   $constantsfield['réseaux > port > mtu'] = 'ifmtu';
   $constantsfield['networking > port > mtu'] = 'ifmtu';
   $constantsfield['Netzwerk > Port > MTU'] = 'ifmtu';

   $constantsfield['réseaux > port > vitesse'] = 'ifspeed';
   $constantsfield['networking > port > speed'] = 'ifspeed';
   $constantsfield['Netzwerk > Port > Geschwindigkeit'] = 'ifspeed';

   $constantsfield['réseaux > port > statut interne'] = 'ifinternalstatus';
   $constantsfield['networking > port > internal status'] = 'ifinternalstatus';
   $constantsfield['Netzwerk > Port > Interner Zustand'] = 'ifinternalstatus';

   $constantsfield['réseaux > port > Dernier changement'] = 'iflastchange';
   $constantsfield['networking > ports > Last change'] = 'iflastchange';
   $constantsfield['Netzwerk > Ports > Letzte &Auml;nderung'] = 'iflastchange';

   $constantsfield['réseaux > port > nombre d\'octets entrés'] = 'ifinoctets';
   $constantsfield['networking > port > number of bytes in'] = 'ifinoctets';
   $constantsfield['Netzwerk > Port > Anzahl eingegangene Bytes'] = 'ifinoctets';

   $constantsfield['réseaux > port > nombre d\'octets sortis'] = 'ifoutoctets';
   $constantsfield['networking > port > number of bytes out'] = 'ifoutoctets';
   $constantsfield['Netzwerk > Port > Anzahl ausgehende Bytes'] = 'ifoutoctets';

   $constantsfield['réseaux > port > nombre d\'erreurs entrées'] = 'ifinerrors';
   $constantsfield['networking > port > number of input errors'] = 'ifinerrors';
   $constantsfield['Netzwerk > Port > Anzahl Input Fehler'] = 'ifinerrors';

   $constantsfield['réseaux > port > nombre d\'erreurs sorties'] = 'ifouterrors';
   $constantsfield['networking > port > number of output errors'] = 'ifouterrors';
   $constantsfield['Netzwerk > Port > Anzahl Fehler Ausgehend'] = 'ifouterrors';

   $constantsfield['réseaux > port > statut de la connexion'] = 'ifstatus';
   $constantsfield['networking > port > connection status'] = 'ifstatus';
   $constantsfield['Netzwerk > Port > Verbingungszustand'] = 'ifstatus';

   $constantsfield['réseaux > port > adresse MAC'] = 'ifPhysAddress';
   $constantsfield['networking > port > MAC address'] = 'ifPhysAddress';
   $constantsfield['Netzwerk > Port > MAC Adresse'] = 'ifPhysAddress';

   $constantsfield['réseaux > port > nom'] = 'ifName';
   $constantsfield['networking > port > name'] = 'ifName';
   $constantsfield['Netzwerk > Port > Name'] = 'ifName';

   $constantsfield['réseaux > port > type'] = 'ifType';
   $constantsfield['networking > ports > type'] = 'ifType';
   $constantsfield['Netzwerk > Ports > Typ'] = 'ifType';

   $constantsfield['réseaux > port > description du port'] = 'ifdescr';
   $constantsfield['networking > port > port description'] = 'ifdescr';
   $constantsfield['Netzwerk > Port > Port Bezeichnung'] = 'ifdescr';

   $constantsfield['réseaux > port > type de duplex'] = 'portDuplex';
   $constantsfield['networking > port > duplex type'] = 'portDuplex';
   $constantsfield['Netzwerk > Port > Duplex Typ'] = 'portDuplex';

   $constantsfield['imprimante > modèle'] = 'model';
   $constantsfield['printer > model'] = 'model';
   $constantsfield['Drucker > Modell'] = 'model';

   $constantsfield['imprimante > fabricant'] = 'enterprise';
   $constantsfield['printer > manufacturer'] = 'enterprise';
   $constantsfield['Drucker > Hersteller'] = 'enterprise';

   $constantsfield['imprimante > numéro de série'] = 'serial';
   $constantsfield['printer > serial number'] = 'serial';
   $constantsfield['Drucker > Seriennummer'] = 'serial';

   $constantsfield['imprimante > contact'] = 'contact';
   $constantsfield['printer > contact'] = 'contact';
   $constantsfield['Drucker > Kontakt'] = 'contact';

   $constantsfield['imprimante > description'] = 'comments';
   $constantsfield['printer > comments'] = 'comments';
   $constantsfield['Drucker > Kommentar'] = 'comments';

   $constantsfield['imprimante > nom'] = 'name';
   $constantsfield['printer > name'] = 'name';
   $constantsfield['Drucker > Name'] = 'name';

   $constantsfield['imprimante > numéro d\'inventaire'] = 'otherserial';
   $constantsfield['printer > Inventory number'] = 'otherserial';
   $constantsfield['Drucker > Inventarnummer'] = 'otherserial';

   $constantsfield['imprimante > mémoire totale'] = 'memory';
   $constantsfield['printer > total memory'] = 'memory';
   $constantsfield['Drucker > Gesamter Speicher'] = 'memory';

   $constantsfield['imprimante > lieu'] = 'location';
   $constantsfield['printer > location'] = 'location';
   $constantsfield['Drucker > Standort'] = 'location';

   $constantsfield['Informations diverses regroupées'] = 'informations';
   $constantsfield['Many informations grouped'] = 'informations';
   $constantsfield['Many informations grouped'] = 'informations';

   $constantsfield['Toner Noir'] = 'tonerblack';
   $constantsfield['Black toner'] = 'tonerblack';

   $constantsfield['Toner Noir Max'] = 'tonerblackmax';
   $constantsfield['Black toner Max'] = 'tonerblackmax';

   $constantsfield['Toner Noir Utilisé'] = 'tonerblackused';

   $constantsfield['Toner Noir Restant'] = 'tonerblackremaining';

   $constantsfield['Toner Noir'] = 'tonerblack2';
   $constantsfield['Black toner'] = 'tonerblack2';

   $constantsfield['Toner Noir Max'] = 'tonerblack2max';
   $constantsfield['Black toner Max'] = 'tonerblack2max';

   $constantsfield['Toner Noir Utilisé'] = 'tonerblack2used';

   $constantsfield['Toner Noir Restant'] = 'tonerblack2remaining';

   $constantsfield['Toner Cyan'] = 'tonercyan';
   $constantsfield['Cyan toner'] = 'tonercyan';

   $constantsfield['Toner Cyan Max'] = 'tonercyanmax';
   $constantsfield['Cyan toner Max'] = 'tonercyanmax';

   $constantsfield['Toner Cyan Utilisé'] = 'tonercyanused';

   $constantsfield['Toner Cyan Restant'] = 'tonercyanremaining';

   $constantsfield['Toner Magenta'] = 'tonermagenta';
   $constantsfield['Magenta toner'] = 'tonermagenta';

   $constantsfield['Toner Magenta Max'] = 'tonermagentamax';
   $constantsfield['Magenta toner Max'] = 'tonermagentamax';

   $constantsfield['Toner Magenta Utilisé'] = 'tonermagentaused';
   $constantsfield['Magenta toner Utilisé'] = 'tonermagentaused';

   $constantsfield['Toner Magenta Restant'] = 'tonermagentaremaining';
   $constantsfield['Magenta toner Restant'] = 'tonermagentaremaining';

   $constantsfield['Toner Jaune'] = 'toneryellow';
   $constantsfield['Yellow toner'] = 'toneryellow';

   $constantsfield['Toner Jaune Max'] = 'toneryellowmax';
   $constantsfield['Yellow toner Max'] = 'toneryellowmax';

   $constantsfield['Toner Jaune Utilisé'] = 'toneryellowused';
   $constantsfield['Yellow toner Utilisé'] = 'toneryellowused';

   $constantsfield['Toner Jaune Restant'] = 'toneryellowremaining';
   $constantsfield['Yellow toner Restant'] = 'toneryellowremaining';

   $constantsfield['Bac récupérateur de déchet'] = 'wastetoner';
   $constantsfield['Waste bin'] = 'wastetoner';
   $constantsfield['Abfalleimer'] = 'wastetoner';

   $constantsfield['Bac récupérateur de déchet Max'] = 'wastetonermax';
   $constantsfield['Waste bin Max'] = 'wastetonermax';

   $constantsfield['Bac récupérateur de déchet Utilisé'] = 'wastetonerused';
   $constantsfield['Waste bin Utilisé'] = 'wastetonerused';

   $constantsfield['Bac récupérateur de déchet Restant'] = 'wastetonerremaining';
   $constantsfield['Waste bin Restant'] = 'wastetonerremaining';

   $constantsfield['Cartouche noir'] = 'cartridgeblack';
   $constantsfield['Black ink cartridge'] = 'cartridgeblack';
   $constantsfield['Schwarze Kartusche'] = 'cartridgeblack';

   $constantsfield['Cartouche noir photo'] = 'cartridgeblackphoto';
   $constantsfield['Photo black ink cartridge'] = 'cartridgeblackphoto';
   $constantsfield['Photoschwarz Kartusche'] = 'cartridgeblackphoto';

   $constantsfield['Cartouche cyan'] = 'cartridgecyan';
   $constantsfield['Cyan ink cartridge'] = 'cartridgecyan';
   $constantsfield['Cyan Kartusche'] = 'cartridgecyan';

   $constantsfield['Cartouche cyan clair'] = 'cartridgecyanlight';
   $constantsfield['Light cyan ink cartridge'] = 'cartridgecyanlight';
   $constantsfield['Leichtes Cyan Kartusche'] = 'cartridgecyanlight';

   $constantsfield['Cartouche magenta'] = 'cartridgemagenta';
   $constantsfield['Magenta ink cartridge'] = 'cartridgemagenta';
   $constantsfield['Magenta Kartusche'] = 'cartridgemagenta';

   $constantsfield['Cartouche magenta clair'] = 'cartridgemagentalight';
   $constantsfield['Light ink magenta cartridge'] = 'cartridgemagentalight';
   $constantsfield['Leichtes Magenta Kartusche'] = 'cartridgemagentalight';

   $constantsfield['Cartouche jaune'] = 'cartridgeyellow';
   $constantsfield['Yellow ink cartridge'] = 'cartridgeyellow';
   $constantsfield['Gelbe Kartusche'] = 'cartridgeyellow';

   $constantsfield['Cartouche grise'] = 'cartridgegrey';
   $constantsfield['Grey ink cartridge'] = 'cartridgegrey';
   $constantsfield['Grey ink cartridge'] = 'cartridgegrey';

   $constantsfield['Kit de maintenance'] = 'maintenancekit';
   $constantsfield['Maintenance kit'] = 'maintenancekit';
   $constantsfield['Wartungsmodul'] = 'maintenancekit';

   $constantsfield['Kit de maintenance Max'] = 'maintenancekitmax';
   $constantsfield['Maintenance kit Max'] = 'maintenancekitmax';

   $constantsfield['Kit de maintenance Utilisé'] = 'maintenancekitused';
   $constantsfield['Maintenance kit Utilisé'] = 'maintenancekitused';

   $constantsfield['Kit de maintenance Restant'] = 'maintenancekitremaining';
   $constantsfield['Maintenance kit Restant'] = 'maintenancekitremaining';

   $constantsfield['Tambour Noir'] = 'drumblack';
   $constantsfield['Black drum'] = 'drumblack';

   $constantsfield['Tambour Noir Max'] = 'drumblackmax';
   $constantsfield['Black drum Max'] = 'drumblackmax';

   $constantsfield['Tambour Noir Utilisé'] = 'drumblackused';
   $constantsfield['Black drum Utilisé'] = 'drumblackused';

   $constantsfield['Tambour Noir Restant'] = 'drumblackremaining';
   $constantsfield['Black drum Restant'] = 'drumblackremaining';

   $constantsfield['Tambour Cyan'] = 'drumcyan';
   $constantsfield['Cyan drum'] = 'drumcyan';

   $constantsfield['Tambour Cyan Max'] = 'drumcyanmax';
   $constantsfield['Cyan drum Max'] = 'drumcyanmax';

   $constantsfield['Tambour Cyan Utilisé'] = 'drumcyanused';
   $constantsfield['Cyan drum Utilisé'] = 'drumcyanused';

   $constantsfield['Tambour Cyan Restant'] = 'drumcyanremaining';
   $constantsfield['Cyan drumRestant'] = 'drumcyanremaining';

   $constantsfield['Tambour Magenta'] = 'drummagenta';
   $constantsfield['Magenta drum'] = 'drummagenta';

   $constantsfield['Tambour Magenta Max'] = 'drummagentamax';
   $constantsfield['Magenta drum Max'] = 'drummagentamax';

   $constantsfield['Tambour Magenta Utilisé'] = 'drummagentaused';
   $constantsfield['Magenta drum Utilisé'] = 'drummagentaused';

   $constantsfield['Tambour Magenta Restant'] = 'drummagentaremaining';
   $constantsfield['Magenta drum Restant'] = 'drummagentaremaining';

   $constantsfield['Tambour Jaune'] = 'drumyellow';
   $constantsfield['Yellow drum'] = 'drumyellow';

   $constantsfield['Tambour Jaune Max'] = 'drumyellowmax';
   $constantsfield['Yellow drum Max'] = 'drumyellowmax';

   $constantsfield['Tambour Jaune Utilisé'] = 'drumyellowused';
   $constantsfield['Yellow drum Utilisé'] = 'drumyellowused';

   $constantsfield['Tambour Jaune Restant'] = 'drumyellowremaining';
   $constantsfield['Yellow drum Restant'] = 'drumyellowremaining';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées'] = 'pagecountertotalpages';
   $constantsfield['printer > meter > total number of printed pages'] = 'pagecountertotalpages';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten'] = 'pagecountertotalpages';

   $constantsfield['imprimante > compteur > nombre de pages noir et blanc imprimées'] = 'pagecounterblackpages';
   $constantsfield['printer > meter > number of printed black and white pages'] = 'pagecounterblackpages';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedrucker Schwarz/Wei&szlig; Seiten'] = 'pagecounterblackpages';

   $constantsfield['imprimante > compteur > nombre de pages couleur imprimées'] = 'pagecountercolorpages';
   $constantsfield['printer > meter > number of printed color pages'] = 'pagecountercolorpages';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Farbseiten'] = 'pagecountercolorpages';

   $constantsfield['imprimante > compteur > nombre de pages recto/verso imprimées'] = 'pagecounterrectoversopages';
   $constantsfield['printer > meter > number of printed duplex pages'] = 'pagecounterrectoversopages';
   $constantsfield['Drucker > Messung > Anzahl der gedruckten Duplex Seiten'] = 'pagecounterrectoversopages';

   $constantsfield['imprimante > compteur > nombre de pages scannées'] = 'pagecounterscannedpages';
   $constantsfield['printer > meter > nomber of scanned pages'] = 'pagecounterscannedpages';
   $constantsfield['Drucker > Messung > Anzahl der gescannten Seiten'] = 'pagecounterscannedpages';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées (impression)'] = 'pagecountertotalpages_print';
   $constantsfield['printer > meter > total number of printed pages (print mode)'] = 'pagecountertotalpages_print';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten (Druck)'] = 'pagecountertotalpages_print';
   
   $constantsfield['imprimante > compteur > nombre de pages noir et blanc imprimées (impression)'] = 'pagecounterblackpages_print';
   $constantsfield['printer > meter > number of printed black and white pages (print mode)'] = 'pagecounterblackpages_print';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seiten (Druck)'] = 'pagecounterblackpages_print';

   $constantsfield['imprimante > compteur > nombre de pages couleur imprimées (impression)'] = 'pagecountercolorpages_print';
   $constantsfield['printer > meter > number of printed color pages (print mode)'] = 'pagecountercolorpages_print';
   $constantsfield['Drucker > Messung > Gesamtanzahl farbig gedruckter Seiten (Druck)'] = 'pagecountercolorpages_print';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées (copie)'] = 'pagecountertotalpages_copy';
   $constantsfield['printer > meter > total number of printed pages (copy mode)'] = 'pagecountertotalpages_copy';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten (Kopie)'] = 'pagecountertotalpages_copy';

   $constantsfield['imprimante > compteur > nombre de pages noir et blanc imprimées (copie)'] = 'pagecounterblackpages_copy';
   $constantsfield['printer > meter > number of printed black and white pages (copy mode)'] = 'pagecounterblackpages_copy';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seite (Kopie)'] = 'pagecounterblackpages_copy';

   $constantsfield['imprimante > compteur > nombre de pages couleur imprimées (copie)'] = 'pagecountercolorpages_copy';
   $constantsfield['printer > meter > number of printed color pages (copy mode)'] = 'pagecountercolorpages_copy';
   $constantsfield['Drucker > Messung > Gesamtanzahl farbig gedruckter Seiten (Kopie)'] = 'pagecountercolorpages_copy';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées (fax)'] = 'pagecountertotalpages_fax';
   $constantsfield['printer > meter > total number of printed pages (fax mode)'] = 'pagecountertotalpages_fax';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten (Fax)'] = 'pagecountertotalpages_fax';

   $constantsfield['imprimante > compteur > nombre total de pages larges imprimées'] = 'pagecounterlargepages';
   $constantsfield['printer > meter > total number of large printed pages'] = 'pagecounterlargepages';

   $constantsfield['imprimante > port > adresse MAC'] = 'ifPhysAddress';
   $constantsfield['printer > port > MAC address'] = 'ifPhysAddress';
   $constantsfield['Drucker > Port > MAC Adresse'] = 'ifPhysAddress';

   $constantsfield['imprimante > port > nom'] = 'ifName';
   $constantsfield['printer > port > name'] = 'ifName';
   $constantsfield['Drucker > Port > Name'] = 'ifName';

   $constantsfield['imprimante > port > adresse IP'] = 'ifaddr';
   $constantsfield['printer > port > IP address'] = 'ifaddr';
   $constantsfield['Drucker > Port > IP Adresse'] = 'ifaddr';

   $constantsfield['imprimante > port > type'] = 'ifType';
   $constantsfield['printer > port > type'] = 'ifType';
   $constantsfield['Drucker > port > Typ'] = 'ifType';

   $constantsfield['imprimante > port > numéro index'] = 'ifIndex';
   $constantsfield['printer > port > index number'] = 'ifIndex';
   $constantsfield['Drucker > Port > Indexnummer'] = 'ifIndex';


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
   $nb = count($constantsfield);

   foreach($constantsfield as $langvalue=>$mappingvalue) {
      $i++;
      $query_update = "UPDATE `glpi_plugin_fusioninventory_snmp_history`
         SET `Field`='".$mappingvalue."'
         WHERE `Field`=\"".$langvalue."\" ";
      $DB->query($query_update);
      changeProgressBarPosition($i, $nb, "$i / $nb");
   }

//   $query = "SELECT *
//             FROM `glpi_plugin_fusioninventory_snmp_history`
//             WHERE `Field` != '0';";
//   if ($result=$DB->query($query)) {
//      $nb = $DB->numrows($result);
//      $i = 0;
//      while ($data=$DB->fetch_array($result)) {
//         $i++;
//         if ($data['Field'] == 'trunk') {
//            $data['Field'] = 'vlanTrunkPortDynamicStatus';
//         }
//         if (isset($constantsfield[$data['Field']])) {
//            $data['Field'] = $constantsfield[$data['Field']];
//            $query_update = "UPDATE `glpi_plugin_fusioninventory_snmp_history`
//               SET `Field`='".$data['Field']."'
//               WHERE `ID`='".$data['ID']."' ";
//            $DB->query($query_update);
//         } else {
//            $query_update = "UPDATE `glpi_plugin_fusioninventory_snmp_history`
//               SET `Field`='".$data['Field']."'
//               WHERE `ID`='".$data['ID']."' ";
//            $DB->query($query_update);
//         }
//         changeProgressBarPosition($i, $nb, "$i / $nb");
//      }
//   }
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
         $query_port = "SELECT * FROM `glpi_networkports`
            WHERE `mac`='".$data['old_value']."' ";
         if ($result_port=$DB->query($query_port)) {
            if ($DB->numrows($result_port) == '1') {
               $input = array();
               $data_port = $DB->fetch_assoc($result_port);
               $input['FK_port_source'] = $data_port['id'];

               $query_port2 = "SELECT * FROM `glpi_networkports`
                  WHERE `items_id` = '".$data['old_device_ID']."'
                     AND `itemtype` = '".$data['old_device_type']."' ";
               if ($result_port2=$DB->query($query_port2)) {
                  if ($DB->numrows($result_port2) == '1') {
                     $data_port2 = $DB->fetch_assoc($result_port2);
                     $input['FK_port_destination'] = $data_port2['id'];

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