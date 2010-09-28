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

class PluginFusinvsnmpStateDiscovery extends CommonDBTM {
   
   function __construct() {
      $this->table = "glpi_plugin_fusinvsnmp_state_discovery";
      $this->type = 'PluginFusinvsnmpStateDiscovery';
   }


   function displayState() {
      global $DB,$CFG_GLPI,$LANG;

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      
      echo "<th>";
      echo $LANG['plugin_fusioninventory']["agents"][28];
      echo "</th>";

      echo "<th>";
      echo "State";
      echo "</th>";

      echo "<th>";
      echo "Start_date";
      echo "</th>";

      echo "<th>";
      echo "end_date";
      echo "</th>";

      echo "<th>";
      echo "threads";
      echo "</th>";

      echo "<th>";
      echo "IP total";
      echo "</th>";

      echo "<th>";
      echo "Total discovery";
      echo "</th>";

      echo "<th>";
      echo "Total in error";
      echo "</th>";

      echo "<th>";
      echo "Devices existent";
      echo "</th>";

      echo "<th>";
      echo "devices imported";
      echo "</th>";

      echo "<th>";
      echo "task job";
      echo "</th>";
      echo "</tr>";


      $query = "SELECT `glpi_plugin_fusioninventory_taskjobs`.*, `glpi_plugin_fusinvsnmp_state_discovery`.* FROM `glpi_plugin_fusinvsnmp_state_discovery`
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobs` on `plugin_fusioninventory_taskjob_id`=`glpi_plugin_fusioninventory_taskjobs`.`id`
         WHERE `status`!=0
            AND `method`='netdiscovery'
         GROUP BY `plugin_fusioninventory_taskjob_id`
         ORDER BY `glpi_plugin_fusinvsnmp_state_discovery`.`date_mod` DESC";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         
         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         echo Dropdown::getDropdownName('glpi_plugin_fusioninventory_agents', $data['plugin_fusioninventory_agents_id']);
         echo "</td>";
         
         echo "<td>";
         echo $data['status'];
         echo "</td>";
         echo "<td>";

         echo "</td>";
         echo "<td>";
         echo "</td>";

         echo "<td>";
         echo $data['threads'];
         echo "</td>";

         echo "<td>";
         echo $data['nb_ip'];
         echo "</td>";

         echo "<td>";
         echo $data['nb_found'];
         echo "</td>";

         echo "<td>";
         echo $data['nb_error'];
         echo "</td>";

         echo "<td>";
         echo $data['nb_exists'];
         echo "</td>";

         echo "<td>";
         echo $data['nb_import'];
         echo "</td>";

         echo "<td>";
         echo $data['id'];
         echo "</td>";
         echo "</tr>";
         
      }
      echo "</table>";
      // Taskjob id=4
         // agent | State | Start_date | end_date | threads | IP total | Total discovery | Total in error | Devices existent | devices mported
         // agent | nb_ip | nb_ip_ok | nb_iperror ...
         // agent | nb_ip | nb_ip_ok | nb_iperror ...
         //-------------------------------------------
         // Resume | nb_ip | nb_ip_ok | nb_iperror ...



   }



   function updateState($p_number, $a_input, $agent_id) {
      $data = $this->find("`plugin_fusioninventory_taskjob_id`='".$p_number."'
                              AND `plugin_fusioninventory_agents_id`='".$agent_id."'");
      if (count($data) == "0") {
         $input = array();
         $input['plugin_fusioninventory_taskjob_id'] = $p_number;
         $input['plugin_fusioninventory_agents_id'] = $agent_id;
         $id = $this->add($input);
         $data[$id] = $this->getFromDB($id);
      }
      
      foreach ($data as $process_id=>$dataInfos) {
         $input['id'] = $process_id;
         foreach ($a_input as $field=>$value) {
            if ($field == 'nb_ip'
                    || $field == 'nb_found'
                    || $field == 'nb_error'
                    || $field == 'nb_exists'
                    || $field == 'nb_import') {

                $input[$field] = $data[$process_id][$field] + $value;
             } else {
                $input[$field] = $value;
            }
         }
         $this->update($input);
      }
      // If discovery and query are finished, we will end Process
      $this->getFromDB($process_id);
      $doEnd = 1;
      if (($this->fields['threads'] != '0') AND ($this->fields['end_time'] == '0000-00-00 00:00:00')) {
         $doEnd = 0;
      }

      if ($doEnd == '1') {
         $this->endState($p_number, date("Y-m-d H:i:s"), $agent_id);
      }
   }


   function endState($p_number, $date_end, $agent_id) {
      $data = $this->find("`plugin_fusioninventory_taskjob_id`='".$p_number."'
                              AND `plugin_fusioninventory_agents_id`='".$agent_id."'");
      foreach ($data as $process_id=>$dataInfos) {
         $input = array();
         $input['end_time'] = $date_end;
         $this->update($input);
      }
   }

}

?>