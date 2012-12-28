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

class PluginFusinvinventoryImport_LicenseInfo extends CommonDBTM {


   /**
   * Add or update license
   *
   * @param $type value "add" or "update"
   * @param $items_id integer
   *     - if add    : id of the computer
   *     - if update : id of the license
   * @param $dataSection array all values of the section 
   *
   * @return id of the license or false
   *
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $pfConfig = new PluginFusioninventoryConfig();
# not implemented yet
#      if ($pfConfig->getValue("import_license") == '0') {
#         return;
#      }

      $pfLicenseInfo = new PluginFusioninventoryComputerLicenseInfo();

      $licenseInfo=array();
      $id_licenseInfo = 0;
      if ($type == "update") {
         $id_licenseInfo = $items_id;
         $pfLicenseInfo->getFromDB($items_id);
         $licenseInfo = $pfLicenseInfo->fields;
      } else if ($type == "add") {
         $id_licenseInfo = 0;
         $licenseInfo=array();
         $licenseInfo['computers_id']=$items_id;
      }


      $a_XMLnode = array('NAME',
                         'FULLNAME',
                         'KEY',
                         'IS_TRIAL',
                         'IS_UPDATE',
                         'IS_OEM',
                         'ACTIVATION_DATE');
      foreach ($a_XMLnode as $k) {
         if (isset($dataSection[$k])) {
            if ($k == "KEY") {
               $licenseInfo['serial'] = $dataSection[$k];
            } else {
               $licenseInfo[strtolower($k)] = $dataSection[$k];
            }
         }
      }

      if (isset($licenseInfo['name']) && !empty($licenseInfo["name"])) {
         if ($type == "update") {
            $id_licenseInfo = $pfLicenseInfo->update($licenseInfo);
         } else if ($type == "add") {
            if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
               $licenseInfo['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
            }
            $id_licenseInfo = $pfLicenseInfo->add($licenseInfo);
         }
      }
      return $id_licenseInfo;
   }



   /**
   * Delete license
   *
   * @param $items_id integer id of the license
   * @param $idmachine integer id of the computer
   *
   * @return nothing
   *
   **/
   function deleteItem($items_id, $idmachine) {
      $pfLicenseInfo = new PluginFusioninventoryComputerLicenseInfo();
      $pfLicenseInfo->getFromDB($items_id);
      if ($pfLicenseInfo->fields['computers_id'] == $idmachine) {
         $pfLicenseInfo->delete(array("id" => $items_id), 0, $_SESSION["plugin_fusinvinventory_history_add"]);
      }
   }
}

?>