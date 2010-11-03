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

class PluginFusinvdeployPackage_File extends CommonDBTM {

   function __construct() {
		$this->table = "glpi_plugin_fusinvdeploy_packages_files";
		$this->type = "PluginFusinvdeployPackageFile";
	}


   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvdeploy']['packagefiles'][0];
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


   function listFiles($id, $options=array()) {
      global $DB,$LANG;
      
      $PluginFusinvdeployFile = new PluginFusinvdeployFile;

      $a_list = $this->find("`plugin_fusinvdeploy_packages_id`='".$id."'");
      $options['colspan'] = 1;
      $this->fields['id'] = 1;
      $this->showFormHeader($options);
      
      echo "<tr>";
      echo "<th>";
      echo "Fichier";
      echo "</th>";
      echo "<th>";
      echo $LANG['plugin_fusinvdeploy']['files'][5];
      echo "</th>";
      echo "</tr>";
      foreach($a_list as $packagefile_id=>$data) {
         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         $PluginFusinvdeployFile->getFromDB($data['plugin_fusinvdeploy_files_id']);
         echo $PluginFusinvdeployFile->getLink(0);
         echo " (".$PluginFusinvdeployFile->fields['filename'].")";
         echo "</td>";
         echo "<td>";
         echo "<input type='text' name='packagepath' size='40' value='".$data["packagepath"]."'/>";
         echo "</td>";
         echo "</tr>";
      }
      $this->showFormButtons($options);

      echo "<br/>";

      $this->getEmpty();
      $options['colspan'] = 2;
      $this->fields['id'] = 0;
      $this->showFormHeader($options);
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo "Fichier : ";
      echo "</td>";
      echo "<td>";
      $droptions = array();
      $droptions['entity'] = $_SESSION['glpiactive_entity'];
      $droptions['entity_sons'] = 1;
      Dropdown::show('PluginFusinvdeployFile', $droptions);
      echo "</td>";
      
      echo "<td>";
      echo $LANG['plugin_fusinvdeploy']['files'][5]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<input type='text' name='packagepath' size='40' value='/'/>";
      echo "</td>";
      echo "</tr>";

      echo "<input type='hidden' name='plugin_fusinvdeploy_packages_id' value='".$id."'/>";
      
      $this->showFormButtons($options);

   }
   
}

?>