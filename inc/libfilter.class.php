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
// Original Author of file: David DURIEUX
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
class PluginFusinvinventoryLibfilter extends CommonDBTM {


    /**
    * get device from pciid
    * @access public
    * @param string $pciid
    */
    public static function filter($section) {

        switch($section->getName()) {
            case 'CONTROLLERS':

                if(isset($section->PCIID) AND $section->PCIID != '') {
                    $manufacturer = self::_getDataFromPCIID($section->PCIID);
                    $section->MANUFACTURER = $manufacturer;
                }

            break;
//
//            case 'NETWORKS':
//                if(isset($section->MACADDR) AND $section->MACADDR != '') {
//                    //Mac address is locally or universal ?
//                    $msByte = substr($section->MACADDR, 0, 2);
//                    $msBin = decbin(hexdec($msByte));
//                    if (substr($msBin, -2, 1) != 1) {
//                        //second bit isn't 1, the mac address isn't locally
//                        $manufacturer = self::_getDataFromMACADDR($section->MACADDR);
//                        $section->addChild('MANUFACTURER', $manufacturer);
//                    }
//                }
//            break;

            case 'USBDEVICES':
                if(isset($section->VENDORID) AND $section->VENDORID != ''
                     AND isset($section->PRODUCTID)) {
                   
                    $dataArray = self::_getDataFromUSBID($section->VENDORID, $section->PRODUCTID);

                    $section->addChild('MANUFACTURER', $dataArray[0]);
                    $section->addChild('PRODUCTNAME', $dataArray[1]);

                }

            break;

            default:
            break;
        }
        return false;
    }

    /**
    * get manufacturer from pciid
    * @access private
    * @param string $pciid
    */
    private static function _getDataFromPCIID($pciid) {
       global $DB;

      $pciidArray = explode(":", $pciid);
      $vendorId = $pciidArray[0];
      $deviceId = $pciidArray[1];

      $query_select = "SELECT id, name FROM `glpi_plugin_fusinvinventory_pcivendors`
        WHERE `vendorid`='".$vendorId."'
           LIMIT 1";
      $resultSelect = $DB->query($query_select);
      if ($DB->numrows($resultSelect) > 0) {
         $rowSelect = mysql_fetch_row($resultSelect);
         $vendors_id = $rowSelect[0];
         $vendors_name = html_entity_decode($rowSelect[1]);

         return $vendors_name;
      } else {
         return "";
      }

    }

    /**
    * get data from macaddr
    * @access private
    * @param string $macaddr
    */
//    private static function _getDataFromMACADDR($macaddr) {
//
//        $macOUI = substr($macaddr, 0, 8);
//
//        $dataPath = sprintf('%s/%s/%s/%s',
//        LIBSERVERFUSIONINVENTORY_STORAGELOCATION,
//        "DataFilter",
//        "oui",
//        strtoupper($macOUI));
//
//        if (is_dir($dataPath)) {
//            $manufacturer = scandir($dataPath);
//
//            return $manufacturer[2];
//        }
//    }

   /**
   * get data from vendorid and productid
   * @access private
   * @param string $usbid
   */
   private static function _getDataFromUSBID($vendorId, $productId) {
      global $DB;

      $vendorId = strtolower($vendorId);
      $deviceId = strtolower($productId);
      $vendors_name = "";
      $devices_name = "";

      $query_select = "SELECT id, name FROM `glpi_plugin_fusinvinventory_usbvendors`
        WHERE `vendorid`='".$vendorId."'
        LIMIT 1";
      $resultSelect = $DB->query($query_select);
      if ($DB->numrows($resultSelect) > 0) {
         $rowSelect = mysql_fetch_row($resultSelect);
         $vendors_id = $rowSelect[0];
         $vendors_name = html_entity_decode($rowSelect[1]);

         $query_selectd = "SELECT name FROM `glpi_plugin_fusinvinventory_usbdevices`
           WHERE `deviceid`='".$deviceId."'
              AND `plugin_fusinvinventory_usbvendor_id`='".$vendors_id."'
           LIMIT 1";
         $resultSelectd = $DB->query($query_selectd);
         if ($DB->numrows($resultSelectd) > 0) {
            $rowSelectd = mysql_fetch_row($resultSelectd);
            $devices_name = html_entity_decode($rowSelectd[0]);
         }
      }
      return array($vendors_name, $devices_name);
    }
   

}

?>