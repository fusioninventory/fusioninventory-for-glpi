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
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class plugin_tracker_computers_history extends CommonDBTM {

	function plugin_tracker_computers_history() {
		$this->table="glpi_plugin_tracker_connection_history";
		$this->type=-1;
	}
	
	function addHistory($input, $date) {
		switch ($input["state"]) {
			case OFF:
			case ON:
			case CONNECTED:
			case DISCONNECTED:
				$input['date'] = $date;
				$this->add($input);
			break;
		}
	}
	
	function countEntries($type, $ID) {
		GLOBAL $DB;
		
		$num = 0;
		$query = "SELECT count(DISTINCT ID) ".
				 "FROM ".$this->table." ";
		
		if ($type == COMPUTER_TYPE) {
			$query .= "WHERE FK_computers = '".$ID."';";
      } else { // $type == USER_TYPE
			$query .= "WHERE FK_users = '".$ID."';";
      }
		if ($result_num=$DB->query($query)) {
			if ($field = $DB->result($result_num,0,0)) {
				$num += $field;
         }
		}
		return $num;
	}
	
	/* Gets history (and the number of entries) of one computer */
	function getEntries($type, $ID, $begin, $limit) {
		GLOBAL $DB;
		
		$datas=array();
		$query = "SELECT * FROM ".$this->table." ";
		
		if ($type == COMPUTER_TYPE) {
			$query .= "WHERE FK_computers = '".$ID."' ";
      } else { // $type == USER_TYPE
			$query .= "WHERE FK_users = '".$ID."' ";
      }
		$query .= "ORDER BY date DESC LIMIT ".$begin.", ".$limit.";";

		if ($result=$DB->query($query)){
			$i = 0;
			while ($data=$DB->fetch_assoc($result)) {
				$data["computer_name"] = plugin_tracker_getDeviceFieldFromId(COMPUTER_TYPE, $data["FK_computers"], "name", NULL);
				$data["user_name"] = plugin_tracker_getDeviceFieldFromId(USER_TYPE, $data["FK_users"], "name", NULL);
				$data['date'] = convDateTime($data['date']);
				$datas["$i"] = $data;
				$i++;				
			}
			return $datas;
		}
		return false;
	}
	
	function showForm($type, $target, $ID) {
		GLOBAL $LANG,$INFOFORM_PAGES,$CFG_GLPI;
		
		if (!plugin_tracker_haveRight("computers_history","r")) {
			return false;
      }
		
		// preparing to display history
		if (!isset($_GET['start'])) {
			$_GET['start'] = 0;
      }
		
		$numrows = $this->countEntries($type, $ID);
		$parameters = "ID=".$_GET["ID"]."&onglet=".$_SESSION["glpi_onglet"];	
		
		echo "<br>";
		printPager($_GET['start'], $numrows, $_SERVER['PHP_SELF'], $parameters);

		if ($_SESSION["glpilist_limit"] < $numrows) {
			$limit = $_SESSION["glpilist_limit"];
      } else {
			$limit = $numrows;
      }
		// Get history
		if (!($data = $this->getEntries($type, $ID, $_GET['start'], $limit))) {
			return false;
      }
		// for $_GET['type'] (useful to check rights)
		if ($type == COMPUTER_TYPE) {
			echo "<div align='center'><form method='post' name='computer_history_form' id='computer_history_form'  action=\"".$target."?type=".COMPUTER_TYPE."\">";
      } else { // $type == USER_TYPE
			echo "<div align='center'><form method='post' name='computer_history_form' id='computer_history_form'  action=\"".$target."?type=".USER_TYPE."\">";
      }
		echo "<table class='tab_cadre_fixe' cellpadding='5'><tr><th colspan='5'>";
		echo $LANG['plugin_tracker']["cpt_history"][0]." :</th></tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<th></th>";
		echo "<th>".$LANG['plugin_tracker']["cpt_history"][4]." :</th>";
		echo "<th>".$LANG['plugin_tracker']["cpt_history"][1]." :</th>";
		if ($type == COMPUTER_TYPE) {
			echo "<th>".$LANG['plugin_tracker']["cpt_history"][3]." :</th>";
      } else { // $type == USER_TYPE
			echo "<th>".$LANG['plugin_tracker']["cpt_history"][2]." :</th>";
      }
		echo "<th>".$LANG['plugin_tracker']["cpt_history"][5]." :</th></tr>";

		for ($i=0 ; $i<$limit ; $i++) {
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'>";
			echo "<input type='checkbox' name='checked_$i' value='1'>";
			echo "</td>";
			echo "<td align='center'>".$LANG['plugin_tracker']["state"][$data["$i"]['state']]."</td>";
			echo "<td align='center'>".$data["$i"]['username']."</td>";
			echo "<td align='center'>";
			if ($type == COMPUTER_TYPE) {
				if ($data["$i"]["user_name"]) {
					echo "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[USER_TYPE]."?ID=".$data["$i"]["FK_users"]."\">";
					echo $data["$i"]["user_name"];
					if (empty($data["$i"]["user_name"]) || $CFG_GLPI["view_ID"]) {
						echo " (".$data["$i"]['FK_users'].")";
               }
					echo "</a>";
				}
			} else { // $type == USER_TYPE
				if ($data["$i"]["computer_name"]) {
					echo "<a href=\"".$CFG_GLPI["root_doc"]."/".$INFOFORM_PAGES[COMPUTER_TYPE]."?ID=".$data["$i"]["FK_computers"]."\">";
					echo $data["$i"]["computer_name"];
					if (empty($data["$i"]["computer_name"]) || $CFG_GLPI["view_ID"]) {
						echo " (".$data["$i"]['FK_computers'].")";
               }
					echo "</a>";
				}
			}
			echo "</td>";
			echo "<td align='center'>".$data["$i"]['date']."</td>";
			echo "</td></tr>";
			echo "<input type='hidden' name='ID_$i' value='".$data["$i"]['ID']."'>";
		}
		
		if (!plugin_tracker_haveRight("computers_history","w")) {
			return false;
      }
			
		echo "<input type='hidden' name='limit' value='".$limit."'>";
		echo "<tr class='tab_bg_1'><td colspan='5'>";
		echo "<div align='center'><a onclick= \"if ( markAllRows('printer_history_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG["buttons"][18]."</a>";
		echo " - <a onclick= \"if ( unMarkAllRows('printer_history_form') ) return false;\" href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG["buttons"][19]."</a> ";
		echo "<input type='submit' name='delete' value=\"".$LANG["buttons"][6]."\" class='submit' ></div></td></tr>";	
		echo "</table></form></div>";
	}
}

?>