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

class PluginFusioninventoryRangeIP extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_fusioninventory_rangeip";
		$this->type = PLUGIN_FUSIONINVENTORY_SNMP_RANGEIP;
	}


	function showForm($id, $options=array()) {
		global $DB,$CFG_GLPI,$LANG;

		if ($id!='') {
			$this->getFromDB($id);
      } else {
			$this->getEmpty();
      }
		$this->showTabs($options);
      $this->showFormHeader($options);

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG["common"][16] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' value='".$this->fields["name"]."'/>";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["rangeip"][0] . "</td>";
		echo "<td align='center'>";
      if (empty($this->fields["ifaddr_start"]))
         $this->fields["ifaddr_start"] = "...";
      $ipexploded = explode(".", $this->fields["ifaddr_start"]);
      $i = 0;
      foreach ($ipexploded as $ipnum) {
         if ($ipnum > 255) {
            $ipexploded[$i] = '';
         }
         $i++;
      }
		echo "<input type='text' value='".$ipexploded[0]."' name='ifaddr_start0' size='3' maxlength='3' >.";
		echo "<input type='text' value='".$ipexploded[1]."' name='ifaddr_start1' size='3' maxlength='3' >.";
		echo "<input type='text' value='".$ipexploded[2]."' name='ifaddr_start2' size='3' maxlength='3' >.";
		echo "<input type='text' value='".$ipexploded[3]."' name='ifaddr_start3' size='3' maxlength='3' >";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["rangeip"][1] . "</td>";
		echo "<td align='center'>";
      unset($ipexploded);
      if (empty($this->fields["ifaddr_end"]))
         $this->fields["ifaddr_end"] = "...";
      $ipexploded = explode(".", $this->fields["ifaddr_end"]);
      $i = 0;
      foreach ($ipexploded as $ipnum) {
         if ($ipnum > 255) {
            $ipexploded[$i] = '';
         }
         $i++;
      }
		echo "<input type='text' value='".$ipexploded[0]."' name='ifaddr_end0' size='3' maxlength='3' >.";
		echo "<input type='text' value='".$ipexploded[1]."' name='ifaddr_end1' size='3' maxlength='3' >.";
		echo "<input type='text' value='".$ipexploded[2]."' name='ifaddr_end2' size='3' maxlength='3' >.";
		echo "<input type='text' value='".$ipexploded[3]."' name='ifaddr_end3' size='3' maxlength='3' >";
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["agents"][12] . "</td>";
		echo "<td align='center'>";
		Dropdown::show("PluginFusioninventoryAgents",
                     array('name'=>"plugin_fusioninventory_agents_id_discover",
                           'value'=>$this->fields["plugin_fusioninventory_agents_id_discover"],
                           'comment'=>false));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["agents"][13] . "</td>";
		echo "<td align='center'>";
		Dropdown::show("PluginFusioninventoryAgents",
                     array('name'=>"plugin_fusioninventory_agents_id_query",
                           'value'=>$this->fields["plugin_fusioninventory_agents_id_query"],
                           'comment'=>false));
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["discovery"][3] . "</td>";
		echo "<td align='center'>";
		Dropdown::showYesNo("discover",$this->fields["discover"]);
		echo "</td>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusioninventory']["rangeip"][3] . "</td>";
		echo "<td align='center'>";
		Dropdown::showYesNo("query",$this->fields["query"]);
		echo "</td>";
		echo "</tr>";

      if (isMultiEntitiesMode()) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>".$LANG['entity'][0]."</td>";
         echo "<td align='center'>";
         Dropdown::show('Entity',
                        array('name'=>'entities_id',
                              'value'=>$this->fields["entities_id"]));
         echo "</td>";
         echo "</tr>";
      }

		$this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
	}



   function Counter($agent_id, $type) {
      global $DB;
      
      $count = 0;
      switch ($type) {

         case "discover":
            $query = "SELECT COUNT(*) as count FROM `glpi_plugin_fusioninventory_rangeip`
               WHERE `plugin_fusioninventory_agents_id_discover`='".$agent_id."'
                  AND `discover`='1' ";
            break;

         case "query":
            $query = "SELECT COUNT(*) as count FROM `glpi_plugin_fusioninventory_rangeip`
               WHERE `plugin_fusioninventory_agents_id_query`='".$agent_id."'
                  AND `query`='1' ";
            break;

      }

      if ($result = $DB->query($query)) {
         $res = $DB->fetch_assoc($result);
         $count = $res["count"];
      }
      return $count;
   }


   function ListRange($agent_id, $type) {
      global $DB;

      $ranges = array();
      switch ($type) {

         case "discover":
            $query = "SELECT * FROM `glpi_plugin_fusioninventory_rangeip`
               WHERE `plugin_fusioninventory_agents_id_discover`='".$agent_id."'
                  AND `discover`='1' ";
            break;

         case "query":
            $query = "SELECT * FROM `glpi_plugin_fusioninventory_rangeip`
               WHERE `plugin_fusioninventory_agents_id_query`='".$agent_id."'
                  AND `query`='1' ";
            break;

      }
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            while ($data=$DB->fetch_array($result)) {
               $ranges[$data["id"]] = $data;
            }
         }
      }
      return $ranges;
   }


   function checkip($a_input) {
      global $LANG;

      $count = 0;
      foreach ($a_input as $num=>$value) {
         if (strstr($num, "ifaddr_")) {
            if (($value>255) OR (!is_numeric($value)) OR strstr($value, ".")) {
               $count++;
               print $num;
               $a_input[$num] = "<font color='#ff0000'>".$a_input[$num]."</font>";
            }
         }
      }

      if ($count == '0') {
         return true;
      } else {
         addMessageAfterRedirect("<font color='#ff0000'>IP incorrecte</font><br/>".
            $LANG['plugin_fusioninventory']["rangeip"][0]." : ".$a_input['ifaddr_start0'].".".$a_input['ifaddr_start1'].".".$a_input['ifaddr_start2'].".".$a_input['ifaddr_start3']."<br/>".
            $LANG['plugin_fusioninventory']["rangeip"][1]." : ".$a_input['ifaddr_end0'].".".$a_input['ifaddr_end1'].".".$a_input['ifaddr_end2'].".".$a_input['ifaddr_end3']);
         return false;
      }
   }
}

?>