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
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryInventoryComputerStorage extends CommonDBTM {

   static $rightname = 'computer';


   static function getTypeName($nb=0) {
      return __('Storage', 'fusioninventory');
   }



   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getType() == 'Computer') {
         if (Session::haveRight('computer', READ)) {
            $a_nb = countElementsInTable(
                        getTableForItemType("PluginFusioninventoryInventoryComputerStorage"),
                        "`computers_id`='".$item->getID()."'");
            if (count($a_nb) > 0) {
//               return self::createTabEntry(__('Storage', 'fusioninventory'));

            }
         }
      }
      return '';
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getID() > 0) {
         $pfInventoryComputerStorage = new self();
         $pfInventoryComputerStorage->showStorage($item->getID());
      }

      return TRUE;
   }



   function showStorage($computers_id) {
      global $DB;

      $pficStorage_Storage = new PluginFusioninventoryInventoryComputerStorage_Storage();

      $totalwidthdiv = 830;

      $a_levels = array();
      $a_levelname = array();
      $a_children = array();
      $higherlevel = 0;
      $higherwidth = 0;
      $query = "SELECT * FROM `glpi_plugin_fusioninventory_inventorycomputerstoragetypes`
         ORDER BY `level`";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $a_levels[$data['level']] = array();
         $a_levelname[$data['level']] = $data['name'];
         $width = 0;
         $querys = "SELECT * FROM `glpi_plugin_fusioninventory_inventorycomputerstorages`
            WHERE `computers_id`='".$computers_id."'
               AND `plugin_fusioninventory_inventorycomputerstoragetypes_id`='".$data['id']."'";
         $results = $DB->query($querys);
         while ($datas = $DB->fetch_array($results)) {
            if ($higherlevel < $data['level']) {
               $higherlevel = $data['level'];
            }
            $width += $datas['totalsize'];
            $a_levels[$data['level']][$datas['id']] = $datas;
            $a_children[$datas['id']] = $pficStorage_Storage->getChildren($data['id'],
                                                                          $data['level']);
         }
         if ($higherwidth < $width) {
            $higherwidth = $width;
         }
      }

      $higherrow = 0;
      $a_row = array();
      foreach ($a_levels as $lev=>$data) {
         if (count($data) > $higherrow) {
            $higherrow = count($data);
         }
         $a_row[$lev] = count($data);
      }

      echo "<div style='height: ".(count($a_levels) * 80)."px;'>";
      $levelnumber = 0;
      $a_pos = array();
      $a_position = array();
      $a_postmp = array();
      $id_used = array();
      $a_size = array();
      foreach ($a_levels as $lev=>$data) {
         if (count($data) > 0) {
            echo "<div style='position: absolute; top: ".((count($a_levels) - $levelnumber) * 45).
                    "px;left: 130px'>";

            echo "<div class='storage'
               style='width: 120px;".
                 "left: -130px; font-weight: bold;'>";
            echo $a_levelname[$lev];
            echo "</div>";
            $a_postmp = array();

            $a_posline = 0;
            foreach ($data as $id=>$a_storage) {
               $totalwidthdivtmp = $totalwidthdiv;
               $totalwidthdivtmp -= ($a_row[$lev] * 5);
               $a_parents = $pficStorage_Storage->getParent($id, $lev);
               if (count($a_parents) == 0) {
                  // No parents
                  $wid = (($totalwidthdivtmp * $a_storage['totalsize']) / $higherwidth);

                  $a_size[$id][$id] = $wid;

                  if (!isset($a_pos[$id])) {
                     $a_pos[$id] = $a_posline;
                     $a_postmp[$id] = $a_posline;
                  }
                  if ($a_pos[$id] > 0
                          && $a_pos[$id] != $a_postmp[$id]) {
                     $a_pos[$id] += ($higherrow - ($a_row[$lev] -1)) * 5;
                     $a_postmp[$id] = $a_pos[$id];
                  }
                  $a_link = array($id."-0");
                  echo "<div class='storage' id='storage".$id."-0'
                     onmouseover='chbgHover([\"".implode('", "', $a_link)."\"])'
                     onmouseout='chbgOut([\"".implode('", "', $a_link)."\"])'
                   style='width: ".$wid."px;".
                       "left: ".$a_pos[$id]."px'>";
                  echo $a_storage['name'];
                  echo "</div>";
                  $a_position[$id][] = array('pos'       => $a_pos[$id],
                                             'width'     => $wid,
                                             'totalsize' => $a_storage['totalsize']);
                  $a_posline += $wid + 5;
                  $id_used[$id] = $id;
               } else {
                  // Have parents
                  $i = 0;
                  foreach ($a_parents as $parents_id=>$level) {
                     $a_parent_children = $pficStorage_Storage->getChildren($parents_id, $level);
                     $remaining_size = $a_storage['totalsize'];

                     foreach ($a_position[$parents_id] as $dataposition) {
                        if ($remaining_size > 0) {
                           if (isset($a_postmp[$parents_id])) {
                              $position_temp = $a_postmp[$parents_id];
                           } else {
                              $position_temp = $dataposition['pos'];
                           }
                           if ($remaining_size > $dataposition['totalsize']) {
                              $cursize = $dataposition['totalsize'];
                              $remaining_size -= $dataposition['totalsize'];
                           } else {
                              $cursize = $remaining_size;
                              $remaining_size = 0;
                              $a_postmp[$parents_id] = $position_temp + 5;
                          }
                           if ($dataposition['totalsize'] > 0) {

                              if ($remaining_size == 0
                                      && count($a_parent_children) > 1) {
                                 $wid = (($dataposition['width'] -
                                             (5 * (count($a_parent_children) - 1)))
                                         * $cursize) / $dataposition['totalsize'];
                                 $a_postmp[$parents_id] += $wid;
                              } else {
                                 $wid = ($dataposition['width'] * $cursize) /
                                             $dataposition['totalsize'];
                              }
                              $wid = round($wid);

                              // parents_link
                              $a_link = array();
                              for ($j = 0; $j < count($a_position[$parents_id]); $j++) {
                                 $a_link[] = $id."-".$j;
                              }
                              for ($j = 0; $j < count($a_parents); $j++) {
                                 $a_link[] = $id."-".$j;
                              }

                              $a_link = $this->getStorageLinks($a_link, $id, $lev, $a_position);

                              echo "<div class='storage' id='storage".$id."-".$i."'
                                  onmouseover='chbgHover([\"".implode('", "', $a_link)."\"])'
                                  onmouseout='chbgOut([\"".implode('", "', $a_link)."\"])'
                                 style='width: ".$wid."px;".
                                   "left: ".$position_temp."px'>";
                              echo $a_storage['name'];
                              echo "</div>";
                              $id_used[$id] = $id;
                              $a_position[$id][] = array('pos'       => $position_temp,
                                                         'width'     => $wid,
                                                         'totalsize' => $cursize);
                              $i++;
                           }
                        }
                     }
                  }
               }
            }
            echo "</div>";
            $levelnumber++;
            $a_pos = $a_postmp;
         }
      }
      echo "</div>";

      echo '<script>
         function chbgHover(params) {
            for(i=0;i<params.length;i++){
               if(document.getElementById(\'storage\' + params[i])) {
                 document.getElementById(\'storage\' + params[i]).style.backgroundColor = "#fb8080";
               }
            }
         }
         function chbgOut(params) {
            for(i=0;i<params.length;i++){
               if(document.getElementById(\'storage\' + params[i])) {
                  document.getElementById(\'storage\' + params[i]).style.backgroundColor = "white";
               }
            }
         }
      </script>';
   }



   function getStorageLinks($a_link, $id, $lev, $a_position) {
      $pficStorage_Storage = new PluginFusioninventoryInventoryComputerStorage_Storage();
      $a_par = $pficStorage_Storage->getParent($id, $lev);

      foreach ($a_par as $parid=>$parlev) {
         for ($j = 0; $j < count($a_position[$parid]); $j++) {
            $a_link[] = $parid."-".$j;
         }
         $a_link = $this->getStorageLinks($a_link, $parid, $parlev, $a_position);
      }
      return $a_link;
   }
}

?>
