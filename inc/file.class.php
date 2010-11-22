<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
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

class PluginFusinvdeployFile extends CommonDBTM {
   function __construct() {
      $this->table = "glpi_plugin_fusinvdeploy_files";
      $this->type = 'PluginFusinvdeployFile';
   }


   
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvdeploy']["files"][0];
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



   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();
//      if ((isset($this->fields['id'])) AND ($this->fields['id'] > 0)){
//         $ong[1]="test";
//      }
      return $ong;
   }


   
   function showForm($id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();
      }

      $this->showTabs($options);
      $options['formoptions'] = " enctype='multipart/form-data'";
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][16]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' value='".$this->fields["name"]."' size='30'/>";
      echo "</td>";
      echo "<td>".$LANG['plugin_fusinvdeploy']['files'][1]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='filename' value='".$this->fields["filename"]."' size='30'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusinvdeploy']['files'][2]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='version' value='".$this->fields["version"]."' size='30'/>";
      echo "</td>";
      echo "<td>".$LANG['plugin_fusinvdeploy']['files'][3]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td></td>";
      echo "<td align='center'>";
      echo "";
      echo "</td>";
      
      if ($this->fields["filename"] == "") {
         echo "<td>".$LANG['plugin_fusinvdeploy']['files'][4]."&nbsp;:</td>";
         echo "<td align='center'>";
         echo "<input type='file' name='uploadfile' size='39'>";
      } else {
         echo "<td colspan='2' align='center'>";
         echo $LANG['document'][26];
      }
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }

}

?>