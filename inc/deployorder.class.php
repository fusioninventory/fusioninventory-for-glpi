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
   @author    Walid Nouh
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

/**
 * Manage packages orders
 **/
class PluginFusioninventoryDeployOrder extends CommonDBTM {

   const INSTALLATION_ORDER   = 0;
   const UNINSTALLATION_ORDER = 1;

   static $rightname = 'plugin_fusioninventory_package';

   function __construct($order_type = NULL, $packages_id = NULL) {

      if (
            (!is_null($order_type) && is_numeric($order_type) )
         && (!is_null($packages_id) && is_numeric($packages_id) )
      ) {
         $this->getFromDBByQuery(
                     " WHERE plugin_fusioninventory_deploypackages_id = $packages_id
                        AND type = $order_type"
                  );
      }

   }


   /*
    * The 'Render' things should be renamed to something appropriate
    * ... don't know yet, so just leaving it as is -- kiniou
    */
   static function getRender($render) {
      if ($render == 'install') {
         return PluginFusioninventoryDeployOrder::INSTALLATION_ORDER;
      } else {
         return PluginFusioninventoryDeployOrder::UNINSTALLATION_ORDER;
      }
   }

   static function getOrderTypeLabel($order_type) {
      switch($order_type) {
         case PluginFusioninventoryDeployOrder::INSTALLATION_ORDER:
            return('install');
            break;
         case PluginFusioninventoryDeployOrder::UNINSTALLATION_ORDER:
            return('uninstall');
            break;
      }
   }

   /**
    * Create installation & uninstallation orders
    * @param packages_id the package ID
    * @return nothing
    */
   static function createOrders($packages_id) {
      $order = new PluginFusioninventoryDeployOrder();
      $tmp['create_date'] = date("Y-m-d H:i:s");
      $tmp['plugin_fusioninventory_deploypackages_id'] = $packages_id;
      foreach (array(PluginFusioninventoryDeployOrder::INSTALLATION_ORDER,
                     PluginFusioninventoryDeployOrder::UNINSTALLATION_ORDER) as $type) {
         $tmp['type'] = $type;
         $tmp['json'] = json_encode(array('jobs' => array(
            'checks' => array(),
            'associatedFiles' => array(),
            'actions' => array()
         ), 'associatedFiles' => array()));
         $order->add($tmp);
      }
   }

   /*
    * Get a sub element at index
    * @param subtype the type of sub element
    * @param the index in element list
    * @return the sub element
    */
   function getSubElement($subtype, $index) {

      $data_o = json_decode($this->fields['json'], TRUE);

      return $data_o['jobs'][$subtype][$index];
   }

   /*
    * Get Order's associated file by hash
    * @param hash the sha512 hash of file
    * @return the associated file for the selected hash
    */
   function getAssociatedFile($hash) {
      $data_o = json_decode($this->fields['json'], TRUE);

      if ( array_key_exists( $hash, $data_o['associatedFiles'] ) ) {
         return $data_o['associatedFiles'][$hash];
      }

      return NULL;
   }

   static function getJson($orders_id) {
      $order = new self;
      $order->getFromDB($orders_id);
      if (!empty($order->fields['json'])) {
         return $order->fields['json'];
      } else {
         return FALSE;
      }
   }



   static function updateOrderJson($orders_id, $datas) {
      $order = new PluginFusioninventoryDeployOrder;
      $options = 0;
      if (version_compare(PHP_VERSION, '5.3.3') >= 0) {
         $options = $options | JSON_NUMERIC_CHECK;
      }
      if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
         $options = $options | JSON_UNESCAPED_SLASHES;
      }

      $json = json_encode($datas, $options);

      $json_error_consts = array(
         JSON_ERROR_NONE => "JSON_ERROR_NONE",
         JSON_ERROR_DEPTH => "JSON_ERROR_DEPTH",
         JSON_ERROR_STATE_MISMATCH => "JSON_ERROR_STATE_MISMATCH",
         JSON_ERROR_CTRL_CHAR => "JSON_ERROR_CTRL_CHAR",
         JSON_ERROR_SYNTAX => "JSON_ERROR_SYNTAX",
      );

      if( version_compare(phpversion(), "5.3.3", "ge") ) {
         $json_error_consts[JSON_ERROR_UTF8] = "JSON_ERROR_UTF8";
      }

      $error_json = json_last_error();

      if ( version_compare(PHP_VERSION, '5.5.0',"ge") ) {
         $error_json_message = json_last_error_msg();
      } else {
         $error_json_message = "";
      }

      $error = 0;

      if ( $error_json != JSON_ERROR_NONE ) {
         $error_msg = "";

         $error_msg = $json_error_consts[$error_json];

         Session::addMessageAfterRedirect(
            __("The modified JSON contained a syntax error :", "fusioninventory") . "<br/>" .
            $error_msg . "<br/>". $error_json_message, FALSE, ERROR, FALSE
         );
         $error = 1;
      } else {
         $error = $order->update(
            array(
               'id' => $orders_id,
               'json' => Toolbox::addslashes_deep($json)
            )
         );
      }
      return $error;
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
      $orders = getAllDatasFromTable('glpi_plugin_fusioninventory_deployorders',
                                     "`plugin_fusioninventory_deploypackages_id`='$packages_id'" .
                                     " AND `type`='$order_type'");

      if (empty($orders)) {
         return 0;
      } else {
         foreach ($orders as $order) {
            return $order['id'];
         }
      }
   }


}

?>
