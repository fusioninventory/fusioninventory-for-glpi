<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryCollect_File_Content extends CommonDBTM {

   static $rightname = 'plugin_fusioninventory_collect';

   static function getTypeName($nb=0) {
      return __('Find file content', 'fusioninventory');
   }



   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getID() > 0) {
         if (get_class($item) == 'PluginFusioninventoryCollect') {
            if ($item->fields['type'] == 'file') {
               $a_colfiles = getAllDatasFromTable('glpi_plugin_fusioninventory_collects_files',
                                                 "`plugin_fusioninventory_collects_id`='".$item->getID()."'");
               if (count($a_colfiles) == 0) {
                  return array();
               }
               $in = array();
               foreach ($a_colfiles as $id=>$data) {
                  $in[] = $id;
               }
               if (countElementsInTable('glpi_plugin_fusioninventory_collects_files_contents',
                                "`plugin_fusioninventory_collects_files_id` IN ('".implode("','", $in)."')") > 0) {
                  return array(__('Find file content', 'fusioninventory'));
               }
            }
         } else if (get_class($item) == 'Computer') {
            if (countElementsInTable('glpi_plugin_fusioninventory_collects_files_contents',
                             "`computers_id`='".$item->getID()."'") > 0) {
               return array(__('Find file content', 'fusioninventory'));
            }
         }
      }
      return array();
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      $pfCollect_File = new PluginFusioninventoryCollect_File_Content();
      if (get_class($item) == 'PluginFusioninventoryCollect') {
         $pfCollect_File->showForCollect($item->getID());
      } else if (get_class($item) == 'Computer') {
         $pfCollect_File->showForComputer($item->getID());
      }
      return true;
   }



   function updateComputer($computers_id, $file_data, $collects_files_id) {
      foreach($file_data as $key => $value) {
         $input = array(
            'computers_id' => $computers_id,
            'plugin_fusioninventory_collects_files_id' => $collects_files_id,
            'pathfile'     => $value['path'],
            'size'         => $value['size']
         );
         $id = $this->add($input);
      }
   }



   function showForCollect($collects_id) {
      $a_colfiles = getAllDatasFromTable('glpi_plugin_fusioninventory_collects_files',
                                              "`plugin_fusioninventory_collects_id`='".$collects_id."'");
      foreach ($a_colfiles as $data) {
         $this->showForCollectFile($data['id']);
      }
   }



   function showForComputer($computers_id) {

      echo "<table class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th>".__('Path/file', 'fusioninventory')."</th>";
      echo "<th>".__('Size', 'fusioninventory')."</th>";
      echo "</tr>";

      $a_data = $this->find("`computers_id`='".$computers_id."'",
                              "`plugin_fusioninventory_collects_files_id`,
                                 `pathfile`");
      foreach ($a_data as $data) {
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
