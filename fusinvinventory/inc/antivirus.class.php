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

class PluginFusinvinventoryAntivirus extends CommonDBTM {
   
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvinventory']['antivirus'][0];
   }

   function canCreate() {
      return Session::haveRight('computer', 'w');
   }


   function canView() {
      return Session::haveRight('computer', 'r');
   }

   
   
   function getSearchOptions() {
      global $LANG;

      $tab = array();
      $tab['common'] = $LANG['common'][32];
      
      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'version';
      $tab[1]['name']          = "Version";
      $tab[1]['type']          = 'text';
      
      return $tab;
   }
      
   
   

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      if ($item->getType() == 'Computer') {
         if (Session::haveRight('computer', "r")) {
            $a_antivirus = $this->find("`computers_id`='".$item->getID()."'", '', 1);
            if (count($a_antivirus) > 0) {
               return self::createTabEntry($LANG['plugin_fusinvinventory']['antivirus'][0]);
            }
         }
      }
      return '';
   }

   
   
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getID() > 0) {
         $pfAntivirus = new self();
         $pfAntivirus->showForm($item->getID());
      }

      return true;
   }
   
   
   
   
   static function addHistory($item){
      
      foreach ($item->oldvalues as $field=>$old_value) {
         $changes = array();
         $changes[0] = 0;
         $changes[1] = '';
         $changes[2] = "Antivirus.".$field." : ".$old_value." --> ".$item->fields[$field];
         Log::history($item->fields['computers_id'], 
                     "Computer", 
                     $changes, 
                     'PluginFusinvinventoryAntivirus', 
                     Log::HISTORY_LOG_SIMPLE_MESSAGE);         
      }
   }
   
   
      
   /**
   * Display form for antivirus
   *
   * @param $items_id integer ID of the antivirus
   * @param $options array
   *
   * @return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {
      global $LANG;

      $a_antivirus = $this->find("`computers_id`='".$items_id."'");
      $antivirusData = array();
      if (count($a_antivirus) > 0) {
         $antivirusData = current($a_antivirus);
      }
      
      echo "<table class='tab_cadre_fixe' cellpadding='1'>";
 
      if (count($antivirusData) == '0') {
         echo "<tr>";
         echo "<th>".$LANG['plugin_fusinvinventory']['antivirus'][0];
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'><br/><strong>";
         echo $LANG['plugin_fusinvinventory']['antivirus'][1]."<br/>";
         echo "</strong><br/></td>";
         echo "</tr>";
      } else {
         echo "<tr>";
         echo "<th colspan='4'>".$LANG['plugin_fusinvinventory']['antivirus'][0];
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo $LANG['common'][16]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo $antivirusData['name'];
         echo "</td>";
         echo "<td>";
         echo $LANG['common'][60]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getYesNo($antivirusData['is_active']);
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo $LANG['common'][5]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getDropdownName('glpi_manufacturers', $antivirusData["manufacturers_id"]);
         echo "</td>";
         echo "<td>";
         echo $LANG['plugin_fusinvinventory']['antivirus'][3]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo Dropdown::getYesNo($antivirusData['uptodate']);
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo $LANG['plugin_fusinvinventory']['antivirus'][2]."&nbsp;:";
         echo "</td>";
         echo "<td>";
         echo $antivirusData['version'];
         echo "</td>";
         echo "<td colspan='2'>";
         echo "</td>";
         echo "</tr>";

      }
      echo "</table>";
      return true;
   }



   /**
   * Delete antivirus on computer
   *
   * @param $items_id integer id of the computer
   *
   * @return nothing
   *
   **/
   static function cleanComputer($items_id) {
      $pfAntivirus = new PluginFusinvinventoryAntivirus();
      $a_antivirus = $pfAntivirus->find("`computers_id`='".$items_id."'");
      if (count($a_antivirus) > 0) {
         $input = current($a_antivirus);
         $pfAntivirus->delete($input);
      }
   }
}

?>