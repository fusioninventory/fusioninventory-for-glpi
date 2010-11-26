<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file: management of communication with agents
// ----------------------------------------------------------------------
/**
 * The datas are XML encoded and compressed with Zlib.
 * XML rules :
 * - XML tags in uppercase
 **/

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class 
 **/
class PluginFusinvinventoryImport_Antivirus extends CommonDBTM {


   function AddUpdateItem($type, $items_id, $dataSection) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"], 
              "import_antivirus") == '0') {
         return;
      }

      $PluginFusinvinventoryAntivirus = new PluginFusinvinventoryAntivirus();

      $antivirus=array();
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
            $antivirus['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
            $id_antivirus = $PluginFusinvinventoryAntivirus->add($antivirus);
         }
      }
      return $id_antivirus;
   }


   
   function deleteItem($items_id, $idmachine) {
      $PluginFusinvinventoryAntivirus = new PluginFusinvinventoryAntivirus();
      $PluginFusinvinventoryAntivirus->getFromDB($items_id);
      if ($PluginFusinvinventoryAntivirus->fields['computers_id'] == $idmachine) {
         $PluginFusinvinventoryAntivirus->delete(array("id" => $items_id));
      }
   }

}

?>