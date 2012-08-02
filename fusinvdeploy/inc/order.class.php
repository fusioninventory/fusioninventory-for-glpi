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

/**
 * Manage packages orders
 **/
class PluginFusinvdeployOrder extends CommonDBTM {

   const INSTALLATION_ORDER   = 0;
   const UNINSTALLATION_ORDER = 1;

   static function getRender($render) {
      if ($render == 'install') {
         return PluginFusinvdeployOrder::INSTALLATION_ORDER;
      } else {
         return PluginFusinvdeployOrder::UNINSTALLATION_ORDER;
      }
   }

   /**
    * Clean orders and related tables for a package
    * @param packages_id the package ID
    * @return nothing
    */
   static function cleanForPackage($packages_id) {
      global $DB;

      $orders = getAllDatasFromTable('glpi_plugin_fusinvdeploy_orders',
                                     "`plugin_fusinvdeploy_packages_id`='$packages_id'");
      foreach ($orders as $order) {
         PluginFusinvdeployCheck::cleanForPackage($order['id']);
      }

      $query = "DELETE FROM `glpi_plugin_fusinvdeploy_orders`
                WHERE `plugin_fusinvdeploy_packages_id`='$packages_id'";
      $DB->query($query);
   }

   /**
    * Create installation & uninstallation orders
    * @param packages_id the package ID
    * @return nothing
    */
   static function createOrders($packages_id) {
      $order = new PluginFusinvdeployOrder();
      $tmp['create_date'] = date("Y-m-d H:i:s");
      $tmp['plugin_fusinvdeploy_packages_id'] = $packages_id;
      foreach (array(PluginFusinvdeployOrder::INSTALLATION_ORDER,
                     PluginFusinvdeployOrder::UNINSTALLATION_ORDER) as $type) {
         $tmp['type'] = $type;
         $order->add($tmp);
      }
   }

   /**
    * TODO:
    * Create Orders from JSON format import/export
    */
   static function createOrdersFromJson($json) {

   }

   /**
    * Get order ID associated with a package, by type
    * @param packages_id the package ID
    * @param order_type can be self::INSTALLATION_ORDER or self::UNINSTALLATION_ORDER
    * @return 0 if no order found or the order's ID
    */
   static function getIdForPackage($packages_id, $order_type = self::INSTALLATION_ORDER) {
      $orders = getAllDatasFromTable('glpi_plugin_fusinvdeploy_orders',
                                     "`plugin_fusinvdeploy_packages_id`='$packages_id'" .
                                     " AND `type`='$order_type'");

      if (empty($orders)) {
         return 0;
      } else {
         foreach ($orders as $order) {
            return $order['id'];
         }
      }
   }

   static function getOrderDetails($status = array(), $order_type = self::INSTALLATION_ORDER) {
      $linked_types = array('PluginFusinvdeployCheck');


      //get all jobstatus for this task
      $results = array();
      $package_id = $status['items_id'];
      $results = getAllDatasFromTable('glpi_plugin_fusinvdeploy_orders',
                                  "`plugin_fusinvdeploy_packages_id`='$package_id'" .
                                        " AND `type`='$order_type'");

      $orders =  array();
      if (!empty($results)) {
         $related_classes = array('PluginFusinvdeployCheck'  => 'checks',
                                  'PluginFusinvdeployFile'   => 'associatedFiles',
                                  'PluginFusinvdeployAction' => 'actions');

         foreach ($related_classes as $class => $key) {
            foreach ($results as $result) {
               $tmp            = call_user_func(array($class,'getForOrder'),$result['id']);
               if ($key == 'associatedFiles') $orders[$key] = $tmp;
               else $orders[$key] = $tmp;
            }
         }
      }

      //set uuid order to jobstatus[id]
      if (!empty($orders)) $orders['uuid'] = $status['id'];

      return $orders;
   }

   static function getOrderDetailsFromPackage($package_id = 0, $order_type = self::INSTALLATION_ORDER) {
      $orders =  array();
      if ($package_id != 0) $order_id = PluginFusinvdeployOrder::getIdForPackage($package_id,
         $order_type);
      if ( isset($order_id) ) {
         
         $related_classes = array('PluginFusinvdeployCheck'  => 'checks',
                                  'PluginFusinvdeployFile'   => 'associatedFiles',
                                  'PluginFusinvdeployAction' => 'actions');

         foreach ($related_classes as $class => $key) {
               $tmp            = call_user_func(array($class,'getForOrder'),$order_id);
               if ($key == 'associatedFiles') $orders[$key] = PluginFusinvdeployFile::getAssociatedFilesForOrder($order_id);
               else $orders[$key] = $tmp;
         }
      }

#      if (!empty($orders)) $orders['uuid'] = $status['uniqid'];

      return $orders;

   }
}

?>