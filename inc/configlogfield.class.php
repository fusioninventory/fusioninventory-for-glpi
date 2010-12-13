<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
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


class PluginFusinvsnmpConfigLogField extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_fusinvsnmp_configlogfields";
	}

   /**
    * Init config log fields : add default values in table
    *
    *@return nothing
    **/
	function initConfig() {
      global $DB,$CFG_GLPI;
      
      $NOLOG = '-1';
      $logs = array();
      $logs['NetworkEquipment']['ifdescr'] = $NOLOG;
      $logs['NetworkEquipment']['ifIndex'] = $NOLOG;
      $logs['NetworkEquipment']['ifinerrors'] = $NOLOG;
      $logs['NetworkEquipment']['ifinoctets'] = $NOLOG;
      $logs['NetworkEquipment']['ifinternalstatus'] = $NOLOG;
      $logs['NetworkEquipment']['iflastchange'] = $NOLOG;
      $logs['NetworkEquipment']['ifmtu'] = $NOLOG;
      $logs['NetworkEquipment']['ifName'] = $NOLOG;
      $logs['NetworkEquipment']['ifouterrors'] = $NOLOG;
      $logs['NetworkEquipment']['ifoutoctets'] = $NOLOG;
      $logs['NetworkEquipment']['ifspeed'] = $NOLOG;
      $logs['NetworkEquipment']['ifstatus'] = $NOLOG;
      $logs['NetworkEquipment']['macaddr'] = $NOLOG;
      $logs['NetworkEquipment']['portDuplex'] = $NOLOG;
      $logs['NetworkEquipment']['vlanTrunkPortDynamicStatus'] = $NOLOG;

      $logs['Printer']['ifIndex'] = $NOLOG;
      $logs['Printer']['ifName'] = $NOLOG;

      $mapping = new PluginFusioninventoryMapping();
      foreach ($logs as $itemtype=>$fields){
         foreach ($fields as $name=>$value){
            $input = array();
            $mapfields = $mapping->get($itemtype, $name);
            if ($mapfields != false) {
               $input['plugin_fusioninventory_mappings_id'] = $mapfields['id'];
               $input['days']  = $value;
               $this->add($input);
            }
         }
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

		
	function showForm($options=array()) {
		global $LANG,$DB;

//      $this->showFormHeader($options);
      echo "<form name='form' method='post' action='".$options['target']."'>";
      echo "<div class='center' id='tabsbody'>";
      echo "<table class='tab_cadre_fixe'>";

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

      $query = "SELECT `".$this->table."`.`id`, `locale`, `days`, `itemtype`, `name`
                FROM `".$this->table."`, `glpi_plugin_fusioninventory_mappings`
                WHERE `".$this->table."`.`plugin_fusioninventory_mappings_id`=
                         `glpi_plugin_fusioninventory_mappings`.`id`
                ORDER BY `itemtype`, `name`;";
      if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            echo "<tr class='tab_bg_1'>";
            echo "<td align='left'>";
            echo $LANG['plugin_fusioninventory']["mapping"][$data['locale']];
            echo "</td>";

            echo "<td align='center'>";
            Dropdown::showFromArray('field-'.$data['id'], $days,
                                    array('value'=>$data['days']));
            echo "</td>";
            echo "</tr>";
         }
      }

//      $this->showFormButtons($options);
      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration", "w")) {
         echo "<tr class='tab_bg_2'><td align='center' colspan='4'>
               <input class='submit' type='submit' name='plugin_fusinvsnmp_configlogfield_set'
                      value='" . $LANG['buttons'][7] . "'></td></tr>";
      }
      echo "</table>";

      echo "<br/>";
      echo "<table class='tab_cadre_fixe' cellpadding='2'>";
      echo "<tr class='tab_bg_2'>";
      echo "<td colspan='1' class='center' height='30'>";
      if (PluginFusioninventoryProfile::haveRight('fusioninventory',"configuration","w")) {
         echo "<input type='submit' class=\"submit\" name='Clean_history' value='".$LANG['buttons'][53]."' >";
      }
      echo "</td>";
      echo "</tr>";
      echo "</table></div></form>";

      return true;
	}

   function putForm($p_post) {
      foreach ($p_post as $field=>$log) {
         if (substr($field, 0, 6) == 'field-') {
            $input['id'] = substr($field, 6);
            $input['days'] = $log;
            $this->update($input);
         }
      }
   }
}

?>