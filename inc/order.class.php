<?php
/*
 * @version $Id: order.class.php 138 2011-03-18 09:49:00Z wnouh $
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
// Original Author of file: Walid Nouh
// Purpose of file: 
// ----------------------------------------------------------------------

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
   
   static function getOrderDetails($task = array(), $order_type = self::INSTALLATION_ORDER) {
      $linked_types = array('PluginFusinvdeployCheck');
      
      $results = getAllDatasFromTable('glpi_plugin_fusinvdeploy_orders',
                                     "`plugin_fusinvdeploy_packages_id`='".$task['items_id']."'" .
                                     " AND `type`='$order_type'");
      if (!empty($results)) {
         $related_classes = array('PluginFusinvdeployCheck'  => 'check', 
                                  'PluginFusinvdeployFile'   => 'associatedFiles',
                                  'PluginFusinvdeployAction' => 'actions');
         $orders =  array();
         foreach ($related_classes as $class => $key) {
            foreach ($results as $result) {
               $tmp            =  call_user_func(array($class,'getForOrder'),$result['id']);
               $tmp['uuid']    = $task['uniqid'];
               $orders[$key][] = $tmp;
            }
         }
      }
      
      return $orders;
   }
}

?>