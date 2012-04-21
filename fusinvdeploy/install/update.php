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
   @co-author Alexandre Delaunay
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

function pluginFusinvdeployGetCurrentVersion($version) {

   if (!TableExists("glpi_plugin_fusinvdeploy_files")) {
      return false;
   }
   
   if (TableExists("glpi_plugin_fusioninventory_configs")) {
      if (!class_exists('PluginFusioninventoryConfig')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/config.class.php");
      }
      if (!class_exists('PluginFusioninventoryAgentmodule')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/agentmodule.class.php");
      }
      if (!class_exists('PluginFusioninventoryModule')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/module.class.php");
      }
      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvdeploy');
      $versionconfig = $PluginFusioninventoryConfig->getValue($plugins_id, "version");
      if (empty($versionconfig)) {
         return "";
      }
      return $versionconfig;
   } else {
      return false;
   }
}


function pluginFusinvdeployUpdate($current_version, $migrationname='Migration') {

   if ($current_version == "") {
      $config = new PluginFusioninventoryConfig;
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvdeploy');
      $a_plugin = plugin_version_fusinvdeploy();
      $params = array(
         'type'         => "version",
         'value'        => $a_plugin['version'],
         'plugins_id'   => $plugins_id
      );
      $config->add($params);

   }
}

?>