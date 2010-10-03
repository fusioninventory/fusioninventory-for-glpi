<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

	This file is part of GLPI.

    GLPI is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    GLPI is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GLPI; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
*/

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginFusioninventoryUnknownDevice extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_fusioninventory_unknowndevices";
      $this->type = "PluginFusioninventoryUnknownDevice";
      $this->dohistory=true;
	}

   function defineTabs($options=array()){
		global $LANG,$CFG_GLPI;


      $ong = array();
		if ($this->fields['id'] > 0){
         $ong[1]=$LANG['title'][27];
         $ong[2]=$LANG['buttons'][37];
         $ptc = new PluginFusioninventoryConfig;
         if (($ptc->is_active('fusioninventory', 'remotehttpagent')) AND(PluginFusioninventoryProfile::haveRight("fusioninventory", "remotecontrol","w"))) {
            $ong[3]=$LANG['plugin_fusioninventory']["task"][2];
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

		$datestring = $LANG["common"][26].": ";
		$date = convDateTime($this->fields["date_mod"]);
		echo "<tr>";
		echo "<th align='center' width='450' colspan='2'>";
		echo $LANG["common"][2]." ".$this->fields["id"];
		echo "</th>";
	
		echo "<th align='center' colspan='2' width='50'>";
		echo $datestring.$date;
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG["common"][16] . " :</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' value='" . $this->fields["name"] . "' size='35'/>";
		echo "</td>";

      if (isMultiEntitiesMode()) {
         echo "<td align='center'>" . $LANG['entity'][0] . " : </td>";
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

      echo "<td align='center'>" . $LANG['common'][18] . " : </td>";
      echo "</td>";
      echo "<td align='center'>";
		echo "<input type='text' name='contact' value='" . $this->fields["contact"] . "' size='35'/>";
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['common'][17] . " :</td>";
		echo "<td align='center'>";
			$type_list[] = COMPUTER_TYPE;
			$type_list[] = NETWORKING_TYPE;
			$type_list[] = PRINTER_TYPE;
			$type_list[] = PERIPHERAL_TYPE;
			$type_list[] = PHONE_TYPE;

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
//			Device::dropdownTypes('type',$this->fields["type"],$type_list);
		echo "</td>";

      echo "<td align='center'>" . $LANG['setup'][89] . " : </td>";
      echo "</td>";
      echo "<td align='center'>";
      Dropdown::show("Domain",
                     array('name'=>"domain",
                           'value'=>$this->fields["domain"]));
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['common'][15] . " :</td>";
		echo "<td align='center'>";
      Dropdown::show("Location",
                     array('name'=>"location",
                           'value'=>$this->fields["location"]));
		echo "</td>";

      echo "<td align='center'>" . $LANG['common'][19] . " : </td>";
      echo "</td>";
      echo "<td align='center'>";
		echo "<input type='text' name='serial' value='" . $this->fields["serial"] . "' size='35'/>";
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["unknown"][2] . " :</td>";
		echo "<td align='center'>";
      Dropdown::showYesNo("accepted", $this->fields["accepted"]);
		echo "</td>";

      echo "<td align='center'>" . $LANG['common'][20] . " : </td>";
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

         echo "<td align='center'>" . $LANG['networking'][15] . " : </td>";
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
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["unknown"][4] . " :</td>";
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
      if ($this->fields["type"] != '0') {
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


   
   function hubNetwork($p_oPort) {
      global $DB;

      $nw = new NetworkPort_NetworkPort;
      $np = new NetworkPort;
      $nn = new NetworkPort_NetworkPort();
      // List of macs : $p_oPort->getPortsToConnect

      // recherche dans unknown_device table le hub
      // qui a un port connecte sur le $port_ID

         // Get port connected on switch port
         if ($id = $nw->getOppositeContact($p_oPort->getValue('id'))) {
            $np->getFromDB($id);
            if ($np->fields["itemtype"] == $this->type) {
               $this->getFromDB($np->fields["items_id"]);
               if ($this->fields["hub"] == "1") {
                  // We will update ports and wire


                  return;
               }
            }
         }

      // sinon on cree un nouveau unknown_device type hub
      // + creation des ports qui sont connectes aux mac
      $input = array();
      $input['hub'] = "1";
      $input['name'] = "hub";
         // get source entity :
         $itemtype = $p_oPort->getValue("itemtype");
         $device = new $itemtype();
         if ($device->getFromDB($p_oPort->getValue("items_id"))) {
            $input['FK_entities'] = $device->getEntityID();
         }
      $id_unknown = $this->add($input);

      $input = array();
      $input["items_id"] = $id_unknown;
      $input["itemtype"] = $this->type;
      $input["name"] = "Link";
      $id_port = $np->add($input);
      $nn->add(array('networkports_id_source'=> $p_oPort->getValue('id'),
                     'networkports_id_destination' => $id_port));

      foreach ($p_oPort->getMacsToConnect() as $mac) {
         $input = array();
         $input["items_id"] = $id_unknown;
         $input["itemtype"] = $this->type;
         $id_port = $np->add($input);

         // TODO : recherche le port qui a cet $mac
         $query = "SELECT * FROM `glpi_networkports`
            WHERE `mac` = '".$mac."' ";
         $result = $DB->query($query);
         if ($DB->numrows($result) == 1) {
            $line = $DB->fetch_assoc($result);
            $nn->add(array('networkports_id_source'=> $line['id'],
                           'networkports_id_destination' => $id_port));
         } else {
            // Create device inconnu

            
         }
      }
   }
}

?>
