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


class PluginFusionInventoryConfigSNMPHistory extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_fusioninventory_config_snmp_history";
	}

	function initConfig() {
		global $DB,$CFG_GLPI;
      // Add all values

      $rights = array();
      $rights['ifmtu'] = '-1';
      $rights['ifdescr'] = '-1';
      $rights['ifinerrors'] = '-1';
      $rights['ifinoctets'] = '-1';
      $rights['ifinternalstatus'] = '-1';
      $rights['iflastchange'] = '-1';
      $rights['ifName'] = '-1';
      $rights['ifouterrors'] = '-1';
      $rights['ifoutoctets'] = '-1';
      $rights['ifspeed'] = '-1';
      $rights['ifstatus'] = '-1';
//      $rights['ifnumber'] = '-1';
//      $rights['mac'] = '-1';
      $rights['trunk'] = '-1';
      $rights['vlanTrunkPortDynamicStatus'] = '-1';
      $rights['portDuplex'] = '-1';
      $rights['ifIndex'] = '-1';
      $rights['macaddr'] = '-1';

      foreach ($rights as $field=>$value){
         $input = array();
         $input['field'] = $field;
         $input['days']  = $value;
         $this->add($input);
      }
	}
	


	function getValue($field) {
		global $DB;

		$query = "SELECT days
                FROM ".$this->table."
                WHERE `field`='".$field."'
                LIMIT 1;";
		if ($result = $DB->query($query)) {
			if ($this->fields = $DB->fetch_row($result)) {
				return $this->fields['0'];
         }
		}
		return false;
	}


   function updateTrackertoFusion() {
      global $DB;

      // Fields to history
      $rights = array();
      $rights['ifmtu'] = '-1';
      $rights['ifdescr'] = '-1';
      $rights['ifinerrors'] = '-1';
      $rights['ifinoctets'] = '-1';
      $rights['ifinternalstatus'] = '-1';
      $rights['iflastchange'] = '-1';
      $rights['ifName'] = '-1';
      $rights['ifouterrors'] = '-1';
      $rights['ifoutoctets'] = '-1';
      $rights['ifspeed'] = '-1';
      $rights['ifstatus'] = '-1';
//      $rights['ifnumber'] = '-1';
//      $rights['mac'] = '-1';
//      $rights['trunk'] = '-1';
      $rights['vlanTrunkPortDynamicStatus'] = '-1';
      $rights['portDuplex'] = '-1';
      $rights['ifIndex'] = '-1';
      $rights['macaddr'] = '-1';

      $query = "SELECT *
                FROM ".$this->table.";";
      if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            $val = str_replace("2-", "", $data['field']);
            if (isset($rights[$val])) {
               $rights[$val] = '0';
            }
         }
      }

      $query = "TRUNCATE TABLE `".$this->table."`";
      $DB->query($query);

      // Add rights in DB
      foreach ($rights as $field=>$value){
         $input = array();
         $input['field'] = $field;
         $input['days']  = $value;
         $this->add($input);
      }
      
   }


		
	function showForm($target) {
      include (GLPI_ROOT . "/plugins/fusioninventory/inc_constants/plugin_fusioninventory.snmp.mapping.constant.php");
      
		global $LANG,$DB,$FUSIONINVENTORY_MAPPING;

      echo "<form method='post' name='functionalities_form' id='functionalities_form' action='".$target."'>";
		echo "<table class='tab_cadre_fixe' cellpadding='2'>";

		echo "<tr>";
		echo "<th colspan='2'>";
		echo $LANG['plugin_fusioninventory']["functionalities"][28]." :";
		echo "</th>";
		echo "</tr>";

		echo "<tr>";
		echo "<th>";
		echo $LANG['plugin_fusioninventory']["functionalities"][29];
		echo "</th>";
		echo "<th>";
		echo $LANG['plugin_fusioninventory']["functionalities"][9];
		echo "</th>";
		echo "</tr>";

      $days = array();
      $days[-1] = 'Jamais';
      $days[0]  = 'Toujours';
      for ($i = 1 ; $i < 366 ; $i++) {
         $days[$i]  = "$i";
      }

      $query = "SELECT *
                FROM ".$this->table.";";
      if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            echo "<tr class='tab_bg_1'>";
            echo "<td align='left'>";
            echo $FUSIONINVENTORY_MAPPING[NETWORKING_TYPE][$data['field']]['name'];
            echo "</td>";

            echo "<td align='center'>";
            Dropdown::showFromArray($data['ID'], $days,
                                    array('value'=>$data['days']));
            echo "</td>";
            echo "</tr>";
         }
      }

      echo "<tr class='tab_bg_2'>";
      echo "<td align='center' colspan='2'>";
      if (PluginFusioninventory::haveRight("configuration","w")) {
   		echo "<input type='hidden' name='tabs' value='history' />";
   		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][2]."\" class='submit' >";
      }
      echo "</td>";
      echo "</tr>";
		echo "</table>";

      echo "<br/>";
      echo "<table class='tab_cadre_fixe' cellpadding='2'>";
      echo "<tr class='tab_bg_2'>";
      echo "<td colspan='1' class='center' height='30'>";
      if (PluginFusioninventory::haveRight("configuration","w")) {
         echo "<input type='submit' class=\"submit\" name='Clean_history' value='".$LANG['buttons'][53]."' >";
      }
      echo "</td>";
      echo "</tr>";
      echo "</table>";

      echo "</form>";

	}
}

?>