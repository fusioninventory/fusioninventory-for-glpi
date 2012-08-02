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
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

class PluginFusioninventoryUnknownDevice extends CommonDBTM {

   public $dohistory = true;

   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusioninventory']['menu'][4];
   }


   function canCreate() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "unknowndevice","w");
   }


   function canView() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "unknowndevice","r");
   }


   function getSearchOptions() {
      global $LANG;

      $tab = array();

      $tab['common'] = $LANG['plugin_fusioninventory']['menu'][4];

      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         =  'name';
      $tab[1]['linkfield']     ='name';
      $tab[1]['name']          = $LANG['common'][16];
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_type'] = $this->getType();

      $tab[2]['table']     = $this->getTable();
      $tab[2]['field']     = 'id';
      $tab[2]['linkfield'] = '';
      $tab[2]['name']      = $LANG['common'][2];

      $tab[3]['table']     = 'glpi_locations';
      $tab[3]['field']     = 'name';
      $tab[3]['linkfield'] = 'locations_id';
      $tab[3]['name']      = $LANG['common'][15];
      $tab[3]['datatype']  = 'text';

      $tab[4]['table']     = $this->getTable();
      $tab[4]['field']     = 'serial';
      $tab[4]['linkfield'] = 'serial';
      $tab[4]['name']      = $LANG['common'][19];
      $tab[4]['datatype']  = 'text';

      $tab[5]['table']     = $this->getTable();
      $tab[5]['field']     = 'otherserial';
      $tab[5]['linkfield'] = 'otherserial';
      $tab[5]['name']      = $LANG['common'][20];
      $tab[5]['datatype']  = 'text';

      $tab[6]['table']     = $this->getTable();
      $tab[6]['field']     = 'contact';
      $tab[6]['linkfield'] = 'contact';
      $tab[6]['name']      = $LANG['common'][92];
      $tab[6]['datatype']  = 'text';

      $tab[7]['table']     = $this->getTable();
      $tab[7]['field']     = 'hub';
      $tab[7]['linkfield'] = 'hub';
      $tab[7]['name']      = $LANG['plugin_fusioninventory']['unknown'][4];
      $tab[7]['datatype']  = 'bool';

      $tab[8]['table']     = 'glpi_entities';
      $tab[8]['field']     = 'completename';
      $tab[8]['linkfield'] = 'entities_id';
      $tab[8]['name']      = $LANG['entity'][0];

      $tab[9]['table']     = 'glpi_domains';
      $tab[9]['field']     = 'name';
      $tab[9]['linkfield'] = 'domain';
      $tab[9]['name']      = $LANG['setup'][89];

      $tab[10]['table']     = $this->getTable();
      $tab[10]['field']     = 'comment';
      $tab[10]['linkfield'] = 'comment';
      $tab[10]['name']      = $LANG['common'][25];
      $tab[10]['datatype']  = 'text';

      $tab[11]['table']     = $this->getTable();
      $tab[11]['field']     = 'ip';
      $tab[11]['linkfield'] = 'ip';
      $tab[11]['name']      = $LANG['networking'][14];
      $tab[11]['datatype']  = 'text';

      $tab[12]['table']     = $this->getTable();
      $tab[12]['field']     = 'mac';
      $tab[12]['linkfield'] = 'mac';
      $tab[12]['name']      = $LANG['networking'][15];
      $tab[12]['datatype']  = 'text';

      $tab[13]['table']     = $this->getTable();
      $tab[13]['field']     = 'item_type';
      $tab[13]['linkfield'] = 'item_type';
      $tab[13]['name']      = $LANG['common'][17];
//      $tab[13]['datatype']  = 'text';

      $tab[14]['table']     = $this->getTable();
      $tab[14]['field']     = 'date_mod';
      $tab[14]['linkfield'] = '';
      $tab[14]['name']      = $LANG['common'][26];
      $tab[14]['datatype']  = 'datetime';

      $tab += NetworkPort::getSearchOptionsToAdd("PluginFusioninventoryUnknownDevice");
      
      return $tab;
   }



   function defineTabs($options=array()) {
      global $LANG;


      $ong = array();
      if ($this->fields['id'] > 0){
         $ong[1]=$LANG['title'][27];
         $ong[2]=$LANG['buttons'][37];
         $ptc = new PluginFusioninventoryConfig;
         if (($ptc->isActive('fusioninventory', 'remotehttpagent')) AND(PluginFusioninventoryProfile::haveRight("fusioninventory", "remotecontrol","w"))) {
            $ong[3]=$LANG['plugin_fusioninventory']['task'][2];
         }
         $ong[4]=$LANG['title'][38];
      }
      return $ong;
   }

   

   /**
   * Display form for unknown device
   *
   * @param $id integer id of the unknown device
   * @param $options array
   *
   * @return bool true if form is ok
   *
   **/
   function showForm($id, $options=array()) {
      global $LANG;

      //PluginFusioninventoryProfile::checkRight("fusioninventory", "networking","r");

      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();
      }

      $this->showTabs($options);
      $this->showFormHeader($options);

      $datestring = $LANG['common'][26].": ";
      $date = Html::convDateTime($this->fields["date_mod"]);
      echo "<tr>";
      echo "<th align='center' width='450' colspan='2'>";
      echo $LANG['common'][2]." ".$this->fields["id"];
      echo "</th>";
   
      echo "<th align='center' colspan='2' width='50'>";
      echo $datestring.$date;
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . $LANG['common'][16] . "&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' value='" . $this->fields["name"] . "' size='35'/>";
      echo "</td>";

      if (Session::isMultiEntitiesMode()) {
         echo "<td align='center'>" . $LANG['entity'][0] . "&nbsp;:</td>";
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
      echo "<td align='center'>" . $LANG['common'][17] . "&nbsp;:</td>";
      echo "<td align='center'>";
         $type_list = array();
         $type_list[] = 'Computer';
         $type_list[] = 'NetworkEquipment';
         $type_list[] = 'Printer';
         $type_list[] = 'Peripheral';
         $type_list[] = 'Phone';
      Dropdown::dropdownTypes('item_type',$this->fields["item_type"],$type_list);
      echo "</td>";
      echo "<td align='center'>" . $LANG['common'][18] . "&nbsp;:</td>";
      echo "</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='contact' value='" . $this->fields["contact"] . "' size='35'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . $LANG['common'][15] . "&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::show("Location",
                     array('name'=>"locations_id",
                           'value'=>$this->fields["locations_id"]));
      echo "</td>";
      echo "<td align='center'>" . $LANG['setup'][89] . "&nbsp;:</td>";
      echo "</td>";
      echo "<td align='center'>";
      Dropdown::show("Domain",
                     array('name'=>"domain",
                           'value'=>$this->fields["domain"]));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . $LANG['plugin_fusioninventory']['unknown'][2] . " :</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo("accepted", $this->fields["accepted"]);
      echo "</td>";
      echo "<td align='center'>" . $LANG['common'][19] . "&nbsp;:</td>";
      echo "</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='serial' value='" . $this->fields["serial"] . "' size='35'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . $LANG['plugin_fusioninventory']['unknown'][4] . " :</td>";
      echo "<td align='center'>";
      echo Dropdown::getYesNo($this->fields["hub"]);
      echo "</td>";
      echo "<td align='center'>" . $LANG['common'][20] . "&nbsp;:</td>";
      echo "</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='otherserial' value='" . $this->fields["otherserial"] . "' size='35'/>";
      echo "</td>";
      echo "</tr>";

      if ((!empty($this->fields["ip"])) OR (!empty($this->fields["mac"]))) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>" . $LANG['networking'][14] . " :</td>";
         echo "<td align='center'>";
         echo "<input type='text' name='ip' value='" . $this->fields["ip"] . "' size='35'/>";
         echo "</td>";

         echo "<td align='center'>" . $LANG['networking'][15] . "&nbsp;:</td>";
         echo "</td>";
         echo "<td align='center'>";
         echo "<input type='text' name='mac' value='" . $this->fields["mac"] . "' size='35'/>";
         echo "</td>";
         echo "</tr>";
      }
      
      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>" . $LANG['common'][25] . " : </td>";
      echo "</td>";
      echo "<td align='middle' colspan='3'>";
      echo "<textarea  cols='80' rows='2' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "</tr>";

     
      $this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
   }



   /**
   * Form to import devices in GLPI inventory (computer, printer...)
   *
   * @param $target target page
   * @param $id integer id of the unknowndevice
   *
   * @return nothing
   *
   **/
   function importForm($target,$id) {
      global $LANG;
      
      echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";
      echo "<table  class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th align='center'>";
      echo $LANG['plugin_fusioninventory']['unknown'][5];
      echo "</th>";
      echo "</tr>";
      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      $this->getFromDB($id);
      if ($this->fields["item_type"] != '0') {
         echo "<input type='hidden' name='id' value=$id>";
         echo "<input type='submit' name='import' value=\"".$LANG['buttons'][37]."\" class='submit'>";
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
                     LEFT JOIN `glpi_plugin_fusioninventory_unknowndevices`
                               ON `items_id`=`glpi_plugin_fusioninventory_unknowndevices`.`id`
                     WHERE `itemtype`='PluginFusioninventoryUnknownDevice'
                           AND `glpi_plugin_fusioninventory_unknowndevices`.`id` IS NULL;";
      $unknown_infos = array();
      $result=$DB->query($query);
      if ($result) {
         while ($data=$DB->fetch_array($result)) {
            $unknown_infos["name"] = '';
            $newID=$this->add($unknown_infos);
            
            $query_update = "UPDATE `glpi_networkports`
                             SET `items_id`='".$newID."'
                             WHERE `id`='".$data["id"]."';";
            $DB->query($query_update);
         }
      }      
   }



   /**
   * Convert an unknown device to unknown "networkequipment"
   *
   * @param $id integer id of the unknown device
   *
   * @return bool
   *
   **/
   function convertUnknownToUnknownNetwork($id) {
      
      $np = new NetworkPort();

      $this->getFromDB($id);

      // Get port
      $a_ports = $np->find('items_id='.$id." AND itemtype='PluginFusioninventoryUnknownDevice'");

      if (count($a_ports) == '1') {
         // Put mac and ip to unknown
         $port = current($a_ports);
         $input = array();
         $input['id'] = $this->fields['id'];
         $input['ip'] = $port['ip'];
         $input['mac'] = $port['mac'];

         $this->update($input);
         $delete_port = $np->getFromDB($port['id']);
         $np->delete($delete_port, 1);
         return true;
      }
      return false;
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
   function hubNetwork($pfNetworkport) {

      $nn = new NetworkPort_NetworkPort();
      $Netport = new NetworkPort();
      // Get port connected on switch port
      $hub_id = 0;
      $ID = $nn->getOppositeContact($pfNetworkport->getNetworkPorts_id());
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
               $hub_id = $this->createHub($pfNetworkport);
            }
         } else {
            // It's a direct connection, so disconnect and create a hub
            $this->disconnectDB($ID);
            $hub_id = $this->createHub($pfNetworkport);
         }
      } else {
         // No connections found and create a hub
         $hub_id = $this->createHub($pfNetworkport);
      }
      // State : Now we have hub and it's id
      
      // Add source port id in comment of hub
      $h_input = array();
      $h_input['id'] = $hub_id;
      $h_input['comment'] = "Port : ".$pfNetworkport->getNetworkPorts_id();
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

      foreach ($pfNetworkport->getMacsToConnect() as $ifmac) {
         $a_ports = $Netport->find("`mac`='".$ifmac."'", "", 1);
         if (count($a_ports) == "1") {
            if (!$this->searchIfmacOnHub($a_ports, $a_portglpi)) {
               // Connect port (port found in GLPI)
               $this->connectPortToHub($a_ports, $hub_id);
            }
         } else if (count($a_ports) == "0") {
            // Port don't exist
            // Create unknown device
            $input = array();
            $input['name'] = '';
            if (isset($_SESSION["plugin_fusinvinventory_entity"])) {
               $input['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
            }
            $unknown_id = $this->add($input);
            $input = array();
            $input["items_id"] = $unknown_id;
            $input["itemtype"] = $this->getType();
            $input["mac"] = $ifmac;
            $id_port = $Netport->add($input);
            $Netport->getFromDB($id_port);
            $this->connectPortToHub(array($id_port => $Netport->fields), $hub_id);
         }
      }
   }



   /**
   * Delete all ports connected in hub and not found in last inventory
   *
   * @param $hub_id integer id of the hub (unknown device)
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
            //plugin_fusioninventory_addLogConnection("remove",$port_id);
            $this->disconnectDB($data['id']);
            $Netport->deleteFromDB($data['id']);
         }
      }
   }



   /**
   * Connect a port to hub
   *
   * @param $a_port array datas of a port
   * @param $hub_id integer id of the hub (unknown device)
   *
   * @return id of the port of the hub where port is connected
   *
   **/
   function connectPortToHub($a_port, $hub_id) {
      global $DB;

      $Netport = new NetworkPort();
      $nn = new NetworkPort_NetworkPort();

      $data = current($a_port);
       //plugin_fusioninventory_addLogConnection("remove",$port_id);
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
         $freeport_id = $Netport->add($input);
      }
      $this->disconnectDB($freeport_id);
      $nn->add(array('networkports_id_1'=> $data['id'], 
                     'networkports_id_2' => $freeport_id));

      //plugin_fusioninventory_addLogConnection("make",$port_id);
      return $freeport_id;
   }


   
   function disconnectDB($p_port) {
      $nn = new NetworkPort_NetworkPort();

      if ($nn->getOppositeContact($p_port) AND $nn->getFromDBForNetworkPort($nn->getOppositeContact($p_port))) {
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

      $data= array();
      $data = current($a_port);
      if (isset($a_portglpi[$data['id']])) {
         return $a_portglpi[$data['id']];
      }
      return false;
   }



   /**
   * Creation of a hub 
   *
   * @param $pfNetworkport object Informations of the network port
   *
   * @return id of the hub (unknowndevice)
   *
   **/
   function createHub($pfNetworkport) {

      $Netport = new NetworkPort();
      $nn = new NetworkPort_NetworkPort();
      //$pfAgentsProcesses = new PluginFusionInventoryAgentsProcesses;

      // Find in the mac connected to the if they are in hub without link port connected
      foreach ($pfNetworkport->getMacsToConnect() as $ifmac) {
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
                           $this->disconnectDB($pfNetworkport->getNetworkPorts_id());
                           $this->disconnectDB($dataLink['id']);
                           $nn->add(array('networkports_id_1'=> $pfNetworkport->getNetworkPorts_id(), 
                                           'networkports_id_2' => $dataLink['id']));
                           $this->releaseHub($this->fields['id'], $pfNetworkport);
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
      if (isset($_SESSION["plugin_fusinvinventory_entity"])) {
         $input['entities_id'] = $_SESSION["plugin_fusinvinventory_entity"];
      }
      $hub_id = $this->add($input);

      $input = array();
      $input["items_id"] = $hub_id;
      $input["itemtype"] = $this->getType();
      $input["name"] = "Link";
      $port_id = $Netport->add($input);
      $this->disconnectDB($pfNetworkport->getNetworkPorts_id());
      $this->disconnectDB($port_id);
      if ($nn->add(array('networkports_id_1'=> $pfNetworkport->getNetworkPorts_id(), 
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
   function releaseHub($hub_id, $pfNetworkport) {

      $Netport = new NetworkPort();
      $nn = new NetworkPort_NetworkPort();

      $a_macOnSwitch = array();
      foreach ($pfNetworkport->getMacsToConnect() as $ifmac) {
         $a_macOnSwitch["$ifmac"] = 1;
      }

      // get all ports of hub
      $releasePorts = array();
      $a_ports = $Netport->find("`items_id`='".$hub_id."' AND `itemtype`='".$this->getType()."' AND (`name` != 'Link' OR `name` IS NULL)");
      foreach ($a_ports as $port_id=>$data) {
         $id = $nn->getOppositeContact($port_id);
         if ($id) {
            $Netport->getFromDB($id);
            if (!isset($a_macOnSwitch[$Netport->fields["mac"]])) {
               $releasePorts[$port_id] = 1;
            }
         }
      }
   }



   /**
   * Clean hubs (unknown device) yet in inventory (clean connections, networkport, and hub)
   *
   * @return nothing
   *
   **/
   function cleanUnknownSwitch() {
      global $DB;

      $query = "SELECT `glpi_plugin_fusioninventory_unknowndevices`.* FROM `glpi_plugin_fusioninventory_unknowndevices`
         INNER JOIN `glpi_plugin_fusinvsnmp_networkequipmentips` ON `glpi_plugin_fusioninventory_unknowndevices`.`ip` = `glpi_plugin_fusinvsnmp_networkequipmentips`.`ip`
         WHERE `glpi_plugin_fusioninventory_unknowndevices`.`ip` IS NOT NULL
            AND `glpi_plugin_fusioninventory_unknowndevices`.`ip` != '' ";
      $result=$DB->query($query);
      if ($result) {
         while ($data=$DB->fetch_array($result)) {
            $query_port = "SELECT * FROM `glpi_networkports`
               WHERE items_id='".$data['id']."'
                  AND itemtype='".$this->getType()."' ";
            $result_port=$DB->query($query_port);
            if ($result_port) {
               while ($data_port=$DB->fetch_array($result_port)) {
                  //plugin_fusioninventory_addLogConnection("remove",$data_port['ID']);
                  $this->disconnectDB($data_port['id']);
                  $np = new NetworkPort();
                  $np->deleteFromDB($data_port['id']);
               }
            }
            $this->deleteFromDB($data['id']);
         }
      }
   }

   
   
// *************************** end hub management ****************************** //

   /**
   * Write XML in a folder when unknown device is created from an inventory by agent
   *
   * @param $items_id integer id of the unknown device
   * @param $xml value xml informations (with XML structure)
   *
   * @return nothing
   *
   **/
   static function writeXML($items_id, $xml, $pluginname='fusioninventory/xml', $itemtype='PluginFusioninventoryUnknownDevice') {

      $folder = substr($items_id,0,-1);
      if (empty($folder)) {
         $folder = '0';
      }
      if (!file_exists(GLPI_PLUGIN_DOC_DIR."/".$pluginname."/".$itemtype)) {
         mkdir(GLPI_PLUGIN_DOC_DIR."/".$pluginname."/".$itemtype);
      }
      if (!file_exists(GLPI_PLUGIN_DOC_DIR."/".$pluginname."/".$itemtype."/".$folder)) {
         mkdir(GLPI_PLUGIN_DOC_DIR."/".$pluginname."/".$itemtype."/".$folder);
      }
      $fileopen = fopen(GLPI_PLUGIN_DOC_DIR."/".$pluginname."/".$itemtype."/".$folder."/".$items_id, 'w');
      fwrite($fileopen, $xml);
      fclose($fileopen);
   }



   /**
   * Purge unknwon devices
   *
   * @param $pram object to purge
   *
   * @return nothing
   *
   **/
   static function purgeUnknownDevice($parm) {

      // Delete XML file if exist
      $folder = substr($parm->fields["id"],0,-1);
      if (empty($folder)) {
         $folder = '0';
      }
      
      if (file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/PluginFusioninventoryUnknownDevice/".$folder."/".$parm->fields["id"])) {
         unlink(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/PluginFusioninventoryUnknownDevice/".$folder."/".$parm->fields["id"]);
      }

      // Delete Networkports
      $NetworkPort = new NetworkPort();
      $a_ports = $NetworkPort->find("`items_id`='".$parm->fields["id"]."'
                     AND `itemtype`='PluginFusioninventoryUnknownDevice'");
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
   function import($items_id,$Import=0, $NoImport=0) {

      $NetworkPort = new NetworkPort();

      $a_NetworkPorts = $NetworkPort->find("`items_id` = '".$items_id."'
                      AND `itemtype` = 'PluginFusioninventoryUnknownDevice'");

      $this->getFromDB($items_id);
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
            $printer_id = $Printer->add($data);

            foreach ($a_NetworkPorts as $data_Port) {
               $data_Port['items_id'] = $printer_id;
               $data_Port['itemtype'] = $Printer->getType();
               $NetworkPort->update($data_Port);
            }

            // Import SNMP if enable
            if (PluginFusioninventoryModule::getModuleId("fusinvsnmp")) {
               $pfUnknownDevice = new PluginFusinvsnmpUnknownDevice();
               $pfUnknownDevice->import($items_id, $printer_id, 'Printer');
            }

            $this->deleteFromDB($items_id,1);
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
            $data_Port = current($a_NetworkPorts);
            $data["ip"] = $data_Port["ip"];
            $data["mac"] = $data_Port["mac"];
            $NetworkEquipment_id = $NetworkEquipment->add($data);

            foreach ($a_NetworkPorts as $data_Port) {
               $data_Port['items_id'] = $NetworkEquipment_id;
               $data_Port['itemtype'] = $NetworkEquipment->getType();
               $NetworkPort->update($data_Port);
            }

            // Import SNMP if enable
            if (PluginFusioninventoryModule::getModuleId("fusinvsnmp")) {
               $pfUnknownDevice = new PluginFusinvsnmpUnknownDevice();
               $pfUnknownDevice->import($items_id, $NetworkEquipment_id, 'NetworkEquipment');
            }

            $this->deleteFromDB($items_id,1);
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
            $Peripheral_id = $Peripheral->add($data);

            foreach ($a_NetworkPorts as $data_Port) {
               $data_Port['items_id'] = $Peripheral_id;
               $data_Port['itemtype'] = $Peripheral->getType();
               $NetworkPort->update($data_Port);
            }

            $this->deleteFromDB($items_id,1);
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
            $Computer_id = $Computer->add($data);

            foreach ($a_NetworkPorts as $data_Port) {
               $data_Port['items_id'] = $Computer_id;
               $data_Port['itemtype'] = $Computer->getType();
               $NetworkPort->update($data_Port);
            }

            $this->deleteFromDB($items_id,1);
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
            $phone_id = $Phone->add($data);

            foreach ($a_NetworkPorts as $data_Port) {
               $data_Port['items_id'] = $phone_id;
               $data_Port['itemtype'] = $Phone->getType();
               $NetworkPort->update($data_Port);
            }

            $this->deleteFromDB($items_id,1);
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