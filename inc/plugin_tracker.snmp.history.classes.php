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


class PluginTrackerSNMPHistory extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_tracker_snmp_history";
		$this->type = PLUGIN_TRACKER_SNMP_HISTORY;
	}

	/**
	 * Insert port history with connection and disconnection
	 *
	 * @param $status status of port ('make' or 'remove')
	 * @param $array with values : $array["FK_ports"], $array["value"], $array["device_type"] and $array["device_ID"]
	 *
	 * @return ID of inserted line
	 *
	**/
	function insert_connection($status,$array,$FK_process=0) {
		global $DB,$CFG_GLPI;
		if ($status == "remove") {
			$query = "INSERT INTO glpi_plugin_tracker_snmp_history
			(FK_ports,old_value,old_device_type,old_device_ID,date_mod,FK_process)
			VALUES('".$array["FK_ports"]."','".$array["value"]."','".$array["device_type"]."','".$array["device_ID"]."','".date("Y-m-d H:i:s")."','".$FK_process."')";
		
		} else if ($status == "make") {
			$query = "INSERT INTO glpi_plugin_tracker_snmp_history
			(FK_ports,new_value,new_device_type,new_device_ID,date_mod,FK_process)
			VALUES('".$array["FK_ports"]."','".$array["value"]."','".$array["device_type"]."','".$array["device_ID"]."','".date("Y-m-d H:i:s")."','".$FK_process."')";
	
		} else if ($status == "field") {
			$query = "INSERT INTO glpi_plugin_tracker_snmp_history
			(FK_ports,field,old_value,new_value,date_mod,FK_process)
			VALUES('".$array["FK_ports"]."','".addslashes($array["field"])."','".$array["old_value"]."','".$array["new_value"]."','".date("Y-m-d H:i:s")."','".$FK_process."')";
	
		}
		$DB->query($query);
		return mysql_insert_id();
	}


   function showForm($target,$ID) {
      global $LANG, $DB;

      echo "<form method='post' name='functionalities_form' id='functionalities_form'  action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='2'>";

		echo "<tr>";
		echo "<th colspan='3'>";
		echo $LANG['plugin_tracker']["functionalities"][28]." :";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td colspan='3'>";
		echo $LANG['plugin_tracker']["functionalities"][29]." :";
		echo "</td>";
		echo "</tr>";

      echo "<tr class='tab_bg_1'>";

      include (GLPI_ROOT . "/plugins/tracker/inc/plugin_tracker.snmp.mapping.constant.php");

      $options="";

      foreach ($TRACKER_MAPPING as $type=>$mapping43) {
         if (isset($TRACKER_MAPPING[$type])) {
            foreach ($TRACKER_MAPPING[$type] as $name=>$mapping) {
               $listName[$type."-".$name]=$TRACKER_MAPPING[$type][$name]["name"];
            }
         }
      }
      if (!empty($listName)) {
         asort($listName);
      }

      // Get list of fields configured for history
      $query = "SELECT * FROM glpi_plugin_tracker_config_snmp_history";
      if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            list($type,$name) = explode("-", $data['field']);
            $options[$data['field']]=$TRACKER_MAPPING[$type][$name]["name"];
            unset($listName[$data['field']]);
         }
      }
      if (!empty($options)) {
         asort($options);
      }
      echo "<td class='right' width='350'>";
      if (count($listName)) {
         echo "<select name='plugin_tracker_extraction_to_add[]' multiple size='15'>";
         foreach ($listName as $key => $val) {
            //list ($item_type, $item) = explode("_", $key);
            echo "<option value='$key'>" . $val . "</option>\n";
         }
         echo "</select>";
      }

      echo "</td><td class='center'>";

      if (count($listName)) {
         echo "<input type='submit'  class=\"submit\" name='plugin_tracker_extraction_add' value='" . $LANG["buttons"][8] . " >>'>";
      }
      echo "<br /><br />";
      if (!empty($options)) {
         echo "<input type='submit'  class=\"submit\" name='plugin_tracker_extraction_delete' value='<< " . $LANG["buttons"][6] . "'>";
      }

      echo "</td><td class='left'>";
      if (!empty($options)) {
         echo "<select name='plugin_tracker_extraction_to_delete[]' multiple size='15'>";
         foreach ($options as $key => $val) {
            //list ($item_type, $item) = explode("_", $key);
            echo "<option value='$key'>" . $val . "</option>\n";
         }
         echo "</select>";
      } else {
         echo "&nbsp;";
      }
      echo "</td>";
		echo "</tr>";


		echo "<tr>";
		echo "<th colspan='3'>";
		echo $LANG['plugin_tracker']["functionalities"][60]." :";
		echo "</th>";
		echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='3' class='center'>";
      echo "<input type='submit' class=\"submit\" name='Clean_history' value='".$LANG['buttons'][53]."' >";
      echo "</td>";
      echo "</tr>";


		echo "<tr>";
		echo "<th colspan='3'>";
      echo "&nbsp;";
		echo "</th>";
		echo "</tr>";


		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
		echo "<input type='hidden' name='tabs' value='history' />";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' ></div></td></tr>";
		echo "</table></form>";

   }

   function UpdateConfigFields($data) {
      global $DB;

		if (isset($data['plugin_tracker_extraction_to_add'])) {
			foreach ($data['plugin_tracker_extraction_to_add'] as $key=>$id_value) {
				$query = "INSERT INTO glpi_plugin_tracker_config_snmp_history
				(field)
				VALUES ('".$id_value."')";
				$DB->query($query);
			}
      }

		if (isset($data['plugin_tracker_extraction_to_delete'])) {
			foreach ($data['plugin_tracker_extraction_to_delete'] as $key=>$id_value) {
				$query = "DELETE FROM glpi_plugin_tracker_config_snmp_history
				WHERE field='".$id_value."'";
				$DB->query($query);
			}
      }
   }


   function CleanHistory($data) {
      global $DB;

      include (GLPI_ROOT . "/plugins/tracker/inc/plugin_tracker.snmp.mapping.constant.php");

      foreach ($TRACKER_MAPPING as $type=>$mapping43) {
         if (isset($TRACKER_MAPPING[$type])) {
            foreach ($TRACKER_MAPPING[$type] as $name=>$mapping) {
               $listName[$type."-".$name]=$TRACKER_MAPPING[$type][$name]["name"];
            }
         }
      }

      $query = "SELECT * FROM glpi_plugin_tracker_config_snmp_history";
      if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            list($type,$name) = explode("-", $data['field']);
            $options[$data['field']]=$TRACKER_MAPPING[$type][$name]["name"];
            unset($listName[$data['field']]);
         }
      }

      foreach ($listName as $var=>$tmp) {
         list($type,$name) = explode("-", $var);
         $query_delete = 'DELETE FROM glpi_plugin_tracker_snmp_history
            WHERE Field="'.$TRACKER_MAPPING[$type][$name]["name"].'" ';
         $DB->query($query_delete);
      }

   }

}

?>
