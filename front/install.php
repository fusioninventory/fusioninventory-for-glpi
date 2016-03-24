<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

 /**
 *  List of all FusionInventorys versions :
 *    1.0.0
 *    1.1.0 non exists glpi_plugin_fusioninventory_agents (MySQL)
 *    2.0.0 non exists glpi_plugin_fusioninventory_config_discovery (MySQL)
 *    2.0.1 Nothing
 *    2.0.2 config version field 2.0.2
 *    2.1.0 config version field 2.1.0
 **/

if (Session::haveRight('config', UPDATE)
        && Session::haveRight('profile', UPDATE)) {
   $config = new PluginFusioninventoryConfig();
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
      if ($config->getValue('version') == "2.0.2"
              || $config->getValue('version') == "2.1.0") {
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

   Html::back();
} else {

   Html::header(__('Access denied'), $_SERVER['PHP_SELF'], "plugins", "fusioninventory");
   echo "<div align='center'><br><br><img src=\"".$CFG_GLPI['root_doc']."/pics/warning.png\"
              alt=\"warning\"><br><br>";
   echo "<b>".__('Access denied')."</b></div>";
   Html::footer();
}

?>
