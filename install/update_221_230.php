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


// Update from 2.2.1 to 2.3.0
function update221to230() {
   global $DB, $CFG_GLPI;

   $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-2.3.0-update";
   $DBf_handle = fopen($DB_file, "rt");
   $sql_query = fread($DBf_handle, filesize($DB_file));
   fclose($DBf_handle);
   foreach ( explode(";\n", "$sql_query") as $sql_line) {
      if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
      if (!empty($sql_line)) {
         $DB->query($sql_line);
      }
   }

   // glpi_plugin_fusioninventory_configs
   $plugin = new Plugin();
   $data = $plugin->find("`name` = 'FusionInventory'");
   $fields = current($data);
   $plugins_id = $fields['id'];
   $url = str_replace("http:","https:",$CFG_GLPI["url_base"]);
   $query = "INSERT INTO `glpi_plugin_fusioninventory_configs`
                         (`type`, `value`, `plugins_id`)
             VALUES ('version', '2.3.0', '".$plugins_id."'),
                    ('URL_agent_conf', '".$url."', '".$plugins_id."'),
                    ('ssl_only', '0', '".$plugins_id."'),
                    ('inventory_frequence', '24', '".$plugins_id."'),
                    ('criteria1_ip', '0', '".$plugins_id."'),
                    ('criteria1_name', '0', '".$plugins_id."'),
                    ('criteria1_serial', '0', '".$plugins_id."'),
                    ('criteria1_macaddr', '0', '".$plugins_id."'),
                    ('criteria2_ip', '0', '".$plugins_id."'),
                    ('criteria2_name', '0', '".$plugins_id."'),
                    ('criteria2_serial', '0', '".$plugins_id."'),
                    ('criteria2_macaddr', '0', '".$plugins_id."'),
                    ('delete_agent_process', '24', '".$plugins_id."'),
                    ('remotehttpagent', '0', '".$plugins_id."'),
                    ('wol', '0', '".$plugins_id."');";
   $DB->query($query);

   //TODO
// Plugin::migrateItemType();


}

?>