<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2014 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2014 FusionInventory team
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
      return TRUE;
   }



   function updateComputer($computers_id, $collects_files_id, $taskjobstates_id) {
      global $DB;

      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjobstate->getFromDB($taskjobstates_id);

      if ($pfTaskjobstate->fields['specificity'] == '') {
         $a_data = $this->find("`computers_id` = '".$computers_id."'
                 AND `plugin_fusioninventory_collects_files_id`=
                  '".$collects_files_id."'");
         foreach ($a_data as $data) {
            $this->delete($data);
         }
         return;
      }
      // Have files found
      $file_data = importArrayFromDB($pfTaskjobstate->fields['specificity']);

      $db_files = array();
      $query = "SELECT `id`, `pathfile`, `size`
            FROM `glpi_plugin_fusioninventory_collects_files_contents`
         WHERE `computers_id` = '".$computers_id."'
              AND `plugin_fusioninventory_collects_files_id`=
               '".$collects_files_id."'";
      $result = $DB->query($query);
      while ($data = $DB->fetch_assoc($result)) {
         $idtmp = $data['id'];
         unset($data['id']);
         $data1 = Toolbox::addslashes_deep($data);
         $db_files[$idtmp] = $data1;
      }

      foreach ($file_data as $key => $array) {
         foreach ($db_files as $keydb => $arraydb) {
            if ($arraydb['pathfile'] == $array['path']) {
               $input = array();
               $input['id'] = $keydb;
               $input['size'] = $array['size'];
               $this->update($input);
               unset($file_data[$key]);
               unset($db_files[$keydb]);
               break;
            }
         }
      }

      if (count($file_data) == 0
         AND count($db_files) == 0) {
         // Nothing to do
      } else {
         if (count($db_files) != 0) {
            foreach ($db_files as $idtmp => $data) {
               $this->delete(array('id'=>$idtmp), 1);
            }
         }
         if (count($file_data) != 0) {
            foreach($file_data as $key=>$value) {
               $input = array(
                   'computers_id' => $computers_id,
                   'plugin_fusioninventory_collects_files_id' => $collects_files_id,
                   'pathfile'     => $value['path'],
                   'size'         => $value['size']
               );
               $this->add($input);
            }
         }
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



   // all files information sent by agent will be stored in field specificity
   // of table glpi_plugin_fusioninventory_taskjobstates
   function storeTempFilesFound($taskjobstates_id, $a_values) {
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();

      $pfTaskjobstate->getFromDB($taskjobstates_id);
      $a_specificity = array();
      if ($pfTaskjobstate->fields['specificity'] != '') {
         $a_specificity = importArrayFromDB($pfTaskjobstate->fields['specificity']);
      }
      unset($a_values['_cpt']);
      $a_specificity[] = $a_values;
      $input = array();
      $input['id'] = $pfTaskjobstate->fields['id'];
      $input['specificity'] = exportArrayToDB($a_specificity);
      $pfTaskjobstate->update($input);
   }
}

?>
