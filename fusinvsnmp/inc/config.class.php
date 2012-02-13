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
   @co-author 
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


class PluginFusinvSNMPConfig extends CommonDBTM {

   function initConfigModule() {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
      $insert = array('storagesnmpauth'      => 'DB',
                      'version'              => PLUGIN_FUSINVSNMP_VERSION,
                      'threads_netdiscovery' => 1,
                      'threads_snmpquery'    => 1);
      $PluginFusioninventoryConfig->initConfig($plugins_id, $insert);
   }


   
   function putForm($p_post) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'storagesnmpauth', 
                                                     $p_post['storagesnmpauth']);
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'threads_netdiscovery',
                                                     $p_post['threads_netdiscovery']);
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, 'threads_snmpquery',
                                                     $p_post['threads_snmpquery']);
   }

   

   function showForm($options=array()) {
      global $LANG,$CFG_GLPI;

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');

      echo "<form name='form' method='post' action='".$options['target']."'>";
      echo "<div class='center' id='tabsbody'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][16]."&nbsp;:</td>";
      echo "<td>";
      $ArrayValues = array();
      $ArrayValues['DB']= $LANG['plugin_fusioninventory']['functionalities'][17];
      $ArrayValues['file']= $LANG['plugin_fusioninventory']['functionalities'][18];
      Dropdown::showFromArray('storagesnmpauth', $ArrayValues,
                              array('value'=>$PluginFusioninventoryConfig->getValue($plugins_id, 'storagesnmpauth')));
      echo "</td>";
      echo "<td colspan='2'></td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusinvsnmp']['agents'][24]."&nbsp;(".strtolower($LANG['plugin_fusinvsnmp']['config'][4]).")&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("threads_netdiscovery", $PluginFusioninventoryConfig->getValue($plugins_id, 'threads_netdiscovery'),1,400);
      echo "</td>";
      echo "<td>".$LANG['plugin_fusinvsnmp']['agents'][24]."&nbsp;(".strtolower($LANG['plugin_fusinvsnmp']['config'][3]).")&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("threads_snmpquery", $PluginFusioninventoryConfig->getValue($plugins_id, 'threads_snmpquery'),1,400);
      echo "</td>";
      echo "</tr>";


      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration", "w")) {
         echo "<tr class='tab_bg_2'><td align='center' colspan='4'>
               <input class='submit' type='submit' name='plugin_fusinvsnmp_config_set'
                      value='" . $LANG['buttons'][7] . "'></td></tr>";
      }
      echo "</table>";
      echo "</form><br/>";

      $PluginFusinvsnmpConfigLogField = new PluginFusinvsnmpConfigLogField();
      $PluginFusinvsnmpConfigLogField->showForm(array('target'=>$CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/functionalities.form.php"));

      return true;
   }
}

?>