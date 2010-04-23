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

class PluginFusionInventoryPrintersHistory extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_fusioninventory_printers_history";
		$this->type=-1;
	}
	
	function countAllEntries($ID) {
		global $DB;
		
		$num = 0;
		$query = "SELECT count(DISTINCT `ID`)
                FROM ".$this->table."
                WHERE `FK_printers` = '".$ID."';";
		if ($result_num=$DB->query($query)) {
			if ($field = $DB->result($result_num,0,0)) {
				$num += $field;
         }
		}
		return $num;
	}

	/* Gets history (and the number of entries) of one printer */
	function getEntries($ID, $begin, $limit) {
		global $DB;
		
		$datas=array();
		$query = "SELECT *
                FROM ".$this->table."
				    WHERE `FK_printers` = '".$ID."'
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
	

	
	function stats($ID) {
		global $DB;
		
		$query = "SELECT MIN(`date`) AS `min_date`, MIN(`pages`) AS `min_pages`, ".
				 		"MAX(`date`) AS `max_date`, MAX(`pages`) AS `max_pages`
                FROM ".$this->table."
                WHERE `FK_printers` = '".$ID."';";

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
	
	function showForm($target, $ID) {
		global $LANG;
		
		if (!PluginFusioninventory::haveRight("snmp_printers","r")) {
			return false;
      }
		
		// display stats
		if ($stats = $this->stats($ID)) {
				
			echo "<br><div align = 'center'>";
			echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='2'>";
			echo $LANG['plugin_fusioninventory']["prt_history"][10]." ".$stats["num_days"]." ".
                 $LANG['plugin_fusioninventory']["prt_history"][11]."</th></tr>";
			
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
		
		$numrows = $this->countAllEntries($ID);
		$parameters = "ID=".$_GET["ID"]."&onglet=".$_SESSION["glpi_onglet"];	
		
		echo "<br>";
		printPager($_GET['start'], $numrows, $_SERVER['PHP_SELF'], $parameters);

		if ($_SESSION["glpilist_limit"] < $numrows) {
			$limit = $_SESSION["glpilist_limit"];
      } else {
			$limit = $numrows;
      }
		// Get history
		if (!($data = $this->getEntries($ID, $_GET['start'], $limit))) {
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
			echo "<input type='hidden' name='ID_$i' value='".$data["$i"]['ID']."'>";
		}
		
		if (!PluginFusioninventory::haveRight("snmp_printers","w")) {
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

class PluginFusionInventoryPrintersHistoryConfig extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_fusioninventory_printers_history_config";
		$this->type=-1;
	}
	
	/**
	 * To get value of one specific cron
	 *
	 * @return value of the counter setting
	 * @return -1 if no entry found
	 * @return false if DB connexion error (which implies same value as no activation)
	 */	
	function getCounterValue($ID) {
		global $DB;

		$query = "SELECT `counter`
                FROM ".$this->table."
                WHERE `FK_printers` = '".$ID."';";
		if ($result = $DB->query($query)) {
			if ($this->fields = $DB->fetch_row($result)) {
				return $this->fields['0'];
         } else {
				return -1;
         }
		}
		return false;
	}
	
	/**
	 * To get counter state from FK_printers (i.e. : printer ID)
	 *
	 * @param $ID : ID of the printer (equiv to FK_printers into table)
	 * @return true if get an entry, otherwise false
	 */
	function getDataFromPrinterId($ID) {
		global $DB;
		$query = "SELECT `ID`, `counter`
                FROM ".$this->table."
                WHERE `FK_printers` = '".$ID."';";
		if ($result = $DB->query($query)) {
			if ($DB->numrows($result) == 1) {
				$this->fields = $DB->fetch_assoc($result);
				return true;
			}
		}
		return false;
	}
	
	/**
	 * set cron to 1 for all printers -- not used
	 *
	 */
	function setAll() {
		global $DB;
		$query= "SELECT `ID`
               FROM `glpi_printers`
               WHERE 1;";
		if ($result=$DB->query($query)) {
			$end = $DB->numrows($result);
			if (($end = $DB->numrows($result)) > 0) {
				$fields = $DB->fetch_row($result);
				$input['counter'] = 1;
				for ($i=0; $i<$end; $i++) {
					$input['FK_printers'] = $fields['0'];
					$this->updateOne($input);
					$fields = $DB->fetch_row($result);
				}
			}
		}
	}
	
	/**
	 * set cron to 0 for all printers -- not used
	 *
	 */
	function unsetAll() {
		global $DB;
		$query = "UPDATE ".$this->table." 
                SET `counter`='0'
                WHERE 1;";
		$DB->query($query);	
	}
	
	/**
	 * Gets the number and all the IDs of activated printers
	 *
	 * @return $datas :
	 * - $datas['number'] for the number of activated printers
	 * - $datas['$i'] : contains the activated printers ID
	 */
	function getAllActivated() {
		global $DB;
		
		$config = new PluginFusionInventoryConfig;
		$statement = $config->getValue("statement_default_value");
		
		$datas=array();
		
		// if statement is not active by default, get exceptions
		if (!$statement) {
			$query = "SELECT `FK_printers`
                   FROM ".$this->table."
                   WHERE `counter` = '1';";
      // if statement is active by default, get all without the exceptions
		} else {
			$query= "SELECT `glpi_printers`.`ID`
                  FROM `glpi_printers`
                  LEFT JOIN ".$this->table." ON `glpi_printers`.`ID`=".$this->table.".`FK_printers`
                  WHERE ".$this->table.".`counter` != '0'
                        OR ".$this->table.".`counter` IS NULL;";
		}
		
		if ($result = $DB->query($query)) {
			$i = 0;
			while ($data=$DB->fetch_row($result)) {
				$data['FK_printers'] = $data[0];
				unset($data[0]);
				$datas["$i"] = $data;
				$i++;
			}
			$datas['number'] = count($datas);
			return $datas;
		}
		return false;		
	}

	function updateOne($input) {
		// if exists
		if ($this->getDataFromPrinterId($input['FK_printers'])) {
			// default value (-1) : no entry in DB
			$input['ID'] = $this->fields['ID'];
			if ($input['counter'] == -1) {
				$this->delete($input);
         } else if ($this->fields['counter'] != $input['counter']) {
				$this->update($input);
			}
		} else {
			if ($input['counter'] != -1) {
				$this->add($input);
         }
		}
	}
	
	function showForm($target,$ID) {
		global $LANG;
		
		if (PluginFusioninventory::haveRight("snmp_printers","w")) {
			echo "<br>";
			echo "<div align='center'><form method='post' name='printer_history_config_form'
                    id='printer_history_config_form'  action=\"".$target."\">";
	
			echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='2'>";
			echo $LANG['plugin_fusioninventory']["cron"][0]." :</th></tr>";
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANG['plugin_fusioninventory']["cron"][1]."</td>";
			echo "<td align='center'>";
			plugin_fusioninventory_dropdownDefaultYesNo("counter", $this->getCounterValue($ID));
			echo "</td>";
			echo "</tr>";
			
			echo "<tr class='tab_bg_1'><td colspan='2'>";
			echo "<input type='hidden' name='FK_printers' value='".$ID."'>";
			echo "<div align='center'><input type='submit' name='update' value=\"".$LANG["buttons"][2].
                    "\" class='submit' ></div></td></tr>";
			echo "</table></form></div>";
		}
	}
}

?>