<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

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

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}


function plugin_fusioninventory_snmp_addLog($port,$field,$old_value,$new_value,$mapping,$FK_process=0) {
	global $DB,$CFG_GLPI;

	$history = new PluginFusionInventorySNMPHistory;
   $doHistory = 1;
   if ($mapping != "") {
      $query = "SELECT *
                FROM `glpi_plugin_fusioninventory_config_snmp_history`
                WHERE `field`='".$mapping."';";
      $result = $DB->query($query);
      if ($DB->numrows($result) == 0) {
         $doHistory = 0;
      }
   }

   if ($doHistory == "1") {

      $array["FK_ports"] = $port;
      $array["field"] = $field;
      $array["old_value"] = $old_value;
      $array["new_value"] = $new_value;

      // Ajouter en DB
      $history->insert_connection("field",$array,$FK_process);
   }
}



function plugin_fusioninventory_networking_ports_addLog($port_id, $new_value, $field) {
   include (GLPI_ROOT . "/plugins/fusioninventory/inc_constants/plugin_fusioninventory.snmp.mapping.constant.php");
   $ptp = new PluginFusionInventoryPort;
   $ptsnmph = new PluginFusionInventorySNMPHistory;

   $db_field = $field;
   switch ($field) {
      case 'ifname':
         $db_field = 'name';
         $field = 'ifName';
         break;

      case 'mac':
         $db_field = 'ifmac'; 
         $field = 'macaddr';
         break;

      case 'ifnumber':
         $db_field = 'logical_number';
         $field = 'ifIndex';
         break;

      case 'trunk':
         $field = 'vlanTrunkPortDynamicStatus';
         break;

      case 'iftype':
         $field = 'ifType';
         break;

      case 'duplex':
         $field = 'portDuplex';
         break;
      
   }

   $ptp->load($port_id);
   echo $ptp->getValue($db_field);
   if ($ptp->getValue($db_field) != $new_value) {
      $array["FK_ports"] = $port_id;
      $array["field"] = $field;
      $array["old_value"] = $ptp->getValue($db_field);
      $array["new_value"] = $new_value;
      $ptsnmph->insert_connection("field",$array,$_SESSION['glpi_plugin_fusioninventory_processnumber']);
   }
}



// $status = connection or disconnection	
function plugin_fusioninventory_addLogConnection($status,$port,$FK_process=0) {
	global $DB,$CFG_GLPI;

	$CommonItem = new CommonItem;
	$pthc = new PluginFusionInventoryHistoryConnections;
	$nw=new Netwire;

   if (($FK_process == '0') AND (isset($_SESSION['glpi_plugin_fusioninventory_processnumber']))) {
      $input['process_number'] = $_SESSION['glpi_plugin_fusioninventory_processnumber'];
   }

	// Récupérer le port de la machine associé au port du switch

	// Récupérer le type de matériel
	$input["FK_port_source"] = $port;
	$opposite_port = $nw->getOppositeContact($port);
	if ($opposite_port == "0") {
		return;
   }
   $input['FK_port_destination'] = $opposite_port;

   $input['date'] = date("Y-m-d H:i:s");

   if ($status == 'remove') {
      $input['creation'] = 0;
   } else if ($status == 'make') {
      $input['creation'] = 1;
   }

   $pthc->add($input);
}



// List of history in networking display
function plugin_fusioninventory_snmp_showHistory($ID_port) {
	global $DB,$LANG,$INFOFORM_PAGES,$CFG_GLPI;

   include (GLPI_ROOT . "/plugins/fusioninventory/inc_constants/plugin_fusioninventory.snmp.mapping.constant.php");

	$CommonItem = new CommonItem;
   $np = new Netport;

   $query = "
      SELECT * FROM(
         SELECT * FROM (
            SELECT date as date, process_number as process_number,
            FK_port_source, FK_port_destination,
            creation as Field, NULL as old_value, NULL as new_value

            FROM glpi_plugin_fusioninventory_snmp_history_connections
            WHERE `FK_port_source`='".$ID_port."'
               OR `FK_port_destination`='".$ID_port."'
            ORDER BY date DESC
            LIMIT 0,30
            )
         AS DerivedTable1
         UNION ALL
         SELECT * FROM (
            SELECT date_mod as date, FK_process as process_number,
            FK_ports AS FK_port_source, NULL as FK_port_destination,
            Field, old_value, new_value

            FROM glpi_plugin_fusioninventory_snmp_history
            WHERE `FK_ports`='".$ID_port."'
            ORDER BY date DESC
            LIMIT 0,30
            )
         AS DerivedTable2)
      AS MainTable
      ORDER BY date DESC
      LIMIT 0,30";
//echo $query."<br/>";
	$text = "<table class='tab_cadre' cellpadding='5' width='950'>";

	$text .= "<tr class='tab_bg_1'>";
	$text .= "<th colspan='8'>";
	$text .= "Historique";
	$text .= "</th>";
	$text .= "</tr>";

	$text .= "<tr class='tab_bg_1'>";
	$text .= "<th>".$LANG['plugin_fusioninventory']["snmp"][50]."</th>";
	$text .= "<th>".$LANG["common"][1]."</th>";
	$text .= "<th>".$LANG["event"][18]."</th>";
	$text .= "<th></th>";
	$text .= "<th></th>";
	$text .= "<th></th>";
	$text .= "<th>".$LANG["common"][27]."</th>";
	$text .= "</tr>";

   if ($result=$DB->query($query)) {
		while ($data=$DB->fetch_array($result)) {
			$text .= "<tr class='tab_bg_1'>";
			if (!empty($data["FK_port_destination"])) {
				// Connections and disconnections
            if ($data['Field'] == '1') {
               $text .= "<td align='center'><img src='".GLPI_ROOT."/plugins/fusioninventory/pics/connection_ok.png'/></td>";
            } else {
               $text .= "<td align='center'><img src='".GLPI_ROOT."/plugins/fusioninventory/pics/connection_notok.png'/></td>";
            }
				if ($ID_port == $data["FK_port_source"]) {
               $np->getFromDB($data["FK_port_destination"]);
               if (isset($np->fields["on_device"])) {
                  $CommonItem->getFromDB($np->fields["device_type"],
                                         $np->fields["on_device"]);
                  $link1 = $CommonItem->getLink(1);
                  $link = "<a href=\"" . $CFG_GLPI["root_doc"] . "/front/networking.port.php?ID=" . $np->fields["ID"] . "\">";
                  if (rtrim($np->fields["name"]) != "")
                     $link .= $np->fields["name"];
                  else
                     $link .= $LANG['common'][0];
                  $link .= "</a>";
                  $text .= "<td align='center'>".$link." ".$LANG['networking'][25]." ".$link1."</td>";
               } else {
                  $text .= "<td align='center'><font color='#ff0000'>".$LANG['common'][28]."</font></td>";
               }

				} else if ($ID_port == $data["FK_port_destination"]) {
               $np->getFromDB($data["FK_port_source"]);
               if (isset($np->fields["on_device"])) {
                  $CommonItem->getFromDB($np->fields["device_type"],
                                         $np->fields["on_device"]);
                  $link1 = $CommonItem->getLink(1);
                  $link = "<a href=\"" . $CFG_GLPI["root_doc"] . "/front/networking.port.php?ID=" . $np->fields["ID"] . "\">";
                  if (rtrim($np->fields["name"]) != "")
                     $link .= $np->fields["name"];
                  else
                     $link .= $LANG['common'][0];
                  $link .= "</a>";
                  $text .= "<td align='center'>".$link." ".$LANG['networking'][25]." ".$link1."</td>";
               } else {
                  $text .= "<td align='center'><font color='#ff0000'>".$LANG['common'][28]."</font></td>";
               }
				}
				$text .= "<td align='center' colspan='4'></td>";
				$text .= "<td align='center'>".convDateTime($data["date"])."</td>";

			} else {
				// Changes values
				$text .= "<td align='center' colspan='2'></td>";
				$text .= "<td align='center'>".$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE][$data["Field"]]['name']."</td>";
				$text .= "<td align='center'>".$data["old_value"]."</td>";
				$text .= "<td align='center'>-></td>";
				$text .= "<td align='center'>".$data["new_value"]."</td>";
				$text .= "<td align='center'>".convDateTime($data["date"])."</td>";
			}
			$text .= "</tr>";
		}

	}








/*
   $pthc = new PluginFusionInventoryHistoryConnections;

   $data_connections = $pthc->find('`FK_port_source`="'.$ID_port.'"
                                       OR `FK_port_destination `="'.$ID_port.'"',
                                    '`date` DESC',
                                    '0,30');
	$query = "SELECT *
             FROM `glpi_plugin_fusioninventory_snmp_history`
             WHERE `FK_ports`='".$ID_port."'
             ORDER BY `date_mod` DESC
             LIMIT 0,30;";


	$text = "<table class='tab_cadre' cellpadding='5' width='950'>";

	$text .= "<tr class='tab_bg_1'>";
	$text .= "<th colspan='8'>";
	$text .= "Historique";
	$text .= "</th>";
	$text .= "</tr>";
	
	$text .= "<tr class='tab_bg_1'>";
	$text .= "<th>".$LANG['plugin_fusioninventory']["snmp"][50]."</th>";
	$text .= "<th>".$LANG["common"][1]."</th>";
	$text .= "<th>".$LANG["networking"][15]."</th>";
	$text .= "<th>".$LANG["event"][18]."</th>";
	$text .= "<th></th>";
	$text .= "<th></th>";
	$text .= "<th></th>";
	$text .= "<th>".$LANG["common"][27]."</th>";
	$text .= "</tr>";
	
	if ($result=$DB->query($query)) {
		while ($data=$DB->fetch_array($result)) {
			$text .= "<tr class='tab_bg_1'>";
			
			if (($data["old_device_ID"] != "0") OR ($data["new_device_ID"] != "0")) {
				// Connections and disconnections
				if ($data["old_device_ID"] != "0") {
					$text .= "<td align='center'>".$LANG['plugin_fusioninventory']["history"][2]."</td>";
					$CommonItem->getFromDB($data["old_device_type"],$data["old_device_ID"]);
					$text .= "<td align='center'>".$CommonItem->getLink(1)."</td>";						
					$text .= "<td align='center'>".$data["old_value"]."</td>";
				} else if ($data["new_device_ID"] != "0") {
					$text .= "<td align='center'>".$LANG['plugin_fusioninventory']["history"][3]."</td>";
					$CommonItem->getFromDB($data["new_device_type"],$data["new_device_ID"]);
					$text .= "<td align='center'>".$CommonItem->getLink(1)."</td>";
					$text .= "<td align='center'>".$data["new_value"]."</td>";
				}
				$text .= "<td align='center' colspan='4'></td>";
				$text .= "<td align='center'>".convDateTime($data["date_mod"])."</td>";

			} else if (($data["old_device_ID"] == "0") AND ($data["new_device_ID"] == "0") AND ($data["Field"] == "0")) {
				// Unknown Mac address
				if (!empty($data["old_value"])) {
					$text .= "<td align='center' background='#cf9b9b' class='tab_bg_1_2'>".$LANG['plugin_fusioninventory']["history"][2]."</td>";
					$CommonItem->getFromDB($data["old_device_type"],$data["old_device_ID"]);
					$text .= "<td align='center' background='#cf9b9b' class='tab_bg_1_2'>".$CommonItem->getLink(1)."</td>";
					$text .= "<td align='center' background='#cf9b9b' class='tab_bg_1_2'>".$data["old_value"]."</td>";
				} else if (!empty($data["new_value"])) {
					$text .= "<td align='center' background='#cf9b9b' class='tab_bg_1_2'>".$LANG['plugin_fusioninventory']["history"][3]."</td>";
					$CommonItem->getFromDB($data["new_device_type"],$data["new_device_ID"]);
					$text .= "<td align='center' background='#cf9b9b' class='tab_bg_1_2'>".$CommonItem->getLink(1)."</td>";
					$text .= "<td align='center' background='#cf9b9b' class='tab_bg_1_2'>".$data["new_value"]."</td>";
				}
				$text .= "<td align='center' colspan='4' background='#cf9b9b' class='tab_bg_1_2'></td>";
				$text .= "<td align='center' background='#cf9b9b' class='tab_bg_1_2'>".convDateTime($data["date_mod"])."</td>";
			} else {
				// Changes values
				$text .= "<td align='center' colspan='3'></td>";
				$text .= "<td align='center'>".$data["Field"]."</td>";
				$text .= "<td align='center'>".$data["old_value"]."</td>";
				$text .= "<td align='center'>-></td>";
				$text .= "<td align='center'>".$data["new_value"]."</td>";
				$text .= "<td align='center'>".convDateTime($data["date_mod"])."</td>";
			}
			$text .= "</tr>";
		}
	}

 */
	$text .= "<tr class='tab_bg_1'>";
	$text .= "<th colspan='8'>";
	$text .= "<a href='".GLPI_ROOT."/plugins/fusioninventory/report/plugin_fusioninventory.switch_ports.history.php?FK_networking_ports=".$ID_port."'>Voir l'historique complet</a>";
	$text .= "</th>";
	$text .= "</tr>";
	$text .= "</table>";
	return $text;
}

?>