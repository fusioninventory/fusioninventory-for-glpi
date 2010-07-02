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

class PluginFusinvsnmpPrinterLog extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_fusinvsnmp_printerlogs";
	}
	
	function countAllEntries($id) {
		global $DB;
		
		$num = 0;
		$query = "SELECT count(DISTINCT `id`)
                FROM ".$this->table."
                WHERE `printers_id` = '".$id."';";
		if ($result_num=$DB->query($query)) {
			if ($field = $DB->result($result_num,0,0)) {
				$num += $field;
         }
		}
		return $num;
	}

	/* Gets history (and the number of entries) of one printer */
	function getEntries($id, $begin, $limit) {
		global $DB;
		
		$datas=array();
		$query = "SELECT *
                FROM ".$this->table."
				    WHERE `printers_id` = '".$id."'
                LIMIT ".$begin.", ".$limit.";";

		if ($result=$DB->query($query)) {
			$i = 0;
			while ($data=$DB->fetch_assoc($result)) {
				$data['date'] = convDateTime($data['date']);
				$datas["$i"] = $data;
				$i++;
			}
			return $datas;
		}
		return false;
	}
	

	
	function stats($id) {
		global $DB;
		
		$query = "SELECT MIN(`date`) AS `min_date`, MIN(`pages`) AS `min_pages`, ".
				 		"MAX(`date`) AS `max_date`, MAX(`pages`) AS `max_pages`
                FROM ".$this->table."
                WHERE `printers_id` = '".$id."';";

		if ($result = $DB->query($query)) {
			if ($fields = $DB->fetch_assoc($result)) {
				$output['num_days'] =
               ceil((strtotime($fields['max_date']) - strtotime($fields['min_date']))/(60*60*24));
				$output['num_pages'] = $fields['max_pages'] - $fields['min_pages'];
				$output['pages_per_day'] = round($output['num_pages'] / $output['num_days']);
				return $output;
			}
		}
		return false;
	}
	
	function showForm($id, $options=array()) {
		global $LANG;
		
		if (!PluginFusioninventoryProfile::haveRight("snmp_printers","r")) {
			return false;
      }
		
		// display stats
		if ($stats = $this->stats($id)) {
				
			$this->showTabs($options);
         $this->showFormHeader($options);
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANG['plugin_fusioninventory']["prt_history"][12]." : </td>";
			echo "<td>".$stats["num_pages"]."</td></tr>";
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANG['plugin_fusioninventory']["prt_history"][13]." : </td>";
			echo "<td>".$stats["pages_per_day"]."</td></tr>";
			
			echo "</table></div>";
		
		}
		
		// preparing to display history
		if (!isset($_GET['start'])) {
			$_GET['start'] = 0;
      }
		
		$numrows = $this->countAllEntries($id);
		$parameters = "id=".$_GET["id"]."&onglet=".$_SESSION["glpi_onglet"];	
		
		echo "<br>";
		printPager($_GET['start'], $numrows, $_SERVER['PHP_SELF'], $parameters);

		if ($_SESSION["glpilist_limit"] < $numrows) {
			$limit = $_SESSION["glpilist_limit"];
      } else {
			$limit = $numrows;
      }
		// Get history
		if (!($data = $this->getEntries($id, $_GET['start'], $limit))) {
			return false;
      }

		echo "<div align='center'><form method='post' name='printer_history_form'
                 id='printer_history_form'  action=\"".$target."\">";

		echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='3'>";
		echo $LANG['plugin_fusioninventory']["prt_history"][20]." :</th></tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<th></th>";
		echo "<th>".$LANG['plugin_fusioninventory']["prt_history"][21]." :</th>";
		echo "<th>".$LANG['plugin_fusioninventory']["prt_history"][22]." :</th></tr>";

		for ($i=0 ; $i<$limit ; $i++) {
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'>";
			echo "<input type='checkbox' name='checked_$i' value='1'>";
			echo "</td>";
			echo "<td align='center'>".$data["$i"]['date']."</td>";
			echo "<td align='center'>".$data["$i"]['pages']."</td>";
			echo "</td></tr>";
			echo "<input type='hidden' name='ID_$i' value='".$data["$i"]['id']."'>";
		}
		
		if (!PluginFusioninventoryProfile::haveRight("snmp_printers","w")) {
			return false;
      }
			
		echo "<input type='hidden' name='limit' value='".$limit."'>";
		echo "<tr class='tab_bg_1'><td colspan='3'>";
		echo "<div align='center'><a onclick= \"if (markAllRows('printer_history_form')) 
                 return false;\"
                 href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG["buttons"][18]."</a>";
		echo " - <a onclick= \"if ( unMarkAllRows('printer_history_form') ) return false;\"
                  href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG["buttons"][19]."</a> ";
		echo "<input type='submit' name='delete' value=\"".$LANG["buttons"][6]."\" class='submit' >
            </div></td></tr>";
		echo "</table></form></div>";
	}
}

?>