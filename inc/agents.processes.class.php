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

class PluginTrackerAgentsProcesses extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_tracker_agents_processes";
		$this->type = PLUGIN_TRACKER_AGENTS_PROCESSES;
	}


	/* Function to get the value of a field */
	function getValue($field,$ID) {
		global $DB;

		$query = "SELECT ".$field."
                FROM ".$this->table."
                WHERE `ID` = '".$ID."';";
		if ($result = $DB->query($query)) {
			if ($this->fields = $DB->fetch_row($result)) {
				return $this->fields['0'];
         }
		}
		return false;
	}


   function defineTabs($ID,$withtemplate) {
		global $LANG,$CFG_GLPI;

		$ong[1]=$LANG['plugin_tracker']["processes"][19];
      $ong[2]=$LANG['plugin_tracker']["title"][2];
		$ong[3]=$LANG['plugin_tracker']["errors"][0];

		return $ong;
	}


   function ShowHeader() {
      global $LANG;
      
      echo "<tr class='tab_bg_1'>";

      echo "<th rowspan='2'>";
      echo $LANG['plugin_tracker']["processes"][1];
      echo "</th>";

      echo "<th rowspan='2'>";
      echo $LANG['plugin_tracker']["processes"][25];
      echo "</th>";

      echo "<th rowspan='2'>";
      echo $LANG['plugin_tracker']["processes"][2];
      echo "</th>";

      echo "<th rowspan='2'>";
      echo $LANG['plugin_tracker']["processes"][4];
      echo "</th>";

      echo "<th rowspan='2'>";
      echo $LANG['plugin_tracker']["processes"][10];
      echo "</th>";

      echo "<th height='28'>";
      echo $LANG['plugin_tracker']["processes"][26].">>";
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][4];
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][10];
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][28];
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][29];
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][37];
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][30]." / <br/>".$LANG['plugin_tracker']["processes"][34];
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][31];
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][32];
      echo "</th>";

      echo "</tr>";

      echo "<tr class='tab_bg_1'>";

      echo "<th height='28'>";
      echo $LANG['plugin_tracker']["processes"][27].">>";
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][4];
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][10];
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][28];
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][29];
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][33];
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][34];
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][35];
      echo "</th>";

      echo "<th>";
      echo $LANG['plugin_tracker']["processes"][36];
      echo "</th>";

      echo "</tr>";
   }

   function ShowProcesses() {
      global $DB,$LANG;

      $ci = new commonitem;

      echo "<table class='tab_cadre' cellpadding='5' width='1150'>";
      $this->ShowHeader();
      $i = 0;
      $query = "SELECT * FROM `".$this->table."`
         ORDER BY `process_number` DESC";
		if ($result = $DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            $i++;
            if ($i == '8') {
               echo "<tr class='tab_bg_2'>";
               echo "<td colspan='14' height='5'></td>";
               echo "</tr>";
               $this->ShowHeader();
               $i = 0;
            }
            echo "<tr class='tab_bg_2'>";
            echo "<td colspan='14' height='5'></td>";
            echo "</tr>";

            echo "<tr class='tab_bg_1 center'>";

            echo "<td rowspan='2'>";
            echo $data['process_number'];
            echo "</td>";

            echo "<td rowspan='2'>";
            $ci->getFromDB(PLUGIN_TRACKER_SNMP_AGENTS,$data['FK_agent']);
				echo $ci->getLink(1);
            echo "</td>";

            echo "<td rowspan='2'>";
				switch($data['status']) {
					case 3 :
						echo "<img src='../pics/export.png' />";
						break;

					case 2 :
						echo "<img src='../pics/wait.png' />";
						break;

					case 1 :
						echo "<img src='../pics/ok2.png' />";
						break;
				}
            echo "</td>";

            echo "<td rowspan='2'>";
            echo convDateTime($data['start_time']);
            echo "</td>";

            echo "<td rowspan='2'>";
            if (($data['start_time'] != '0000-00-00 00:00:00') AND
                    ($data['end_time'] != '0000-00-00 00:00:00')) {

               $duree_timestamp = strtotime($data['end_time']) - strtotime($data['start_time']);
               echo timestampToString($duree_timestamp);
            } else {
               echo '-';
            }
            echo "</td>";

            echo "<td height='28'>";
            if ($data['start_time_discovery'] != '0000-00-00 00:00:00') {
               if ($data['end_time_discovery'] != '0000-00-00 00:00:00') {
                  echo "<img src='../pics/export.png' />";
               } else {
                  echo "<img src='../pics/wait.png' />";
               }
            } else if($data['discovery_core'] != '0') {
               echo "<img src='../pics/ok2.png' />";
            }
            echo "</td>";

            echo "<td>";
            if ($data['start_time_discovery'] != '0000-00-00 00:00:00') {
               echo convDateTime($data['start_time_discovery']);
            } else {
               echo '-';
            }
            echo "</td>";

            echo "<td>";
            if (($data['start_time_discovery'] != '0000-00-00 00:00:00') AND
                    ($data['end_time_discovery'] != '0000-00-00 00:00:00')) {

               $duree_timestamp = strtotime($data['end_time_discovery']) - strtotime($data['start_time_discovery']);
               echo timestampToString($duree_timestamp);
            } else {
               echo '-';
            }
            echo "</td>";

            echo "<td>";
            echo $data['discovery_core'];
            echo "</td>";

            echo "<td>";
            echo $data['discovery_threads'];
            echo "</td>";

            echo "<td>";
            echo $data['discovery_nb_ip'];
            echo "</td>";
            
            echo "<td>";
            echo $data['discovery_nb_found'];
            if ($data['discovery_nb_error'] > 0) {
               echo " / <a href='".GLPI_ROOT."/plugins/tracker/front/plugin_tracker.agents.processes.form.php?process_number=".$data['process_number']."&agent_type=NETDISCOVERY'>
                  <font color='#ff0000'>".$data['discovery_nb_error']."</font></a>";
            } else {
               echo " / ".$data['discovery_nb_error'];
            }
            echo "</td>";

            echo "<td>";
            echo $data['discovery_nb_exists'];
            echo "</td>";

            echo "<td>";
            echo $data['discovery_nb_import'];
            echo "</td>";

            echo "</tr>";

            echo "<tr class='tab_bg_1 center'>";

            echo "<td height='28'>";
            if ($data['start_time_query'] != '0000-00-00 00:00:00') {
               if ($data['end_time_query'] != '0000-00-00 00:00:00') {
                  echo "<img src='../pics/export.png' />";
               } else {
                  echo "<img src='../pics/wait.png' />";
               }
            } else if($data['query_core'] != '0') {
               echo "<img src='../pics/ok2.png' />";
            }
            echo "</td>";

            echo "<td>";
            if ($data['start_time_query'] != '0000-00-00 00:00:00') {
               echo convDateTime($data['start_time_query']);
            } else {
               echo '-';
            }
            echo "</td>";

            echo "<td>";
            if (($data['start_time_query'] != '0000-00-00 00:00:00') AND
                    ($data['end_time_query'] != '0000-00-00 00:00:00')) {

               $duree_timestamp = strtotime($data['end_time_query']) - strtotime($data['start_time_query']);
               echo timestampToString($duree_timestamp);
            } else {
               echo '-';
            }
            echo "</td>";

            echo "<td>";
            echo $data['query_core'];
            echo "</td>";

            echo "<td>";
            echo $data['query_threads'];
            echo "</td>";

            echo "<td>";
            echo $data['query_nb_query'];
            echo "</td>";

            echo "<td>";
            if ($data['query_nb_error'] > 0) {
               echo "<a href='".GLPI_ROOT."/plugins/tracker/front/plugin_tracker.agents.processes.form.php?process_number=".$data['process_number']."&agent_type=SNMPQUERY'>
                  <font color='#ff0000'>".$data['query_nb_error']."</font></a>";
            } else {
               echo $data['query_nb_error'];
            }
            echo "</td>";

            echo "<td>";
            if ($data['query_nb_connections_created'] > 0) {
               echo "<a href='".GLPI_ROOT."/plugins/tracker/front/plugin_tracker.agents.processes.form.php?process_number=".$data['process_number']."&amp;created=1'>
                  ".$data['query_nb_connections_created']."</a>";
            } else {
               echo $data['query_nb_connections_created'];
            }
            echo "</td>";

            echo "<td>";
            if ($data['query_nb_connections_deleted'] > 0) {
               echo "<a href='".GLPI_ROOT."/plugins/tracker/front/plugin_tracker.agents.processes.form.php?process_number=".$data['process_number']."&amp;created=0'>
                  ".$data['query_nb_connections_deleted']."</a>";
            } else {
               echo $data['query_nb_connections_deleted'];
            }
            echo "</td>";

            echo "</tr>";


         }
      }
      echo "<tr class='tab_bg_2'>";
      echo "<td colspan='14' height='5'></td>";
      echo "</tr>";
      echo "</table>";
   }



   function addProcess($pxml) {

      $pta = new PluginTrackerAgents;
      
      $agent = $pta->InfosByKey($pxml->DEVICEID);

      $input['FK_agent'] = $agent["ID"];
      $input['process_number'] = time()."/".sprintf('%03d', $input['FK_agent']);
      $input['status'] = 2;
      $input['start_time'] = date("Y-m-d H:i:s");

      $process_id = $this->add($input);
      
      return $input['process_number'];
   }

   /**
    * Update process with informations
    *
    *@param $p_number Process number to update
    *@param $a_input Array of values to update
    * 
    *@return nothing
    **/
   function updateProcess($p_number, $a_input) {
      $data = $this->find("`process_number`='".$p_number."'");
      foreach ($data as $process_id=>$dataInfos) {
         $input['ID'] = $process_id;
         foreach ($a_input as $field=>$value) {
            if ($field == 'discovery_nb_found'
                    || $field == 'discovery_nb_exists'
                    || $field == 'discovery_nb_import'
                    || $field == 'query_nb_query'
                    || $field == 'query_nb_error'
                    || $field == 'query_nb_connections_created'
                    || $field == 'query_nb_connections_deleted'
                    || $field == 'discovery_nb_ip') {

                $input[$field] = $data[$process_id][$field] + $value;
             } else {
                $input[$field] = $value;
            }
         }
         $this->update($input);
      }
   }

   
   function endProcess($p_number, $date_end) {
      $data = $this->find("`process_number`='".$p_number."'");
      foreach ($data as $process_id=>$dataInfos) {
         $input['ID'] = $process_id;
         $input['end_time'] = $date_end;
         $input['status'] = '3';
         $this->update($input);
      }
   }


   function CleanProcesses() {
      $ptc = new PluginTrackerConfig;
      $data = $this->find("`start_time`<DATE_SUB(NOW(), INTERVAL ".$ptc->getValue('delete_agent_process')." HOUR)");
      foreach ($data as $process_id=>$dataInfos) {
         $this->deleteFromDB($process_id,1);
      }
      
   }

}

?>