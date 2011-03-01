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

// Update from 1.1.0 to 2.0.0
function update110to200() {
   global $DB;

   $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_tracker-2.0.0-update";
   $DBf_handle = fopen($DB_file, "rt");
   $sql_query = fread($DBf_handle, filesize($DB_file));
   fclose($DBf_handle);
   foreach ( explode(";\n", "$sql_query") as $sql_line) {
      if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
      if (!empty($sql_line)) {
         $DB->query($sql_line);
      }
   }

   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
   }

   //      $config_discovery = new PluginFusioninventoryConfigDiscovery;
   //      $config_discovery->initConfig();
   //      $config_snmp_script = new PluginFusioninventoryConfigSNMPScript;
   //      $config_snmp_script->initConfig();
         // Import models
   //      $importexport = new PluginFusioninventoryImportExport;
   //      foreach (glob(GLPI_ROOT.'/plugins/fusioninventory/models/*.xml') as $file) $importexport->import($file,0);
         // Clean DB (ports in glpi_plugin_fusioninventory_networkports..... )

}

?>