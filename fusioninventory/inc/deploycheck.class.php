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
   @author    Walid Nouh
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

class PluginFusioninventoryDeployCheck extends CommonDBTM {

   const WINKEY_EXISTS   = 'winkeyExists';    //Registry key present
   const WINKEY_MISSING   = 'winkeyMissing';    //Registry key missing
   const WINKEY_EQUAL     = 'winkeyEquals';      //Registry equals a value
   const FILE_EXISTS     = 'fileExists';      //File is present
   const FILE_MISSING     = 'fileMissing';      //File is missing
   const FILE_SIZEGREATER = 'fileSizeGreater';         //File size
   const FILE_SIZEEQUAL   = 'fileSizeEquals';         //File size
   const FILE_SIZELOWER   = 'fileSizeLower';         //File size
   const FILE_SHA512      = 'fileSHA512';       //File sha512 checksum
   const FREE_SPACE       = 'freespaceGreater'; //Disk free space

   static function getTypeName($nb=0) {
      return __('Audits');
   }

   static function displayForm($order_type, $packages_id, $datas) {
      global $CFG_GLPI;

      $rand = mt_rand();

      echo "<div style='display:none' id='checks_block' >";

      echo "<span id='showCheckType'>&nbsp;</span>";
      echo "<script type='text/javascript'>";
      Ajax::UpdateItemJsCode("showCheckType",
                                $CFG_GLPI["root_doc"].
                                "/plugins/fusioninventory/ajax/deploy_dropdownchecktype.php",
                                array('rand' => $rand),
                                "dropdown_deploy_checktype");
      echo "</script>";


      echo "<span id='showCheckValue'>&nbsp;</span>";
      
      echo "<hr>";
      echo "</div>";

      if (!isset($datas['jobs']['checks'])) return;
      echo "<ul>";
      foreach ($datas['jobs']['checks'] as $check) {
         echo "<li>".$check['type']." ".$check['path']." ".$check['value']."</li>";
      }
      echo "<ul>";
   }

   static function dropdownCheckType($rand) {
      global $CFG_GLPI;

      $checks_types = array(
         '--',
         self::WINKEY_EXISTS    => __("winkeyExists"),
         self::WINKEY_MISSING   => __("winkeyMissing"),
         self::WINKEY_EQUAL     => __("winkeyEquals"),
         self::FILE_EXISTS      => __("fileExists"),
         self::FILE_MISSING     => __("fileMissing"),
         self::FILE_SIZEGREATER => __("fileSizeGreater"),
         self::FILE_SIZEEQUAL   => __("fileSizeEquals"),
         self::FILE_SIZELOWER   => __("fileSizeLower"),
         self::FILE_SHA512      => __("fileSHA512"),
         self::FREE_SPACE       => __("freespaceGreater")
      );
      Dropdown::showFromArray("deploy_checktype", $checks_types, array('rand' => $rand));

      //ajax update of check value span
      $params = array('checktype' => '__VALUE__',
                      'rand'      => $rand,
                      'myname'    => 'method',
                      'typename'  => "");
      Ajax::updateItemOnEvent("dropdown_deploy_checktype".$rand,
                              "showCheckValue",
                              $CFG_GLPI["root_doc"].
                              "/plugins/fusioninventory/ajax/deploy_displaycheckvalue.php",
                              $params,
                              array("change", "load"));

   }

   static function ajaxDisplayCheckValue($checktype, $rand) {
      echo $checktype;
   }

   /**
    * Get all checks for an order
    * @param orders_id the order ID
    * @return an array with all checks, or an empty array is nothing defined
    */
   static function getForOrder($orders_id) {
      $check = new self;
      $results = $check->find("`plugin_fusioninventory_deployorders_id`='$orders_id'", 
                              "ranking ASC");

      $checks = array();
      foreach ($results as $result) {
         $tmp = array();
         if (empty($result['type'])) continue;

         if (isset($result['match'])) {
            $tmp['match'] = $result['match'];
         }
         if ($result['value'] != "")   $tmp['value'] = $result['value'];
         if ($result['path'] != "")    $tmp['path'] = $result['path'];
         if ($result['type'] != "")    $tmp['type'] = $result['type'];

         $tmp['return'] = "error";

         if ($tmp['type'] == "fileSizeGreater" || $tmp['type'] == "fileSizeLower" 
               || $tmp['type'] == "fileSizeEquals") {
            # according to the requirment, We want Bytes!
            $tmp['value'] *= 1024 * 1024;
         }
         $checks[] = $tmp;
      }

      return $checks;
   }


}

?>
