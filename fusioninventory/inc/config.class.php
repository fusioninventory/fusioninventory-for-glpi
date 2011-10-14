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

class PluginFusioninventoryConfig extends CommonDBTM {

   /**
    * Init config
    *
    *@param $p_plugins_id Plugin id
    *@param $p_insert Array('type'=>'value')
    * 
    *@return nothing
    **/
   function initConfig($plugins_id, $p_insert) {
      global $DB;

      foreach ($p_insert as $type=>$value) {
         $this->addConfig($plugins_id, $type, $value);
      }
   }


   /**
   * Get value of a config field for a fusioninventory plugin
   *
   *@param $p_plugins_id integer id of the plugin
   *@param $p_type value name of the config field to retrieve
   * 
   *@return value or this field or false
   **/
   static function getValue($p_plugins_id, $p_type) {
      global $DB;

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      $data = $PluginFusioninventoryConfig->find("`plugins_id`='".$p_plugins_id."'
                          AND `type`='".$p_type."'");
      $config = current($data);
      if (isset($config['value'])) {
         return $config['value'];
      }
      return false;
   }


   
   /**
   * give state of a config field for a fusioninventory plugin
   *
   *@param $p_plugins_id integer id of the plugin
   *@param $p_type value name of the config field to retrieve
   *
   *@return bool true if field is active or false
   **/
   function is_active($p_plugins_id, $p_type) {
      if (!($this->getValue($p_plugins_id, $p_type))) {
         return false;
      } else {
         return true;
      }
   }



   /**
   * Display form for config
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($options=array()) {
      global $LANG,$CFG_GLPI;

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusioninventory');

      echo "<form name='form' method='post' action='".$options['target']."'>";
      echo "<div class='center' id='tabsbody'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][27]."&nbsp;:</td>";
      echo "<td width='20%'>";
      Dropdown::showYesNo("ssl_only", $this->is_active($plugins_id, 'ssl_only'));
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']['config'][0]."&nbsp;:</td>";
      echo "<td width='20%'>";
      Dropdown::showInteger("inventory_frequence",
                            $this->getValue($plugins_id, 'inventory_frequence'),1,240);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][32]." :</td>";
      echo "<td>";
      Dropdown::showInteger("delete_task",
                            $this->getValue($plugins_id, 'delete_task'),1,240);
      echo " ".strtolower($LANG['stats'][31]);
      echo "</td>";

      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][8]." :</td>";
      echo "<td>";
      echo "<input type='text' name='agent_port' value='".$this->getValue($plugins_id, 'agent_port')."'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['functionalities'][76]." :</td>";
      echo "<td>";
      Dropdown::showYesNo("extradebug", $this->is_active($plugins_id, 'extradebug'));
      echo "</td>";

      echo "<td colspan='2'></td>";
      echo "</tr>";


      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration", "w")) {
         echo "<tr class='tab_bg_2'><td align='center' colspan='4'>
               <input class='submit' type='submit' name='plugin_fusioninventory_config_set'
                      value='" . $LANG['buttons'][7] . "'></td></tr>";
      }
      echo "</table></div></form>";

      return true;
   }


   
   /**
    * Add config
    *
    *@param $p_plugins_id Plugin id
    *@param $p_type Config type ('ssl_only', 'URL_agent_conf'...)
    *@param $p_value Value value of the type
    * 
    *@return integer the new id of the added item (or false if fail)
    **/
   function addConfig($p_plugins_id, $p_type, $p_value) {
      return $this->add(array('plugins_id'=>$p_plugins_id, 
                              'type'=>$p_type,
                              'value'=>$p_value));
   }


   
   /**
    * Update config
    *
    *@param $p_id Config id
    *@param $p_value Value
    * 
    *@return boolean : true on success
    **/
   function updateConfig($p_id, $p_value) {
      return $this->update(array('id'=>$p_id, 'value'=>$p_value));
   }


   
   /**
    * Update config type
    *
    *@param $p_plugins_id Plugin id
    *@param $p_type Config type ('ssl_only', 'URL_agent_conf'...)
    *@param $p_value Value
    * 
    *@return boolean : true on success
    **/
   function updateConfigType($p_plugins_id, $p_type, $p_value) {
      $data = $this->find("`plugins_id`='".$p_plugins_id."'
                          AND `type`='".$p_type."'");
      $config = current($data);
      if (isset($config['id'])) {
         return $this->updateConfig($config['id'], $p_value);
      }
      return false;
   }


   
   /**
    * Delete config
    *
    *@param $p_id Config id
    * 
    *@return boolean : true on success
    **/
   function deleteConfig($p_id) {
      return $this->delete(array('id'=>$p_id));
   }



   /**
    * Clean config
    *
    *@param $p_plugins_id Plugin id
    * 
    *@return boolean : true on success
    **/
   function cleanConfig($p_plugins_id) {
      global $DB;

      $delete = "DELETE FROM `".$this->getTable()."`
                 WHERE `plugins_id`='".$p_plugins_id."';";
      return $DB->query($delete);
   }
}

?>