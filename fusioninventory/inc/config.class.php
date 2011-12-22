<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2011 FusionInventory team
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

class PluginFusioninventoryConfig extends CommonDBTM {

   
   /**
   * Initialize config values of fusioninventory plugin
   *
   * @return nothing
   *
   **/
   function initConfigModule() {

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

      $input = array();
      $input['version']                = PLUGIN_FUSIONINVENTORY_VERSION;
      $input['ssl_only']               = '0';
      $input['delete_task']            = '20';
      $input['inventory_frequence']    = '24';
      $input['agent_port']             = '62354';
      $input['extradebug']             = '0';
      $PluginFusioninventorySetup = new PluginFusioninventorySetup();
      $users_id = $PluginFusioninventorySetup->createFusionInventoryUser();
      $input['users_id']               = $users_id;

      foreach ($input as $key => $value) {
         $this->initConfig($plugins_id, array($key => $value), '');
      }
      
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
      $input['location']               = 0;
      $input['group']                  = 0;
      $input['component_networkcardvirtual'] = 1;

      foreach ($input as $key => $value) {
         $this->initConfig($plugins_id, array($key => $value), 'inventory');
      }
   }
  
   
   
  static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusioninventory']['functionalities'][2];
   }
   
   
   function canCreate() {
      return PluginFusioninventoryProfile::haveRight('fusioninventory', 'configuration', 'w');
   }

   function canView() {
      return PluginFusioninventoryProfile::haveRight('fusioninventory', 'configuration', 'r');
   }
   
   
   
   /**
    * Init config
    *
    * @param $p_plugins_id Plugin id
    * @param $p_insert Array('type'=>'value')
    * 
    * @return nothing
    **/
   function initConfig($plugins_id, $p_insert, $module) {

      foreach ($p_insert as $type=>$value) {
         if (is_null($this->getValue($plugins_id, $type, $module))) {
            $this->addConfig($plugins_id, $type, $value,$module);
         } else {
            $this->updateConfigType($plugins_id, $type, $value, $module);
         }         
      }
   }
   
   

   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();
      $moduleTabs = array();
      $this->addStandardTab("PluginFusioninventoryConfig", $ong, $options);
      $this->addStandardTab("PluginFusioninventoryAgentmodule", $ong, $options);

      if (isset($_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms'])) {
         $fusionTabs = $ong;
         $moduleTabForms = $_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms'];
         if (count($moduleTabForms)) {
            foreach ($moduleTabForms as $module=>$form) {
               $plugin = new Plugin;
               if ($plugin->isActivated($module)) {
                  $this->addStandardTab($form[key($form)]['class'], $ong, $options);
               }
            }
            $moduleTabs = array_diff($ong, $fusionTabs);
         }
         $_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabs'] = $moduleTabs;
      }
      return $ong;
   }
   
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      if ($item->getType()==__CLASS__) {
         $array_ret = array();
         $array_ret[0] = $LANG['plugin_fusioninventory']['functionalities'][2];         
         $array_ret[1] = $LANG['plugin_fusioninventory']['config'][1];
         return $array_ret;
      }
      return '';
   }
   
   
   
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($tabnum == '0') {
         $item->showForm();
      } else if ($tabnum == '1') {
         $item->showFormInventory();
      }
     return true;
   }
   


   
   /**
   * Get value of a config field for a fusioninventory plugin
   *
   * @param $p_plugins_id integer id of the plugin
   * @param $p_type value name of the config field to retrieve
   * 
   * @return value or this field or false
   **/
   static function getValue($p_plugins_id, $p_type, $module) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      $config = current($PluginFusioninventoryConfig->find("`plugins_id`='".$p_plugins_id."'
                          AND `type`='".$p_type."'
                          AND `module`='".$module."'"));
      if (isset($config['value'])) {
         return $config['value'];
      }
      return NULL;
   }


   
   /**
   * give state of a config field for a fusioninventory plugin
   *
   * @param $p_plugins_id integer id of the plugin
   * @param $p_type value name of the config field to retrieve
   *
   * @return bool true if field is active or false
   **/
   function is_active($p_plugins_id, $p_type, $module) {
      if (!($this->getValue($p_plugins_id, $p_type, $module))) {
         return false;
      } else {
         return true;
      }
   }



   /**
   * Display form for config
   *
   * @return bool true if form is ok
   *
   **/
   function showForm($options=array()) {
      global $LANG;

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][27]."&nbsp;:</td>";
      echo "<td width='20%'>";
      Dropdown::showYesNo("ssl_only", $this->is_active($plugins_id, 'ssl_only', ''));
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']['config'][0]."&nbsp;:</td>";
      echo "<td width='20%'>";
      Dropdown::showInteger("inventory_frequence",
                            $this->getValue($plugins_id, 'inventory_frequence', ''),1,240);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][32]." :</td>";
      echo "<td>";
      Dropdown::showInteger("delete_task",
                            $this->getValue($plugins_id, 'delete_task', ''),1,240);
      echo " ".strtolower($LANG['calendar'][12]);
      echo "</td>";

      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][8]." :</td>";
      echo "<td>";
      echo "<input type='text' name='agent_port' value='".$this->getValue($plugins_id, 'agent_port', '')."'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][76]." :</td>";
      echo "<td>";
      Dropdown::showYesNo("extradebug", $this->is_active($plugins_id, 'extradebug', ''));
      echo "</td>";
      echo "<td colspan='2'></td>";
      echo "</tr>";

      $options['candel'] = false;
      $this->showFormButtons($options);

      return true;
   }


   
   /**
    * Add config
    *
    * @param $p_plugins_id Plugin id
    * @param $p_type Config type ('ssl_only', 'URL_agent_conf'...)
    * @param $p_value Value value of the type
    * 
    * @return integer the new id of the added item (or false if fail)
    **/
   function addConfig($p_plugins_id, $p_type, $p_value, $module) {
      $existing_value = self::getValue($p_plugins_id, $p_type, $module); 
      if ($existing_value) {
         return $existing_value;
      } else {
         return $this->add(array('plugins_id' => $p_plugins_id, 
                                 'type'       => $p_type,
                                 'value'      => $p_value,
                                 'module'     => $module));
      }
   }


   
   /**
    * Update config
    *
    * @param $p_id Config id
    * @param $p_value Value
    * 
    * @return boolean : true on success
    **/
   function updateConfig($p_id, $p_value) {
      return $this->update(array('id'=>$p_id, 'value'=>$p_value));
   }


   
   /**
    * Update config type
    *
    * @param $p_plugins_id Plugin id
    * @param $p_type Config type ('ssl_only', 'URL_agent_conf'...)
    * @param $p_value Value
    * 
    * @return boolean : true on success
    **/
   function updateConfigType($p_plugins_id, $p_type, $p_value, $module) {
      $config = current($this->find("`plugins_id`='".$p_plugins_id."'
                          AND `type`='".$p_type."'
                          AND `module`='".$module."'"));
      if (isset($config['id'])) {
         return $this->updateConfig($config['id'], $p_value);
      }
      return false;
   }


   
   /**
    * Delete config
    *
    * @param $p_id Config id
    * 
    * @return boolean : true on success
    **/
   function deleteConfig($p_id) {
      return $this->delete(array('id'=>$p_id));
   }



   /**
    * Clean config
    *
    * @param $p_plugins_id Plugin id
    * 
    * @return boolean : true on success
    **/
   function cleanConfig($p_plugins_id) {
      global $DB;

      $delete = "DELETE FROM `".$this->getTable()."`
                 WHERE `plugins_id`='".$p_plugins_id."';";
      return $DB->query($delete);
   }

   
   
   /**
    * Check if extradebug mode is activate
    */
   static function isExtradebugActive() {
      return self::getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug', '');
   }
   
   
   
   /**
    * Log when extra-debug is activated
    */
   static function logIfExtradebug($file, $message) {
      if (self::isExtradebugActive()) {
         Toolbox::logInFile($file, $message);
      }
   }

   
   
   function showFormInventory($options=array()) {
      global $LANG;

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');

      $this->fields['id'] = 1;
      $this->showFormHeader($options);
 
      echo "<tr>";
      echo "<th colspan='4'>".$LANG['plugin_fusioninventory']['setup'][20];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['Menu'][3]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $array = array();
      $array[0] = $LANG['plugin_fusioninventory']['setup'][23];
      $array[1] = $LANG['plugin_fusioninventory']['setup'][22];
      $array[2] = $LANG['plugin_fusioninventory']['setup'][24];
      $array[3] = $LANG['plugin_fusioninventory']['setup'][27];
      Dropdown::showFromArray("import_monitor", $array, 
                              array('value' => 
                                 $PluginFusioninventoryConfig->getValue($plugins_id, 
                                                                        'import_monitor', 'inventory')));
      echo "&nbsp;";
      $text = "* ".$LANG['plugin_fusioninventory']['setup'][23]."&nbsp;:&nbsp;".
      $LANG['plugin_fusioninventory']['setup'][32]."<br/><br/>".
      "* ".$LANG['plugin_fusioninventory']['setup'][22]."&nbsp;:&nbsp;".
      $LANG['plugin_fusioninventory']['setup'][33]."<br/><br/>".
      "* ".$LANG['plugin_fusioninventory']['setup'][24]."&nbsp;:&nbsp;".
      $LANG['plugin_fusioninventory']['setup'][34]."<br/><br/>".
      "* ".$LANG['plugin_fusioninventory']['setup'][27]."&nbsp;:&nbsp;".
      $LANG['plugin_fusioninventory']['setup'][35];
      Html::showToolTip($text);
      echo "</td>";
      echo "<th colspan='2'>";
      echo $LANG['plugin_fusioninventory']['setup'][21];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['Menu'][2]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $array = array();
      $array[0] = $LANG['plugin_fusioninventory']['setup'][23];
      $array[1] = $LANG['plugin_fusioninventory']['setup'][22];
      $array[2] = $LANG['plugin_fusioninventory']['setup'][24];
      $array[3] = $LANG['plugin_fusioninventory']['setup'][27];
      Dropdown::showFromArray("import_printer", $array, 
                              array('value' => 
                                 $PluginFusioninventoryConfig->getValue($plugins_id, 
                                                                        'import_printer', 'inventory')));
      echo "&nbsp;";
      Html::showToolTip($text);
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][4]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_processor", 
                          $PluginFusioninventoryConfig->getValue($plugins_id, 
                                                                'component_processor', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['Menu'][16]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $array = array();
      $array[0] = $LANG['plugin_fusioninventory']['setup'][23];
      $array[1] = $LANG['plugin_fusioninventory']['setup'][22];
      $array[2] = $LANG['plugin_fusioninventory']['setup'][24];
      $array[3] = $LANG['plugin_fusioninventory']['setup'][27];
      Dropdown::showFromArray("import_peripheral", $array, 
                              array('value' => 
                                       $PluginFusioninventoryConfig->getValue($plugins_id, 
                                                                              'import_peripheral', 'inventory')));
      echo "&nbsp;";
      Html::showToolTip($text);
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][6]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_memory", 
                          $PluginFusioninventoryConfig->getValue($plugins_id, 'component_memory', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['Menu'][4]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_software", 
                          $PluginFusioninventoryConfig->getValue($plugins_id, 'import_software', 'inventory'));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][1]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_harddrive", 
                          $PluginFusioninventoryConfig->getValue($plugins_id, 'component_harddrive', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['computers'][8]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_volume", 
                          $PluginFusioninventoryConfig->getValue($plugins_id, 'import_volume', 'inventory'));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][3]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_networkcard", 
                          $PluginFusioninventoryConfig->getValue($plugins_id, 'component_networkcard', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['plugin_fusioninventory']['antivirus'][0]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_antivirus", 
                          $PluginFusioninventoryConfig->getValue($plugins_id, 'import_antivirus', 'inventory'));
      echo "</td>";
      echo "<td>";
      echo $LANG['plugin_fusioninventory']['setup'][31]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_networkcardvirtual", 
                          $PluginFusioninventoryConfig->getValue($plugins_id, 'component_networkcardvirtual', 'inventory'));
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
//      echo $LANG['plugin_fusioninventory']['setup'][25]."&nbsp;:";
      echo "</td>";
      echo "<td>";
//      Dropdown::showYesNo("import_registry", 
//                          $PluginFusioninventoryConfig->getValue($plugins_id, 'import_registry'));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][2]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_graphiccard", 
                          $PluginFusioninventoryConfig->getValue($plugins_id, 'component_graphiccard', 'inventory'));
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
//      echo $LANG['plugin_fusioninventory']['setup'][26]."&nbsp;:";
      echo "</td>";
      echo "<td>";
//      Dropdown::showYesNo("import_process", 
//                          $PluginFusioninventoryConfig->getValue($plugins_id, 'import_process'));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][7]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_soundcard", 
                          $PluginFusioninventoryConfig->getValue($plugins_id, 'component_soundcard', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['computers'][57]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_vm", 
                          $PluginFusioninventoryConfig->getValue($plugins_id, 'import_vm', 'inventory'));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][19]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_drive", 
                          $PluginFusioninventoryConfig->getValue($plugins_id, 'component_drive', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['common'][15]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showFromArray("location",
                              array("0"=>"------",
                                    "1"=>$LANG['plugin_fusioninventory']['rule'][28]),
                              array('value'=>$PluginFusioninventoryConfig->getValue($plugins_id, 'location', 'inventory')));

      echo "</td>";
      echo "<td>";
      echo $LANG['plugin_fusioninventory']['setup'][30]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_networkdrive",
                          $PluginFusioninventoryConfig->getValue($plugins_id, 'component_networkdrive', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['common'][35]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showFromArray("group",
                              array("0"=>"------",
                                    "1"=>$LANG['plugin_fusioninventory']['rule'][28]),
                              array('value'=>$PluginFusioninventoryConfig->getValue($plugins_id, 'group', 'inventory')));
      echo "</td>";
      echo "<td>";
      echo $LANG['devices'][20]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_control",
                          $PluginFusioninventoryConfig->getValue($plugins_id,
                                                                 'component_control', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['setup'][36]."&nbsp;:</td>";
      echo "<td>";
      Dropdown::show('State',
                     array('name'   => 'states_id_default',
                           'value'  => $PluginFusioninventoryConfig->getValue($plugins_id,
                                                                              'states_id_default', 'inventory')));
      echo "</td>";
      echo "<td colspan='2'></td>";
      echo "</tr>";
      
      
      echo "<tr>";
      echo "<th colspan='4'>".$LANG['plugin_fusioninventory']['setup'][28];
      echo "</th>";
      echo "</tr>";

      echo "<td colspan='2'>";
      echo $LANG['plugin_fusioninventory']['setup'][29]."&nbsp:";
      echo "</td>";
      echo "<td colspan='2'>";
      Dropdown::show("Transfer",
                     array('name'=>"transfers_id_auto",
                           'value'=>$PluginFusioninventoryConfig->getValue($plugins_id, 
                                                                           'transfers_id_auto', 'inventory'),
                           'comment'=>0));
      echo "</td>";
      echo "</tr>";
      
      $options['candel'] = false;
      $this->showFormButtons($options);

      return true;
   }   
   
   
   
}

?>