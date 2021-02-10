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
 * This file is used to manage the unmanaged devices (not manage into GLPI).
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
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
 * Manage the unmanaged devices (not manage into GLPI).
 */
class PluginFusioninventoryUnmanaged extends CommonDBTM {

   /**
    * We activate the history.
    *
    * @var boolean
    */
   public $dohistory = true;

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_unmanaged';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
      return __('Unmanaged device', 'fusioninventory');
   }


   /**
    * Check is use deleted to lock if dynamic
    *
    * @return boolean
    */
   function useDeletedToLockIfDynamic() {
      return false;
   }


   /**
    * Get menu name
    *
    * @return string
    */
   static function getMenuName() {
      return self::getTypeName();
   }


   /**
    * Get content menu breadcrumb
    *
    * @return array
    */
   static function getMenuContent() {
      $menu = [];
      if (Session::haveRight(static::$rightname, READ)) {
         $menu['title']           = self::getTypeName();
         $menu['page']            = self::getSearchURL(false);
         $menu['links']['search'] = self::getSearchURL(false);
      }
      return $menu;
   }


   /**
    * Get search function for the class
    *
    * @return array
    */
   function rawSearchOptions() {

      $tab = [];

      $tab[] = [
         'id' => 'common',
         'name' => __('Unmanaged device', 'fusioninventory')
      ];

      $tab[] = [
         'id'            => '1',
         'table'         => $this->getTable(),
         'field'         => 'name',
         'name'          => __('Name'),
         'datatype'      => 'itemlink',
         'itemlink_type' => $this->getType(),
         'autocomplete'  => true,
      ];

      $tab[] = [
         'id'        => '2',
         'table'     => $this->getTable(),
         'field'     => 'id',
         'name'      => __('ID'),
      ];

      $tab[] = [
         'id'        => '3',
         'table'     => 'glpi_locations',
         'field'     => 'name',
         'linkfield' => 'locations_id',
         'name'      => __('Location'),
         'datatype'  => 'dropdown',
      ];

      $tab[] = [
         'id'           => '4',
         'table'        => $this->getTable(),
         'field'        => 'serial',
         'name'         => __('Serial Number'),
         'autocomplete' => true,
      ];

      $tab[] = [
         'id'           => '5',
         'table'        => $this->getTable(),
         'field'        => 'otherserial',
         'name'         => __('Inventory number'),
         'autocomplete' => true,
      ];

      $tab[] = [
         'id'           => '6',
         'table'        => $this->getTable(),
         'field'        => 'contact',
         'name'         => __('Contact'),
         'autocomplete' => true,
      ];

      $tab[] = [
         'id'        => '7',
         'table'     => $this->getTable(),
         'field'     => 'hub',
         'name'      => __('Network hub', 'fusioninventory'),
         'datatype'  => 'bool',
      ];

      $tab[] = [
         'id'        => '8',
         'table'     => 'glpi_entities',
         'field'     => 'completename',
         'linkfield' => 'entities_id',
         'name'      => Entity::getTypeName(1),
         'datatype'  => 'dropdown',
      ];

      $tab[] = [
         'id'        => '9',
         'table'     => 'glpi_domains',
         'field'     => 'name',
         'linkfield' => 'domain',
         'name'      => __('Domain'),
         'datatype'  => 'dropdown',
      ];

      $tab[] = [
         'id'        => '10',
         'table'     => $this->getTable(),
         'field'     => 'comment',
         'name'      => __('Comments'),
         'datatype'  => 'text',
      ];

      $tab[] = [
         'id'        => '13',
         'table'     => $this->getTable(),
         'field'     => 'item_type',
         'name'      => __('Type'),
         'datatype'  => 'dropdown',
      ];

      $tab[] = [
         'id'        => '14',
         'table'     => $this->getTable(),
         'field'     => 'date_mod',
         'name'      => __('Last update'),
         'datatype'  => 'datetime',
      ];

      $tab[] = [
         'id'        => '15',
         'table'     => $this->getTable(),
         'field'     => 'sysdescr',
         'name'      => __('Sysdescr', 'fusioninventory'),
         'datatype'  => 'text',
      ];

      $tab[] = [
         'id'        => '17',
         'table'     => 'glpi_plugin_fusioninventory_configsecurities',
         'field'     => 'name',
         'linkfield' => 'plugin_fusioninventory_configsecurities_id',
         'name'      => __('SNMP credentials', 'fusioninventory'),
         'datatype'  => 'dropdown',
      ];

      $tab[] = [
         'id'           => '18',
         'table'        => $this->getTable(),
         'field'        => 'ip',
         'name'         => __('IP'),
         'autocomplete' => true,
      ];

      return $tab;
   }


   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {

      $ong = [];
      if ($item->fields['id'] > 0) {
         $ong[1]=__('Import');

         $pfConfig = new PluginFusioninventoryConfig();
         if (($pfConfig->isFieldActive('remotehttpagent'))
                 && (Session::haveRight('plugin_fusioninventory_remotecontrol', UPDATE))) {
            $ong[2]=__('Job', 'fusioninventory');
         }
      }
      return $ong;
   }


   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return boolean
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      if ($tabnum == 1) {
         $pfUnmanaged = new self();
         $pfUnmanaged->importForm(Plugin::getWebDir('fusioninventory') .
               '/front/unmanaged.form.php?id='.$item->fields["id"],
                                   $item->fields["id"]);
         return true;
      }
      return false;
   }


   /**
    * Define tabs to display on form page
    *
    * @param array $options
    * @return array containing the tabs name
    */
   function defineTabs($options = []) {

      $ong = [];
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('NetworkPort', $ong, $options);
      $this->addStandardTab('PluginFusioninventoryUnmanaged', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);
      return $ong;
   }


   /**
    * Get the massive actions for this object
    *
    * @param object|null $checkitem
    * @return array list of actions
    */
   function getSpecificMassiveActions($checkitem = null) {

      $actions = [];
      if (Session::haveRight('plugin_fusioninventory_unmanaged', UPDATE)) {
         $actions['PluginFusioninventoryUnmanaged'.MassiveAction::CLASS_ACTION_SEPARATOR.'import']    = __('Import');
      }
      if (Session::haveRight('plugin_fusioninventory_configsecurity', READ)) {
         $actions['PluginFusioninventoryUnmanaged'.MassiveAction::CLASS_ACTION_SEPARATOR.'assign_auth']       =
                                       __('Assign SNMP credentials', 'fusioninventory');
      }
      return $actions;
   }


   /**
    * Display form related to the massive action selected
    *
    * @param object $ma MassiveAction instance
    * @return boolean
    */
   static function showMassiveActionsSubForm(MassiveAction $ma) {
      if ($ma->getAction() == 'assign_auth') {
         PluginFusioninventoryConfigSecurity::authDropdown();
         echo "<br><br>";
         return true;
      }
      return parent::showMassiveActionsSubForm($ma);
   }


   /**
    * Execution code for massive action
    *
    * @param object $ma MassiveAction instance
    * @param object $item item on which execute the code
    * @param array $ids list of ID on which execute the code
    */
   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {

      switch ($ma->getAction()) {

         case "import" :
            $Import = 0;
            $NoImport = 0;
            $pfUnmanaged = new PluginFusioninventoryUnmanaged();
            foreach ($ids as $key) {
               list($Import, $NoImport) = $pfUnmanaged->import($key, $Import, $NoImport);
               $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
            }
            $ma->addMessage(__('Number of imported devices', 'fusioninventory')." : ".$Import);
            $ma->addMessage(__('Number of devices not imported because type not defined', 'fusioninventory').
                     " : ".$NoImport);
            break;

      }
   }


   /**
    * Display form for unmanaged device
    *
    * @param integer $id id of the unmanaged device
    * @param array $options
    * @return true
    */
   function showForm($id, $options = []) {

      $this->initForm($id, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . __('Name') . "&nbsp;:</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this, 'name', ['size' => 35]);
      echo "</td>";

      if (Session::isMultiEntitiesMode()) {
         echo "<td align='center'>" . Entity::getTypeName(1) . "&nbsp;:</td>";
         echo "</td>";
         echo "<td align='center'>";
         Dropdown::show("Entity",
                        ['name'=>'entities_id',
                              'value'=>$this->fields["entities_id"]]);
         echo "</td>";
         echo "</tr>";
         echo "</tr>";
      } else {
         echo "<td align='center'></td>";
         echo "</td>";
         echo "<td align='center'></td>";
         echo "</tr>";
         echo "</tr>";
      }

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . __('Type') . "&nbsp;:</td>";
      echo "<td align='center'>";
         $type_list = [];
         $type_list[] = 'Computer';
         $type_list[] = 'NetworkEquipment';
         $type_list[] = 'Printer';
         $type_list[] = 'Peripheral';
         $type_list[] = 'Phone';
      Dropdown::showItemTypes('item_type', $type_list,
                                          ['value' => $this->fields["item_type"]]);
      echo "</td>";
      echo "<td align='center'>" . __('Alternate username') . "&nbsp;:</td>";
      echo "</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this, 'contact', ['size' => 35]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . __('Location') . "&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::show("Location",
                     ['name'=>"locations_id",
                           'value'=>$this->fields["locations_id"]]);
      echo "</td>";
      echo "<td align='center'>" . __('Domain') . "&nbsp;:</td>";
      echo "</td>";
      echo "<td align='center'>";
      Dropdown::show("Domain",
                     ['name'=>"domain",
                           'value'=>$this->fields["domain"]]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . __('Approved devices', 'fusioninventory') . " :</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo("accepted", $this->fields["accepted"]);
      echo "</td>";
      echo "<td align='center'>" . __('Serial Number') . "&nbsp;:</td>";
      echo "</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this, 'serial', ['size' => 35]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . __('Network hub', 'fusioninventory') . " :</td>";
      echo "<td align='center'>";
      echo Dropdown::getYesNo($this->fields["hub"]);
      echo "</td>";
      echo "<td align='center'>" . __('Inventory number') . "&nbsp;:</td>";
      echo "</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this, 'otherserial', ['size' => 35]);
      echo "</td>";
      echo "</tr>";

      if ((!empty($this->fields["ip"])) OR (!empty($this->fields["mac"]))) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>" . __('IP') . " :</td>";
         echo "<td align='center'>";
         Html::autocompletionTextField($this, 'ip', ['size' => 35]);
         echo "</td>";

         echo "<td colspan='2'></td>";
         echo "</tr>";
      }

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' rowspan='2'>";
      echo __('Sysdescr', 'fusioninventory')."&nbsp;:";
      echo "</td>";
      echo "<td rowspan='2'>";
      echo "<textarea name='sysdescr'  cols='45' rows='5'>".$this->fields["sysdescr"]."</textarea>";
      echo "<td align='center'></td>";
      echo "<td align='center'>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>".__('SNMP credentials', 'fusioninventory')."&nbsp;:</td>";
      echo "<td align='center'>";
      PluginFusioninventoryConfigSecurity::authDropdown(
               $this->fields['plugin_fusioninventory_configsecurities_id']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . __('Comments') . " : </td>";
      echo "</td>";
      echo "<td align='middle' colspan='3'>";
      echo "<textarea  cols='80' rows='2' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      return true;
   }


   /**
    * Form to import devices in GLPI inventory (computer, printer...)
    *
    * @param string $target target page
    * @param integer $id id of the unmanaged
    */
   function importForm($target, $id) {

      echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";
      echo "<table  class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th align='center'>";
      echo __('Import unmanaged device into asset', 'fusioninventory');

      echo "</th>";
      echo "</tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      $this->getFromDB($id);
      if ($this->fields["item_type"] != '0') {
         echo "<input type='hidden' name='id' value=$id>";
         echo "<input type='submit' name='import' value=\"".__('Import')."\" class='submit'>";
      }
      echo "</td>";
      echo "</table>";
      Html::closeForm();
      echo "</div>";
   }


   /**
    * Clean orphelins connections
    *
    * @global object $DB
    */
   function cleanOrphelinsConnections() {
      global $DB;

      $query = "SELECT `glpi_networkports`.`id`
                FROM `glpi_networkports`
                     LEFT JOIN `glpi_plugin_fusioninventory_unmanageds`
                               ON `items_id`=`glpi_plugin_fusioninventory_unmanageds`.`id`
                     WHERE `itemtype`='PluginFusioninventoryUnmanaged'
                           AND `glpi_plugin_fusioninventory_unmanageds`.`id` IS NULL;";
      $unmanaged_infos = [];
      $result=$DB->query($query);
      if ($result) {
         while ($data=$DB->fetchArray($result)) {
            $unmanaged_infos["name"] = '';
            $newID=$this->add($unmanaged_infos);

            $DB->update(
               'glpi_networkports', [
                  'items_id' => $newID
               ], [
                  'id' => $data['id']
               ]
            );
         }
      }
   }


   // ************************* Hub Management ************************ //


   /**
    * Manage a hub (many mac on a port mean you have a hub)
    *
    * @param object $pfNetworkport  Informations of the network port (switch port)
    * @param array $ports_list list of ports found on the switch port
    */
   function hubNetwork($pfNetworkport, $ports_list) {

      $nn = new NetworkPort_NetworkPort();
      $networkPort = new NetworkPort();
      // Get port connected on switch port
      $hub_id = 0;
      $ID = $nn->getOppositeContact($pfNetworkport->getValue('networkports_id'));
      if ($ID) {
         $networkPort->getFromDB($ID);
         if ($networkPort->fields["itemtype"] == $this->getType()) {
            $this->getFromDB($networkPort->fields["items_id"]);
            if ($this->fields["hub"] == "1") {
               // It's a hub connected, so will update connections
               //$this->releaseHub($this->fields['id'], $p_oPort);
               $hub_id = $this->fields['id'];
            } else {
               // It's a direct connection, so disconnect and create a hub
               $this->disconnectDB($ID);
               $hub_id = $this->createHub($pfNetworkport, $ports_list);
            }
         } else {
            // It's a direct connection, so disconnect and create a hub
            $this->disconnectDB($ID);
            $hub_id = $this->createHub($pfNetworkport, $ports_list);
         }
      } else {
         // No connections found and create a hub
         $hub_id = $this->createHub($pfNetworkport, $ports_list);
      }
      // State : Now we have hub and the hubs id

      // Add source port id in comment of hub
      $h_input = [
         'id'      => $hub_id,
         'comment' => "Port : ".$pfNetworkport->getValue('networkports_id'),
      ];
      $this->update($h_input);

      // Get all ports connected to this hub
      $a_portglpi = [];

      $a_ports = $networkPort->find(
            ['items_id' => $hub_id,
             'itemtype' => $this->getType()]);
      foreach ($a_ports as $data) {
         $id = $nn->getOppositeContact($data['id']);
         if ($id) {
            $a_portglpi[$id] = $data['id'];
         }
      }

      foreach ($ports_list as $ports_id) {
         if (!isset($a_portglpi[$ports_id])) {
            // Connect port (port found in GLPI)
            $this->connectPortToHub($ports_id, $hub_id);
         }
      }
   }


   /**
    * Delete all ports connected in hub and not found in last inventory
    *
    * @param integer $hub_id id of the hub (unmanaged device)
    * @param array $a_portUsed list of the ports found in last inventory
    */
   function deleteNonUsedPortHub($hub_id, $a_portUsed) {

      $Netport = new NetworkPort();

      $a_ports = $Netport->find(
            ['items_id' => $hub_id,
             'itemtype' => $this->getType(),
             'OR' => [
                'name' => ['!=', 'Link'],
                'name' => null]
             ]);
      foreach ($a_ports as $data) {
         if (!isset($a_portUsed[$data['id']])) {
            //plugin_fusioninventory_addLogConnection("remove", $port_id);
            $this->disconnectDB($data['id']);
            $Netport->deleteFromDB($data['id']);
         }
      }
   }


   /**
    * Connect a port to hub
    *
    * @global object $DB
    * @param array $ports_id id of a port
    * @param integer $hub_id id of the hub (unmanaged device)
    * @return integer id of the port of the hub where port is connected
    */
   function connectPortToHub($ports_id, $hub_id) {
      global $DB;

      $networkPort = new NetworkPort();
      $nn = new NetworkPort_NetworkPort();

      $this->disconnectDB($ports_id);
      // Search free port
      $query = "SELECT `glpi_networkports`.`id` FROM `glpi_networkports`
         LEFT JOIN `glpi_networkports_networkports`
            ON `glpi_networkports`.`id` = `networkports_id_2`
         WHERE `itemtype`='".$this->getType()."'
            AND `items_id`='".$hub_id."'
            AND `networkports_id_1` is null
         LIMIT 1;";
      $result = $DB->query($query);
      $freeport_id = 0;
      if ($DB->numrows($result) == 1) {
         $freeport = $DB->fetchAssoc($result);
         $freeport_id = $freeport['id'];
      } else {
         // Create port
         $input = [];
         $input["items_id"] = $hub_id;
         $input["itemtype"] = $this->getType();
         $input['instantiation_type'] = "NetworkPortEthernet";
         $freeport_id = $networkPort->add($input);
      }
      $nn->add([
         'networkports_id_1'=> $ports_id,
         'networkports_id_2' => $freeport_id
      ]);
      return $freeport_id;
   }


   /**
    * Disconnect a port
    *
    * @param integer $ports_id
    */
   function disconnectDB($ports_id) {
      $nn = new NetworkPort_NetworkPort();

      if ($nn->getOppositeContact($ports_id)
              && $nn->getFromDBForNetworkPort($nn->getOppositeContact($ports_id))) {
         if ($nn->delete($nn->fields)) {
            plugin_item_purge_fusioninventory($nn);
         }
      }
      if ($nn->getFromDBForNetworkPort($ports_id)) {
         if ($nn->delete($nn->fields)) {
            plugin_item_purge_fusioninventory($nn);
         }
      }
   }


   /**
    * Creation of a hub
    *
    * @param object $pfNetworkport Informations of the network port
    * @param array $ports_list liste of ports
    * @return integer id of the hub (unmanaged device)
    */
   function createHub($pfNetworkport, $ports_list) {

      $networkPort = new NetworkPort();
      $nn = new NetworkPort_NetworkPort();

      // Not found, creation hub and link port
      $input = [
         'hub'  => 1,
         'name' => 'hub',
      ];
      if (isset($_SESSION["plugin_fusioninventory_entity"])) {
         $input['entities_id'] = $_SESSION["plugin_fusioninventory_entity"];
      }
      $hub_id = $this->add($input);

      $input = [
         'items_id' => $hub_id,
         'itemtype' => $this->getType(),
         'name'     => "Link",
         'instantiation_type' => 'NetworkPortEthernet'
      ];
      $ports_id = $networkPort->add($input);
      $this->disconnectDB($pfNetworkport->getValue('networkports_id'));
      $nn->add(['networkports_id_1'=> $pfNetworkport->getValue('networkports_id'),
                'networkports_id_2' => $ports_id]);
      return $hub_id;
   }


   /**
    * Remove all connections on a hub
    *
    * @param integer $hub_id id of the hub
    * @param object $pfNetworkport Informations of the network port
    * @param array $a_mac
    */
   function releaseHub($hub_id, $pfNetworkport, $a_mac) {

      $Netport = new NetworkPort();
      $nn = new NetworkPort_NetworkPort();

      $a_macOnSwitch = [];
      foreach ($a_mac as $ifmac) {
         $a_macOnSwitch["$ifmac"] = 1;
      }

      // get all ports of hub
      $releasePorts = [];
      $a_ports = $Netport->find(
            ['items_id' => $hub_id,
             'itemtype' => $this->getType(),
             'OR' => ['name'  => ['!=', 'Link'],
                      'name' => null]]);
      foreach (array_keys($a_ports) as $ports_id) {
         $id = $nn->getOppositeContact($ports_id);
         if ($id) {
            $Netport->getFromDB($id);
            if (!isset($a_macOnSwitch[$Netport->fields["mac"]])) {
               $releasePorts[$ports_id] = 1;
            }
         }
      }
   }


   // *************************** end hub management ****************************** //


   /**
    * Purge unmanaged devices
    *
    * @param object $parm object to purge
    */
   static function purgeUnmanagedDevice($parm) {
      // Delete XML file if exist
      $folder = substr($parm->fields["id"], 0, -1);
      if (empty($folder)) {
         $folder = '0';
      }

      if (file_exists(GLPI_PLUGIN_DOC_DIR.
              "/fusioninventory/xml/PluginFusioninventoryUnmanaged/".$folder."/".
              $parm->fields["id"])) {
         unlink(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/PluginFusioninventoryUnmanaged/".
                 $folder."/".$parm->fields["id"]);
      }

      // Delete Networkports
      $NetworkPort = new NetworkPort();
      $a_ports = $NetworkPort->find(
            ['items_id' => $parm->fields["id"],
             'itemtype' => 'PluginFusioninventoryUnmanaged']);
      foreach ($a_ports as $a_port) {
         $NetworkPort->delete($a_port, 1);
      }
   }


   /**
    * Function to import discovered device
    *
    * @global object $DB
    * @param integer $items_id
    * @param integer $Import
    * @param integer $NoImport
    * @return array
    */
   function import($items_id, $Import = 0, $NoImport = 0) {
      global $DB;

      $NetworkPort = new NetworkPort();

      $a_NetworkPorts = $NetworkPort->find(
            ['items_id' => $items_id,
             'itemtype' => 'PluginFusioninventoryUnmanaged']);

      $this->getFromDB($items_id);
      $this->fields = Toolbox::addslashes_deep($this->fields);
      $data = [];
      switch ($this->fields['item_type']) {
         case 'Printer':
            $Printer = new Printer();

            $data["entities_id"] = $this->fields["entities_id"];
            if (!empty($this->fields["name"])) {
               $data["name"] = $this->fields["name"];
            }
            $data["locations_id"] = $this->fields["locations_id"];
            $data["serial"] = $this->fields["serial"];
            $data["otherserial"] = $this->fields["otherserial"];
            $data["contact"] = $this->fields["contact"];
            $data["domain"] = $this->fields["domain"];
            $data["comment"] = $this->fields["comment"];
            $data["is_dynamic"] = 1;
            $printer_id = $Printer->add($data);

            foreach ($a_NetworkPorts as $data_Port) {
               $data_Port['items_id'] = $printer_id;
               $data_Port['itemtype'] = $Printer->getType();
               $NetworkPort->update($data_Port);
            }

            // Import SNMP
            $pfPrinter = new PluginFusioninventoryPrinter();
            $_SESSION['glpi_plugins_fusinvsnmp_table'] = "glpi_plugin_fusioninventory_printers";
            $query = "SELECT *
                      FROM `glpi_plugin_fusioninventory_printers`
                      WHERE `printers_id`='".$printer_id."' ";
            $result = $DB->query($query);
            $data = [];
            if ($DB->numrows($result) > 0) {
               $data = $DB->fetchAssoc($result);
            }
            $data['sysdescr'] = $this->fields['sysdescr'];
            $data['plugin_fusioninventory_configsecurities_id'] =
                           $this->fields['plugin_fusioninventory_configsecurities_id'];
            if ($DB->numrows($result) == 0) {
               $data['printers_id'] = $printer_id;
               $pfPrinter->add($data);
            } else {
               $pfPrinter->update($data);
            }
            $this->deleteFromDB(1);
            $Import++;
            break;

         case 'NetworkEquipment':
            $NetworkEquipment = new NetworkEquipment();

            $data["entities_id"] = $this->fields["entities_id"];
            if (!empty($this->fields["name"])) {
               $data["name"] = $this->fields["name"];
            }
            $data["locations_id"] = $this->fields["locations_id"];
            $data["serial"] = $this->fields["serial"];
            $data["otherserial"] = $this->fields["otherserial"];
            $data["contact"] = $this->fields["contact"];
            $data["domain"] = $this->fields["domain"];
            $data["comment"] = $this->fields["comment"];
            $data["is_dynamic"] = 1;
            //            $data_Port = current($a_NetworkPorts);
            //            $data["ip"] = $data_Port["ip"];
            //            $data["mac"] = $data_Port["mac"];
            $NetworkEquipment_id = $NetworkEquipment->add($data);

            foreach ($a_NetworkPorts as $data_Port) {
               $data_Port['items_id'] = $NetworkEquipment_id;
               $data_Port['itemtype'] = $NetworkEquipment->getType();
               $NetworkPort->update($data_Port);
            }

            $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();
            $_SESSION['glpi_plugins_fusinvsnmp_table'] =
                           "glpi_plugin_fusioninventory_networkequipments";
            $query = "SELECT *
                      FROM `glpi_plugin_fusioninventory_networkequipments`
                      WHERE `networkequipments_id`='".$NetworkEquipment_id."' ";
            $result = $DB->query($query);
            $data = [];
            if ($DB->numrows($result) > 0) {
               $data = $DB->fetchAssoc($result);
            }

            $data['sysdescr'] = $this->fields['sysdescr'];
            $data['plugin_fusioninventory_configsecurities_id'] =
                           $this->fields['plugin_fusioninventory_configsecurities_id'];
            if ($DB->numrows($result) == 0) {
               $data['networkequipments_id'] = $NetworkEquipment_id;
               $pfNetworkEquipment->add($data);
            } else {
               $pfNetworkEquipment->update($data);
            }

            $this->deleteFromDB(1);
            $Import++;
            break;

         case 'Peripheral':
            $Peripheral = new Peripheral();

            $data["entities_id"] = $this->fields["entities_id"];
            if (!empty($this->fields["name"])) {
               $data["name"] = $this->fields["name"];
            }
            $data["locations_id"] = $this->fields["locations_id"];
            $data["serial"] = $this->fields["serial"];
            $data["otherserial"] = $this->fields["otherserial"];
            $data["contact"] = $this->fields["contact"];
            $data["comment"] = $this->fields["comment"];
            $data["is_dynamic"] = 1;
            $Peripheral_id = $Peripheral->add($data);

            foreach ($a_NetworkPorts as $data_Port) {
               $data_Port['items_id'] = $Peripheral_id;
               $data_Port['itemtype'] = $Peripheral->getType();
               $NetworkPort->update($data_Port);
            }

            $this->deleteFromDB(1);
            $Import++;
            break;

         case 'Computer':
            $Computer = new Computer();

            $data["entities_id"] = $this->fields["entities_id"];
            if (!empty($this->fields["name"])) {
               $data["name"] = $this->fields["name"];
            }
            $data["locations_id"] = $this->fields["locations_id"];
            $data["serial"] = $this->fields["serial"];
            $data["otherserial"] = $this->fields["otherserial"];
            $data["contact"] = $this->fields["contact"];
            $data["domain"] = $this->fields["domain"];
            $data["comment"] = $this->fields["comment"];
            $data["is_dynamic"] = 1;
            $Computer_id = $Computer->add($data);

            foreach ($a_NetworkPorts as $data_Port) {
               $data_Port['items_id'] = $Computer_id;
               $data_Port['itemtype'] = $Computer->getType();
               $NetworkPort->update($data_Port);
            }
            $this->deleteFromDB(1);
            $Import++;
            break;

         case 'Phone':
            $Phone = new Phone();

            $data["entities_id"] = $this->fields["entities_id"];
            $data["name"] = $this->fields["name"];
            $data["locations_id"] = $this->fields["locations_id"];
            $data["serial"] = $this->fields["serial"];
            $data["otherserial"] = $this->fields["otherserial"];
            $data["contact"] = $this->fields["contact"];
            $data["comment"] = $this->fields["comment"];
            $data["is_dynamic"] = 1;
            $phone_id = $Phone->add($data);

            foreach ($a_NetworkPorts as $data_Port) {
               $data_Port['items_id'] = $phone_id;
               $data_Port['itemtype'] = $Phone->getType();
               $NetworkPort->update($data_Port);
            }

            $this->deleteFromDB(1);
            $Import++;
            break;

         default:
            $NoImport++;
            break;

      }
      return [$Import, $NoImport];
   }


   /**
    * Clean linked elements when purge an item
    */
   function cleanDBonPurge() {
      $networkPort= new NetworkPort();
      $networkPort->cleanDBonItemDelete($this->getType(), $this->fields['id']);
   }
}
