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

class PluginTrackerAgents extends CommonDBTM {

   function __construct() {
		$this->table = "glpi_plugin_tracker_agents";
		$this->type = PLUGIN_TRACKER_SNMP_AGENTS;
	}


	function PushData($ID, $key) {
		$this->getFromDB($ID);
		// Name of server
		// $this->fields["name"];
		
		$xml = "<snmp>\n";
		// ** boucle sur les équipements réseau
		// ** détection des équipements avec le bon status et l'IP dans la plage de l'agent
		//  Ecriture du fichier xml pour l'envoi à l'agent
	
		$xml .= "</snmp>\n";
		// Affichage du fichier xml pour que l'agent récupère les paramètres
		echo $xml;
	}


	function showForm($target, $ID = '') {
		GLOBAL $DB,$CFG_GLPI,$LANG;

		if ($ID!='') {
			$this->getFromDB($ID);
      } else {
			$this->getEmpty();
      }

		$this->showTabs($ID, "",$_SESSION['glpi_tab']);
		echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";

		echo "<table class='tab_cadre' cellpadding='5' width='950'>";
		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_tracker']["agents"][0];
		echo " :</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG["common"][16] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' value='".$this->fields["name"]."'/>";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>".$LANG['plugin_tracker']["agents"][5]."</td>";
		echo "<td align='center'>";
		echo $this->fields["tracker_agent_version"];
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_tracker']["agents"][6] . "</td>";
		echo "<td align='center'>";
		dropdownYesNo("lock",$this->fields["lock"]);
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG["Menu"][30] . "</td>";
		echo "<td align='center'>";
		$ArrayValues[]= $LANG["choice"][0];
		$ArrayValues[]= $LANG["choice"][1];
		$ArrayValues[]= $LANG["setup"][137];
		if (empty($this->fields["logs"])) {
			dropdownArrayValues("logs",$ArrayValues,$ArrayValues[0]);
      } else {
			dropdownArrayValues("logs",$ArrayValues,$this->fields["logs"]);
      }
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<th colspan='2'>";
		echo "<a href='#' onClick='getSlide(\"optionavance\");'>".$LANG['plugin_tracker']["agents"][9]." :</a>";
		echo "</th>";
		echo "</tr>";

      echo "</table>";
      echo "<div  id='optionavance' style='display: none;'>";
      echo "<table class='tab_cadre' cellpadding='5' width='950'>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center' width='200'>" . $LANG['plugin_tracker']["agents"][11] . "</td>";
		echo "<td align='center' width='200'>";
		dropdownInteger("core_discovery", $this->fields["core_discovery"],1,32);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_tracker']["agents"][3]."</td>";
		echo "<td align='center'>";
		dropdownInteger("threads_discovery", $this->fields["threads_discovery"],1,400);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_tracker']["agents"][10]."</td>";
		echo "<td align='center'>";
		dropdownInteger("core_query", $this->fields["core_query"],1,200);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_tracker']["agents"][2] . "</td>";
		echo "<td align='center'>";
		dropdownInteger("threads_query", $this->fields["threads_query"],1,200);
		echo "</td>";
		echo "</tr>";


		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_tracker']["agents"][8] . "</td>";
		echo "<td align='center'>";
		if (empty($this->fields["fragment"]))
			$this->fields["fragment"] = 50;
		echo "<input type='text' name='fragment' value='".$this->fields["fragment"]."'/>";
		echo "</td>";
		echo "</tr>";

      echo "</table>";
      echo "</div>";
      echo "<table class='tab_cadre' cellpadding='5' width='950'>";

		echo "<tr class='tab_bg_1'><td align='center' colspan='3'>";
		if ($ID=='') {
			// Generator of Key
			$chrs = 30;
			$chaine = ""; 
			$list = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
			mt_srand((double)microtime()*1000000);
			$newstring="";
			while( strlen( $newstring )< $chrs ) {
				$newstring .= $list[mt_rand(0, strlen($list)-1)];
			}

			echo "<input type='hidden' name='key' value='".$newstring."'/>";
			echo "<div align='center'><input type='submit' name='add' value=\"" . $LANG["buttons"][8] . "\" class='submit' >";
		} else {
			echo "<input type='hidden' name='ID' value='" . $ID . "'/>";
			echo "<div align='center'><input type='submit' name='update' value=\"" . $LANG["buttons"][7] . "\" class='submit' >";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' name='delete' value=\"" . $LANG["buttons"][6] . "\" class='submit'>";
		}
		echo "</td></tr>";
		echo "</table></form></div>";

	}


	
	function export_config($ID) {
		GLOBAL $DB;
	
		$tracker_config = new PluginTrackerConfig;
		$tracker_config->getFromDB(1);

		$this->getFromDB($ID);
		echo "server=".$tracker_config->fields["URL_agent_conf"]."/plugins/tracker/front/plugin_tracker.agents.diag.php\n";
		echo "id=".$ID."\n";
		echo "key=".$this->fields["key"]."\n";
	}

}

?>