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
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..'); 
include (GLPI_ROOT . "/inc/includes.php");

 /**
 *  List of all FusionInventorys versions :
 *    1.0.0
 *    1.1.0 non exists glpi_plugin_fusioninventory_agents (MySQL)
 *    2.0.0 non exists glpi_plugin_fusioninventory_config_discovery (MySQL)
 *    2.0.1 Nothing
 *    2.0.2 config version field 2.0.2
 *    2.1.0 config version field 2.1.0
 **/

if (haveRight("config","w") && haveRight("profile","w")) {
   $config = new PluginFusioninventoryConfig;
   if (!TableExists("glpi_plugin_fusioninventory_agents")) {
      PluginFusioninventorySetup::update("1.1.0");
   }
   if (!TableExists("glpi_plugin_fusioninventory_config_discovery")) {
      PluginFusioninventorySetup::update("2.0.0");
   }
   if (!FieldExists("glpi_plugin_fusioninventory_configs", "version")) {
      PluginFusioninventorySetup::update("2.0.2");
   }
   if (FieldExists("glpi_plugin_fusioninventory_configs", "version")) {
      if ($config->getValue('version') == "2.0.2") {
         $DB->query("UPDATE `glpi_plugin_fusioninventory_configs` 
                     SET `version` = '2.1.1'
                     WHERE `id`=1");
      }
      if ($config->getValue('version') == "2.1.0") {
         $DB->query("UPDATE `glpi_plugin_fusioninventory_configs` 
                     SET `version` = '2.1.1'
                     WHERE `id`=1");
      }
      PluginFusioninventorySetup::update("2.0.2");
      if  ($config->getValue('version') == "0") {
         $DB->query("UPDATE `glpi_plugin_fusioninventory_configs` 
                     SET `version` = '2.1.1'
                     WHERE `id`=1");
      }
   }

   glpi_header($_SERVER['HTTP_REFERER']);
} else {

   commonHeader($LANG['login'][5],$_SERVER['PHP_SELF'],"plugins","fusioninventory");
   echo "<div align='center'><br><br><img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\"
              alt=\"warning\"><br><br>";
   echo "<b>".$LANG['login'][5]."</b></div>";
   commonFooter();
}

?>