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


function pluginFusioninventoryInstall($version) {
   global $DB,$LANG,$CFG_GLPI;

   // Get informations of plugin
   $a_plugin = plugin_version_fusioninventory();

   $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-"
              .$version."-empty.sql";
   $DBf_handle = fopen($DB_file, "rt");
   $sql_query = fread($DBf_handle, filesize($DB_file));
   fclose($DBf_handle);
   foreach ( explode(";\n", "$sql_query") as $sql_line) {
      if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
      if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
   }

   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
   }

   $plugin = new Plugin();
   $data = $plugin->find("`name` = 'FusionInventory'");
   $fields = current($data);
   $plugins_id = $fields['id'];
   $a_rights = array();
   $a_rights['agents'] = 'w';
//   $a_rights['agentsprocesses'] = 'w';
   $a_rights['remotecontrol'] = 'w';
   $a_rights['wol'] = 'w';
   $a_rights['configuration'] = 'w';
   PluginFusioninventoryProfile::initProfile($plugins_id,$a_rights);

   // glpi_plugin_fusioninventory_configs
   $query = "INSERT INTO `glpi_plugin_fusioninventory_configs`
                         (`type`, `value`, `plugins_id`)
             VALUES ('version', '".$version."', '".$plugins_id."'),
                    ('ssl_only', '0', '".$plugins_id."'),
                    ('delete_task', '24', '".$plugins_id."'),
                    ('inventory_frequence', '24', '".$plugins_id."');";
//                    ('criteria1_ip', '0', '".$plugins_id."'),
//                    ('criteria1_name', '0', '".$plugins_id."'),
//                    ('criteria1_serial', '0', '".$plugins_id."'),
//                    ('criteria1_macaddr', '0', '".$plugins_id."'),
//                    ('criteria2_ip', '0', '".$plugins_id."'),
//                    ('criteria2_name', '0', '".$plugins_id."'),
//                    ('criteria2_serial', '0', '".$plugins_id."'),
//                    ('criteria2_macaddr', '0', '".$plugins_id."'),
   $DB->query($query);

   PluginFusioninventoryProfile::changeProfile($plugins_id);
}

?>