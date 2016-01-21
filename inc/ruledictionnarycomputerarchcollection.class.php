<?php
/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2015 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2015 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */


class PluginFusioninventoryRuleDictionnaryComputerArchCollection extends RuleDictionnaryDropdownCollection {
   public $item_table  = "glpi_plugin_fusioninventory_computerarchs";

   /**
    * @see RuleCollection::getTitle()
   **/
   function getTitle() {
      return __('Dictionnary of computer architectures', 'fusioninventory');
   }


   function replayRulesOnExistingDB($offset=0, $maxtime=0, $items=array(), $params=array()) {
      global $DB;

      // Model check : need to check using manufacturer extra data so specific function
      if (strpos($this->item_table,'models')) {
         return $this->replayRulesOnExistingDBForModel($offset, $maxtime);
      }

      if (isCommandLine()) {
         printf(__('Replay rules on existing database started on %s')."\n", date("r"));
      }

      // Get All items
      $Sql = "SELECT *
              FROM `".$this->item_table."`";
      if ($offset) {
         $Sql .= " LIMIT ".intval($offset).",999999999";
      }
      $result  = $DB->query($Sql);

      $nb      = $DB->numrows($result)+$offset;
      $i       = $offset;
      if ($result
          && ($nb > $offset)) {
         // Step to refresh progressbar
         $step              = (($nb > 20) ? floor($nb/20) : 1);
         $send              = array();
         $send["tablename"] = $this->item_table;

         while ($data = $DB->fetch_assoc($result)) {
            if (!($i % $step)) {
               if (isCommandLine()) {
                  //TRANS: %1$s is a row, %2$s is total rows
                  printf(__('Replay rules on existing database: %1$s/%2$s')."\r", $i, $nb);
               } else {
                  Html::changeProgressBarPosition($i, $nb, "$i / $nb");
               }
            }

            //Replay Type dictionnary
            $ID = Dropdown::importExternal(getItemTypeForTable($this->item_table),
                                           addslashes($data["name"]), -1, array(),
                                           addslashes($data["comment"]));
            if ($data['id'] != $ID) {
               $tomove[$data['id']] = $ID;
               $type                = GetItemTypeForTable($this->item_table);

               // select all computer with old arch top update it
               $query_fi_comp = "UPDATE `glpi_plugin_fusioninventory_inventorycomputercomputers` 
               SET plugin_fusioninventory_computerarchs_id = '$ID'
               WHERE plugin_fusioninventory_computerarchs_id = '".$data['id']."'";
               $DB->query($query_fi_comp);

               //delete unused arch
               if ($dropdown = getItemForItemtype($type)) {
                  $dropdown->delete(array('id'          => $data['id'],
                                          '_replace_by' => $ID));
               }
            }
            $i++;

            if ($maxtime) {
               $crt = explode(" ", microtime());
               if (($crt[0]+$crt[1]) > $maxtime) {
                  break;
               }
            }
         } // end while
      }

      if (isCommandLine()) {
         printf(__('Replay rules on existing database started on %s')."\n", date("r"));
      } else {
         Html::changeProgressBarPosition($i, $nb, "$i / $nb");
      }
      return (($i == $nb) ? -1 : $i);
   }
}
?>
