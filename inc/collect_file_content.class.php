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
 * This file is used to manage the files found on computr by agent and
 * linked to the computer
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
 * Manage the files found by the collect module of agent.
 */
class PluginFusioninventoryCollect_File_Content extends CommonDBTM {

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
      return __('Find file content', 'fusioninventory');
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
            if ($item->fields['type'] == 'file') {
               $a_colfiles = getAllDatasFromTable('glpi_plugin_fusioninventory_collects_files',
                                                 "`plugin_fusioninventory_collects_id`='".$item->getID()."'");
               if (count($a_colfiles) == 0) {
                  return '';
               }
               $in = array_keys($a_colfiles);
               if (countElementsInTable('glpi_plugin_fusioninventory_collects_files_contents',
                                "`plugin_fusioninventory_collects_files_id` IN ('".implode("','", $in)."')") > 0) {
                  return __('Find file content', 'fusioninventory');
               }
            }
         } else if (get_class($item) == 'Computer') {
            if (countElementsInTable('glpi_plugin_fusioninventory_collects_files_contents',
                             "`computers_id`='".$item->getID()."'") > 0) {
               return __('Find file content', 'fusioninventory');
            }
         }
      }
      return '';
   }

   /**
    * Delete all files contents linked to the computer (most cases when delete a
    * computer)
    *
    * @param integer $computers_id
    */
   static function cleanComputer($computers_id) {
      $file_content = new self();
      $file_content->deleteByCriteria(array('computers_id' => $computers_id));
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
      $pfCollect_File = new PluginFusioninventoryCollect_File_Content();
      if (get_class($item) == 'PluginFusioninventoryCollect') {
         $pfCollect_File->showForCollect($item->getID());
      } else if (get_class($item) == 'Computer') {
         $pfCollect_File->showForComputer($item->getID());
      }
      return true;
   }


   /**
    * Update computer files (add and update files) related to this
    * collect file id
    *
    * @global object $DB
    * @param integer $computers_id id of the computer
    * @param integer $collects_files_id id of collect_file
    * @param integer $taskjobstates_id id of taskjobstate
    */
   function updateComputer($computers_id, $file_data, $collects_files_id) {
      foreach($file_data as $key => $value) {
         $input = array(
            'computers_id' => $computers_id,
            'plugin_fusioninventory_collects_files_id' => $collects_files_id,
            'pathfile'     => str_replace(['\\', '//'], ['/', '/'], $value['path']),
            'size'         => $value['size']
         );
         $this->add($input);
      }
   }


   /**
    * Show all files defined
    *
    * @param integer $collects_id id of collect
    */
   function showForCollect($collects_id) {
      $a_colfiles = getAllDatasFromTable('glpi_plugin_fusioninventory_collects_files',
                                              "`plugin_fusioninventory_collects_id`='".$collects_id."'");
      foreach ($a_colfiles as $data) {
         $this->showForCollectFile($data['id']);
      }
   }



   /**
    * Display files found on the computer
    *
    * @param integer $computers_id id of the computer
    */
   function showForComputer($computers_id) {
      $pfCollect_File = new PluginFusioninventoryCollect_File();

      echo "<table class='tab_cadre_fixe'>";

      $a_data = $this->find("`computers_id`='".$computers_id."'",
                              "`plugin_fusioninventory_collects_files_id`,
                                 `pathfile`");
      $previous_key = 0;
      foreach ($a_data as $data) {
         $pfCollect_File->getFromDB($data['plugin_fusioninventory_collects_files_id']);
         if ($previous_key != $data['plugin_fusioninventory_collects_files_id']) {
            echo "<tr class='tab_bg_1'>";
            echo '<th colspan="3">';
            echo $pfCollect_File->fields['name']. ": ".$pfCollect_File->fields['dir'];
            echo '</th>';
            echo '</tr>';

            echo "<tr>";
            echo "<th>".__('Path/file', 'fusioninventory')."</th>";
            echo "<th>".__('Size', 'fusioninventory')."</th>";
            echo "</tr>";

            $previous_key = $data['plugin_fusioninventory_collects_files_id'];
         }

         echo "<tr class='tab_bg_1'>";
         echo '<td>';
         echo $data['pathfile'];
         echo '</td>';
         echo '<td>';
         echo Toolbox::getSize($data['size']);
         echo '</td>';
         echo "</tr>";
      }
      echo '</table>';
   }



   /**
    * Display all files found on all computers related to the collect file
    *
    * @param integer $collects_files_id id of collect_file
    */
   function showForCollectFile($collects_files_id) {
      $pfCollect_File = new PluginFusioninventoryCollect_File();
      $computer = new Computer();

      $pfCollect_File->getFromDB($collects_files_id);

      echo "<table class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th colspan='3'>";
      echo $pfCollect_File->fields['name'];
      echo "</th>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>".__('Computer')."</th>";
      echo "<th>".__('pathfile', 'fusioninventory')."</th>";
      echo "<th>".__('Size', 'fusioninventory')."</th>";
      echo "</tr>";

      $a_data = $this->find("`plugin_fusioninventory_collects_files_id`='".$collects_files_id."'",
                              "`pathfile`");
      foreach ($a_data as $data) {
         echo "<tr class='tab_bg_1'>";
         echo '<td>';
         $computer->getFromDB($data['computers_id']);
         echo $computer->getLink(1);
         echo '</td>';
         echo '<td>';
         echo $data['pathfile'];
         echo '</td>';
         echo '<td>';
         echo Toolbox::getSize($data['size']);
         echo '</td>';
         echo "</tr>";
      }
      echo '</table>';
   }

}

?>
