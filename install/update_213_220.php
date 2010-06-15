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
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------


// Update from 2.1.3 to 2.2.0
function update213to220() {
   global $DB;

   $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-2.2.0-update";
   $DBf_handle = fopen($DB_file, "rt");
   $sql_query = fread($DBf_handle, filesize($DB_file));
   fclose($DBf_handle);
   foreach ( explode(";\n", "$sql_query") as $sql_line) {
      if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
      if (!empty($sql_line)) {
         $DB->query($sql_line);
      }
   }

   ini_set("memory_limit", "-1");
   ini_set("max_execution_time", "0");

   $config_modules = new PluginFusioninventoryConfigModules;
   $config_modules->initConfig();
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
   }
   // Update right
   PluginFusioninventory::updateaccess($_SESSION['glpiactiveprofile']['id']);

   // Delete old agents
   $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_agents`";
   $DB->query($query_delete);

   // Delete models
   $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_snmpmodels`";
   $DB->query($query_delete);

   // Import models
   $importexport = new PluginFusioninventoryImportExport;
//         include_once(GLPI_ROOT.'/inc/setup.function.php');
//         include_once(GLPI_ROOT.'/inc/rulesengine.function.php');
   foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0,1);

   // Update ports history from lang traduction into field constant (MySQL fiel 'Field')
   $pfisnmph = new PluginFusioninventoryNetworkPortLog;
   $pfisnmph->ConvertField();

   // Delete all values in glpi_plugin_fusioninventory_configlogfields
   $pficlf = new PluginFusioninventoryConfigLogField();
   $pficlf->initConfig();
   $pficlf->updateTrackertoFusion();
   // Delete all ports present in fusion but deleted in glpi_networking
   $query = "SELECT `glpi_plugin_fusioninventory_networkports`.`id` AS `fusinvId`
             FROM `glpi_plugin_fusioninventory_networkports`
                  LEFT JOIN `glpi_networkports`
                     ON `networkports_id`=`glpi_networkports`.`id`
             WHERE `glpi_networkports`.`id` IS NULL";
   if ($result=$DB->query($query)) {
      while ($data=$DB->fetch_array($result)) {
         $query_delete = "DELETE FROM `glpi_plugin_fusioninventory_networkports`
            WHERE `id`='".$data['fusinvId']."' ";
         $DB->query($query_delete);
      }
   }
   // Add IP of switch in table glpi_plugin_fusioninventory_networkequimentips if not present
   $query = "SELECT * FROM glpi_networkequipments";
   if ($result=$DB->query($query)) {
      while ($data=$DB->fetch_array($result)) {
         $query_ip = "SELECT * FROM `glpi_plugin_fusioninventory_networkequipmentips`
            WHERE `ip`='".$data['ip']."' ";
         $result_ip = $DB->query($query_ip);
         if ($DB->numrows($result_ip) == "0") {
            $query_add = "INSERT INTO `glpi_plugin_fusioninventory_networkequipmentips`
               (`networkequipments_id`, `ip`) VALUES ('".$data['id']."', '".$data['ip']."')";
            $DB->query($query_add);
         }
      }
   }

}
?>