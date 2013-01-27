<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
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

class PluginFusioninventoryInventoryComputerLibfilter extends CommonDBTM {


    /**
    * get manufacturer from pciid
    *
    * @param $pciid value id of the PCI (vendor identifiant)
    *
    * @return manufacturer name or nothing
    *
    */
    static function getDataFromPCIID($pciid) {
       global $DB;

      $pciidArray = explode(":", $pciid);
      $vendorId = $pciidArray[0];

      $query_select = "SELECT id, name FROM `glpi_plugin_fusioninventory_pcivendors`
        WHERE `vendorid`='".$vendorId."'
           LIMIT 1";
      $resultSelect = $DB->query($query_select);
      if ($DB->numrows($resultSelect) > 0) {
         $data = $DB->fetch_assoc($resultSelect);
         $vendors_name = html_entity_decode($data['name']);
         return $vendors_name;
      } else {
         return "";
      }
    }




   /**
   * get data from vendorid and productid USB
   *
   * @param $vendorId value USB id of vendor
   * @param $productId value id of product
   *
   * @return array (vendor name, device name)
   *
   */
   static function getDataFromUSBID($vendorId, $productId) {
      global $DB;

      $vendorId = strtolower($vendorId);
      $deviceId = strtolower($productId);
      $vendors_name = "";
      $devices_name = "";

      $query_select = "SELECT id, name FROM `glpi_plugin_fusioninventory_usbvendors`
        WHERE `vendorid`='".$vendorId."'
        LIMIT 1";
      $resultSelect = $DB->query($query_select);
      if ($DB->numrows($resultSelect) > 0) {
         $data = $DB->fetch_assoc($resultSelect);
         $vendors_id = $data['id'];
         $vendors_name = html_entity_decode($data['name']);

         $query_selectd = "SELECT name FROM `glpi_plugin_fusioninventory_usbdevices`
           WHERE `deviceid`='".$deviceId."'
              AND `plugin_fusioninventory_usbvendor_id`='".$vendors_id."'
           LIMIT 1";
         $resultSelectd = $DB->query($query_selectd);
         if ($DB->numrows($resultSelectd) > 0) {
            $data = $DB->fetch_assoc($resultSelectd);
            $devices_name = html_entity_decode($data['name']);
         }
      }
      return array($vendors_name, $devices_name);
   }
}

?>
