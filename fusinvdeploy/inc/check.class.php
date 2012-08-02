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

class PluginFusinvdeployCheck extends CommonDBTM {

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
      $check = new self;
      $results = $check->find("`plugin_fusinvdeploy_orders_id`='$orders_id'", "ranking ASC");

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

	 if ($tmp['type'] == "fileSizeGreater" || $tmp['type'] == "fileSizeLower" || $tmp['type'] == "fileSizeEquals") {
# according to the requirment, We want Bytes!
             $tmp['value'] *= 1024 * 1024;
	 }
         $checks[] = $tmp;
      }

      return $checks;
   }

   function update_ranking($params = array())  {

      //get params
      $id_moved = $params['id'];
      $old_ranking = $params['old_ranking'];
      $new_ranking = $params['new_ranking'];
      $package_id = $params['package_id'];
      $render = $params['render'];

      //get order id
      $render_type   = PluginFusinvdeployOrder::getRender($render);
      $order_id = PluginFusinvdeployOrder::getIdForPackage($package_id,$render_type);

      //get rankings
      $action_moved = new $this;
      $action_moved->getFromDB($id_moved);
      $ranking_moved = $action_moved->getField('ranking');
      $ranking_destination = $new_ranking;

      $actions = new $this;
      if ($ranking_moved < $ranking_destination) {
         //get all rows between this two rows
         $rows_id = $actions->find("plugin_fusinvdeploy_orders_id = '$order_id'
               AND ranking > '$ranking_moved'
               AND ranking <= '$ranking_destination'"
         );

         //decrement ranking for all this rows
         foreach($rows_id as $id => $values) {
            $options = array();
            $options['id'] = $id;
            $options['ranking'] = $values['ranking']-1;
            $actions->update($options);
            unset($options);
         }
      } else {
         //get all rows between this two rows
         $rows_id = $actions->find("plugin_fusinvdeploy_orders_id = '$order_id'
               AND ranking < '$ranking_moved'
               AND ranking >= '$ranking_destination'"
         );

         //decrement ranking for all this rows
         foreach($rows_id as $id => $values) {
            $options = array();
            $options['id'] = $id;
            $options['ranking'] = $values['ranking']+1;
            $actions->update($options);
            unset($options);
         }
      }

      //set ranking to moved row
      $options['id'] = $id_moved;
      $options['ranking'] = $ranking_destination;
      $action_moved->update($options);

      return "{success:true}";

   }
}

?>