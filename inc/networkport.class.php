<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the network ports display and parse the
 * inventory to add / update in database.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Vincent Mazzoni
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * Manage the network ports display and parse the  inventory to add / update
 * in database.
 */
class PluginFusioninventoryNetworkPort extends CommonDBTM {

   /**
    * Initialize the port of database
    *
    * @var array
    */
   private $portDB = [];

   /**
    * Initialize the port information from inventory
    *
    * @var array
    */
   private $portModif = [];

   /**
    * Initialize network port id
    *
    * @var integer
    */
   private $plugin_fusinvsnmp_networkports_id = 0;

   /**
    * Initialize VLANs (number and name) of port
    *
    * @var array
    */
   private $portVlans=[];


   /**
    * Get search function for the class
    *
    * @return array
    */
   function rawSearchOptions() {

      $tab                     = [];
      $tab[] = [
         'id' => 'common',
         'name' => __('Characteristics')
      ];

      $tab[] = [
         'id'            => '1',
         'table'         => 'glpi_networkports',
         'field'         => 'name',
         'name'          => __('Name'),
         'type'          => 'text',
         'massiveaction' => false,
      ];

      $tab[] = [
         'id'    => '3',
         'table' => $this->getTable(),
         'field' => 'ifmtu',
         'name'  => __('MTU', 'fusioninventory'),
      ];

      $tab[] = [
         'id'    => '5',
         'table' => $this->getTable(),
         'field' => 'ifspeed',
         'name'  => __('Speed'),
      ];

      $tab[] = [
         'id'    => '6',
         'table' => $this->getTable(),
         'field' => 'ifinternalstatus',
         'name'  => __('Internal status', 'fusioninventory'),
      ];

      $tab[] = [
         'id'    => '7',
         'table' => $this->getTable(),
         'field' => 'iflastchange',
         'name'  => __('Last change', 'fusioninventory'),
      ];

      $tab[] = [
         'id'    => '8',
         'table' => $this->getTable(),
         'field' => 'ifinoctets',
         'name'  => __('Number of bytes received / Number of bytes sent', 'fusioninventory'),
      ];

      $tab[] = [
         'id'    => '9',
         'table' => $this->getTable(),
         'field' => 'ifinerrors',
         'name'  => __('Number of input errors / Number of errors in reception', 'fusioninventory'),
      ];

      $tab[] = [
         'id'    => '10',
         'table' => $this->getTable(),
         'field' => 'portduplex',
         'name'  => __('Duplex', 'fusioninventory'),
      ];

      $tab[] = [
         'id'    => '11',
         'table' => $this->getTable(),
         'field' => 'mac',
         'name'  => __('Internal MAC address', 'fusioninventory'),
      ];

      $tab[] = [
         'id'    => '12',
         'table' => $this->getTable(),
         'field' => 'vlan',
         'name'  => __('VLAN'),
      ];

      $tab[] = [
         'id'    => '13',
         'table' => $this->getTable(),
         'field' => 'connectedto',
         'name'  => __('Connected to'),
      ];

      $tab[] = [
         'id'    => '14',
         'table' => $this->getTable(),
         'field' => 'ifconnectionstatus',
         'name'  => __('Connection'),
      ];

      $tab[] = [
         'id'    => '15',
         'table' => $this->getTable(),
         'field' => 'lastup',
         'name'  => __('Port not connected since', 'fusioninventory'),
      ];

      $tab[] = [
         'id'    => '16',
         'table' => $this->getTable(),
         'field' => 'ifalias',
         'name'  => __('Alias', 'fusioninventory'),
      ];

      $tab[] = [
         'id'    => '17',
         'table' => 'glpi_netpoints',
         'field' => 'name',
         'name'  => _n('Network outlet', 'Network outlets', 1),
      ];

      return $tab;
   }


   /**
    * Load an optionnaly existing port
    *
    * @param integer $networkports_id
    */
   function loadNetworkport($networkports_id) {

      $networkport = new NetworkPort();
      $networkport->getFromDB($networkports_id);
      $this->portDB = $networkport->fields;

      $a_fusports = $this->find(['networkports_id' => $networkports_id], [], 1);
      if (count($a_fusports) > 0) {
         $a_fusport = current($a_fusports);
         foreach ($a_fusport as $key=>$value) {
            if ($key == 'id') {
               $this->plugin_fusinvsnmp_networkports_id = $value;
            } else {
               $this->portDB[$key] = $value;
            }
         }
      }
   }


   /**
    * Disconnect a port in DB
    *
    *@param $p_port Port id to disconnect
    *@return nothing
    **/
   function disconnectDB($p_port) {
      if ($p_port=='') {
         return;
      }
      $nn = new NetworkPort_NetworkPort();

      $contact_id = $nn->getOppositeContact($p_port);
      if ($contact_id AND $nn->getFromDBForNetworkPort($contact_id)) {
         $nn->delete($nn->fields, 1);
      }
      if ($nn->getFromDBForNetworkPort($p_port)) {
         $nn->delete($nn->fields, 1);
      }
   }


   /**
    * Add vlan
    *
    *@param $p_number Vlan number
    *@param $p_name Vlan name
    *@return nothing
    **/
   function addVlan($p_number, $p_name) {
      $this->portVlans[$p_number] = $p_name;
   }


   /**
    * Get unique object fields by id of network port
    *
    * @global object $DB
    * @param integer $id
    * @return array
    */
   static function getUniqueObjectfieldsByportID($id) {
      global $DB;

      $array = [];
      $query = "SELECT *
                FROM `glpi_networkports`
                WHERE `id`='".$id."';";
      if (($result = $DB->query($query))) {
         $data = $DB->fetchArray($result);
         $array["items_id"] = $data["items_id"];
         $array["itemtype"] = $data["itemtype"];
      }
      if ($array["itemtype"] == NETWORKING_TYPE) {
         $query = "SELECT *
                   FROM `glpi_networkequipments`
                   WHERE `id`='".$array["itemtype"]."'
                   LIMIT 0, 1;";
         if (($result = $DB->query($query))) {
            $data = $DB->fetchArray($result);
            $array["name"] = $data["name"];
         }
      }
      return $array;
   }


   /**
    * Get a value
    *
    * @param string $name
    * @return string
    */
   function getValue($name) {
      if (isset($this->portModif[$name])) {
         return $this->portModif[$name];
      } else if (isset($this->portDB[$name])) {
         return $this->portDB[$name];
      }
      return '';
   }


   /**
    * Get the network port id
    *
    * @return integer
    */
   function getNetworkPortsID() {
      if (isset($this->portDB['id'])) {
         return $this->portDB['id'];
      } else if (isset($this->portModif['id'])) {
         return $this->portModif['id'];
      }
      return 0;
   }


   /**
    * Function used to detect if port has multiple mac connected
    *
    * @param integer $networkports_id
    * @return boolean
    */
   static function isPortHasMultipleMac($networkports_id) {
      $nw = new NetworkPort_NetworkPort();
      $networkPort = new NetworkPort();

      $is_multiple = false;
      $opposite_port = $nw->getOppositeContact($networkports_id);
      if ($opposite_port != ""
              && $opposite_port!= 0) {
         $networkPort->getFromDB($opposite_port);
         if ($networkPort->fields["itemtype"] == 'PluginFusioninventoryUnmanaged') {
            $pfUnmanaged = new PluginFusioninventoryUnmanaged();
            if ($pfUnmanaged->getFromDB($networkPort->fields['items_id'])) {
               if ($pfUnmanaged->fields['hub'] == 1) {
                  $is_multiple = true;
               }
            }
         }
      }
      return $is_multiple;
   }
}
