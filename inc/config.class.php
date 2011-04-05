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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryConfig extends CommonDBTM {


   /**
   * Initialize config values of fusinvinventory plugin
   *
   *@return nothing
   *
   **/
   function initConfigModule() {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');

      $input = array();
      $input['import_monitor']         = 2;
      $input['import_printer']         = 2;
      $input['import_peripheral']      = 2;
      $input['import_software']        = 1;
      $input['import_volume']          = 1;
      $input['import_antivirus']       = 1;
      $input['import_registry']        = 1;
      $input['import_process']         = 1;
      $input['import_vm']              = 1;
      $input['component_processor']    = 1;
      $input['component_memory']       = 1;
      $input['component_harddrive']    = 1;
      $input['component_networkcard']  = 1;
      $input['component_graphiccard']  = 1;
      $input['component_soundcard']    = 1;
      $input['component_drive']        = 1;
      $input['component_control']      = 1;
      $input['transfers_id_auto']      = 1;
      $a_infos = plugin_version_fusinvinventory();
      $input['version']                = $a_infos['version'];
      $input['location']               = 0;

      foreach ($input as $key => $value) {
         $PluginFusioninventoryConfig->initConfig($plugins_id, array($key => $value));
      }
   }



   /**
   * Update values from tab in fusioninventory configuration form
   *
   * @param $p_post array values of config to update
   *
   * @return nothing
   *
   **/
	function putForm($p_post) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');
      foreach ($p_post as $key => $value) {
         if ((strstr($key, "component_"))
                 OR (strstr($key, "import_"))
                 OR (strstr($key, "location"))) {

            $PluginFusioninventoryConfig->updateConfigType($plugins_id, $key, $value);
         }
      }
   }



   /**
   * Display form for config tab in fusioninventory config form
   *
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($options=array()) {
      global $LANG;

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');

      echo "<form name='form' method='post' action='".$options['target']."'>";
      echo "<table class='tab_cadre_fixe' cellpadding='1'>";
 
      echo "<tr>";
      echo "<th colspan='4'>".$LANG['plugin_fusinvinventory']['setup'][20];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['Menu'][3];
      echo "</td>";
      echo "<td>";
      $array = array();
      $array[0] = $LANG['plugin_fusinvinventory']['setup'][23];
      $array[1] = $LANG['plugin_fusinvinventory']['setup'][22];
      $array[2] = $LANG['plugin_fusinvinventory']['setup'][24];
      $array[3] = $LANG['plugin_fusinvinventory']['setup'][27];
      Dropdown::showFromArray("import_monitor", $array, 
                              array('value' => 
                                 $PluginFusioninventoryConfig->getValue($plugins_id, 
                                                                        'import_monitor')));
      echo "</td>";
      echo "<th colspan='2'>";
      echo $LANG['plugin_fusinvinventory']['setup'][21];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['Menu'][2];
      echo "</td>";
      echo "<td>";
      $array = array();
      $array[0] = $LANG['plugin_fusinvinventory']['setup'][23];
      $array[1] = $LANG['plugin_fusinvinventory']['setup'][22];
      $array[2] = $LANG['plugin_fusinvinventory']['setup'][24];
      $array[3] = $LANG['plugin_fusinvinventory']['setup'][27];
      Dropdown::showFromArray("import_printer", $array, 
                              array('value' => 
                                 $PluginFusioninventoryConfig->getValue($plugins_id, 
                                                                        'import_printer')));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][4];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_processor", 
                          $PluginFusioninventoryConfig->getValue($plugins_id, 'component_processor'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['Menu'][16];
      echo "</td>";
      echo "<td>";
      $array = array();
      $array[0] = $LANG['plugin_fusinvinventory']['setup'][23];
      $array[1] = $LANG['plugin_fusinvinventory']['setup'][22];
      $array[2] = $LANG['plugin_fusinvinventory']['setup'][24];
      $array[3] = $LANG['plugin_fusinvinventory']['setup'][27];
      Dropdown::showFromArray("import_peripheral", $array, 
                              array('value' => 
                                       $PluginFusioninventoryConfig->getValue($plugins_id, 
                                                                              'import_peripheral')));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][6];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_memory", $PluginFusioninventoryConfig->getValue($plugins_id, 'component_memory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['Menu'][4];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_software", $PluginFusioninventoryConfig->getValue($plugins_id, 'import_software'));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][1];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_harddrive", $PluginFusioninventoryConfig->getValue($plugins_id, 'component_harddrive'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['computers'][8];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_volume", $PluginFusioninventoryConfig->getValue($plugins_id, 'import_volume'));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][3];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_networkcard", $PluginFusioninventoryConfig->getValue($plugins_id, 'component_networkcard'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['plugin_fusinvinventory']['antivirus'][0];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_antivirus", $PluginFusioninventoryConfig->getValue($plugins_id, 'import_antivirus'));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][2];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_graphiccard", $PluginFusioninventoryConfig->getValue($plugins_id, 'component_graphiccard'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['plugin_fusinvinventory']['setup'][25];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_registry", $PluginFusioninventoryConfig->getValue($plugins_id, 'import_registry'));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][7];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_soundcard", $PluginFusioninventoryConfig->getValue($plugins_id, 'component_soundcard'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['plugin_fusinvinventory']['setup'][26];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_process", $PluginFusioninventoryConfig->getValue($plugins_id, 'import_process'));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][19];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_drive", $PluginFusioninventoryConfig->getValue($plugins_id, 'component_drive'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['computers'][57];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_vm", 
                          $PluginFusioninventoryConfig->getValue($plugins_id, 'import_vm'));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][20];
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_control", $PluginFusioninventoryConfig->getValue($plugins_id, 'component_control'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['common'][15];
      echo "</td>";
      echo "<td>";
      Dropdown::showFromArray("location",
                              array("0"=>"------",
                                    "1"=>$LANG['plugin_fusinvinventory']['rule'][8]),
                              array('value'=>$PluginFusioninventoryConfig->getValue($plugins_id, 'location')));
      echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<th colspan='4'>".$LANG['plugin_fusinvinventory']['setup'][28];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'>";
      echo $LANG['plugin_fusinvinventory']['setup'][29]."&nbsp:";
      echo "</td>";
      echo "<td colspan='2'>";
      Dropdown::show("Transfer",
                     array('name'=>"transfers_id_auto",
                           'value'=>$PluginFusioninventoryConfig->getValue($plugins_id, 'transfers_id_auto'),
                           'comment'=>0));
      echo "</td>";
      echo "</tr>";

      
      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration", "w")) {
         echo "<tr class='tab_bg_2'><td align='center' colspan='4'>
               <input class='submit' type='submit' name='plugin_fusinvinventory_config_set'
                      value='" . $LANG['buttons'][7] . "'></td></tr>";
      }
      echo "</table>";
      echo "</form>";

      return true;
   }
}

?>