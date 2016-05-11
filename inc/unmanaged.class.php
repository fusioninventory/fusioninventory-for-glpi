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

class PluginFusioninventoryUnmanaged extends CommonDBTM {

   public $dohistory = TRUE;

   static $rightname = 'plugin_fusioninventory_unmanaged';

   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Unmanaged device', 'fusioninventory');
   }


   /**
    * @see CommonDBTM::useDeletedToLockIfDynamic()
    *
    * @since version 0.85+1.0
   **/
   function useDeletedToLockIfDynamic() {
      return false;
   }



   static function getMenuName() {
      return self::getTypeName();
   }

   static function getMenuContent() {
      global $CFG_GLPI;
      $menu          = array();
      if (Session::haveRight(static::$rightname, READ)) {
         $menu['title']           = self::getTypeName();
         $menu['page']            = self::getSearchURL(false);
         $menu['links']['search'] = self::getSearchURL(false);
      }
      return $menu;
   }

   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('Unmanaged device', 'fusioninventory');

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         =  'name';
      $tab[1]['linkfield']     ='name';
      $tab[1]['name']          = __('Name');
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_type'] = $this->getType();

      $tab[2]['table']     = $this->getTable();
      $tab[2]['field']     = 'id';
      $tab[2]['linkfield'] = '';
      $tab[2]['name']      = __('ID');

      $tab[3]['table']     = 'glpi_locations';
      $tab[3]['field']     = 'name';
      $tab[3]['linkfield'] = 'locations_id';
      $tab[3]['name']      = __('Location');
      $tab[3]['datatype']  = 'dropdown';

      $tab[4]['table']     = $this->getTable();
      $tab[4]['field']     = 'serial';
      $tab[4]['linkfield'] = 'serial';
      $tab[4]['name']      = __('Serial Number');

      $tab[5]['table']     = $this->getTable();
      $tab[5]['field']     = 'otherserial';
      $tab[5]['linkfield'] = 'otherserial';
      $tab[5]['name']      = __('Inventory number');

      $tab[6]['table']     = $this->getTable();
      $tab[6]['field']     = 'contact';
      $tab[6]['linkfield'] = 'contact';
      $tab[6]['name']      = __('Contact');

      $tab[7]['table']     = $this->getTable();
      $tab[7]['field']     = 'hub';
      $tab[7]['linkfield'] = 'hub';
      $tab[7]['name']      = __('Network hub', 'fusioninventory');
      $tab[7]['datatype']  = 'bool';

      $tab[8]['table']     = 'glpi_entities';
      $tab[8]['field']     = 'completename';
      $tab[8]['linkfield'] = 'entities_id';
      $tab[8]['name']      = __('Entity');
      $tab[8]['datatype']  = 'dropdown';

      $tab[9]['table']     = 'glpi_domains';
      $tab[9]['field']     = 'name';
      $tab[9]['linkfield'] = 'domain';
      $tab[9]['name']      = __('Domain');
      $tab[9]['datatype']  = 'dropdown';


      $tab[10]['table']     = $this->getTable();
      $tab[10]['field']     = 'comment';
      $tab[10]['linkfield'] = 'comment';
      $tab[10]['name']      = __('Comments');
      $tab[10]['datatype']  = 'text';

      $tab[13]['table']     = $this->getTable();
      $tab[13]['field']     = 'item_type';
      $tab[13]['linkfield'] = 'item_type';
      $tab[13]['name']      = __('Type');
      $tab[13]['datatype']  = 'dropdown';

      $tab[14]['table']     = $this->getTable();
      $tab[14]['field']     = 'date_mod';
      $tab[14]['linkfield'] = '';
      $tab[14]['name']      = __('Last update');
      $tab[14]['datatype']  = 'datetime';

      $tab[15]['table']     = $this->getTable();
      $tab[15]['field']     = 'sysdescr';
      $tab[15]['linkfield'] = '';
      $tab[15]['name']      = __('Sysdescr', 'fusioninventory');
      $tab[15]['datatype']  = 'text';

      $tab[17]['table']     = 'glpi_plugin_fusioninventory_configsecurities';
      $tab[17]['field']     = 'name';
      $tab[17]['linkfield'] = 'plugin_fusioninventory_configsecurities_id';
      $tab[17]['name']      = __('SNMP authentication', 'fusioninventory');
      $tab[17]['datatype']  = 'dropdown';

      $tab += NetworkPort::getSearchOptionsToAdd("PluginFusioninventoryUnmanaged");

      return $tab;
   }


   /**
    * Display tab
    *
    * @param CommonGLPI $item
    * @param integer $withtemplate
    *
    * @return varchar name of the tab(s) to display
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      $ong = array();
      if ($item->fields['id'] > 0){
         $ong[1]=__('Import');

         $pfConfig = new PluginFusioninventoryConfig();
         if (($pfConfig->isActive('remotehttpagent'))
                 && (Session::haveRight('plugin_fusioninventory_remotecontrol', UPDATE))) {
            $ong[2]=__('Job', 'fusioninventory');
         }
      }
      return $ong;
   }



   /**
    * Display content of tab
    *
    * @param CommonGLPI $item
    * @param integer $tabnum
    * @param interger $withtemplate
    *
    * @return boolean TRUE
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      global $CFG_GLPI;

      if ($tabnum == 1) {
         $pfUnmanaged = new self();
         $pfUnmanaged->importForm($CFG_GLPI['root_doc'] .
               '/plugins/fusioninventory/front/unmanaged.form.php?id='.$item->fields["id"],
                                   $item->fields["id"]);
      }
      return TRUE;
   }



   function defineTabs($options=array()) {

      $ong = array();
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('NetworkPort', $ong, $options);
      $this->addStandardTab('PluginFusioninventoryUnmanaged', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);
      return $ong;
   }



   /**
    * Massive action ()
    */
   function getSpecificMassiveActions($checkitem=NULL) {

      $actions = array();
      if (Session::haveRight('plugin_fusioninventory_unmanaged', UPDATE)) {
         $actions['PluginFusioninventoryUnmanaged'.MassiveAction::CLASS_ACTION_SEPARATOR.'import']    = __('Import');
      }
      if(Session::haveRight('plugin_fusioninventory_configsecurity', READ)) {
         $actions['PluginFusioninventoryUnmanaged'.MassiveAction::CLASS_ACTION_SEPARATOR.'assign_auth']       =
                                       __('Assign SNMP authentication', 'fusioninventory');
      }
      return $actions;
   }



   /**
    * @since version 0.85
    *
    * @see CommonDBTM::showMassiveActionsSubForm()
   **/
   static function showMassiveActionsSubForm(MassiveAction $ma) {

      switch ($ma->getAction()) {

         case "assign_auth" :
            PluginFusioninventoryConfigSecurity::auth_dropdown();
            echo "<br><br>";
            break;

      }
      return parent::showMassiveActionsSubForm($ma);
   }



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
      return;
   }



   /**
   * Display form for unmanaged device
   *
   * @param $id integer id of the unmanaged device
   * @param $options array
   *
   * @return bool TRUE if form is ok
   *
   **/
   function showForm($id, $options=array()) {

      $this->initForm($id, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . __('Name') . "&nbsp;:</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this,'name', array('size' => 35));
      echo "</td>";

      if (Session::isMultiEntitiesMode()) {
         echo "<td align='center'>" . __('Entity') . "&nbsp;:</td>";
         echo "</td>";
         echo "<td align='center'>";
         Dropdown::show("Entity",
                        array('name'=>'entities_id',
                              'value'=>$this->fields["entities_id"]));
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
         $type_list = array();
         $type_list[] = 'Computer';
         $type_list[] = 'NetworkEquipment';
         $type_list[] = 'Printer';
         $type_list[] = 'Peripheral';
         $type_list[] = 'Phone';
      Dropdown::showItemTypes('item_type', $type_list,
                                          array('value' => $this->fields["item_type"]));
      echo "</td>";
      echo "<td align='center'>" . __('Alternate username') . "&nbsp;:</td>";
      echo "</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this,'contact', array('size' => 35));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . __('Location') . "&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::show("Location",
                     array('name'=>"locations_id",
                           'value'=>$this->fields["locations_id"]));
      echo "</td>";
      echo "<td align='center'>" . __('Domain') . "&nbsp;:</td>";
      echo "</td>";
      echo "<td align='center'>";
      Dropdown::show("Domain",
                     array('name'=>"domain",
                           'value'=>$this->fields["domain"]));
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
      Html::autocompletionTextField($this,'serial', array('size' => 35));
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
      Html::autocompletionTextField($this,'otherserial', array('size' => 35));
      echo "</td>";
      echo "</tr>";

      if ((!empty($this->fields["ip"])) OR (!empty($this->fields["mac"]))) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>" . __('IP') . " :</td>";
         echo "<td align='center'>";
         Html::autocompletionTextField($this,'ip', array('size' => 35));
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
      echo "<td align='center'>".__('SNMP authentication', 'fusioninventory')."&nbsp;:</td>";
      echo "<td align='center'>";
      PluginFusioninventoryConfigSecurity::auth_dropdown(
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

      return TRUE;
   }



   /**
   * Form to import devices in GLPI inventory (computer, printer...)
   *
   * @param $target target page
   * @param $id integer id of the unmanaged
   *
   * @return nothing
   *
   **/
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
   * @return nothing
   *
   **/
   function CleanOrphelinsConnections() {
      global $DB;

      $query = "SELECT `glpi_networkports`.`id`
                FROM `glpi_networkports`
                     LEFT JOIN `glpi_plugin_fusioninventory_unmanageds`
                               ON `items_id`=`glpi_plugin_fusioninventory_unmanageds`.`id`
                     WHERE `itemtype`='PluginFusioninventoryUnmanaged'
                           AND `glpi_plugin_fusioninventory_unmanageds`.`id` IS NULL;";
      $unmanaged_infos = array();
      $result=$DB->query($query);
      if ($result) {
         while ($data=$DB->fetch_array($result)) {
            $unmanaged_infos["name"] = '';
            $newID=$this->add($unmanaged_infos);

            $query_update = "UPDATE `glpi_networkports`
                             SET `items_id`='".$newID."'
                             WHERE `id`='".$data["id"]."';";
            $DB->query($query_update);
         }
      }
   }



// ************************* Hub Management ************************ //

   /**
   * Manage a hub (many mac on a port mean you have a hub)
   *
   * @param $pfNetworkport object Informations of the network port (switch port)
   *
   * @return bool
   *
   **/
   function hubNetwork($pfNetworkport, $a_mac) {

      $nn = new NetworkPort_NetworkPort();
      $Netport = new NetworkPort();
      // Get port connected on switch port
      $hub_id = 0;
      $ID = $nn->getOppositeContact($pfNetworkport->fields['networkports_id']);
      if ($ID) {
         $Netport->getFromDB($ID);
         if ($Netport->fields["itemtype"] == $this->getType()) {
            $this->getFromDB($Netport->fields["items_id"]);
            if ($this->fields["hub"] == "1") {
               // It's a hub connected, so will update connections
               //$this->releaseHub($this->fields['id'], $p_oPort);
               $hub_id = $this->fields['id'];
            } else {
               // It's a direct connection, so disconnect and create a hub
               $this->disconnectDB($ID);
               $hub_id = $this->createHub($pfNetworkport, $a_mac);
            }
         } else {
            // It's a direct connection, so disconnect and create a hub
            $this->disconnectDB($ID);
            $hub_id = $this->createHub($pfNetworkport, $a_mac);
         }
      } else {
         // No connections found and create a hub
         $hub_id = $this->createHub($pfNetworkport, $a_mac);
      }
      // State : Now we have hub and it's id

      // Add source port id in comment of hub
      $h_input = array();
      $h_input['id'] = $hub_id;
      $h_input['comment'] = "Port : ".$pfNetworkport->fields['networkports_id'];
      $this->update($h_input);


      // Get all ports connected to this hub
      $a_portglpi = array();
      $a_ports = $Netport->find("`items_id`='".$hub_id."'
          AND `itemtype`='".$this->getType()."'");
      foreach ($a_ports as $data) {
         $id = $nn->getOppositeContact($data['id']);
         if ($id) {
            $a_portglpi[$id] = $data['id'];
         }
      }

      foreach ($a_mac as $ifmac) {
         $a_ports = $Netport->find("`mac`='".$ifmac."'", "", 1);
         if (count($a_ports) == "1") {
            if (!$this->searchIfmacOnHub($a_ports, $a_portglpi)) {
               // Connect port (port found in GLPI)
               $this->connectPortToHub($a_ports, $hub_id);
            }
         } else if (count($a_ports) == "0") {
            // Port don't exist
            // Create unmanaged device
            $input = array();
            $a_manufacturer = array(
                0 => PluginFusioninventoryInventoryExternalDB::getManufacturerWithMAC($ifmac)
            );
            $a_manufacturer = PluginFusioninventoryFormatconvert::cleanArray($a_manufacturer);
            $input['name'] = $a_manufacturer[0];
            if (isset($_SESSION["plugin_fusioninventory_entity"])) {
               $input['entities_id'] = $_SESSION["plugin_fusioninventory_entity"];
            }
            $unmanageds_id = $this->add($input);
            $input = array();
            $input["items_id"] = $unmanageds_id;
            $input["itemtype"] = $this->getType();
            $input["mac"] = $ifmac;
            $input['instantiation_type'] = "NetworkPortEthernet";
            $id_port = $Netport->add($input);
            $Netport->getFromDB($id_port);
            $this->connectPortToHub(array($id_port => $Netport->fields), $hub_id);
         }
      }
   }



   /**
   * Delete all ports connected in hub and not found in last inventory
   *
   * @param $hub_id integer id of the hub (unmanaged device)
   * @param $a_portUsed array list of the ports found in last inventory
   *
   * @return nothing
   *
   **/
   function deleteNonUsedPortHub($hub_id, $a_portUsed) {

      $Netport = new NetworkPort();

      $a_ports = $Netport->find("`items_id`='".$hub_id."'
          AND `itemtype`='".$this->getType()."'
          AND (`name` != 'Link' OR `name` IS NULL)");
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
   * @param $a_port array datas of a port
   * @param $hub_id integer id of the hub (unmanaged device)
   *
   * @return id of the port of the hub where port is connected
   *
   **/
   function connectPortToHub($a_port, $hub_id) {
      global $DB;

      $Netport = new NetworkPort();
      $nn = new NetworkPort_NetworkPort();

      $data = current($a_port);
       //plugin_fusioninventory_addLogConnection("remove", $port_id);
      $this->disconnectDB($data['id']);
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
         $freeport = $DB->fetch_assoc($result);
         $freeport_id = $freeport['id'];
      } else {
         // Create port
         $input = array();
         $input["items_id"] = $hub_id;
         $input["itemtype"] = $this->getType();
         $input['instantiation_type'] = "NetworkPortEthernet";
         $freeport_id = $Netport->add($input);
      }
      $this->disconnectDB($freeport_id);
      $nn->add(array('networkports_id_1'=> $data['id'],
                     'networkports_id_2' => $freeport_id));

      //plugin_fusioninventory_addLogConnection("make", $port_id);
      return $freeport_id;
   }



   function disconnectDB($p_port) {
      $nn = new NetworkPort_NetworkPort();

      if ($nn->getOppositeContact($p_port)
              && $nn->getFromDBForNetworkPort($nn->getOppositeContact($p_port))) {
         if ($nn->delete($nn->fields)) {
            plugin_item_purge_fusioninventory($nn);
         }
      }
      if ($nn->getFromDBForNetworkPort($p_port)) {
         if ($nn->delete($nn->fields)) {
            plugin_item_purge_fusioninventory($nn);
         }
      }
   }



   /**
   * Search if port yet connected to hub
   *
   * @param $a_port array datas of a port
   * @param $a_portglpi array all ports connected to the hub
   *
   * @return id of the port of the hub where port is connected
   *
   **/
   function searchIfmacOnHub($a_port, $a_portglpi) {

      $data = current($a_port);
      if (isset($a_portglpi[$data['id']])) {
         return $a_portglpi[$data['id']];
      }
      return FALSE;
   }



   /**
   * Creation of a hub
   *
   * @param $pfNetworkport object Informations of the network port
   *
   * @return id of the hub (unmanageddevice)
   *
   **/
   function createHub($pfNetworkport, $a_mac) {

      $Netport = new NetworkPort();
      $nn = new NetworkPort_NetworkPort();
      //$pfAgentsProcesses = new PluginFusionInventoryAgentsProcesses;

      // Find in the mac connected to the if they are in hub without link port connected
      foreach ($a_mac as $ifmac) {
         $a_ports = $Netport->find("`mac`='".$ifmac."'");
         foreach ($a_ports as $data) {
            $ID = $nn->getOppositeContact($pfNetworkport->getNetworkPorts_id());
            if ($ID) {
               $Netport->getFromDB($ID);
               if ($Netport->fields["itemtype"] == $this->getType()) {
                  if ($this->fields["hub"] == "1") {
                     $a_portLink = $Netport->find("`name`='Link'
                        AND `items_id`='".$this->fields['id']."'
                        AND `itemtype`='".$this->getType()."'");
                     foreach ($a_portLink as $dataLink) {
                        if ($nn->getOppositeContact($dataLink['id'])) {

                        } else {
                           // We have founded a hub orphelin
                           $this->disconnectDB($pfNetworkport->fields['networkports_id']);
                           $this->disconnectDB($dataLink['id']);
                           $nn->add(array('networkports_id_1'=>
                                                $pfNetworkport->fields['networkports_id'],
                                          'networkports_id_2' => $dataLink['id']));
                           $this->releaseHub($this->fields['id'], $pfNetworkport, $a_mac);
                           return $this->fields['id'];
                        }
                     }
                  }
               }
            }
         }
      }
      // Not found, creation hub and link port
      $input = array();
      $input['hub'] = "1";
      $input['name'] = "hub";
      if (isset($_SESSION["plugin_fusioninventory_entity"])) {
         $input['entities_id'] = $_SESSION["plugin_fusioninventory_entity"];
      }
      $hub_id = $this->add($input);

      $input = array();
      $input["items_id"] = $hub_id;
      $input["itemtype"] = $this->getType();
      $input["name"] = "Link";
      $input['instantiation_type'] = "NetworkPortEthernet";
      $port_id = $Netport->add($input);
      $this->disconnectDB($pfNetworkport->fields['networkports_id']);
      $this->disconnectDB($port_id);
      if ($nn->add(array('networkports_id_1'=> $pfNetworkport->fields['networkports_id'],
                         'networkports_id_2' => $port_id))) {
      }
      return $hub_id;
   }



   /**
   * Remove all connections on a hub
   *
   * @param $hub_id integer id of the hub
   * @param $pfNetworkport object Informations of the network port
   *
   * @return nothing
   *
   **/
   function releaseHub($hub_id, $pfNetworkport, $a_mac) {

      $Netport = new NetworkPort();
      $nn = new NetworkPort_NetworkPort();

      $a_macOnSwitch = array();
      foreach ($a_mac as $ifmac) {
         $a_macOnSwitch["$ifmac"] = 1;
      }

      // get all ports of hub
      $releasePorts = array();
      $a_ports = $Netport->find("`items_id`='".$hub_id."' AND `itemtype`='".$this->getType()."' ".
                                 "AND (`name` != 'Link' OR `name` IS NULL)");
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
   * @param $pram object to purge
   *
   * @return nothing
   *
   **/
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
      $a_ports = $NetworkPort->find("`items_id`='".$parm->fields["id"]."'
                     AND `itemtype`='PluginFusioninventoryUnmanaged'");
      foreach($a_ports as $a_port) {
         $NetworkPort->delete($a_port, 1);
      }

   }



   /**
    * Function to import discovered device
    *
    * @param $items_id id of the device to import
    *
    * @return nothing
    *
   **/
   function import($items_id, $Import=0, $NoImport=0) {
      global $DB;

      $NetworkPort = new NetworkPort();

      $a_NetworkPorts = $NetworkPort->find("`items_id` = '".$items_id."'
                      AND `itemtype` = 'PluginFusioninventoryUnmanaged'");

      $this->getFromDB($items_id);
      $this->fields = Toolbox::addslashes_deep($this->fields);
      $data = array();
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
            $data = array();
            if ($DB->numrows($result) > 0) {
               $data = $DB->fetch_assoc($result);
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

            $this->deleteFromDB($items_id, 1);
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
            $data = array();
            if ($DB->numrows($result) > 0) {
               $data = $DB->fetch_assoc($result);
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

            $this->deleteFromDB($items_id, 1);
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

            $this->deleteFromDB($items_id, 1);
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

            $this->deleteFromDB($items_id, 1);
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

            $this->deleteFromDB($items_id, 1);
            $Import++;
            break;

         default:
            $NoImport++;
            break;

      }
      return array($Import, $NoImport);
   }



   function cleanDBonPurge() {
      $networkPort= new NetworkPort();
      $networkPort->cleanDBonItemDelete($this->getType(), $this->fields['id']);
   }
}

?>
