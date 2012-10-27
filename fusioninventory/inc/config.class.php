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
      $input['location']               = 0;
      $input['group']                  = 0;
      $input['component_networkcardvirtual'] = 1;

      foreach ($input as $key => $value) {
         $this->addValues($plugin_id, array($key => $value), 'inventory');
      }
      
      $input = array();
      $input['threads_networkdiscovery'] = 1;
      $input['threads_networkinventory'] = 1;

      foreach ($input as $key => $value) {
         $this->addValues($plugin_id, array($key => $value), 'network');
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

      return __('General configuration');

   }



   static function canCreate() {
      return PluginFusioninventoryProfile::haveRight('fusioninventory', 'configuration', 'w');
   }

   static function canView() {
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
      global $CFG_GLPI;

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

      if ($item->getType()==__CLASS__) {
         $array_ret = array();
         $array_ret[0] = __('General configuration');

         $array_ret[1] = __('Computer Inventory');
         
         $array_ret[2] = __('Network Inventory');

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
      } else if ($tabnum == '2') {
         $item->showFormNetworkInventory();
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

      if (isset($_SESSION['plugin_fusioninventory_configvalues'][$plugin_id][$module][$name])) {
         return $_SESSION['plugin_fusioninventory_configvalues'][$plugin_id][$module][$name];
      }
      
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

      $plugin_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('SSL-only for agent')."&nbsp;:</td>";
      echo "<td width='20%'>";
      Dropdown::showYesNo("ssl_only", $this->isActive($plugin_id, 'ssl_only', ''));
      echo "</td>";
      echo "<td>".__('Inventory frequency (in hours)')."&nbsp;:</td>";
      echo "<td width='20%'>";
      Dropdown::showInteger("inventory_frequence",
                            $this->getValue($plugin_id, 'inventory_frequence', ''),1,240);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Delete tasks after')." :</td>";
      echo "<td>";
      Dropdown::showInteger("delete_task",
                            $this->getValue($plugin_id, 'delete_task', ''),1,240, 1,
                            array(),
                            array('unit'=>'day'));

      echo "</td>";

      echo "<td>".__('Agent port')." :</td>";
      echo "<td>";
      echo "<input type='text' name='agent_port' value='".$this->getValue($plugin_id, 'agent_port', '')."'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Extra-debug')." :</td>";
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

      $pfConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

      $pfConfig->fields['id'] = 1;
      $pfConfig->showFormHeader($options);

      echo "<tr>";
      echo "<th colspan='4'>".__('Import options');

      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo _n('Monitor', 'Monitors', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $elements = array();
      $elements[0] = __('No import');
      $elements[1] = __('Global import');
      $elements[2] = __('Unique import');
      $elements[3] = __('Unique import on serial number');

      Dropdown::showFromArray("import_monitor", $elements,
                              array('value' =>
                                 $pfConfig->getValue($plugins_id, 'import_monitor', 'inventory')));
      echo "&nbsp;";
      $text = "* ".__('No import')."&nbsp;:&nbsp;".
      __('This option will not import this item')."<br/><br/>".
      "* ".__('Global import')."&nbsp;:&nbsp;".
      __('This option will merge items with same name to reduce number of items if this management isn\'t important')."<br/><br/>".
      "* ".__('Unique import')."&nbsp;:&nbsp;".
      __('This option will create one item for each item found')."<br/><br/>".
      "* ".__('Unique import on serial number')."&nbsp;:&nbsp;".
      __('This option will create one item for each item have serial number');

      Html::showToolTip($text);
      echo "</td>";
      echo "<th colspan='2'>";
      echo __('Components');

      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo _n('Printer', 'Printers', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $elements = array();
      $elements[0] = __('No import');
      $elements[1] = __('Global import');
      $elements[2] = __('Unique import');
      $elements[3] = __('Unique import on serial number');

      Dropdown::showFromArray("import_printer", $elements,
                              array('value' =>
                                 $pfConfig->getValue($plugins_id, 'import_printer', 'inventory')));
      echo "&nbsp;";
      Html::showToolTip($text);
      echo "</td>";
      echo "<td>";
      echo _n('Processor', 'Processors', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_processor",
                          $pfConfig->getValue($plugins_id, 'component_processor', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo _n('Device', 'Devices', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $elements = array();
      $elements[0] = __('No import');
      $elements[1] = __('Global import');
      $elements[2] = __('Unique import');
      $elements[3] = __('Unique import on serial number');
      Dropdown::showFromArray("import_peripheral", $elements,
                              array('value' =>
                                       $pfConfig->getValue($plugins_id, 'import_peripheral', 'inventory')));
      echo "&nbsp;";
      Html::showToolTip($text);
      echo "</td>";
      echo "<td>";
      echo _n('Memory', 'Memories', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_memory",
                          $pfConfig->getValue($plugins_id, 'component_memory', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo _n('Software', 'Software', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_software",
                          $pfConfig->getValue($plugins_id, 'import_software', 'inventory'));
      echo "</td>";
      echo "<td>";
      echo _n('Hard drive', 'Hard drives', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_harddrive",
                          $pfConfig->getValue($plugins_id, 'component_harddrive', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo _n('Volume', 'Volumes', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_volume",
                          $pfConfig->getValue($plugins_id, 'import_volume', 'inventory'));
      echo "</td>";
      echo "<td>";
      echo _n('Network card', 'Network cards', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_networkcard",
                          $pfConfig->getValue($plugins_id, 'component_networkcard', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Antivirus')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_antivirus",
                          $pfConfig->getValue($plugins_id, 'import_antivirus', 'inventory'));
      echo "</td>";
      echo "<td>";
      echo __('Virtual network card')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_networkcardvirtual",
                          $pfConfig->getValue($plugins_id, 'component_networkcardvirtual', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "<td>";
      echo _n('Graphic card', 'Graphic cards', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_graphiccard",
                          $pfConfig->getValue($plugins_id, 'component_graphiccard', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "<td>";
      echo _n('Sound card', 'Sound cards', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_soundcard",
                          $pfConfig->getValue($plugins_id, 'component_soundcard', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo _n('Virtual machine', 'Virtual machines', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("import_vm",
                          $pfConfig->getValue($plugins_id, 'import_vm', 'inventory'));
      echo "</td>";
      echo "<td>";
      echo _n('Drive', 'Drives', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_drive",
                          $pfConfig->getValue($plugins_id, 'component_drive', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo _n('Location', 'Locations', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showFromArray("location",
                              array("0"=>"------",
                                    "1"=>__('Tag')),
                              array('value'=>$pfConfig->getValue($plugins_id, 'location', 'inventory')));

      echo "</td>";
      echo "<td>";
      echo __('Network drives')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_networkdrive",
                          $pfConfig->getValue($plugins_id, 'component_networkdrive', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo _n('Group', 'Groups', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showFromArray("group",
                              array("0"=>"------",
                                    "1"=>__('Tag')),
                              array('value'=>$pfConfig->getValue($plugins_id, 'group', 'inventory')));
      echo "</td>";
      echo "<td>";
      echo _n('Controller', 'Controllers', 2)."&nbsp;:";
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("component_control",
                          $pfConfig->getValue($plugins_id, 'component_control', 'inventory'));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Default status')."&nbsp;:</td>";
      echo "<td>";
      Dropdown::show('State',
                     array('name'   => 'states_id_default',
                           'value'  => $pfConfig->getValue($plugins_id, 'states_id_default', 'inventory')));
      echo "</td>";
      echo "<td colspan='2'></td>";
      echo "</tr>";


      echo "<tr>";
      echo "<th colspan='4'>".__('Automatic computers transfer');

      echo "</th>";
      echo "</tr>";

      echo "<td colspan='2'>";
      echo __('Model for automatic computers transfer in an other entity')."&nbsp:";
      echo "</td>";
      echo "<td colspan='2'>";
      Dropdown::show("Transfer",
                     array('name'=>"transfers_id_auto",
                           'value'=>$pfConfig->getValue($plugins_id, 'transfers_id_auto', 'inventory'),
                           'comment'=>0));
      echo "</td>";
      echo "</tr>";

      $options['candel'] = false;
      $pfConfig->showFormButtons($options);

      return true;
   }


   
   /**
   * Display form for config tab in network inventory config form
   *
   * @param $options array
   *
   * @return bool true if form is ok
   *
   **/
   static function showFormNetworkInventory($options=array()) {
      global $CFG_GLPI;

      $pfConfig = new PluginFusioninventoryConfig();
      $pfsnmpConfig = new self();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');

      $pfsnmpConfig->fields['id'] = 1;
      $pfsnmpConfig->showFormHeader($options);

      echo "<tr>";
      echo "<th colspan='4'>";
      echo __('Network options');
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Threads number')."&nbsp;(".strtolower(__('Network discovery')).")&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("threads_networkdiscovery", 
                            $pfConfig->getValue($plugins_id, 'threads_networkdiscovery', 'network'),1,400);
      echo "</td>";
      echo "<td>".__('Threads number')."&nbsp;(".strtolower(__('Network inventory (SNMP)')).")&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("threads_networkinventory", 
                            $pfConfig->getValue($plugins_id, 'threads_networkinventory', 'network'),1,400);
      echo "</td>";
      echo "</tr>";
      
      $options['candel'] = false;
      $pfsnmpConfig->showFormButtons($options);

      $pfConfigLogField = new PluginFusioninventoryConfigLogField();
      $pfConfigLogField->showForm(array('target'=>$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/configlogfield.form.php"));

      $pfNetworkporttype = new PluginFusioninventoryNetworkporttype();
      $pfNetworkporttype->showNetworkporttype();
      
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
   function updateValue($plugin_id, $name, $value, $module) {
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
      return $fConfig->getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug', 'fusioninventory');
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
   
   
   
   static function loadCache() {
      global $DB;

      $_SESSION['plugin_fusioninventory_configvalues'] = array();
      $query = "SELECT * FROM `glpi_plugin_fusioninventory_configs`";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $_SESSION['plugin_fusioninventory_configvalues'][$data['plugins_id']][$data['module']][$data['type']] = $data['value'];
      }
   }
}

?>
