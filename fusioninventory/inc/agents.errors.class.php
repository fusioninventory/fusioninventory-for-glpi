<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginFusionInventoryAgentsErrors extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_fusioninventory_agents_errors";
	}


   function ShowErrors($input='') {
      global $DB,$LANG;

      $ci = new commonitem;

      echo "<table class='tab_cadre' cellpadding='5' width='950'>";
      echo "<tr class='tab_bg_1'>";

      echo "<th>";
      echo $LANG['plugin_fusioninventory']["processes"][1];
      echo " <a href='".GLPI_ROOT."/plugins/fusioninventory/front/plugin_fusioninventory.agents.processes.form.php'>(".$LANG['common'][66].")</a>";
      echo "</th>";

      echo "<th>";
      echo $LANG['common'][27];
      echo "</th>";

      echo "<th>";
      echo $LANG['common'][17];
      echo "</th>";

      echo "<th>";
      echo $LANG['common'][1];
      echo "</th>";
      
      echo "<th>";
      echo $LANG['plugin_fusioninventory']["errors"][104];
      echo "</th>";

      echo "</tr>";

      $condition = "";
      if (isset($input['process_number'])) {
         $condition = "WHERE `process_number`='".$input['process_number']."'";
         if (isset($input['agent_type'])) {
            $condition .= " AND agent_type='".$input['agent_type']."' ";
         }
      }
      $query = "SELECT * FROM `".$this->table."`
         ".$condition."
         ORDER BY `process_number` DESC";
		if ($result = $DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {

            echo "<tr class='tab_bg_1 center'>";

            echo "<td>";
            echo $data['process_number'];
            echo "</td>";

            echo "<td>";
            echo convDateTime($data['date']);
            echo "</td>";

            echo "<td>";
            if ($data['agent_type'] == "SNMPQUERY") {
               echo $LANG['plugin_fusioninventory']["processes"][27];
            } else if ($data['agent_type'] == "NETDISCOVERY") {
               echo $LANG['plugin_fusioninventory']["processes"][26];
            }
            echo "</td>";

            echo "<td>";
            $ci->getFromDB($data['device_type'],$data['on_device']);
				echo $ci->getLink(1);
            echo "</td>";

            echo "<td>";
            echo $data['error_message'];
            echo "</td>";

            echo "</tr>";
         }
      }
      echo "</table>";
   }



   function addError($a_input) {

      $input['on_device'] = $a_input['ID'];
      if (!isset($a_input['TYPE'])) {
         $a_input['TYPE'] = 0;
      }
      $input['device_type'] = $a_input['TYPE'];
      $input['process_number'] = $_SESSION['glpi_plugin_fusioninventory_processnumber'];
      $input['error_message'] = htmlentities($a_input['MESSAGE']);
      $input['agent_type'] = $a_input['agent_type'];
      $input['date'] = date("Y-m-d H:i:s");

      $this->add($input);
   }


   function CleanErrors() {
      $ptc = new PluginFusionInventoryConfig;
      $data = $this->find("`date`<DATE_SUB(NOW(), INTERVAL ".$ptc->getValue('delete_agent_process')." HOUR)");
      foreach ($data as $process_id=>$dataInfos) {
         $this->deleteFromDB($process_id,1);
      }
   }


}

?>