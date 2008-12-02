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

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
}



class plugin_tracker_mib_networking extends CommonDBTM
{
	function __construct()
	{
		$this->table="glpi_plugin_tracker_mib_networking";
	}
	/*
	function showAddMIB()
	{
		GLOBAL $CFG_GLPI,$LANGTRACKER;
		echo "<br>";
		echo "<div align='center'>";
		echo "<table class='tab_cadre' cellpadding='5' width='800'><tr><td class='tab_bg_2' align='center'>";
		echo "<a href=''><b>".$LANGTRACKER["mib"][4]."</b></a></td></tr>";
		echo "</table></div>";
	
	}*/

	function showForm($target,$ID)
	{
		GLOBAL $DB,$CFG_GLPI,$LANG,$LANGTRACKER,$TRACKER_MAPPING,$IMPORT_TYPES;
		
		if ( !plugin_tracker_haveRight("errors","r") )
		{
			return false;
		}
		else
		{
			$query = "SELECT glpi_plugin_tracker_mib_networking.* FROM glpi_plugin_tracker_mib_networking
			LEFT JOIN glpi_plugin_tracker_model_infos ON glpi_plugin_tracker_mib_networking.FK_model_infos=glpi_plugin_tracker_model_infos.ID
			WHERE glpi_plugin_tracker_model_infos.ID=".$ID;
			
			if ($result = $DB->query($query))
			{
				echo "<br>";
				echo "<div align='center'><form method='post' name='odi_list' id='oid_list'  action=\"".$target."\">";
		
				//echo "<table class='tab_cadre' cellpadding='5' width='800'><tr><th colspan='7'>";
				echo "<table class='tab_cadre_fixe'><tr><th colspan='7'>";
				echo $LANGTRACKER["mib"][5]."</th></tr>";
				
				echo "<tr class='tab_bg_1'>";
				echo "<th align='center'></th>";
				echo "<th align='center'>".$LANGTRACKER["mib"][1]."</th>";
				echo "<th align='center'>".$LANGTRACKER["mib"][2]."</th>";
				echo "<th align='center'>".$LANGTRACKER["mib"][3]."</th>";
				echo "<th align='center'>".$LANGTRACKER["mib"][6]."</th>";
				echo "<th align='center'>".$LANGTRACKER["mib"][7]."</th>";
				echo "<th align='center' width='250'>".$LANGTRACKER["mib"][8]."</th>";
				echo "</tr>";
				while ($data=$DB->fetch_array($result))
				{
					echo "<tr class='tab_bg_1'>";
					echo "<td align='center'>";
					echo "<input name='item_coche[]' value='".$data["ID"]."' type='checkbox'>";
					echo "</td>";
	
					echo "<td align='center'>";
					echo getDropdownName("glpi_dropdown_plugin_tracker_mib_label",$data["FK_mib_label"]);
					echo "</td>";
					
					echo "<td align='center'>";
					echo getDropdownName("glpi_dropdown_plugin_tracker_mib_object",$data["FK_mib_object"]);
					echo "</td>";
					
					echo "<td align='center'>";
					echo getDropdownName("glpi_dropdown_plugin_tracker_mib_oid",$data["FK_mib_oid"]);
					echo "</td>";
					
					echo "<td align='center'>";
					if ($data["oid_port_counter"] == "1")
					{
						echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
					}
					echo "</td>";
					
					echo "<td align='center'>";
					if ($data["oid_port_dyn"] == "1")
					{
						echo "<img src='".$CFG_GLPI["root_doc"]."/pics/bookmark.png'/>";
					}
					echo "</td>";
					
					echo "<td align='center'>";
					if (isset($TRACKER_MAPPING[$data['mapping_type']][$data["mapping_name"]]['name']))
						echo $TRACKER_MAPPING[$data['mapping_type']][$data["mapping_name"]]['name'];
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
	
				echo "<br/>";
				echo "<table class='tab_cadre_fixe'>";
				/*
				echo "<tr class='tab_bg_1'>
					<td colspan='7'><input type='submit' name='delete_oid' value=\"".$LANG["buttons"][6]."\" class='submit' ></td>
				</tr>";
				echo "<tr><td colspan='7'></td></tr>";
				*/
				
				// ********** Ajout d'une dernière ligne pour ajouter nouveau OID ********** //
				echo "<tr class='tab_bg_1'><th colspan='7'>".$LANGTRACKER["mib"][4]."</th></tr>";				
				
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'></td>";
				
				echo "<td align='center'>";
				dropdownValue("glpi_dropdown_plugin_tracker_mib_label","FK_mib_label",0,1);
				echo "</td>";
				
				echo "<td align='center'>";
				dropdownValue("glpi_dropdown_plugin_tracker_mib_object","FK_mib_object",0,1);
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
				foreach ($TRACKER_MAPPING as $type=>$mapping43)
				{
					if (isset($TRACKER_MAPPING[$type]))
					{
						foreach ($TRACKER_MAPPING[$type] as $name=>$mapping)
						{
							$types[$type."||".$name]=$TRACKER_MAPPING[$type][$name]["name"];
							//echo "<option value='".$type."||".$name."'>".$TRACKER_MAPPING[$type][$name]["name"]."</option>";
						}
					}
				}
				//echo "</select>";
				dropdownArrayValues("links_oid_fields",$types);

				echo "</td>";
				
				echo "</tr>";
				
				echo "<tr class='tab_bg_1'><td colspan='7' align='center'>";
				echo "<input type='hidden' name='FK_model_infos' value='".$ID."'/>";
				echo "<input type='submit' name='add_oid' value=\"".$LANG["buttons"][2]."\" class='submit' >";
				echo "</td></tr>";	
				
				echo "</table></form></div>";
			}		
		}
	}

	function prepareInputForUpdate($input)
	{
		$explode = explode("||",$input["links_oid_fields"]);
		$input["mapping_type"] = $explode[0];
		$input["mapping_name"] = $explode[1];
		return $input;
	}

	function prepareInputForAdd($input)
	{
		$explode = explode("||",$input["links_oid_fields"]);
		$input["mapping_type"] = $explode[0];
		$input["mapping_name"] = $explode[1];
		return $input;
	}

/*
	function addentry($target,$ID)
	{
		GLOBAL $DB,$CFG_GLPI,$LANG,$LANGTRACKER;
		
		$explode = explode("||",$_POST["links_oid_fields"]);
		
		$query = "INSERT INTO glpi_plugin_tracker_mib_networking
		(FK_model_infos, FK_mib_label, FK_mib_oid, FK_mib_object, oid_port_counter, oid_port_dyn, mapping_type, mapping_name)
		VALUES ('".$ID."', '".$_POST["mib_label"]."', '".$_POST["mib_oid"]."', '".$_POST["mib_object"]."', 
		'".$_POST["port_counter_new"]."','".$_POST["port_dyn_new"]."','".$explode[0]."','".$explode[1]."')";
		
		$DB->query($query);
		
		echo "Ajouté avec succès<br/>";
		echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/tracker/front/plugin_tracker.models.form.php?ID=".$ID."'><b>Retour</b></a>";
	}
	*/
	

	
	function delete($item_coche)
	{
		global $DB;
		
		for ($i = 0; $i < count($item_coche); $i++)
		{
			$query = "DELETE FROM glpi_plugin_tracker_mib_networking WHERE id=".$item_coche[$i]." ";
			$DB->query($query);
		}

	}


}

?>