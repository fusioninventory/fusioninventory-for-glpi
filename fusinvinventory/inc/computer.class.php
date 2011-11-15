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

class PluginFusinvinventoryComputer extends CommonDBTM {
   
   static function getTypeName() {
      global $LANG;

      return "";
   }

   function canCreate() {
      return haveRight('computer', 'w');
   }


   function canView() {
      return haveRight('computer', 'r');
   }

   
   function showForm($computers_id) {
      global $LANG;
      
      $a_computerextend = current($this->find("`computers_id`='".$computers_id."'", 
                                              "", 1));
      $inputCext = array();
      if (empty($a_computerextend)) {
         $this->getEmpty();
         $a_computerextend = $this->fields;
      }
      
      echo '<div align="center">';
      echo '<table class="tab_cadre_fixe" style="margin: 0; margin-top: 5px;">';
      echo '<tr>';
      echo '<th colspan="4">'.$LANG['entity'][14].'</th>';
      echo '</tr>';

      echo '<tr class="tab_bg_1">';
      echo '<th colspan="2" width="50%">'.$LANG['plugin_fusinvinventory']['bios'][0].'</th>';
      echo '<th colspan="2">'.$LANG['common'][67].'</th>';
      echo '</tr>';
      
      echo '<tr class="tab_bg_1">';
      echo '<td>'.$LANG['common'][27].'&nbsp;:</td>';
      echo '<td>'.convDate($a_computerextend['bios_date']).'</td>';
      echo "<td>".$LANG['computers'][9]." - ".$LANG['install'][3]." (".strtolower($LANG['common'][27]).")&nbsp;:</td>";
      echo '<td>'.convDate($a_computerextend['operatingsystem_installationdate']).'</td>';
      echo '</tr>';

      echo '<tr class="tab_bg_1">';
      echo '<td>'.$LANG['rulesengine'][78].'&nbsp;:</td>';
      echo '<td>'.$a_computerextend['bios_version'].'</td>';
      echo '<td>'.$LANG['plugin_fusinvinventory']['computer'][1].'&nbsp;:</td>';
      echo '<td>'.$a_computerextend['winowner'].'</td>';
      echo '</tr>';

      echo '<tr class="tab_bg_1">';
      echo '<td>'.$LANG['common'][5].'&nbsp;:</td>';
      echo '<td>';
      echo Dropdown::getDropdownName("glpi_manufacturers", $a_computerextend['bios_manufacturers_id']);
      echo '</td>';
      echo '<td>'.$LANG['plugin_fusinvinventory']['computer'][2].'&nbsp;:</td>';
      echo '<td>'.$a_computerextend['wincompany'].'</td>';
      echo '</tr>';
            
      echo '</table>';
      echo '</div>';
      
   }
   
   
}

?>