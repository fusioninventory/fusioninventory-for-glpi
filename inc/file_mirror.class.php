<?php

/*
 * @version $Id: file_mirror.class.php 134 2011-03-16 19:02:08Z wnouh $
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

class PluginFusinvdeployFile_Mirror extends CommonDBTM {
      
   static function cleanForFile($files_id) {
      global $DB;
      $query = "DELETE FROM `glpi_plugin_fusinvdeploy_files_mirrors` 
                WHERE `plugin_fusinvdeploy_files_id`='$files_id'";
      $DB->query($query);
   }
   
   static function getForFile($files_id) {
      $results = getAllDatasFromTable('glpi_plugin_fusinvdeploy_files_mirrors',
                                      "`plugin_fusinvdeploy_files_id`='$files_id'");
      
      $mirrors = array();
      foreach ($results as $result) {
         $mirror = new PluginFusinvdeployMirror();
         if ($result['plugin_fusinvdeploy_mirrors_id']) {
            $mirror->getFromDB($result['plugin_fusinvdeploy_mirrors_id']);
            $mirrors[] = $mirror->fields['url'];
         }
      }
      
      return $mirrors;
   }

}
?>