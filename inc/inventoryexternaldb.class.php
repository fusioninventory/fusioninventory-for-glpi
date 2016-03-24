<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2016 FusionInventory team
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

class PluginFusioninventoryInventoryExternalDB extends CommonDBTM {


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

      $a_return = array();

      if ($pciid == '') {
         return $a_return;
      }

      $pciidArray = explode(":", $pciid);

      if (!isset($pciidArray[1])) {
         return $a_return;
      }

      $vendorId = $pciidArray[0];

      $query_select = "SELECT `glpi_plugin_fusioninventory_pcivendors`.`name` as `manufacturer`,
         `glpi_plugin_fusioninventory_pcidevices`.`name` as `name`
         FROM `glpi_plugin_fusioninventory_pcivendors`
         LEFT JOIN `glpi_plugin_fusioninventory_pcidevices`
            ON `plugin_fusioninventory_pcivendor_id` = `glpi_plugin_fusioninventory_pcivendors`.`id`
         WHERE `vendorid`='".$vendorId."'
            AND `deviceid`='".$pciidArray[1]."'
            LIMIT 1";
      $resultSelect = $DB->query($query_select);
      if ($DB->numrows($resultSelect) > 0) {
         $data = $DB->fetch_assoc($resultSelect);
         $a_return['name'] = html_entity_decode($data['name']);
         $a_return['manufacturer'] = html_entity_decode($data['manufacturer']);
      }
      return $a_return;
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



   static function getManufacturerWithMAC($mac) {
      global $DB;

      $a_mac = explode(":", $mac);
      if (isset($a_mac[2])) {
         $searchMac = $a_mac[0].":".$a_mac[1].":".$a_mac[2];

         $query_select = "SELECT name FROM `glpi_plugin_fusioninventory_ouis`
           WHERE `mac`='".$searchMac."'
           LIMIT 1";
         $resultSelect = $DB->query($query_select);
         if ($DB->numrows($resultSelect) == 1) {
            $data = $DB->fetch_assoc($resultSelect);
            return $data['name'];
         }
      }
      return "";
   }
}

?>
