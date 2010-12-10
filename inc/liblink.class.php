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

class PluginFusinvinventoryLiblink extends CommonDBTM {
   
   function __construct() {
      $this->table = "glpi_plugin_fusinvinventory_liblink";
      $this->type = 'PluginFusinvinventoryLiblink';
   }


   static function addComputerInDB($Computer_id, $libFilename) {
      $input = array();
      $input["computers_id"] = $Computer_id;
      $input["filename"] = $libFilename;
      $PluginFusinvinventoryLiblink = new PluginFusinvinventoryLiblink();
      $PluginFusinvinventoryLiblink->add($input);
   }


   
   function deleteComputerInLib($items_id) {

      $a_link = $this->find("`computers_id`='".$items_id."' ");
      foreach ($a_link as $data) {
         $criterias = file_get_contents(GLPI_DOC_DIR."/_plugins/fusioninventory/machines/".$data['filename']."/criterias");
         $a_criteria = explode(",", $criterias);
         foreach ($a_criteria as $filename) {
            $a_filename = explode('_plugins/fusioninventory', $filename);
            if (file_exists(GLPI_DOC_DIR."/_plugins/fusioninventory/".$a_filename[1]."/".$data['filename'])) {
               rmdir(GLPI_DOC_DIR."/_plugins/fusioninventory/".$a_filename[1]."/".$data['filename']);
               $files = scandir(GLPI_DOC_DIR."/_plugins/fusioninventory/".$a_filename[1]);
               $key = array_search('.', $files);
               if (isset($key)) {
                  unset($files[$key]);
               }
               $key = array_search('..', $files);
               if (isset($key)) {
                  unset($files[$key]);
               }
               if (count($files) == "0") {
                  rmdir(GLPI_DOC_DIR."/_plugins/fusioninventory/".$a_filename[1]);
               }
            }
         }
         unlink(GLPI_DOC_DIR."/_plugins/fusioninventory/machines/".$data['filename']."/criterias");
         unlink(GLPI_DOC_DIR."/_plugins/fusioninventory/machines/".$data['filename']."/infos.file");
         rmdir(GLPI_DOC_DIR."/_plugins/fusioninventory/machines/".$data['filename']);
         $this->delete($data);
      }



   }
   
}

?>