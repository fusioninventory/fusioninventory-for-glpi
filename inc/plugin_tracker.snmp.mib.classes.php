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

class plugin_tracker_mib_networking extends CommonDBTM {
	function __construct() {
		$this->table="glpi_plugin_tracker_mib_networking";
		$this->type = -1;
	}



	function showForm($target,$ID) {
		include (GLPI_ROOT . "/plugins/tracker/inc/plugin_tracker.snmp.mapping.constant.php");

		GLOBAL $DB,$CFG_GLPI,$LANG,$TRACKER_MAPPING,$IMPORT_TYPES;
		
		if (!plugin_tracker_haveRight("snmp_models","r")) {
			return false;
      } else if ((isset($ID)) AND (!empty($ID))) {
			$query = "SELECT device_type FROM glpi_plugin_tracker_model_infos
			WHERE ID='".$ID."' ";
			$result = $DB->query($query);		
			$data = $DB->fetch_assoc($result);
			$type_model = $data['device_type'];		
		
			$query = "SELECT glpi_plugin_tracker_model_infos.device_type,glpi_plugin_tracker_mib_networking.* FROM glpi_plugin_tracker_mib_networking
			LEFT JOIN glpi_plugin_tracker_model_infos ON glpi_plugin_tracker_mib_networking.FK_model_infos=glpi_plugin_tracker_model_infos.ID
			WHERE glpi_plugin_tracker_model_infos.ID=".$ID;
			
			if ($result = $DB->query($query)) {
				$object_used = array();
				$linkoid_used = array();
				
				echo "<br>";
				echo "<div align='center'><form method='post' name='odi_list' id='oid_list'  action=\"".$target."\">";
		
				//echo "<table class='tab_cadre' cellpadding='5' width='800'><tr><th colspan='7'>";
				$nb_col = 8;
				if ($data['device_type'] == NETWORKING_TYPE) {
					$nb_col++;
            }
				echo "<table class='tab_cadre_fixe'><tr><th colspan='".$nb_col."'>";
				echo $LANG['plugin_tracker']["mib"][5]."</th></tr>";
				
				echo "<tr class='tab_bg_1'>";
				echo "<th align='center'></th>";
				echo "<th align='center'>".$LANG['plugin_tracker']["mib"][1]."</th>";
				echo "<th align='center'>".$LANG['plugin_tracker']["mib"][2]."</th>";
				echo "<th align='center'>".$LANG['plugin_tracker']["mib"][3]."</th>";
				echo "<th align='center'>".$LANG['plugin_tracker']["mib"][6]."</th>";
				echo "<th align='center'>".$LANG['plugin_tracker']["mib"][7]."</th>";
				echo "<th align='center' width='250'>".$LANG['plugin_tracker']["mib"][8]."</th>";
				if ($data['device_type'] == NETWORKING_TYPE) {
					echo "<th align='center'>".$LANG['plugin_tracker']["mib"][9]."</th>";
            }
				echo "<th align='center'>".$LANG['plugin_tracker']["model_info"][11]."</th>";
				
				echo "</tr>";
				while ($data=$DB->fetch_array($result)) {
					if ($data["activation"] == "0") {
						echo "<tr class='tab_bg_1' style='color: grey; '>";
               } else {
						echo "<tr class='tab_bg_1'>";
               }
					echo "<td align='center'>";
					echo "<input name='item_coche[]' value='".$data["ID"]."' type='checkbox'>";
					echo "</td>";
	
					echo "<td align='center'>";
					echo getDropdownName("glpi_dropdown_plugin_tracker_mib_label",$data["FK_mib_label"]);
					echo "</td>";
					
					echo "<td align='center'>";
					$object_used[] = $data["FK_mib_object"];
					echo getDropdownName("glpi_dropdown_plugin_tracker_mib_object",$data["FK_mib_object"]);
					echo "</td>";
					
					echo "<td align='center'>";
					echo getDropdownName("glpi_dropdown_plugin_tracker_mib_oid",$data["FK_mib_oid"]);
					echo "</td>";
					
					echo "<td align='center'>";
					if ($data["oid_port_counter"] == "1") {
						if ($data["activation"] == "1") {
							echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
                  } else if ($data["activation"] == "0") {
							echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/tracker/pics/bookmark_off.png'/>";
                  }
               }
					echo "</td>";
					
					echo "<td align='center'>";
					if ($data["oid_port_dyn"] == "1") {
						if ($data["activation"] == "1") {
							echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
                  } else if ($data["activation"] == "0") {
							echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/tracker/pics/bookmark_off.png'/>";
                  }
					echo "</td>";
					
					echo "<td align='center'>";
					if (isset($TRACKER_MAPPING[$data['mapping_type']][$data["mapping_name"]]['name'])) {
						echo $TRACKER_MAPPING[$data['mapping_type']][$data["mapping_name"]]['name'];
						$linkoid_used[$data['mapping_type']."||".$data["mapping_name"]] = 1;
					}
					echo "</td>";
					
					if ($data['device_type'] == NETWORKING_TYPE) {
						echo "<td align='center'>";
						if ($data["vlan"] == "1") {
							if ($data["activation"] == "1") {
								echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
                     } else if ($data["activation"] == "0") {
								echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/tracker/pics/bookmark_off.png'/>";
                     }
						echo "</td>";
					}
					
					echo "<td align='center'>";
					echo "<a href='".$target."?ID=".$ID."&activation=".$data["ID"]."'>";
					if ($data["activation"] == "1") {
						echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
               } else if ($data["activation"] == "0") {
						echo "<img src='".$CFG_GLPI["root_doc"]."/plugins/tracker/pics/bookmark_off.png'/>";
               }
					echo "</a>";
					echo "</td>";
					
					echo "</tr>";
				}
				echo "</table>";
				
				echo "<div align='center'>";
				echo "<table class='tab_cadre_fixe'>";
				echo "<tr>"; 
				echo "<td><img src=\"".$CFG_GLPI["root_doc"]."/pics/arrow-left.png\" alt=''></td><td align='center'><a onclick= \"if ( markAllRows('oid_list') ) return false;\" href='".$_SERVER['PHP_SELF']."?check=all'>".$LANG["buttons"][18]."</a></td>";
				echo "<td>/</td><td align='center'><a onclick= \"if ( unMarkAllRows('oid_list') ) return false;\" href='".$_SERVER['PHP_SELF']."?check=none'>".$LANG["buttons"][19]."</a>";
				echo "</td><td align='left' colspan='6' width='80%'>"; 
				echo "<input class='submit' type='submit' name='delete_oid' value='" . $LANG["buttons"][6] . "'>";
				echo "</td>";
				echo "</tr>";
				echo "</table></div>";


				// ********** Ajout d'un tableau pour ajouter nouveau OID ********** //
				echo "<br/>";
				echo "<table class='tab_cadre_fixe'>";
				
				echo "<tr class='tab_bg_1'><th colspan='7'>".$LANG['plugin_tracker']["mib"][4]."</th></tr>";

				echo "<tr class='tab_bg_1'>";
				echo "<th align='center'>".$LANG['plugin_tracker']["mib"][1]."</th>";
				echo "<th align='center'>".$LANG['plugin_tracker']["mib"][2]."</th>";
				echo "<th align='center'>".$LANG['plugin_tracker']["mib"][3]."</th>";
				echo "<th align='center'>".$LANG['plugin_tracker']["mib"][6]."</th>";
				echo "<th align='center'>".$LANG['plugin_tracker']["mib"][7]."</th>";
				echo "<th align='center' width='250'>".$LANG['plugin_tracker']["mib"][8]."</th>";
				if ($type_model == NETWORKING_TYPE) {
					echo "<th align='center'>".$LANG["networking"][56]."</th>";
            }
				echo "</tr>";

				echo "<td align='center'>";
				dropdownValue("glpi_dropdown_plugin_tracker_mib_label","FK_mib_label",0,1);
				echo "</td>";
				
				echo "<td align='center'>";
				dropdownValue("glpi_dropdown_plugin_tracker_mib_object","FK_mib_object",0,1,-1,'');
				echo "</td>";

				echo "<td align='center'>";
				dropdownValue("glpi_dropdown_plugin_tracker_mib_oid","FK_mib_oid",0,1);
				echo "</td>";
				
				echo "<td align='center'>";
				//echo "<input name='oid_port_counter' value='0' type='checkbox'>";
				dropdownYesNo("oid_port_counter");	
				echo "</td>";
				
				echo "<td align='center'>";
				//echo "<input name='oid_port_dyn' value='0' type='checkbox'>";
				dropdownYesNo("oid_port_dyn");
				echo "</td>";
				
				echo "<td align='center'>";
				//echo "<select name='links_oid_fields' size='1'>";
				$types = array();
				$types[] = "-----";
				foreach ($TRACKER_MAPPING as $type=>$mapping43) {
					if (($type_model == $type) OR ($type_model == "0")) {
						if (isset($TRACKER_MAPPING[$type])) {
							foreach ($TRACKER_MAPPING[$type] as $name=>$mapping) {
								$types[$type."||".$name]=$TRACKER_MAPPING[$type][$name]["name"];
							}
						}
					}
				}

				dropdownArrayValues("links_oid_fields",$types,'',$linkoid_used);

				echo "</td>";

				if ($type_model == NETWORKING_TYPE) {
					echo "<td align='center'>";
					dropdownYesNo("vlan");
					echo "</td>";
				}
				
				echo "</tr>";
				
				echo "<tr class='tab_bg_1'><td colspan='7' align='center'>";
				echo "<input type='hidden' name='FK_model_infos' value='".$ID."'/>";
				echo "<input type='submit' name='add_oid' value=\"".$LANG["buttons"][2]."\" class='submit' >";
				echo "</td></tr>";	
				
				echo "</table></form></div>";
			}		
		}
	}

	function prepareInputForUpdate($input) {
		$explode = explode("||",$input["links_oid_fields"]);
		$input["mapping_type"] = $explode[0];
		$input["mapping_name"] = $explode[1];
		return $input;
	}

	function prepareInputForAdd($input) {
		$explode = explode("||",$input["links_oid_fields"]);
		$input["mapping_type"] = $explode[0];
		$input["mapping_name"] = $explode[1];
		return $input;
	}

	

	
	function deleteMib($item_coche) {
		global $DB;
		
		plugin_tracker_checkRight("snmp_models","w");
		
		for ($i = 0; $i < count($item_coche); $i++) {
			$query = "DELETE FROM glpi_plugin_tracker_mib_networking WHERE id=".$item_coche[$i]." ";
			$DB->query($query);
		}
	}



	function activation($ID) {
		global $DB;
		
		$mib_networking = new plugin_tracker_mib_networking();
		
		$mib_networking->getFromDB($ID);
		$data['ID'] = $ID;
		$data = $mib_networking->fields;
		if ($mib_networking->fields["activation"] == "1") {
			$data['activation'] = 0;
      } else {
			$data['activation'] = 1;
      }
		$data["links_oid_fields"]=$data["mapping_type"]."||".$data["mapping_name"];
		$mib_networking->update($data);
	}

}

?>