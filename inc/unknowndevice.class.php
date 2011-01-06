<?php
/*
 ----------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

	This file is part of GLPI.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginFusioninventoryUnknownDevice extends CommonDBTM {

	function __construct() {
		$this->dohistory=true;
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

      $tab['common'] = $LANG['plugin_fusioninventory']['agents'][28];

		$tab[1]['table'] = $this->getTable();
		$tab[1]['field'] = 'name';
		$tab[1]['linkfield'] = 'name';
		$tab[1]['name'] = $LANG['common'][16];
		$tab[1]['datatype'] = 'itemlink';

		$tab[2]['table'] = $this->getTable();
		$tab[2]['field'] = 'location';
		$tab[2]['linkfield'] = 'location';
		$tab[2]['name'] = 'location';
		$tab[2]['datatype'] = 'text';

		$tab[3]['table'] = $this->getTable();
		$tab[3]['field'] = 'serial';
		$tab[3]['linkfield'] = 'serial';
		$tab[3]['name'] = 'serial';
		$tab[3]['datatype'] = 'text';

		$tab[4]['table'] = $this->getTable();
		$tab[4]['field'] = 'otherserial';
		$tab[4]['linkfield'] = 'otherserial';
		$tab[4]['name'] = 'otherserial';
		$tab[4]['datatype'] = 'text';

		$tab[5]['table'] = $this->getTable();
		$tab[5]['field'] = 'contact';
		$tab[5]['linkfield'] = 'contact';
		$tab[5]['name'] = 'contact';
		$tab[5]['datatype'] = 'itemlink';

		$tab[6]['table'] = $this->getTable();
		$tab[6]['field'] = 'hub';
		$tab[6]['linkfield'] = 'hub';
		$tab[6]['name'] ='hub';
		$tab[6]['datatype'] = 'bool';

      return $tab;
   }



   function defineTabs($options=array()){
		global $LANG,$CFG_GLPI;


      $ong = array();
		if ($this->fields['id'] > 0){
         $ong[1]=$LANG['title'][27];
         $ong[2]=$LANG['buttons'][37];
         $ptc = new PluginFusioninventoryConfig;
         if (($ptc->is_active('fusioninventory', 'remotehttpagent')) AND(PluginFusioninventoryProfile::haveRight("fusioninventory", "remotecontrol","w"))) {
            $ong[3]=$LANG['plugin_fusioninventory']['task'][2];
         }
         $ong[4]=$LANG['title'][38];
      }
		return $ong;
	}

	function showForm($id, $options=array()) {
		global $DB,$CFG_GLPI,$LANG;

		//PluginFusioninventoryProfile::checkRight("fusioninventory", "networking","r");

		if ($id!='') {
			$this->getFromDB($id);
      } else {
			$this->getEmpty();
      }

      $this->showTabs($options);
      $this->showFormHeader($options);

		$datestring = $LANG['common'][26].": ";
		$date = convDateTime($this->fields["date_mod"]);
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

      if (isMultiEntitiesMode()) {
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
		echo "<td align='center'></td>";
		echo "<td align='center'>";
		echo "</td>";
      echo "<td align='center'>" . $LANG['common'][18] . "&nbsp;:</td>";
      echo "</td>";
      echo "<td align='center'>";
		echo "<input type='text' name='contact' value='" . $this->fields["contact"] . "' size='35'/>";
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['common'][17] . "&nbsp;:</td>";
		echo "<td align='center'>";
         $type_list = array();
			$type_list[] = 'Computer';
			$type_list[] = 'NetworkEquipment';
			$type_list[] = 'Printer';
			$type_list[] = 'Peripheral';
			$type_list[] = 'Phone';
//         // GENERIC OBJECT : Search types in generic object
//         $plugin = new Plugin;
//         if ($plugin->isActivated('genericobject')) {
//            if (TableExists("glpi_plugin_genericobject_types")) {
//               $query = "SELECT * FROM `glpi_plugin_genericobject_types`
//                  WHERE `status`='1' ";
//               if ($result=$DB->query($query)) {
//                  while ($data=$DB->fetch_array($result)) {
//                     $type_list[] = $data['itemtype'];
//                  }
//               }
//            }
//         }
//         // END GENERIC OBJECT
      Dropdown::dropdownTypes('itemtype',$this->fields["itemtype"],$type_list);
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
		echo "<td align='center'>" . $LANG['common'][15] . "&nbsp;:</td>";
		echo "<td align='center'>";
      Dropdown::show("Location",
                     array('name'=>"location",
                           'value'=>$this->fields["location"]));
		echo "</td>";

      echo "<td align='center'>" . $LANG['common'][19] . "&nbsp;:</td>";
      echo "</td>";
      echo "<td align='center'>";
		echo "<input type='text' name='serial' value='" . $this->fields["serial"] . "' size='35'/>";
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']['unknown'][2] . " :</td>";
		echo "<td align='center'>";
      Dropdown::showYesNo("accepted", $this->fields["accepted"]);
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
         echo "<input type='text' name='otherserial' value='" . $this->fields["ip"] . "' size='35'/>";
         echo "</td>";

         echo "<td align='center'>" . $LANG['networking'][15] . "&nbsp;:</td>";
         echo "</td>";
         echo "<td align='center'>";
         echo "<input type='text' name='otherserial' value='" . $this->fields["mac"] . "' size='35'/>";
         echo "</td>";
         echo "</tr>";
      }
      
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'></td>";
		echo "<td align='center'>";
		echo "</td>";

      echo "<td align='center'>" . $LANG['common'][25] . " : </td>";
      echo "</td>";
      echo "<td align='middle'>";
      echo "<textarea  cols='50' rows='5' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']['unknown'][4] . " :</td>";
		echo "<td align='center'>";
      echo Dropdown::getYesNo($this->fields["hub"]);
		echo "</td>";

      echo "<td align='center' colspan='2'></td>";
		echo "</tr>";
      
      $this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
	}


   
   function importForm($target,$id) {
      global $LANG;
      
      echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";
		echo "<table  class='tab_cadre_fixe'>";
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>";
      $this->getFromDB($id);
      if ($this->fields["itemtype"] != '0') {
         echo "<input type='hidden' name='id' value=$id>";
         echo "<input type='submit' name='import' value=\"".$LANG['buttons'][37]."\" class='submit'>";
      }
      echo "</td>";
      echo "</table>";
      echo "</div>";
   }

   function CleanOrphelinsConnections() {
      global $DB;

      $query = "SELECT `glpi_networkports`.`id`
                FROM `glpi_networkports`
                     LEFT JOIN `glpi_plugin_fusioninventory_unknowndevices`
                               ON `items_id`=`glpi_plugin_fusioninventory_unknowndevices`.`id`
                     WHERE `itemtype`=".'PluginFusioninventoryUnknownDevice'."
                           AND `glpi_plugin_fusioninventory_unknowndevices`.`id` IS NULL;";
      $unknown_infos = array();
      if ($result=$DB->query($query)) {
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



	function FusionUnknownKnownDevice() {
		global $DB;

		$query = "SELECT *
                FROM `glpi_networkports`
                WHERE `mac` != ''
                      AND `mac` != '00:00:00:00:00:00'
                      AND `itemtype`=".'PluginFusioninventoryUnknownDevice'."
                GROUP BY `mac`
                HAVING COUNT(*)>0;";
		if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
				// $data = id of unknown device
				$query_known = "SELECT *
                            FROM `glpi_networkports`
                            WHERE `mac` IN ('".$data["mac"]."','".strtoupper($data["mac"])."',
                                              '".strtolower($data["mac"])."')
                                  AND `itemtype`!=".'PluginFusioninventoryUnknownDevice'."
                            LIMIT 0,1;";
				$result_known=$DB->query($query_known);
            if ($DB->numrows($result_known) > 0) {
               $data_known=$DB->fetch_array($result_known);

               $query_update = "UPDATE `glpi_networkports`
                                SET `items_id`='".$data_known["items_id"]."',
                                    `itemtype='".$data_known["itemtype"]."',
                                    `logical_number`='".$data_known["logical_number"]."',
                                    `name`='".$data_known["name"]."',
                                    `ip`='".$data_known["ip"]."',
                                    `networkinterfaces_id`='".$data_known["networkinterfaces_id"]."',
                                    `netpoints_id`='".$data_known["netpoints_id"]."',
                                    `netmask`='".$data_known["netmask"]."',
                                    `gateway`='".$data_known["gateway"]."',
                                    `subnet`='".$data_known["subnet"]."'
                                  WHERE `id`='".$data["id"]."';";
               $DB->query($query_update);

               // Delete old networking port
               $this->delete($data_known,1);

               // Delete unknown device
               $a_unknowndevice = $this->getFromDB($data["items_id"]);
               $this->delete($a_unknowndevice,1);

               // Modify OCS link of this networking port
               $query = "SELECT *
                         FROM `glpi_ocs_link`
                         WHERE `glpi_id`='".$data_known["items_id"]."';";
               $result = $DB->query($query);
               if ($DB->numrows($result) == 1) {
                  $line = $DB->fetch_assoc($result);

                  $import_ip = importArrayFromDB($line["import_ip"]);
                  $ip_port = $import_ip[$data_known["id"]];
                  unset($import_ip[$data_known["id"]]);
                  $import_ip[$data["id"]] = $ip_port;

                  $query_update = "UPDATE `glpi_ocs_link`
                                   SET `import_ip`='" . exportArrayToDB($import_ip) . "'
                                   WHERE `glpi_id`='".$line["id"]."';";
                  $DB->query($query_update);
               }
            }
			}
		}
	}

   function convertUnknownToUnknownNetwork($id) {
      global $DB;

      $np = new NetworkPort;

      $this->getFromDB($id);

      // Get port
      $a_ports = $np->find('items_id='.$id." AND itemtype='PluginFusioninventoryUnknownDevice'");

      if (count($a_ports) == '1') {
         // Put mac and ip to unknown
         $port = current($a_ports);
         $this->fields['ip'] = $port['ip'];
         $this->fields['mac'] = $port['mac'];

         $this->update($this->fields);
         $delete_port = $np->getFromDB($port['id']);
         $np->delete($delete_port, 1);
         return true;
      }
      return false;
   }


   
   function hubNetwork($p_oPort, $agent_id) {
      global $DB;

      $nn = new NetworkPort_NetworkPort();
      $Netport = new NetworkPort();
      //$PluginFusionInventoryAgentsProcesses = new PluginFusioninventoryAgentsProcesses;

      // Get port connected on switch port
      $hub_id = 0;
      if ($ID = $nn->getOppositeContact($p_oPort->getValue('id'))) {
         $Netport->getFromDB($ID);
         if ($Netport->fields["itemtype"] == $this->type) {
            $this->getFromDB($Netport->fields["items_id"]);
            if ($this->fields["hub"] == "1") {
               $this->releaseHub($this->fields['id'], $p_oPort);
               $hub_id = $this->fields['id'];
            } else {
//               plugin_fusioninventory_addLogConnection("remove",$ID);
               if ($nn->delete(array('id' => $ID))) {
//                  $PluginFusionInventoryAgentsProcesses->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
//                              array('query_nb_connections_deleted' => '1'));
               }
               $hub_id = $this->createHub($p_oPort, $agent_id);
            }
         } else {
//            plugin_fusioninventory_addLogConnection("remove",$ID);
            if ($nn->delete(array('id' => $ID))) {
//               $PluginFusionInventoryAgentsProcesses->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
//                           array('query_nb_connections_deleted' => '1'));
            }
            $hub_id = $this->createHub($p_oPort, $agent_id);
         }
      } else {
         $hub_id = $this->createHub($p_oPort, $agent_id);
      }


      // Get all ports connected to this hub
      $a_portglpi = array();
      $a_ports = $Netport->find("`items_id`='".$hub_id."'
          AND `itemtype`='".$this->type."'");
      foreach ($a_ports as $data) {
         if ($id = $nn->getOppositeContact($data['id'])) {
            $a_portglpi[$id] = $data['id'];
         }
      }

      $a_portUsed = array();
      $used_id = 0;
      foreach ($p_oPort->getMacsToConnect() as $ifmac) {
         $a_ports = $Netport->find("`mac`='".$ifmac."'");
         if (count($a_ports) == "1") {
            if ($used_id = $this->searchIfmacOnHub($a_ports, $a_portglpi)) {
            } else {
               // Connect port
               $used_id = $this->connectPortToHub($a_ports, $hub_id);
            }
         } else if (count($a_ports) == "0") {

            // Port don't exist
            // Create unknown device
            $input = array();
            $input['name'] = '';
               // get source entity :
//               $Netport->getDeviceData($p_oPort->getValue("items_id"),$p_oPort->getValue("itemtype"));
//               if (isset($Netport->entities_id)) {
//                  $input['entities_id'] = $Netport->entities_id;
//               }
            $unknown_id = $this->add($input);
            $input = array();
            $input["items_id"] = $unknown_id;
            $input["itemtype"] = $this->type;
            $input["mac"] = $ifmac;
            $id_port = $Netport->add($input);
            $a_portcreate = array();
            $Netport->getFromDB($id_port);
            $a_portcreate[$id_port] = $Netport->fields;
            $used_id = $this->connectPortToHub($a_portcreate, $hub_id);
         }
         $a_portUsed[$used_id] = 1;
      }
      $this->deleteNonUsedPortHub($hub_id, $a_portUsed);
   }



   function deleteNonUsedPortHub($hub_id, $a_portUsed) {

      $Netport = new NetworkPort();
      $nn = new NetworkPort_NetworkPort();

      $a_ports = $Netport->find("`items_id`='".$hub_id."'
          AND `itemtype`='".$this->type."'
          AND (`name` != 'Link' OR `name` IS NULL)");
      foreach ($a_ports as $data) {
         if (!isset($a_portUsed[$data['id']])) {
            //plugin_fusioninventory_addLogConnection("remove",$port_id);
            $nn->delete(array('id' => $data['id']));
            $Netport->deleteFromDB($data['id']);
         }
      }
   }



   function connectPortToHub($a_ports, $hub_id) {
      global $DB;

      $Netport = new NetworkPort();
      $nn = new NetworkPort_NetworkPort();

      foreach ($a_ports as $data) {
         //plugin_fusioninventory_addLogConnection("remove",$port_id);
         $nn->delete(array('id' => $data['id']));
         // Search free port
         $query = "SELECT `glpi_networkports`.`id` FROM `glpi_networkports`
            LEFT JOIN `glpi_networkports_networkports`
               ON `glpi_networkports`.`id` = `networkports_id_1` OR `glpi_networkports`.`id` = `networkports_id_2`
            WHERE `itemtype`='".$this->type."'
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
            $input["itemtype"] = $this->type;
            $freeport_id = $Netport->add($input);
         }
         $nn->add(array('networkports_id_1'=> $data['id'], 'networkports_id_2' => $freeport_id));

         //plugin_fusioninventory_addLogConnection("make",$port_id);
         return $freeport_id;
      }
   }



   function searchIfmacOnHub($a_ports, $a_portglpi) {

      foreach ($a_ports as $data) {
         if (isset($a_portglpi[$data['id']])) {
            return $a_portglpi[$data['id']];
         }
      }
      return false;
   }



   function createHub($p_oPort, $agent_id) {
      global $DB;

      $Netport = new NetworkPort();
      $nn = new NetworkPort_NetworkPort();
      //$PluginFusionInventoryAgentsProcesses = new PluginFusionInventoryAgentsProcesses;

      // Find in the mac connected to the if they are in hub without link port connected
      foreach ($p_oPort->getMacsToConnect() as $ifmac) {
         $a_ports = $Netport->find("`mac`='".$ifmac."'");
         foreach ($a_ports as $data) {
            if ($ID = $nn->getOppositeContact($p_oPort->getValue('id'))) {
               $Netport->getFromDB($ID);
               if ($Netport->fields["itemtype"] == $this->type) {
                  if ($this->fields["hub"] == "1") {
                     $a_portLink = $Netport->find("`name`='Link'
                        AND `items_id`='".$this->fields['id']."'
                        AND `itemtype`='".$this->type."'");
                     foreach ($a_portLink as $dataLink) {
                        if ($nn->getOppositeContact($dataLink['id'])) {

                        } else {
                           // We have founded a hub orphelin
                           if ($nn->add(array('networkports_id_1'=> $p_oPort->getValue('id'), 'networkports_id_2' => portLink_id))) {
//                              $PluginFusionInventoryAgentsProcesses->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
//                                          array('query_nb_connections_created' => '1'));
//                              plugin_fusioninventory_addLogConnection("make",$p_oPort->getValue('ID'));
                           }
                           releaseHub($this->fields['id'], $p_oPort);
                           return $this->fields['id'];
                        }
                     }
                  }
               }
            }
         }
      }
      // Not founded, creation hub and link port
      $input = array();
      $input['hub'] = "1";
      $input['name'] = "hub";
//      $input["plugin_fusioninventory_agents_id"] = $agent_id;
         // get source entity :
//         $datas = $Netport->getDeviceData($p_oPort->getValue("items_id"),$p_oPort->getValue("itemtype"));
//         if (isset($Netport->entities_id)) {
//            $input['entities_id'] = $Netport->entities_id;
//         }
      $hub_id = $this->add($input);

      $input = array();
      $input["items_id"] = $hub_id;
      $input["itemtype"] = $this->type;
      $input["name"] = "Link";
      $port_id = $Netport->add($input);
      if ($nn->add(array('networkports_id_1'=> $p_oPort->getValue('id'), 'networkports_id_2' => $port_id))) {
//         $PluginFusionInventoryAgentsProcesses->updateProcess($_SESSION['glpi_plugin_fusioninventory_processnumber'],
//                     array('query_nb_connections_created' => '1'));
//         plugin_fusioninventory_addLogConnection("make",$p_oPort->getValue('ID'));
      }
      return $hub_id;
   }



   function releaseHub($hub_id, $p_oPort) {

      $Netport = new NetworkPort();
      $nn = new NetworkPort_NetworkPort();

      $a_macOnSwitch = array();
      foreach ($p_oPort->getMacsToConnect() as $ifmac) {
         $a_macOnSwitch["$ifmac"] = 1;
      }

      // get all ports of hub
      $releasePorts = array();
      $a_ports = $Netport->find("`items_id`='".$hub_id."' AND `itemtype`='".$this->type."' AND (`name` != 'Link' OR `name` IS NULL)");
      foreach ($a_ports as $port_id=>$data) {
         if ($id = $nn->getOppositeContact($port_id)) {
            $Netport->getFromDB($id);
            if (!isset($a_macOnSwitch[$Netport->fields["mac"]])) {
               $releasePorts[$port_id] = 1;
            }
         }
      }
      foreach ($releasePorts as $port_id=>$data) {
         //plugin_fusioninventory_addLogConnection("remove",$port_id);
         $nn->delete(array('id' => $port_id));
      }
   }





   function cleanUnknownSwitch() {
      global $DB;

      $nn = new NetworkPort_NetworkPort();

      $query = "SELECT `glpi_plugin_fusioninventory_unknowndevices`.* FROM `glpi_plugin_fusioninventory_unknowndevices`
         INNER JOIN `glpi_plugin_fusinvsnmp_networkequipmentips` ON `glpi_plugin_fusioninventory_unknowndevices`.`ip` = `glpi_plugin_fusinvsnmp_networkequipmentips`.`ip`
         WHERE `glpi_plugin_fusioninventory_unknowndevices`.`ip` IS NOT NULL
            AND `glpi_plugin_fusioninventory_unknowndevices`.`ip` != '' ";
      if ($result=$DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            $query_port = "SELECT * FROM `glpi_networkports`
               WHERE items_id='".$data['id']."'
                  AND itemtype='".$this->type."' ";
            if ($result_port=$DB->query($query_port)) {
               while ($data_port=$DB->fetch_array($result_port)) {
                  //plugin_fusioninventory_addLogConnection("remove",$data_port['ID']);
                  $nn->delete(array('id' => $data_port['id']));
                  $np = new NetworkPort();
                  $np->deleteFromDB($data_port['id']);
               }
            }
            $this->deleteFromDB($data['id']);
         }
      }
   }

   function writeXML($items_id, $xml) {

      $folder = substr($items_id,0,-1);
      if (empty($folder)) {
         $folder = '0';
      }
      if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/u".$folder)) {
         mkdir(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/u".$folder);
      }
      $fileopen = fopen(GLPI_PLUGIN_DOC_DIR."/fusioninventory/xml/u".$folder."/u".$items_id, 'w');
      fwrite($fileopen, $xml);
      fclose($fileopen);


   }

}

?>