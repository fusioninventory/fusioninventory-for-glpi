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

class PluginFusioninventoryConfig extends CommonDBTM {
   public $displaylist = false;
   
   
   /**
   * Initialize config values of fusioninventory plugin
   *
   * @return nothing
   *
   **/
   function initConfigModule() {

      $plugin_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

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
         $this->addValues($plugin_id, array($key => $value), '');
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
         $this->addValues($plugin_id, array($key => $value), 'inventory');
      }
   }
  
   
   
   /**
    * Display name of itemtype
    * 
    * @global array $LANG
    * 
    * @return value name of this itemtype
    **/
   static function getTypeName($nb=0) {
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
    * add multiple configuration values
    *
    * @param $plugin_id plugin id
    * @param $values array of configuration values, indexed by name
    * 
    * @return nothing
    **/
   function addValues($plugin_id, $values, $module) {

      foreach ($values as $type=>$value) {
         if (is_null($this->getValue($plugin_id, $type, $module))) {
            $this->addValue($plugin_id, $type, $value,$module);
         } else {
            $this->updateValue($plugin_id, $type, $value, $module);
         }         
      }
   }
   
   

   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

		$plugin = new Plugin;

      $ong = array();
      $moduleTabs = array();
      $this->addStandardTab("PluginFusioninventoryConfig", $ong, $options);
      $this->addStandardTab("PluginFusioninventoryAgentmodule", $ong, $options);

      if (isset($_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms'])) {
         $fusionTabs = $ong;
         $moduleTabForms = $_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms'];
         if (count($moduleTabForms)) {
            foreach ($moduleTabForms as $module=>$form) {               
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
   
   
   
   /**
    * Display tab
    * 
    * @global array $LANG
    * 
    * @param CommonGLPI $item
    * @param integer $withtemplate
    * 
    * @return varchar name of the tab(s) to display 
    */
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
   
   
   
   /**
    * Display content of tab
    * 
    * @param CommonGLPI $item
    * @param integer $tabnum
    * @param interger $withtemplate
    * 
    * @return boolean true
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($tabnum == '0') {
         $item->showForm();
      } else if ($tabnum == '1') {
         $item->showFormInventory();
      }
      return true;
   }
   


   
   /**
   * Get configuration value
   *
   * @param $plugin_id plugin id
   * @param $name field name
   * @param $module ?
   * 
   * @return field value for an existing field, false otherwise
   **/
   function getValue($plugin_id, $name, $module) {

      $config = current($this->find("`plugins_id`='".$plugin_id."'
                          AND `type`='".$name."'
                          AND `module`='".$module."'"));
      if (isset($config['value'])) {
         return $config['value'];
      }
      return NULL;
   }


   
   /**
   * give state of a config field for a fusioninventory plugin
   *
   * @param $plugin_id plugin id
   * @param $name field name
   * @param $module ?
   *
   * @return true for an existing field, false otherwise
   **/
   function isActive($plugin_id, $name, $module) {
      if (!($this->getValue($plugin_id, $name, $module))) {
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

      $plugin_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][27]."&nbsp;:</td>";
      echo "<td width='20%'>";
      Dropdown::showYesNo("ssl_only", $this->isActive($plugin_id, 'ssl_only', ''));
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']['config'][0]."&nbsp;:</td>";
      echo "<td width='20%'>";
      Dropdown::showInteger("inventory_frequence",
                            $this->getValue($plugin_id, 'inventory_frequence', ''),1,240);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][32]." :</td>";
      echo "<td>";
      Dropdown::showInteger("delete_task",
                            $this->getValue($plugin_id, 'delete_task', ''),1,240, 1,
                            array(),
                            array('unit'=>'day'));

      echo "</td>";

      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][8]." :</td>";
      echo "<td>";
      echo "<input type='text' name='agent_port' value='".$this->getValue($plugin_id, 'agent_port', '')."'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][76]." :</td>";
      echo "<td>";
      Dropdown::showYesNo("extradebug", $this->isActive($plugin_id, 'extradebug', ''));
      echo "</td>";
      echo "<td colspan='2'></td>";
      echo "</tr>";

      $options['candel'] = false;
      $this->showFormButtons($options);

      return true;
   }
   
   
   
   /**
   * Display form for config tab in fusioninventory config form
   *
   * @param $options array
   *
   * @return bool true if form is ok
   *
   **/
   static function showFormInventory($options=array()) {
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


   
   /**
    * Add configuration value, if not already present
    *
    * @param $plugin_id plugin id
    * @param $name field name
    * @param $value field value
    * @param $module ?
    * 
    * @return integer the new id of the added item (or false if fail)
    **/
   function addValue($plugin_id, $name, $value, $module) {
      $existing_value = $this->getValue($plugin_id, $name, $module); 
      if (!is_null($existing_value)) {
         return $existing_value;
      } else {
         return $this->add(array('plugins_id' => $plugin_id, 
                                 'type'       => $name,
                                 'value'      => $value,
                                 'module'     => $module));
      }
   }


   /**
    * Update configuration value
    *
    * @param $plugin_id plugin id
    * @param $name field name
    * @param $value field value
    * 
    * @return boolean : true on success
    **/
   function updateValue($plugin_id, $name, $value) {
      $config = current($this->find("`plugins_id`='".$plugin_id."'
                          AND `type`='".$name."'
                          AND `module`='".$module."'"));
      if (isset($config['id'])) {
         return $this->update(array('id'=> $config['id'], 'value'=>$value));
      }
      return false;
   }


   
   /**
    * Delete configuration field
    *
    * @param $field_id field id
    * 
    * @return boolean : true on success
    **/
   function deleteConfig($field_id) {
      return $this->delete(array('id'=>$field_id));
   }



   /**
    * Clean config
    *
    * @param $plugin_id Plugin id
    * 
    * @return boolean : true on success
    **/
   function cleanConfig($plugin_id) {
      global $DB;

      $delete = "DELETE FROM `".$this->getTable()."`
                 WHERE `plugins_id`='".$plugin_id."';";
      return $DB->query($delete);
   }

   
   
   /**
    * Check if extradebug mode is activate
    */
   static function isExtradebugActive() {
      $fConfig = new self();
      return $fConfig->getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug');
   }
   
   
   
   /**
    * Log when extra-debug is activated
    */
   static function logIfExtradebug($file, $message) {
      if (self::isExtradebugActive()) {
         Toolbox::logInFile($file, $message);
      }
   }
   
   

   /**
    * Update configuration field
    *
    * @param $field_id field id
    * @param $value field value
    * 
    * @return boolean : true on success
    **/
   function updateConfig($field_id, $value) {
      return $this->update(array('id'=>$field_id, 'value'=>$value));
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
   function updateConfigType($p_plugins_id, $p_type, $p_value) {
      $config = current($this->find("`plugins_id`='".$p_plugins_id."'
                          AND `type`='".$p_type."'"));
      if (isset($config['id'])) {
         return $this->updateConfig($config['id'], $p_value);
      }
      return false;
   }
   
   
   


   
   /**
   * give state of a config field for a fusioninventory plugin
   *
   * @param $p_plugins_id integer id of the plugin
   * @param $p_type value name of the config field to retrieve
   *
   * @return bool true if field is active or false
   **/
   function is_active($p_plugins_id, $p_type) {
      if (!($this->getValue($p_plugins_id, $p_type))) {
         return false;
      } else {
         return true;
      }
   }
}

?>
