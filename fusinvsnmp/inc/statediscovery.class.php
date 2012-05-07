<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvsnmpStateDiscovery extends CommonDBTM {
   

   function canView() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "task", "r");
   }


  
   function updateState($p_number, $a_input, $agent_id) {
      $data = $this->find("`plugin_fusioninventory_taskjob_id`='".$p_number."'
                              AND `plugin_fusioninventory_agents_id`='".$agent_id."'");
      if (count($data) == "0") {
         $input = array();
         $input['plugin_fusioninventory_taskjob_id'] = $p_number;
         $input['plugin_fusioninventory_agents_id'] = $agent_id;
         $id = $this->add($input);
         $this->getFromDB($id);
         $data[$id] = $this->fields;
      }
      
      foreach ($data as $process_id=>$input) {
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
      foreach ($data as $input) {
         $input['end_time'] = $date_end;
         $this->update($input);
      }
   }
   
   
   
   function display() {
      global $DB,$LANG,$CFG_GLPI;

      $pfAgent = new PluginFusioninventoryAgent();
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $pfStateInventory = new PluginFusinvsnmpStateInventory();

      $start = 0;
      if (isset($_REQUEST["start"])) {
         $start = $_REQUEST["start"];
      }

      // Total Number of events
      $querycount = "SELECT count(*) AS cpt FROM `glpi_plugin_fusioninventory_taskjobstates`
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobs` on `plugin_fusioninventory_taskjobs_id` = `glpi_plugin_fusioninventory_taskjobs`.`id`
         WHERE `method` = 'netdiscovery'
         GROUP BY `uniqid`
         ORDER BY `uniqid` DESC ";
      
     
      $resultcount = $DB->query($querycount);
      $number = $DB->numrows($resultcount);

      // Display the pager
      Html::printPager($start,$number,$CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/stateinventory.php",'');

      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th>".$LANG['plugin_fusioninventory']['task'][47]."</th>";
      echo "<th>".$LANG['plugin_fusioninventory']['agents'][28]."</th>";
      echo "<th>".$LANG['joblist'][0]."</th>";
      echo "<th>".$LANG['plugin_fusinvsnmp']['state'][4]."</th>";
      echo "<th>".$LANG['plugin_fusinvsnmp']['state'][5]."</th>";
      echo "<th>".$LANG['job'][20]."</th>";
      echo "<th>".$LANG['plugin_fusinvsnmp']['state'][6]."</th>";
      echo "<th>".$LANG['plugin_fusinvsnmp']['state'][8]."</th>";
      echo "<th>".$LANG['plugin_fusinvsnmp']['state'][9]."</th>";
      echo "<th>".$LANG['plugin_fusinvsnmp']['state'][10]."</th>";
      echo "</tr>";

      $sql = "SELECT `glpi_plugin_fusioninventory_taskjobstates`.*
            FROM `glpi_plugin_fusioninventory_taskjobstates`
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobs` on `plugin_fusioninventory_taskjobs_id` = `glpi_plugin_fusioninventory_taskjobs`.`id`
         WHERE `method` = 'netdiscovery'
         GROUP BY `uniqid`
         ORDER BY `uniqid` DESC
         LIMIT ".intval($start)."," . intval($_SESSION['glpilist_limit']);
      
      $result=$DB->query($sql);
      while ($data=$DB->fetch_array($result)) {
         echo "<tr class='tab_bg_1'>";
         echo "<td>".$data['uniqid']."</td>";
         $pfAgent->getFromDB($data['plugin_fusioninventory_agents_id']);
         echo "<td>".$pfAgent->getLink(1)."</td>";
         $nb_found = 0;
         $nb_threads = 0;
         $start_date = "";
         $end_date = "";
         $notimporteddevices= 0;
         $updateddevices = 0;
         $createddevices = 0;
         $a_taskjobstates = $pfTaskjobstate->find("`uniqid`='".$data['uniqid']."'");
         foreach ($a_taskjobstates as $datastate) {
            $a_taskjoblog = $pfTaskjoblog->find("`plugin_fusioninventory_taskjobstates_id`='".$datastate['id']."'");
            foreach($a_taskjoblog as $taskjoblog) {
               if (strstr($taskjoblog['comment'], " ==fusinvsnmp::2==")) {
                  $nb_found += str_replace(" ==fusinvsnmp::2==", "", $taskjoblog['comment']);
               } else if (strstr($taskjoblog['comment'], "==fusioninventory::3==")) {
                  $notimporteddevices++;
               } else if (strstr($taskjoblog['comment'], "==fusinvsnmp::5==")) {
                  $updateddevices++;
               } else if (strstr($taskjoblog['comment'], "==fusinvsnmp::4==")) {
                  $createddevices++;
               } else if ($taskjoblog['state'] == "1") {
                  $nb_threads = str_replace(" threads", "", $taskjoblog['comment']);
                  $start_date = $taskjoblog['date'];
               }

               if (($taskjoblog['state'] == "2")
                  OR ($taskjoblog['state'] == "3")
                  OR ($taskjoblog['state'] == "4")
                  OR ($taskjoblog['state'] == "5")) {

                  if (!strstr($taskjoblog['comment'], 'Merged with ')) {
                     $end_date = $taskjoblog['date'];
                  }
               }
            }
         }
         // State
         echo "<td>";
         switch ($data['state']) {

            case 0:
               echo $LANG['plugin_fusioninventory']['taskjoblog'][7];
               break;

            case 1:
            case 2:
               echo $LANG['plugin_fusioninventory']['taskjoblog'][1];
               break;

            case 3:
               echo $LANG['plugin_fusioninventory']['task'][20];
               break;

         }
         echo "</td>";

         echo "<td>".Html::convDateTime($start_date)."</td>";
         echo "<td>".Html::convDateTime($end_date)."</td>";

         if ($end_date == '') {
            $end_date = date("Y-m-d H:i:s");
         }
         if ($start_date == '') {
            echo "<td>-</td>";
         } else {
            $interval = '';
            if (phpversion() >= 5.3) {
               $date1 = new DateTime($start_date);
               $date2 = new DateTime($end_date);
               $interval = $date1->diff($date2);
               $display_date = '';
               if ($interval->h > 0) {
                  $display_date .= $interval->h."h ";
               } else if ($interval->i > 0) {
                  $display_date .= $interval->i."min ";
               }
               echo "<td>".$display_date.$interval->s."s</td>";
            } else {
               $interval = $pfStateInventory->date_diff($start_date, $end_date);
            }
         }
         echo "<td>".$nb_found."</td>";
         echo "<td>".$notimporteddevices."</td>";
         echo "<td>".$updateddevices."</td>";
         echo "<td>".$createddevices."</td>";
         echo "</tr>";      
      }
      echo "</table>";
   }
}

?>