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

class PluginFusionInventoryAgents extends CommonDBTM {

   function __construct() {
		$this->table = "glpi_plugin_fusioninventory_agents";
		$this->type = PLUGIN_FUSIONINVENTORY_SNMP_AGENTS;
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
		global $DB,$CFG_GLPI,$LANG;

		if ($ID!='') {
			$this->getFromDB($ID);
      } else {
			$this->getEmpty();
      }

      $CommonItem = new CommonItem;

		$this->showTabs($ID, "",$_SESSION['glpi_tab']);
		echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";

		echo "<table class='tab_cadre' cellpadding='5' width='950'>";
		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_fusioninventory']["agents"][0];
		echo " :</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG["common"][16] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' value='".$this->fields["name"]."'/>";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>".$LANG['plugin_fusioninventory']["agents"][5]."</td>";
		echo "<td align='center'>";
		echo $this->fields["fusioninventory_agent_version"];
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["agents"][6] . "</td>";
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

		echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>Token</td>";
		echo "<td align='center'>";
		echo $this->fields["token"];
		echo "</td>";
		echo "</tr>";
      
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center' colspan='2'>";
      $CommonItem->getFromDB($this->fields["device_type"],
                                $this->fields["on_device"]);
      echo $CommonItem->getLink(1);
		echo "</td>";
		echo "</tr>";


		echo "<tr>";
		echo "<th colspan='2'>";
		echo "<a href='#' onClick='getSlide(\"optionavance\");'>".$LANG['plugin_fusioninventory']["agents"][9]." :</a>";
		echo "</th>";
		echo "</tr>";

      echo "</table>";
      echo "<div  id='optionavance' style='display: none;'>";
      echo "<table class='tab_cadre' cellpadding='5' width='950'>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center' width='200'>" . $LANG['plugin_fusioninventory']["agents"][11] . "</td>";
		echo "<td align='center' width='200'>";
		dropdownInteger("core_discovery", $this->fields["core_discovery"],1,32);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_fusioninventory']["agents"][3]."</td>";
		echo "<td align='center'>";
		dropdownInteger("threads_discovery", $this->fields["threads_discovery"],1,400);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_fusioninventory']["agents"][10]."</td>";
		echo "<td align='center'>";
		dropdownInteger("core_query", $this->fields["core_query"],1,200);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["agents"][2] . "</td>";
		echo "<td align='center'>";
		dropdownInteger("threads_query", $this->fields["threads_query"],1,200);
		echo "</td>";
		echo "</tr>";


		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["agents"][8] . "</td>";
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
//			$chrs = 30;
//			$chaine = "";
//			$list = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
//			mt_srand((double)microtime()*1000000);
			$newstring="";
//			while( strlen( $newstring )< $chrs ) {
//				$newstring .= $list[mt_rand(0, strlen($list)-1)];
//			}

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
		global $DB;
	
		$fusioninventory_config = new PluginFusionInventoryConfig;
		$fusioninventory_config->getFromDB(1);

		$this->getFromDB($ID);
		echo "server=".$fusioninventory_config->fields["URL_agent_conf"]."/plugins/fusioninventory/front/plugin_fusioninventory.communication.php\n";
//		echo "id=".$ID."\n";
		echo "key=".$this->fields["key"]."\n";
	}



   function InfosByKey($key) {
      global $DB;

      $query = "SELECT * FROM `glpi_plugin_fusioninventory_agents`
      WHERE `key`='".$key."' LIMIT 1";

      $agent = array();
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            $agent = $DB->fetch_assoc($result);
         }
      }
      return $agent;
   }


   function RemoteStartAgent($ID, $ip) {
      $ptcm = new PluginFusionInventoryConfigModules;
      if (!$ptcm->isActivated('remotehttpagent')) {
         return false;
      }
      $this->getFromDB($ID);
      if(!($fp = fsockopen($ip, 62354, $errno, $errstr, 3))) {
         $input = 'Agent don\'t respond';
         addMessageAfterRedirect($input);
         return false;
      } else {
         $handle = fopen("http://".$ip.":62354/now/".$this->fields['token'], "r");
         $input = 'Agent run Now';
         fclose($fp);
         addMessageAfterRedirect($input);
         return true;
      }
      
   }

   function RemoteStateAgent($target, $ID) {
      global $LANG;

      $ptcm = new PluginFusionInventoryConfigModules;
      $np = new Netport;

      if (!$ptcm->isActivated('remotehttpagent')) {
         return;
      }
      $this->getFromDB($ID);
      
      echo "<div align='center'><form method='post' name='' id=''  action=\"" . $target . "\">";

		echo "<table  class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th colspan='2'>";
      echo $LANG['plugin_fusioninventory']["agents"][14];
      echo " : </th>";
      echo "</tr>";

      $a_data = $np->find("`on_device`='".$this->fields['on_device']."' AND `device_type`='".$this->fields['device_type']."'");
      foreach ($a_data as $port_id=>$port) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         if(!($fp = fsockopen($port['ifaddr'], 62354, $errno, $errstr, 1))) {
             echo $port['ifaddr']." : </td><td align='center'><b>".$LANG['plugin_fusioninventory']["task"][9]."</b>";
         } else {
            echo $port['ifaddr']." : </td><td align='center'><b>".$LANG['plugin_fusioninventory']["task"][8]."</b>";
            $ip = $port['ifaddr'];
            fclose($fp);
         }
         echo "</td>";
         echo "</tr>";
      }

      echo "<tr class='tab_bg_2'>";
		echo "<td align='center'>";
      echo "<input type='hidden' name='agentID' value='".$ID."'/>";
      echo "<input type='hidden' name='ip' value='".$ip."'/>";
      echo "<input type='submit' name='startagent' value=\"".$LANG['plugin_fusioninventory']["task"][12]."\" class='submit' >";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      echo "</form>";
      echo "</div>";      
   }


}

?>