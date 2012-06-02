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
   @author    Vincent Mazzoni
   @co-author Alexandre Delaunay
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployConfig extends CommonDBTM {

   function initConfigModule() {
      global $DB;

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;
      $Config = new Config;
      $Config->getFromDB('1');

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvdeploy');

      $glpi_path = str_replace("http://", "", $Config->fields['url_base']);

      $insert = array('glpi_path'=>$glpi_path);
      $PluginFusioninventoryConfig->initConfig($plugins_id, $insert);
      
      $a_infos = plugin_version_fusinvdeploy();
      $PluginFusioninventoryConfig->initConfig($plugins_id, array('version' => $a_infos['version']));
      
   }

   function putForm($p_post) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvdeploy');
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'glpi_path', $p_post['glpi_path']);
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'server_upload_path', $p_post['server_upload_path']);
   }

   function showForm($options=array()) {
      global $LANG,$CFG_GLPI;

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig;

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvdeploy');

      echo "<form name='form' method='post' action='".$options['target']."'>";
      echo "<div class='center' id='tabsbody'>";
      echo "<table class='tab_cadre_fixe'>";

#      echo "<tr class='tab_bg_1'>";
#      echo "<td>".$LANG['plugin_fusinvdeploy']['config'][0]."&nbsp;:</td>";
#      echo "<td>";
#      echo "<input type='text' name='glpi_path' value='".$PluginFusioninventoryConfig->getValue($plugins_id, 'glpi_path')."' />";
#      echo "</td>";
#      echo "<td colspan='2'></td>";;
#      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusinvdeploy']['config'][1]."&nbsp;:</td>";
      echo "<td>";
      echo "<input type='text' name='server_upload_path' value='".$PluginFusioninventoryConfig->getValue($plugins_id, 'server_upload_path')."' size='60' />";
      echo "</td>";
      echo "<td colspan='2'></td>";;
      echo "</tr>";

      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration", "w")) {
         echo "<tr class='tab_bg_2'><td align='center' colspan='4'>
               <input class='submit' type='submit' name='plugin_fusinvdeploy_config_set'
                      value='" . $LANG['buttons'][7] . "'></td></tr>";
      }
      echo "</table>";
      echo "</form>";

      return true;
   }
}

?>