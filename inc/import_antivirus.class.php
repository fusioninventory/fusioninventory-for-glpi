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

class PluginFusinvinventoryImport_Antivirus extends CommonDBTM {


   /**
   * Add or update antivirus
   *
   * @param $type value "add" or "update"
   * @param $items_id integer
   *     - if add    : id of the computer
   *     - if update : id of the antivirus
   * @param $dataSection array all values of the section 
   *
   *@return id of the antivirus or false
   *
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"], 
              "import_antivirus") == '0') {
         return;
      }

      $PluginFusinvinventoryAntivirus = new PluginFusinvinventoryAntivirus();

      $antivirus=array();
      $id_antivirus = 0;
      if ($type == "update") {
         $id_antivirus = $items_id;
         $PluginFusinvinventoryAntivirus->getFromDB($items_id);
         $antivirus = $PluginFusinvinventoryAntivirus->fields;
      } else if ($type == "add") {
         $id_antivirus = 0;
         $antivirus=array();
         $antivirus['computers_id']=$items_id;
      }

      if (isset($dataSection['NAME'])) {
         $antivirus['name'] = $dataSection['NAME'];
      }
      if (isset($dataSection['COMPANY'])) {
         $antivirus['manufacturers_id'] = Dropdown::importExternal('Manufacturer', $dataSection['COMPANY']);
      }
      if (isset($dataSection['VERSION'])) {
         $antivirus['version'] = $dataSection['VERSION'];
      }
      if (isset($dataSection['ENABLED'])) {
         $antivirus['is_active'] = $dataSection['ENABLED'];
      }
      if (isset($dataSection['UPTODATE'])) {
         $antivirus['uptodate'] = $dataSection['UPTODATE'];
      }

      if (isset($antivirus['name']) && !empty($antivirus["name"])) {
         if ($type == "update") {
            $id_antivirus = $PluginFusinvinventoryAntivirus->update($antivirus);
         } else if ($type == "add") {
            if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
               $antivirus['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
            }
            $id_antivirus = $PluginFusinvinventoryAntivirus->add($antivirus);
         }
      }
      return $id_antivirus;
   }



   /**
   * Delete antivirus
   *
   * @param $items_id integer id of the antivirus
   * @param $idmachine integer id of the computer
   *
   *@return nothing
   *
   **/
   function deleteItem($items_id, $idmachine) {
      $PluginFusinvinventoryAntivirus = new PluginFusinvinventoryAntivirus();
      $PluginFusinvinventoryAntivirus->getFromDB($items_id);
      if ($PluginFusinvinventoryAntivirus->fields['computers_id'] == $idmachine) {
         $PluginFusinvinventoryAntivirus->delete(array("id" => $items_id));
      }
   }
}

?>