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


class PluginFusionInventorySNMPHistory extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_fusioninventory_snmp_history";
		$this->type = PLUGIN_FUSIONINVENTORY_SNMP_HISTORY;
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

      $pthc = new PluginFusionInventoryHistoryConnections;

      $input['date'] = date("Y-m-d H:i:s");
      $input['FK_ports'] = $array['FK_ports'];

      if ($status == "field") {

			$query = "INSERT INTO `glpi_plugin_fusioninventory_snmp_history` (
                               `FK_ports`,`field`,`old_value`,`new_value`,`date_mod`,`FK_process`)
                   VALUES('".$array["FK_ports"]."','".addslashes($array["field"])."',
                          '".$array["old_value"]."','".$array["new_value"]."',
                          '".date("Y-m-d H:i:s")."','".$FK_process."');";
         $DB->query($query);
		}
 	}


   function showForm($target,$ID) {
      global $LANG, $DB;

      echo "<form method='post' name='functionalities_form' id='functionalities_form'
                  action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='2'>";

		echo "<tr>";
		echo "<th colspan='3'>";
		echo $LANG['plugin_fusioninventory']["functionalities"][28]." :";
		echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td colspan='3'>";
		echo $LANG['plugin_fusioninventory']["functionalities"][29]." :";
		echo "</td>";
		echo "</tr>";

      echo "<tr class='tab_bg_1'>";

      include (GLPI_ROOT . "/plugins/fusioninventory/inc_constants/plugin_fusioninventory.snmp.mapping.constant.php");

      $options="";

      foreach ($FUSIONINVENTORY_MAPPING as $type=>$mapping43) {
         if (isset($FUSIONINVENTORY_MAPPING[$type])) {
            foreach ($FUSIONINVENTORY_MAPPING[$type] as $name=>$mapping) {
               $listName[$type."-".$name]=$FUSIONINVENTORY_MAPPING[$type][$name]["name"];
            }
         }
      }
      if (!empty($listName)) {
         asort($listName);
      }

      // Get list of fields configured for history
      $query = "SELECT *
                FROM `glpi_plugin_fusioninventory_config_snmp_history`;";
      if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            list($type,$name) = explode("-", $data['field']);
            if (!isset($FUSIONINVENTORY_MAPPING[$type][$name]["name"])) {
               $query_del = "DELETE FROM `glpi_plugin_fusioninventory_config_snmp_history`
                  WHERE id='".$data['id']."' ";
                  $DB->query($query_del);
            } else {
               $options[$data['field']]=$FUSIONINVENTORY_MAPPING[$type][$name]["name"];
            }
            unset($listName[$data['field']]);
         }
      }
      if (!empty($options)) {
         asort($options);
      }
      echo "<td class='right' width='350'>";
      if (count($listName)) {
         echo "<select name='plugin_fusioninventory_extraction_to_add[]' multiple size='15'>";
         foreach ($listName as $key => $val) {
            //list ($item_type, $item) = explode("_", $key);
            echo "<option value='$key'>" . $val . "</option>\n";
         }
         echo "</select>";
      }

      echo "</td><td class='center'>";

      if (count($listName)) {
         if (plugin_fusioninventory_haveRight("configuration","w")) {
            echo "<input type='submit'  class=\"submit\" name='plugin_fusioninventory_extraction_add' value='" . $LANG["buttons"][8] . " >>'>";
         }
      }
      echo "<br /><br />";
      if (!empty($options)) {
         if (plugin_fusioninventory_haveRight("configuration","w")) {
            echo "<input type='submit'  class=\"submit\" name='plugin_fusioninventory_extraction_delete' value='<< " . $LANG["buttons"][6] . "'>";
         }
      }
      echo "</td><td class='left'>";
      if (!empty($options)) {
         echo "<select name='plugin_fusioninventory_extraction_to_delete[]' multiple size='15'>";
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
		echo $LANG['plugin_fusioninventory']["functionalities"][60]." :";
		echo "</th>";
		echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='3' class='center'>";
      if (plugin_fusioninventory_haveRight("configuration","w")) {
         echo "<input type='submit' class=\"submit\" name='Clean_history' value='".$LANG['buttons'][53]."' >";
      }
      echo "</td>";
      echo "</tr>";


		echo "<tr>";
		echo "<th colspan='3'>";
      echo "&nbsp;";
		echo "</th>";
		echo "</tr>";


		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
      if (plugin_fusioninventory_haveRight("configuration","w")) {
   		echo "<input type='hidden' name='tabs' value='history' />";
   		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' >";
      }
      echo "</td>";
      echo "</tr>";
		echo "</table></form>";

   }

   function UpdateConfigFields($data) {
      global $DB;

		if (isset($data['plugin_fusioninventory_extraction_to_add'])) {
			foreach ($data['plugin_fusioninventory_extraction_to_add'] as $key=>$id_value) {
				$query = "INSERT INTO `glpi_plugin_fusioninventory_config_snmp_history` (`field`)
                      VALUES ('".$id_value."');";
				$DB->query($query);
			}
      }

		if (isset($data['plugin_fusioninventory_extraction_to_delete'])) {
			foreach ($data['plugin_fusioninventory_extraction_to_delete'] as $key=>$id_value) {
				$query = "DELETE FROM `glpi_plugin_fusioninventory_config_snmp_history`
                      WHERE `field`='".$id_value."';";
				$DB->query($query);
			}
      }
   }


   function CleanHistory($data) {
      global $DB;

      include (GLPI_ROOT . "/plugins/fusioninventory/inc_constants/plugin_fusioninventory.snmp.mapping.constant.php");

      foreach ($FUSIONINVENTORY_MAPPING as $type=>$mapping43) {
         if (isset($FUSIONINVENTORY_MAPPING[$type])) {
            foreach ($FUSIONINVENTORY_MAPPING[$type] as $name=>$mapping) {
               $listName[$type."-".$name]=$FUSIONINVENTORY_MAPPING[$type][$name]["name"];
            }
         }
      }

      $query = "SELECT *
                FROM `glpi_plugin_fusioninventory_config_snmp_history`;";
      if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            list($type,$name) = explode("-", $data['field']);
            $options[$data['field']]=$FUSIONINVENTORY_MAPPING[$type][$name]["name"];
            unset($listName[$data['field']]);
         }
      }

      foreach ($listName as $var=>$tmp) {
         list($type,$name) = explode("-", $var);
         $query_delete = 'DELETE FROM `glpi_plugin_fusioninventory_snmp_history`
                          WHERE `field`="'.$FUSIONINVENTORY_MAPPING[$type][$name]["name"].'";';
         $DB->query($query_delete);
      }
   }



   function ConvertField() {
      include (GLPI_ROOT . "/plugins/fusioninventory/inc_constants/plugin_fusioninventory.snmp.mapping.constant.php");
      global $DB, $LANG;

      $constantsfield = array();
      foreach ($FUSIONINVENTORY_MAPPING[NETWORKING_TYPE] as $fieldtype=>$array) {
         $constantsfield[$FUSIONINVENTORY_MAPPING[NETWORKING_TYPE][$fieldtype]['name']] = $fieldtype;
      }

      echo "<center><table align='center' width='500'>";
      echo "<tr>";
      echo "<td>";
      createProgressBar("Update Ports history");

      $query = "SELECT *
                FROM ".$this->table."
                WHERE `Field` != '0';";
      if ($result=$DB->query($query)) {
         $nb = $DB->numrows($result);
         $i = 0;
			while ($data=$DB->fetch_array($result)) {
            $i++;
            if (isset($constantsfield[$data['Field']])) {
               $data['Field'] = $constantsfield[$data['Field']];
               $this->update($data);
               if (preg_match("/00$/", $i)) {
                  changeProgressBarPosition($i, $nb, "$i / $nb");
               }
            }
         }
      }
      echo "</td>";
      echo "</tr>";
      echo "</table></center>";
   }
}

?>