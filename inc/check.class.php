<?php

/*
 * @version $Id: check.class.php 166 2011-03-21 22:57:17Z wnouh $
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
// Original Author of file: Walid Nouh (wnouh@teclib.com)
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployCheck extends CommonDBTM {

   const WINKEY_PRESENT   = 'winkeyPresent';    //Registry key present
   const WINKEY_MISSING   = 'winkeyMissing';    //Registry key missing
   const WINKEY_EQUAL     = 'winkeyEqual';      //Registry equals a value
   const FILE_PRESENT     = 'filePresent';      //File is present
   const FILE_MISSING     = 'fileMissing';      //File is missing
   const FILE_SIZEGREATER = 'fileSizeGreater';         //File size
   const FILE_SIZEEQUAL   = 'fileSizeEqual';         //File size
   const FILE_SIZELOWER   = 'fileSizeLower';         //File size
   const FILE_SHA512      = 'fileSHA512';       //File sha512 checksum
   const FREE_SPACE       = 'freespaceGreater'; //Disk free space

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvdeploy']['package'][11];
   }

   /**
    * Clean all checks when an order is purged
    * @param orders_id the order ID
    * @return nothing
    */
   static function cleanForPackage($orders_id) {
      global $DB;
      $query = "DELETE FROM `glpi_plugin_fusinvdeploy_checks` 
                WHERE `plugin_fusinvdeploy_orders_id`='$orders_id'";
      $DB->query($query);
   }

   /**
    * Get all checks for an order
    * @param orders_id the order ID
    * @return an array with all checks, or an empty array is nothing defined
    */
   static function getForOrder($orders_id) {
      $results = getAllDatasFromTable('glpi_plugin_fusinvdeploy_checks',
                                      "`plugin_fusinvdeploy_orders_id`='$orders_id'");
      
      $checks = array();
      foreach ($results as $result) {
         $tmp['type'] = $result['type'];
         if (isset($result['match'])) {
            $tmp['match'] = $result['match'];
         } 
         $tmp['path'] = $result['path'];
         $tmp['value'] = $result['value'];
         $checks[] = $tmp;
      }
      
      return $checks;
   }
}
?>