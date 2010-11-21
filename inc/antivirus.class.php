<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryAntivirus extends CommonDBTM {
   
   function __construct() {
      $this->table = "glpi_plugin_fusinvinventory_antivirus";
      $this->type = 'PluginFusinvinventoryAntivirus';
   }


   
   static function getTypeName() {
      global $LANG;

      return "antivirus";
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function canCancel() {
      return true;
   }

   function canUndo() {
      return true;
   }

   function canValidate() {
      return true;
   }


   function showForm($items_id) {
      global $LANG;

      $a_antivirus = $this->find("`computers_id`='".$items_id."'");
      $antivirusData = array();
      foreach ($a_antivirus as $antivirus_id => $antivirusData) {

      }

      echo "<table class='tab_cadre_fixe' cellpadding='1'>";
 
      if (count($antivirusData) == '0') {
         echo "<tr>";
         echo "<th>".$LANG['plugin_fusinvinventory']["antivirus"][0];
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'><br/><strong>";
         echo $LANG['plugin_fusinvinventory']['antivirus'][1]."<br/>";
         echo "</strong><br/></td>";
         echo "</tr>";
      } else {
         echo "<tr>";
         echo "<th colspan='4'>".$LANG['plugin_fusinvinventory']["antivirus"][0];
         echo "</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo $LANG['common'][16];
         echo "</td>";
         echo "<td>";
         echo "<input type='text' name='name' value='".$antivirusData['name']."' size='40'/>";
         echo "</td>";
         echo "<td>";
         echo $LANG['common'][60];
         echo "</td>";
         echo "<td>";
         Dropdown::showYesNo('is_active', $antivirusData['is_active']);
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo $LANG['common'][5];
         echo "</td>";
         echo "<td>";
         Dropdown::show('Manufacturer', array('value' => $antivirusData["manufacturers_id"]));
         echo "</td>";
         echo "<td>";
         echo $LANG['plugin_fusinvinventory']['antivirus'][3];
         echo "</td>";
         echo "<td>";
         Dropdown::showYesNo('is_active', $antivirusData['uptodate']);
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo $LANG['plugin_fusinvinventory']['antivirus'][2];
         echo "</td>";
         echo "<td>";
         echo "<input type='text' name='name' value='".$antivirusData['version']."' size='40'/>";
         echo "</td>";
         echo "<td colspan='2'>";
         echo "</td>";
         echo "</tr>";

      }
      echo "</table>";
   }

}

?>