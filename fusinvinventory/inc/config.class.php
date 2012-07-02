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

class PluginFusinvinventoryConfig extends CommonDBTM {


   function canCreate() {
      return PluginFusioninventoryProfile::haveRight('fusioninventory', 'configuration', 'w');
   }

   function canView() {
      return PluginFusioninventoryProfile::haveRight('fusioninventory', 'configuration', 'r');
   }
   
   
   /**
   * Initialize config values of fusinvinventory plugin
   *
   * @return nothing
   *
   **/
   function initConfigModule() {

      $pfConfig = new PluginFusioninventoryConfig();

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
      $input['component_networkdrive'] = 1;
      $input['component_control']      = 1;
      $input['transfers_id_auto']      = 1;
      $input['states_id_default']      = 0;
      $a_infos = plugin_version_fusinvinventory();
      $input['version']                = $a_infos['version'];
      $input['location']               = 0;
      $input['group']                  = 0;
      $input['component_networkcardvirtual'] = 1;

      foreach ($input as $key => $value) {
         $pfConfig->addValues($plugins_id, array($key => $value));
      }
   }


   
  function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      if ($item->getType()=='PluginFusioninventoryConfig') {
         return self::createTabEntry($LANG['plugin_fusinvinventory']['title'][0]);
         return $LANG['plugin_fusinvinventory']['title'][0];
      }
      return '';
   }
   
   
   
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType()=='PluginFusioninventoryConfig') {
         $pfConfig = new self();
         $pfConfig->showForm();
      }
      return true;
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

      $pfConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');
      foreach ($p_post as $key => $value) {
         if (preg_match("/[component_|import_|transfers_id_auto|states_id_auto]/",$key)) {
            $pfConfig->updateConfigType($plugins_id, $key, $value);

         }
      }
   }



   /**
   * Display form for config tab in fusioninventory config form
   *
   * @param $options array
   *
   * @return bool true if form is ok
   *
   **/
   static function showForm($options=array()) {
      global $LANG;

      $pfConfig = new PluginFusioninventoryConfig();
      $pfsnmpConfig = new self();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');

      $pfsnmpConfig->fields['id'] = 1;
      $pfsnmpConfig->showFormHeader($options);
 
      echo "<tr>";
      echo "<th colspan='4'>".$LANG['plugin_fusinvinventory']['setup'][20];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['Menu'][3]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $array = array();
      $array[0] = $LANG['plugin_fusinvinventory']['setup'][23];
      $array[1] = $LANG['plugin_fusinvinventory']['setup'][22];
      $array[2] = $LANG['plugin_fusinvinventory']['setup'][24];
      $array[3] = $LANG['plugin_fusinvinventory']['setup'][27];
      Dropdown::showFromArray("import_monitor", $array, 
                              array('value' => 
                                 $pfConfig->getValue($plugins_id, 
                                                                        'import_monitor')));
      echo "&nbsp;";
      $text = "* ".$LANG['plugin_fusinvinventory']['setup'][23]."&nbsp;:&nbsp;".
      $LANG['plugin_fusinvinventory']['setup'][32]."<br/><br/>".
      "* ".$LANG['plugin_fusinvinventory']['setup'][22]."&nbsp;:&nbsp;".
      $LANG['plugin_fusinvinventory']['setup'][33]."<br/><br/>".
      "* ".$LANG['plugin_fusinvinventory']['setup'][24]."&nbsp;:&nbsp;".
      $LANG['plugin_fusinvinventory']['setup'][34]."<br/><br/>".
      "* ".$LANG['plugin_fusinvinventory']['setup'][27]."&nbsp;:&nbsp;".
      $LANG['plugin_fusinvinventory']['setup'][35];
      Html::showToolTip($text);
      echo "</td>";
      echo "<th colspan='2'>";
      echo $LANG['plugin_fusinvinventory']['setup'][21];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['Menu'][2]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $array = array();
      $array[0] = $LANG['plugin_fusinvinventory']['setup'][23];
      $array[1] = $LANG['plugin_fusinvinventory']['setup'][22];
      $array[2] = $LANG['plugin_fusinvinventory']['setup'][24];
      $array[3] = $LANG['plugin_fusinvinventory']['setup'][27];
      Dropdown::showFromArray("import_printer", $array, 
                              array('value' => 
                                 $pfConfig->getValue($plugins_id, 
                                                                        'import_printer')));
      echo "&nbsp;";
      Html::showToolTip($text);
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][4]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_processor", 
                          $pfConfig->getValue($plugins_id, 
                                                                'component_processor'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['Menu'][16]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $array = array();
      $array[0] = $LANG['plugin_fusinvinventory']['setup'][23];
      $array[1] = $LANG['plugin_fusinvinventory']['setup'][22];
      $array[2] = $LANG['plugin_fusinvinventory']['setup'][24];
      $array[3] = $LANG['plugin_fusinvinventory']['setup'][27];
      Dropdown::showFromArray("import_peripheral", $array, 
                              array('value' => 
                                       $pfConfig->getValue($plugins_id, 
                                                                              'import_peripheral')));
      echo "&nbsp;";
      Html::showToolTip($text);
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][6]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_memory", 
                          $pfConfig->getValue($plugins_id, 'component_memory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['Menu'][4]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_software", 
                          $pfConfig->getValue($plugins_id, 'import_software'));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][1]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_harddrive", 
                          $pfConfig->getValue($plugins_id, 'component_harddrive'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['computers'][8]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_volume", 
                          $pfConfig->getValue($plugins_id, 'import_volume'));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][3]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_networkcard", 
                          $pfConfig->getValue($plugins_id, 'component_networkcard'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['plugin_fusinvinventory']['antivirus'][0]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_antivirus", 
                          $pfConfig->getValue($plugins_id, 'import_antivirus'));
      echo "</td>";
      echo "<td>";
      echo $LANG['plugin_fusinvinventory']['setup'][31]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_networkcardvirtual", 
                          $pfConfig->getValue($plugins_id, 'component_networkcardvirtual'));
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][2]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_graphiccard", 
                          $pfConfig->getValue($plugins_id, 'component_graphiccard'));
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][7]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_soundcard", 
                          $pfConfig->getValue($plugins_id, 'component_soundcard'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['computers'][57]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_vm", 
                          $pfConfig->getValue($plugins_id, 'import_vm'));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][19]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_drive", 
                          $pfConfig->getValue($plugins_id, 'component_drive'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['common'][15]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showFromArray("location",
                              array("0"=>"------",
                                    "1"=>$LANG['plugin_fusinvinventory']['rule'][8]),
                              array('value'=>$pfConfig->getValue($plugins_id, 'location')));

      echo "</td>";
      echo "<td>";
      echo $LANG['plugin_fusinvinventory']['setup'][30]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_networkdrive",
                          $pfConfig->getValue($plugins_id, 'component_networkdrive'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['common'][35]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showFromArray("group",
                              array("0"=>"------",
                                    "1"=>$LANG['plugin_fusinvinventory']['rule'][8]),
                              array('value'=>$pfConfig->getValue($plugins_id, 'group')));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][20]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_control",
                          $pfConfig->getValue($plugins_id,
                                                                 'component_control'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusinvinventory']['setup'][36]."&nbsp;:</td>";
      echo "<td>";
      Dropdown::show('State',
                     array('name'   => 'states_id_default',
                           'value'  => $pfConfig->getValue($plugins_id,
                                                                              'states_id_default')));
      echo "</td>";
      echo "<td colspan='2'></td>";
      echo "</tr>";
      
      
      echo "<tr>";
      echo "<th colspan='4'>".$LANG['plugin_fusinvinventory']['setup'][28];
      echo "</th>";
      echo "</tr>";

      echo "<td colspan='2'>";
      echo $LANG['plugin_fusinvinventory']['setup'][29]."&nbsp:";
      echo "</td>";
      echo "<td colspan='2'>";
      Dropdown::show("Transfer",
                     array('name'=>"transfers_id_auto",
                           'value'=>$pfConfig->getValue($plugins_id, 
                                                                           'transfers_id_auto'),
                           'comment'=>0));
      echo "</td>";
      echo "</tr>";
      
      $options['candel'] = false;
      $pfsnmpConfig->showFormButtons($options);

      return true;
   }

}

?>