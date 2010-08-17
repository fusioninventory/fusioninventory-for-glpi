<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

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

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

// Installation function
function plugin_fusioninventory_installing($version) {
	global $DB,$LANG;

	$DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-".$version."-empty.sql";
	$DBf_handle = fopen($DB_file, "rt");
	$sql_query = fread($DBf_handle, filesize($DB_file));
	fclose($DBf_handle);
	foreach ( explode(";\n", "$sql_query") as $sql_line) {
		if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
		if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
	}

	cleanCache("GLPI_HEADER_".$_SESSION["glpiID"]);
	plugin_fusioninventory_createfirstaccess($_SESSION['glpiactiveprofile']['ID']);
	if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
		mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
	}
	
	$config = new PluginFusionInventoryConfig;
	$config->initConfig($version);
	$config_modules = new PluginFusionInventoryConfigModules;
	$config_modules->initConfig();
	$config_snmp_networking = new PluginFusionInventoryConfigSNMPNetworking;
	$config_snmp_networking->initConfig();
   $config_history = new PluginFusionInventoryConfigSNMPHistory;
   $config_history->initConfig();

	// Import models
	$importexport = new PluginFusionInventoryImportExport;
	include(GLPI_ROOT.'/inc/setup.function.php');
	include(GLPI_ROOT.'/inc/rulesengine.function.php');
	foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0,1);
	
	plugin_fusioninventory_initSession();
   return true;
}


function plugin_fusioninventory_update($version) {
	global $DB,$LANG;

   if ((file_exists(GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-".$version."-update.sql"))
         OR (file_exists(GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_tracker-".$version."-update.sql"))){
   	if (file_exists(GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-".$version."-update.sql")) {
         $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-".$version."-update.sql";
      } else if (file_exists(GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_tracker-".$version."-update.sql")) {
         $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_tracker-".$version."-update.sql";
      }
      $DBf_handle = fopen($DB_file, "rt");
   	$sql_query = fread($DBf_handle, filesize($DB_file));
   	fclose($DBf_handle);
      foreach ( explode(";\n", "$sql_query") as $sql_line) {
         if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
         if (!empty($sql_line)) {
            $DB->query($sql_line);
         }
      }
   }
	if ($version == "2.0.0") {
		if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
			mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
		}
//		$config_discovery = new PluginFusionInventoryConfigDiscovery;
//		$config_discovery->initConfig();
//		$config_snmp_script = new PluginFusionInventoryConfigSNMPScript;
//		$config_snmp_script->initConfig();
		// Import models
//		$importexport = new PluginFusionInventoryImportExport;
//		foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0);
		// Clean DB (ports in glpi_plugin_fusioninventory_networking_ports..... )
		
	}
	if ($version == "2.0.2") {
		// Migrate unknown mac address in unknown device (MySQL table)
//		$ptud = new PluginFusionInventoryUnknownDevice;
//		$ptud->updateFromOldVersion_unknown_mac;
		// Delete MySQL table "glpi_plugin_fusioninventory_unknown_mac"
		$DB->query("DROP TABLE `glpi_plugin_tracker_unknown_mac`;");
		$DB->query("UPDATE `glpi_plugin_tracker_config`
                  SET `version` = '2.0.2'
                  WHERE `ID`='1'
                  LIMIT 1 ;");
	}
   if ($version == "2.1.0") {
      $DB->query("UPDATE `glpi_plugin_tracker_networking`
                  SET `last_PID_update` = '0';");
      $DB->query("UPDATE `glpi_plugin_tracker_config`
                  SET `version` = '2.1.0'
                  WHERE `ID`='1'
                  LIMIT 1 ;");
   }
   if ($version == "2.1.1") {
      $DB->query("UPDATE `glpi_plugin_tracker_config`
                  SET `version` = '2.1.1'
                  WHERE `ID`=1
                  LIMIT 1 ;");
   }
   if ($version == "2.1.2") {
      $DB->query("UPDATE `glpi_plugin_tracker_config`
                  SET `version` = '2.1.3'
                  WHERE `ID`=1
                  LIMIT 1 ;");
      //plugin_fusioninventory_clean_db();
   }
   if ($version == "2.1.3") {
      $DB->query("UPDATE `glpi_plugin_tracker_config`
                  SET `version` = '2.2.0'
                  WHERE `ID`=1
                  LIMIT 1 ;");
      ini_set("memory_limit","-1");
      ini_set("max_execution_time", "0");
      $pthc = new PluginFusionInventoryHistoryConnections;
      $pthc->migration();
      plugin_fusioninventory_clean_db();
   }
   if ($version == "2.2.0") {
      ini_set("memory_limit", "-1");
      ini_set("max_execution_time", "0");
      
      $config_modules = new PluginFusionInventoryConfigModules;
   	$config_modules->initConfig();
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
      }
      // Update right
      plugin_fusioninventory_updateaccess($_SESSION['glpiactiveprofile']['ID']);

      // Delete old agents
      $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_agents`";
      $DB->query($query_delete);

      // Delete models
      $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_model_infos`";
      $DB->query($query_delete);

      // Import models
      $importexport = new PluginFusionInventoryImportExport;
      include(GLPI_ROOT.'/inc/setup.function.php');
      include(GLPI_ROOT.'/inc/rulesengine.function.php');
      foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0,1);

      // Update ports history from lang traduction into field constant (MySQL fiel 'Field')
      $pfisnmph = new PluginFusionInventorySNMPHistory;
      $pfisnmph->ConvertField();

      // Delete all values in glpi_plugin_fusioninventory_config_snmp_history
      $pficsnmph = new PluginFusionInventoryConfigSNMPHistory;
      $pficsnmph->initConfig();
      $pficsnmph->updateTrackertoFusion();

      // Delete all ports present in fusion but deleted in glpi_networking
      $query = "SELECT glpi_plugin_fusioninventory_networking_ports.ID AS fusinvID FROM `glpi_plugin_fusioninventory_networking_ports`
         LEFT JOIN `glpi_networking_ports` ON FK_networking_ports=glpi_networking_ports.ID
         WHERE glpi_networking_ports.ID IS NULL";
      if ($result=$DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networking_ports`
               WHERE `ID`='".$data['fusinvID']."' ";
            $DB->query($query_delete);

         }
      }
      // Add IP of switch in table glpi_plugin_fusioninventory_networking_ifaddr if not present
      $query = "SELECT * FROM glpi_networking";
      if ($result=$DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            $query_ifaddr = "SELECT * FROM `glpi_plugin_fusioninventory_networking_ifaddr`
               WHERE `ifaddr`='".$data['ifaddr']."' ";
            $result_ifaddr = $DB->query($query_ifaddr);
            if ($DB->numrows($result_ifaddr) == "0") {
               $query_add = "INSERT INTO `glpi_plugin_fusioninventory_networking_ifaddr`
                  (`FK_networking`, `ifaddr`) VALUES ('".$data['ID']."', '".$data['ifaddr']."')";
               $DB->query($query_add);
            }
         }
      }
   }
   if ($version == "2.2.1") {
      // Clean ifaddr fusion when FK_networking has been delete (bug from Tracker 2.1.3 and before)
      $query = "SELECT glpi_plugin_fusioninventory_networking_ifaddr.*
      FROM glpi_plugin_fusioninventory_networking_ifaddr
      left join glpi_networking on FK_networking=glpi_networking.ID
      WHERE glpi_networking.ID is null";
      if ($result=$DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            $query_delete = "DELETE FROM glpi_plugin_fusioninventory_networking_ifaddr
               WHERE ID='".$data['ID']."' ";
            $DB->query($query_delete);
         }
      }
      // delete when ifaddr not weel valid (bug from Tracker 2.1.3 and before)
      $query = "SELECT * FROM glpi_plugin_fusioninventory_networking_ifaddr";
      if ($result=$DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            if (!preg_match("/^((25[0-5]|2[0-4]\d|1?\d?\d).){3}(25[0-5]|2[0-4]\d|1?\d?\d)$/",$data['ifaddr'])) {
               $query_delete = "DELETE FROM glpi_plugin_fusioninventory_networking_ifaddr
                  WHERE ID='".$data['ID']."' ";
               $DB->query($query_delete);
            }
         }
      }

      // locations with entity -1 (bad code)
      $query = "DELETE FROM glpi_dropdown_locations
         WHERE FK_entities='-1' ";
      $DB->query($query);

      //CLean glpi_display
      $query = "DELETE FROM glpi_display
         WHERE type='".PLUGIN_FUSIONINVENTORY_MODEL."'
         AND num NOT IN (1, 30, 3, 5, 6, 7, 8)";
      $DB->query($query);
      $query = "DELETE FROM glpi_display
         WHERE type='".PLUGIN_FUSIONINVENTORY_SNMP_AUTH."'
         AND num NOT IN (1, 30, 3, 4, 5, 7, 8, 9, 10)";
      $DB->query($query);
      $query = "DELETE FROM glpi_display
         WHERE type='".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."'
         AND num NOT IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19)";
      $DB->query($query);
      $query = "DELETE FROM glpi_display
         WHERE type='".PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS."'
         AND num NOT IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15)";
      $DB->query($query);
      $query = "DELETE FROM glpi_display
         WHERE type='".PLUGIN_FUSIONINVENTORY_SNMP_AGENTS."'
         AND num NOT IN (1, 30, 4, 6, 8, 9, 10, 11, 12, 13, 14, 15)";
      $DB->query($query);
      $query = "DELETE FROM glpi_display
         WHERE type='".PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP."'
         AND num NOT IN (1, 2, 3, 30, 5, 6, 7, 8, 9)";
      $DB->query($query);
      $query = "DELETE FROM glpi_display
         WHERE type='".PLUGIN_FUSIONINVENTORY_SNMP_HISTORY."'
         AND num NOT IN (1, 2, 3, 4, 5, 6)";
      $DB->query($query);
      $query = "DELETE FROM glpi_display
         WHERE type='".PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2."'
         AND num NOT IN (30, 1, 2, 3)";
      $DB->query($query);
   }
   if ($version == "2.2.2") {
      // Update & add model list
         $importexport = new PluginFusionInventoryImportExport;
         include(GLPI_ROOT.'/inc/setup.function.php');
         include(GLPI_ROOT.'/inc/rulesengine.function.php');
         foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0,1);

      // Update models for printers and switches
      $PluginFusionInventoryModelInfos = new PluginFusionInventoryModelInfos;
      $PluginFusionInventoryModelInfos->modifyModelDefined("1035120", "5547209", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("1268668", "7327673", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("1274893", "4111321", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("1435609", "2075377", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("1485438", "7714675", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("1612246", "638207", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("1635677", "1263137", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("2209837", "7051162", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("2325676", "5487096", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("2442408", "5379162", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("2531750", "5101291", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("2854950", "7362423", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("3235433", "548869", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("3355590", "8800076", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("3381134", "8800076", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("3802712", "9581305", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("3811618", "1377379", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("3818287", "2778418", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("3881228", "38638", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("3978040", "1816151", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("4150935", "7779300", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("4191096", "4455187", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("423548", "9769689", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("4287623", "943883", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("4715578", "4111321", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("478103", "9237473", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("4789519", "5630446", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("4816050", "7731164", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("5217313", "9449988", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("523939", "7951579", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("5246672", "8965910", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("5264937", "9216976", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("533006", "7579925", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("5475348", "5910705", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("5737879", "4678398", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("5784626", "2011692", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("5847307", "1747304", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("5905240", "9743586", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("6085529", "8668453", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("6171176", "6363084", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("6599346", "38638", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("6831406", "8965910", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("6982457", "4453057", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("701135", "5639995", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("7105398", "7805253", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("711451", "4901782", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("7122512", "1942119", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("7409071", "9372497", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("7446064", "7951579", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("7449716", "3050163", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("7457538", "6363084", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("7767627", "7030328", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("7883129", "9449988", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("8001607", "4280668", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("8047251", "4981364", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("8049199", "5451695", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("8103516", "4901782", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("8112312", "5910705", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("816731", "5303197", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("8295590", "5910705", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("8497286", "1559737", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("8635846", "8800076", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("8661591", "4564877", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("8713464", "1718358", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("8992438", "100911", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("9071791", "8336819", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("9092720", "1568308", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("9265755", "6013357", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("9303532", "9449988", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("9359113", "3592450", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("9642868", "6430101", 1);
      $PluginFusionInventoryModelInfos->modifyModelDefined("9933282", "3594228", 1);

      $a_model = $PluginFusionInventoryModelInfos->find("`name`='1409900'");
      foreach ($a_model as $id=>$data) {
         $PluginFusionInventoryModelInfos->deleteFromDB($id);
      }
      $a_model = $PluginFusionInventoryModelInfos->find("`name`='3080441'");
      foreach ($a_model as $id=>$data) {
         $PluginFusionInventoryModelInfos->deleteFromDB($id);
      }
      $a_model = $PluginFusionInventoryModelInfos->find("`name`='3283574'");
      foreach ($a_model as $id=>$data) {
         $PluginFusionInventoryModelInfos->deleteFromDB($id);
      }
      $a_model = $PluginFusionInventoryModelInfos->find("`name`='3692917'");
      foreach ($a_model as $id=>$data) {
         $PluginFusionInventoryModelInfos->deleteFromDB($id);
      }
      $a_model = $PluginFusionInventoryModelInfos->find("`name`='4141487'");
      foreach ($a_model as $id=>$data) {
         $PluginFusionInventoryModelInfos->deleteFromDB($id);
      }
      $a_model = $PluginFusionInventoryModelInfos->find("`name`='6936229'");
      foreach ($a_model as $id=>$data) {
         $PluginFusionInventoryModelInfos->deleteFromDB($id);
      }
      $a_model = $PluginFusionInventoryModelInfos->find("`name`='8328709'");
      foreach ($a_model as $id=>$data) {
         $PluginFusionInventoryModelInfos->deleteFromDB($id);
      }

      $a_sysdescr_reload = array();
      $a_sysdescr_reload[] = "NRG MP C2500 1.59 / NRG Network Printer C model / NRG Network Scanner C model";
      $a_sysdescr_reload[] = "NRG SP C410DN 1.09 / NRG Network Printer C model";
      $a_sysdescr_reload[] = "Dell Laser Printer 1720dn version NM.NA.N094a-p1 kernel 2.6.10 All-N-1";
      $a_sysdescr_reload[] = "Dell Laser Printer 5210n version NS.NP.N212 kernel 2.6.6 All-N-1";
      $a_sysdescr_reload[] = "Dell Laser Printer 5210n version NS.NP.N224 kernel 2.6.6 All-N-1";
      $a_sysdescr_reload[] = "Lexmark Optra T614  Version 3.14.16  Ethernet 10/100.";
      $a_sysdescr_reload[] = "Lexmark T620  Version 5.20.26  FaxSCSI-Ethernet.";
      $a_sysdescr_reload[] = "Lexmark T632 version 55.00.39 kernel 2.4.0-test6 All-N-1";
      $a_sysdescr_reload[] = "Brother NC-6100h, Firmware Ver.1.01  (03.10.09),MID 84UZ51";
      $a_sysdescr_reload[] = "Brother NC-6100h, Firmware Ver.1.03  (04.03.29),MID 84UZ51";
      $a_sysdescr_reload[] = "Dell Laser Printer 5210n version NS.NP.N240 kernel 2.6.6 All-N-1";
      $a_sysdescr_reload[] = "Dell Laser Printer 5310n version NS.NP.N224 kernel 2.6.6 All-N-1";
      $a_sysdescr_reload[] = "Samsung CLP-610 Series; OS 1.29.01.22 11-23-2007;Engine 1.00.83;NIC V4.01.01(CLP-610) 10-01-2007;S/N 4D21B1BPC00621X";
      $a_sysdescr_reload[] = "Xerox Phaser 6280DN; NIC 13.40,ESS 200903261050,IOT 05.04.00,Boot 200805161055";
      $a_sysdescr_reload[] = "Brother NC-3100h, Firmware Ver.1.20  (02.07.12),MID 84UZ34,FID 3";
      $a_sysdescr_reload[] = "Brother NC-3100h, Firmware Ver.3.20  (00.08.31)";
      $a_sysdescr_reload[] = "Brother NC-5100h, Firmware Ver.1.50  (04.04.19),MID 84UZ74,FID 1";
      $a_sysdescr_reload[] = "Brother NC-6400h, Firmware Ver.1.04  (05.11.10),MID 84UZ92";
      $a_sysdescr_reload[] = "Brother NC-6400h, Firmware Ver.1.11b  (07.05.16),MID 84UZ92";
      $a_sysdescr_reload[] = "Brother NC-6800h, Firmware Ver.1.01  (08.12.12),MID 84UB03";

      $a_sysdescr_reload[] = "D-Link Access Point";
      $a_sysdescr_reload[] = "EMC DS-4400M Model 001 Fibre Channel Switch, firmware 08.01.01 4";
      $a_sysdescr_reload[] = "Ethernet Routing Switch 4550T-PWR HW:03 FW:5.1.0.7 SW:v5.1.0.000 BN:00 (c) Nortel Networks";
      $a_sysdescr_reload[] = "Ethernet Switch 425-24T HW:06 FW:3.5.0.2 SW:v3.5.0.06 BN:6 (c) Nortel Networks";
      $a_sysdescr_reload[] = "Cisco IOS Software, Catalyst 4500 L3 Switch Software (cat4500-ENTSERVICES-M), Version 12.2(52)SG, RELEASE SOFTWARE (fc1)";
      $a_sysdescr_reload[] = "Cisco IOS Software, Catalyst 4500 L3 Switch Software (cat4500-ENTSERVICESK9-M), Version 12.2(37)SG1, RELEASE SOFTWARE (fc2)";
      $a_sysdescr_reload[] = "Cisco IOS Software, s72033_rp Software (s72033_rp-ADVIPSERVICESK9_WAN-M), Version 12.2(33)SXH6, RELEASE SOFTWARE (fc1)";
      $a_sysdescr_reload[] = "Cisco IOS Software, s72033_rp Software (s72033_rp-ADVIPSERVICESK9_WAN-M), Version 12.2(33)SXI, RELEASE SOFTWARE (fc2)";
      $a_sysdescr_reload[] = "IBM OS/400 V4R5M0";
      $a_sysdescr_reload[] = "IBM PowerPC CHRP Computer";
      $a_sysdescr_reload[] = "ITIUM 4030";
      $a_sysdescr_reload[] = "Microsoft Windows CE Version 5.0 (Build 0)";
      $a_sysdescr_reload[] = "ProCurve j9020a Switch 2510-48, revision U.11.08, ROM R.10.06 (/sw/code/build/dosx(ndx))";
      $a_sysdescr_reload[] = "Ethernet Routing Switch 5520-48T-PWR HW:34 FW:5.0.0.4 SW:v5.1.0.014 BN:14 (c) Nortel Networks";
      $a_sysdescr_reload[] = "Cisco IOS Software, s72033_rp Software (s72033_rp-ADVIPSERVICESK9_WAN-M), Version 12.2(33)SXH4, RELEASE SOFTWARE (fc1)";
      $a_sysdescr_reload[] = "Cisco IOS Software, s72033_rp Software (s72033_rp-ADVIPSERVICESK9_WAN-M), Version 12.2(33)SXI3, RELEASE SOFTWARE (fc2)";
      $a_sysdescr_reload[] = "Dell Laser Printer 1720dn version NM.NA.N094a-p1 kernel 2.6.10 All-N-1";
      $a_sysdescr_reload[] = "Dell Laser Printer 5210n version NS.NP.N212 kernel 2.6.6 All-N-1";
      $a_sysdescr_reload[] = "Dell Laser Printer 5210n version NS.NP.N224 kernel 2.6.6 All-N-1";
      $a_sysdescr_reload[] = "TOSHIBA TEC B-EV4";
      $a_sysdescr_reload[] = "Brother NC-3100h, Firmware Ver.1.20 (02.07.12),MID 84UZ34,FID 3";
      $a_sysdescr_reload[] = "Brother NC-3100h, Firmware Ver.3.20 (00.08.31)";
      $a_sysdescr_reload[] = "Brother NC-5100h, Firmware Ver.1.50 (04.04.19),MID 84UZ74,FID 1";
      $a_sysdescr_reload[] = "Brother NC-6400h, Firmware Ver.1.04 (05.11.10),MID 84UZ92";
      $a_sysdescr_reload[] = "Brother NC-6400h, Firmware Ver.1.11b (07.05.16),MID 84UZ92";
      $a_sysdescr_reload[] = "Brother NC-6800h, Firmware Ver.1.01 (08.12.12),MID 84UB03";
      $a_sysdescr_reload[] = "NRG MP C2500 1.59 / NRG Network Printer C model / NRG Network Scanner C model";
      $a_sysdescr_reload[] = "NRG SP C410DN 1.09 / NRG Network Printer C model";
      $a_sysdescr_reload[] = "Lexmark Optra T614 Version 3.14.16 Ethernet 10/100.";
      $a_sysdescr_reload[] = "Lexmark T620 Version 5.20.26 FaxSCSI-Ethernet.";
      $a_sysdescr_reload[] = "Lexmark T632 version 55.00.39 kernel 2.4.0-test6 All-N-1";
      $a_sysdescr_reload[] = "Lexmark T644 version NS.NP.N219 kernel 2.6.6 All-N-1";
      $a_sysdescr_reload[] = "Samsung CLP-610 Series; OS 1.29.01.22 11-23-2007;Engine 1.00.83;NIC V4.01.01(CLP-610) 10-01-2007;S/N 4D21B1BPC00621X";
      $a_sysdescr_reload[] = "Xerox Phaser 6280DN; NIC 13.40,ESS 200903261050,IOT 05.04.00,Boot 200805161055";
      $a_sysdescr_reload[] = "Brother NC-4100h, Firmware Ver.1.01 (02.09.06),MID 84TU07,FID 5";
      $a_sysdescr_reload[] = "HP LaserJet 4050 Series";
      $a_sysdescr_reload[] = "DesignJet 800PS (C7780C)";
      $a_sysdescr_reload[] = "hp color LaserJet 5550";
      $a_sysdescr_reload[] = "Brother NC-6400h, Firmware Ver.1.12 (06.04.20),MID 8C5-B35,FID 2";
      $a_sysdescr_reload[] = "Brother NC-6400h, Firmware Ver.1.08 (06.05.08),MID 84UZ93";
      $a_sysdescr_reload[] = "Brother NC-6400h, Firmware Ver.1.09 (06.07.13),MID 84UZ93";
      $a_sysdescr_reload[] = "Brother NC-6200h, Firmware Ver.G ,MID 8C5-A45,FID 2";
      $a_sysdescr_reload[] = "Xerox WorkCentre M20i ; OS 1.22 Engine 4.1.08 NIC V2.22(M20i) DADF 1.04";
      $a_sysdescr_reload[] = "HP LaserJet 1200";
      $a_sysdescr_reload[] = "Canon iR2020 /P";
      $a_sysdescr_reload[] = "Dell Laser Printer 5210n version NS.NP.N240 kernel 2.6.6 All-N-1";
      $a_sysdescr_reload[] = "Dell Laser Printer 5310n version NS.NP.N224 kernel 2.6.6 All-N-1";
      $a_sysdescr_reload[] = "KONICA MINOLTA 362";
      $a_sysdescr_reload[] = "HP LaserJet 3052";
      $a_sysdescr_reload[] = "Brother NC-6100h, Firmware Ver.1.01 (03.10.09),MID 84UZ51";
      $a_sysdescr_reload[] = "Brother NC-6100h, Firmware Ver.1.03 (04.03.29),MID 84UZ51";

      foreach($a_sysdescr_reload as $num=>$sysdescr) {
         $PluginFusionInventoryModelInfos->getrightmodelBySysdescr($sysdescr);
      }
   }

   // Remote IP of switch ports
   $query = "UPDATE `glpi_networking_ports`
      SET `ifaddr` = NULL
      WHERE device_type =2
         AND ifaddr IS NOT NULL ";
   $DB->query($query);
   
	plugin_fusioninventory_initSession();
}



// Uninstallation function
function plugin_fusioninventory_uninstall() {
   global $DB;

   $np = new Netport;

   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
      if($dir = @opendir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
         $current_dir = GLPI_PLUGIN_DOC_DIR.'/fusioninventory/';
         while (($f = readdir($dir)) !== false) {
            if($f > '0' and filetype($current_dir.$f) == "file") {
               unlink($current_dir.$f);
            } else if ($f > '0' and filetype($current_dir.$f) == "dir") {
               delTree($current_dir.$f);
            }
         }
         closedir($dir);
         rmdir($current_dir);
      }
   }

	$query = "SHOW TABLES;";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if ((strstr($data[0],"glpi_dropdown_plugin_fusioninventory_"))
         OR (strstr($data[0],"glpi_plugin_fusioninventory_"))){
         $query_delete = "DROP TABLE `".$data[0]."`;";
         $DB->query($query_delete) or die($DB->error());
      }
   }

	$query="DELETE FROM `glpi_display`
           WHERE `type`='".PLUGIN_FUSIONINVENTORY_ERROR_TYPE."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_MODEL."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_AUTH."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_PRINTERS_CARTRIDGES."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_AGENTS."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_AGENTS_PROCESSES."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_HISTORY."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_NETWORKING_PORTS2."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_SNMP_CONFIG."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_TASK."'
                 OR `type`='".PLUGIN_FUSIONINVENTORY_CONSTRUCT_DEVICE."' ;";
	$DB->query($query) or die($DB->error());


   $a_netports = $np->find("`device_type`='".PLUGIN_FUSIONINVENTORY_MAC_UNKNOWN."' ");
   foreach ($a_netports as $netport){
      $np->cleanDBonPurge($netport['ID']);
      $np->deleteFromDB($netport['ID']);
   }
}


function delTree($dir) {
    $files = glob( $dir . '*', GLOB_MARK );
    foreach( $files as $file ){
        if( is_dir( $file ) )
            delTree( $file );
        else
            unlink( $file );
    }

    if (is_dir($dir)) rmdir( $dir );

}

?>