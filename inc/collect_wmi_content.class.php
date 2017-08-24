<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the wmi content found by agent and linked
 * to the computer
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the wmi information found by the collect module of agent.
 */

class PluginFusioninventoryCollect_Wmi_Content extends CommonDBTM {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_collect';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb=0) {
      return __('Windows WMI content', 'fusioninventory');
   }



   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getID() > 0) {
         if (get_class($item) == 'PluginFusioninventoryCollect') {
            if ($item->fields['type'] == 'wmi') {
               $a_colregs = getAllDatasFromTable('glpi_plugin_fusioninventory_collects_wmis',
                                                 "`plugin_fusioninventory_collects_id`='".$item->getID()."'");
               if (count($a_colregs) == 0) {
                  return '';
               }
               $in = array_keys($a_colregs);
               if (countElementsInTable('glpi_plugin_fusioninventory_collects_wmis_contents',
                                "`plugin_fusioninventory_collects_wmis_id` IN ('".implode("','", $in)."')") > 0) {
                  return __('Windows WMI content', 'fusioninventory');
               }
            }
         }
      }
      return '';
   }



   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return boolean
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi_Content();
      if (get_class($item) == 'PluginFusioninventoryCollect') {
         $pfCollect_Wmi->showForCollect($item->getID());
      }
      return TRUE;
   }

   /**
    * Delete all WMI contents linked to the computer (most cases when delete a
    * computer)
    *
    * @param integer $computers_id
    */
   static function cleanComputer($computers_id) {
      $collect_content = new self();
      $collect_content->deleteByCriteria(array('computers_id' => $computers_id));
   }

   /**
    * update wmi data to compute (add and update) with data sent by the agent
    *
    * @global object $DB
    * @param integer $computers_id id of the computer
    * @param array $wmi_data
    * @param integer $collects_wmis_id
    */
   function updateComputer($computers_id, $wmi_data, $collects_wmis_id) {
      global $DB;

      $db_wmis = array();
      $query = "SELECT `id`, `property`, `value`
                FROM `glpi_plugin_fusioninventory_collects_wmis_contents`
                WHERE `computers_id` = '".$computers_id."'
                  AND `plugin_fusioninventory_collects_wmis_id` =
                  '".$collects_wmis_id."'";
      $result = $DB->query($query);
      while ($data = $DB->fetch_assoc($result)) {
         $wmi_id = $data['id'];
         unset($data['id']);
         $data1 = Toolbox::addslashes_deep($data);
         $db_wmis[$wmi_id] = $data1;
      }

      unset($wmi_data['_sid']);
      foreach ($wmi_data as $key => $value) {
         foreach ($db_wmis as $keydb => $arraydb) {
            if ($arraydb['property'] == $key) {
               $input = array('property' => $arraydb['property'],
                              'id'       => $keydb,
                              'value'    => $value);
               $this->update($input);
               unset($wmi_data[$key]);
               unset($db_wmis[$keydb]);
               break;
            }
         }
      }

      foreach ($db_wmis as $id => $data) {
         $this->delete(array('id' => $id), true);
      }
      foreach($wmi_data as $key => $value) {
         $input = array(
            'computers_id' => $computers_id,
            'plugin_fusioninventory_collects_wmis_id' => $collects_wmis_id,
            'property'     => $key,
            'value'        => $value
         );
         $this->add($input);
      }
   }



   /**
    * Display wmi information in collect form (tab wmi)
    *
    * @param integer $collects_id id of collect
    */
   function showForCollect($collects_id) {

      $a_colregs = getAllDatasFromTable('glpi_plugin_fusioninventory_collects_wmis',
                                              "`plugin_fusioninventory_collects_id`='".$collects_id."'");
      foreach ($a_colregs as $data) {
         $this->showForCollectWmi($data['id']);
      }
   }



   /**
    * Display wmi information of computer
    *
    * @param integer $computers_id id of computer
    */
   function showForComputer($computers_id) {

      $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th>".__('Moniker', 'fusioninventory')."</th>";
      echo "<th>".__('Class', 'fusioninventory')."</th>";
      echo "<th>".__('Property', 'fusioninventory')."</th>";
      echo "<th>".__('Value', 'fusioninventory')."</th>";
      echo "</tr>";

      $a_data = $this->find("`computers_id`='".$computers_id."'",
                              "`plugin_fusioninventory_collects_wmis_id`,
                                 `property`");
      foreach ($a_data as $data) {
         echo "<tr class='tab_bg_1'>";
         echo '<td>';
         $pfCollect_Wmi->getFromDB($data['plugin_fusioninventory_collects_wmis_id']);
         echo $pfCollect_Wmi->fields['moniker'];
         echo '</td>';
         echo '<td>';
         echo $pfCollect_Wmi->fields['class'];
         echo '</td>';
         echo '<td>';
         echo $data['property'];
         echo '</td>';
         echo '<td>';
         echo $data['value'];
         echo '</td>';
         echo "</tr>";
      }
      echo '</table>';
   }



   /**
    * Display wmi information of collect_wmi_id
    *
    * @param integer $collects_wmis_id
    */
   function showForCollectWmi($collects_wmis_id) {
      $pfCollect_Wmi = new PluginFusioninventoryCollect_Wmi();
      $computer = new Computer();

      $pfCollect_Wmi->getFromDB($collects_wmis_id);

      echo "<table class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th colspan='3'>";
      echo $pfCollect_Wmi->fields['class'];
      echo "</th>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>".__('Computer')."</th>";
      echo "<th>".__('Property', 'fusioninventory')."</th>";
      echo "<th>".__('Value', 'fusioninventory')."</th>";
      echo "</tr>";

      $a_data = $this->find("`plugin_fusioninventory_collects_wmis_id`='".$collects_wmis_id."'",
                              "`property`");
      foreach ($a_data as $data) {
         echo "<tr class='tab_bg_1'>";
         echo '<td>';
         $computer->getFromDB($data['computers_id']);
         echo $computer->getLink(1);
         echo '</td>';
         echo '<td>';
         echo $data['property'];
         echo '</td>';
         echo '<td>';
         echo $data['value'];
         echo '</td>';
         echo "</tr>";
      }
      echo '</table>';
   }
}

?>
