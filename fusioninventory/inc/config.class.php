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
    * Display name of itemtype
    * 
    * @global array $LANG
    * 
    * @return value name of this itemtype
    */
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
    * add multiple configuration values
    *
    * @param $plugin_id plugin id
    * @param $values array of configuration values, indexed by name
    * 
    * @return nothing
    **/
   function addValues($plugin_id, $values) {

      foreach ($values as $name => $value) {
         if (is_null($this->getValue($plugin_id, $name))) {
            $this->addValue($plugin_id, $name, $value);
         } else {
            $this->updateValue($plugin_id, $name, $value);
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
         
         return $LANG['plugin_fusioninventory']['functionalities'][2];
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

      if ($item->getType()=='PluginFusioninventoryConfig') {
         $item->showForm();
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
   function getValue($plugin_id, $name) {

      $config = current($this->find("`type`='".$name."'
                          AND `plugins_id`='".$plugin_id."'"));
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
   function isActive($plugin_id, $name) {
      if (!($this->getValue($plugin_id, $name))) {
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
      Dropdown::showYesNo("ssl_only", $this->isActive($plugin_id, 'ssl_only'));
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']['config'][0]."&nbsp;:</td>";
      echo "<td width='20%'>";
      Dropdown::showInteger("inventory_frequence",
                            $this->getValue($plugin_id, 'inventory_frequence'),1,240);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][32]." :</td>";
      echo "<td>";
      Dropdown::showInteger("delete_task",
                            $this->getValue($plugin_id, 'delete_task'),1,240);
      echo " ".strtolower($LANG['calendar'][12]);
      echo "</td>";

      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][8]." :</td>";
      echo "<td>";
      echo "<input type='text' name='agent_port' value='".$this->getValue($plugin_id, 'agent_port')."'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][76]." :</td>";
      echo "<td>";
      Dropdown::showYesNo("extradebug", $this->isActive($plugin_id, 'extradebug'));
      echo "</td>";
      echo "<td colspan='2'></td>";
      echo "</tr>";

      $options['candel'] = false;
      $this->showFormButtons($options);

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
   function addValue($plugin_id, $name, $value) {
      $existing_value = $this->getValue($plugin_id, $name); 
      if (!is_null($existing_value)) {
         return $existing_value;
      } else {
         return $this->add(array('plugins_id' => $plugin_id, 
                                 'type'       => $name,
                                 'value'      => $value));
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
                          AND `type`='".$name."'"));
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