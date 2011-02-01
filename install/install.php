<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------


function pluginFusinvsnmpInstall() {
   global $DB,$LANG;


   // Get informations of plugin
   $a_plugin = plugin_version_fusinvsnmp();

   include (GLPI_ROOT . "/plugins/fusinvsnmp/install/update.php");
   $version_detected = pluginfusinvsnmpGetCurrentVersion($a_plugin['version']);
   if ((isset($version_detected)) AND ($version_detected != $a_plugin['version'])) {
      // Update
      pluginFusinvsnmpUpdate();
   } else {
      // Installation

      // Create database
      $DB_file = GLPI_ROOT ."/plugins/fusinvsnmp/install/mysql/plugin_fusinvsnmp-".$a_plugin['version']."-empty.sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach ( explode(";\n", $sql_query) as $sql_line) {
         if (get_magic_quotes_runtime())
            $sql_line=stripslashes_deep($sql_line);
         if (!empty($sql_line))
            $DB->query($sql_line)/* or die($DB->error())*/;
      }

      // Create folder in GLPI_PLUGIN_DOC_DIR
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
         mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'].'/tmp');
      }

      $configLogField = new PluginFusinvsnmpConfigLogField();
      $configLogField->initConfig();

      // Import models
      $importexport = new PluginFusinvsnmpImportExport;
      
      $nb = 0;
      foreach (glob(GLPI_ROOT.'/plugins/fusinvsnmp/models/*.xml') as $file) {
         $nb++;
      }
      $i = 0;
      echo "<table class='tab_cadre'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th align='center'>";
      echo "Import SNMP models";
      echo "</th>";
      echo "</tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      createProgressBar("Import SNMP models");
      foreach (glob(GLPI_ROOT.'/plugins/fusinvsnmp/models/*.xml') as $file) {
         $importexport->import($file,0,1);
         $i++;
         changeProgressBarPosition($i,$nb,"$i / $nb");
      }
      echo "</td>";
      echo "</table>";

      $plugins_id = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);
      PluginFusioninventoryProfile::initProfile($a_plugin['shortname'], $plugins_id);

      $configSNMP = new PluginFusinvSNMPConfig;
      $configSNMP->initConfigModule();
      // Creation config values
//      PluginFusioninventoryConfig::add($modules_id, type, value);

      PluginFusioninventoryProfile::changeProfile($plugins_id);
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "SNMPQUERY";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $PluginFusioninventoryAgentmodule->add($input);
      
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "NETDISCOVERY";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $PluginFusioninventoryAgentmodule->add($input);

   }
}


function pluginFusinvsnmpUninstall() {
   global $DB;

   // Get informations of plugin
   $a_plugin = plugin_version_fusinvsnmp();

   $PluginFusioninventorySetup = new PluginFusioninventorySetup();

   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
      $PluginFusioninventorySetup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
   }

   PluginFusioninventoryProfile::cleanProfile($a_plugin['shortname']);

   $query = "SHOW TABLES;";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if (strstr($data[0],"glpi_plugin_".$a_plugin['shortname']."_")){
         $query_delete = "DROP TABLE `".$data[0]."`;";
         $DB->query($query_delete) or die($DB->error());
      }
   }

   $query="DELETE FROM `glpi_displaypreferences`
           WHERE `itemtype` LIKE 'PluginFusinvsnmp%';";
   $DB->query($query) or die($DB->error());
//   $a_netports = $np->find("`itemtype`='PluginFusioninventoryUnknownDevice' ");
//   foreach ($a_netports as $NetworkPort){
//      $np->cleanDBonPurge($NetworkPort['id']);
//      $np->deleteFromDB($NetworkPort['id']);
//   }
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');

   $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
   $PluginFusioninventoryAgentmodule->deleteModule($plugins_id);


   $config = new PluginFusioninventoryConfig;
   $config->cleanConfig($plugins_id);
   return true;
}

?>
