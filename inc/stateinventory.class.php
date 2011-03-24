<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvsnmpStateInventory extends CommonDBTM {

   function __construct() {
      global $CFG_GLPI;

      $CFG_GLPI['glpitablesitemtype']['PluginFusinvsnmpStateInventory'] = 'glpi_plugin_fusioninventory_taskjobstatus';
   }


   function canView() {
      return true;
   }



   function display() {
      global $DB,$LANG;

      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();

      $start = 0;
      if (isset($_REQUEST["start"])) {
         $start = $_REQUEST["start"];
      }

      // Total Number of events
      $querycount = "SELECT count(*) AS cpt FROM `glpi_plugin_fusioninventory_taskjobstatus`
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobs` on `plugin_fusioninventory_taskjobs_id` = `glpi_plugin_fusioninventory_taskjobs`.`id`
         WHERE `method` = 'snmpinventory'
         GROUP BY `uniqid`
         ORDER BY `uniqid` DESC ";
      $resultcount = $DB->query($querycount);
      $number = $DB->numrows($resultcount);

      // Display the pager
      printPager($start,$number,GLPI_ROOT."/plugins/fusinvsnmp/front/stateinventory.php",'');

      echo "<table class='tab_cadre_fixe'>";

		echo "<tr class='tab_bg_1'>";
      echo "<th>uniqid</th>";
      echo "<th>agent</th>";
      echo "<th>state</th>";
      echo "<th>startdate</th>";
      echo "<th>enddate</th>";
      echo "<th>totaltime execution</th>";
      echo "<th>ratio nb/s</th>";
      echo "<th>nbthreads</th>";
      echo "<th>nb query</th>";
      echo "<th>nb error</th>";
      echo "</tr>";

      $sql = "SELECT `glpi_plugin_fusioninventory_taskjobstatus`.*
            FROM `glpi_plugin_fusioninventory_taskjobstatus`
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobs` on `plugin_fusioninventory_taskjobs_id` = `glpi_plugin_fusioninventory_taskjobs`.`id`
         WHERE `method` = 'snmpinventory'
         GROUP BY `uniqid`
         ORDER BY `uniqid` DESC
         LIMIT ".intval($start)."," . intval($_SESSION['glpilist_limit']);
      $result=$DB->query($sql);
      while ($data=$DB->fetch_array($result)) {
         echo "<tr class='tab_bg_1'>";
         echo "<td>".$data['uniqid']."</td>";
         $PluginFusioninventoryAgent->getFromDB($data['plugin_fusioninventory_agents_id']);
         echo "<td>".$PluginFusioninventoryAgent->getLink(1)."</td>";
         $nb_query = 0;
         $nb_threads = 0;
         $start_date = "";
         $end_date = "";
         $nb_errors = 0;
         $a_taskjobstatus = $PluginFusioninventoryTaskjobstatus->find("`uniqid`='".$data['uniqid']."'");
         foreach ($a_taskjobstatus as $datastatus) {
            $a_taskjoblog = $PluginFusioninventoryTaskjoblog->find("`plugin_fusioninventory_taskjobstatus_id`='".$datastatus['id']."'");
            foreach($a_taskjoblog as $taskjoblog) {
               if (strstr($taskjoblog['comment'], " ==fusinvsnmp::1==")) {
                  $nb_query += str_replace(" ==fusinvsnmp::1==", "", $taskjoblog['comment']);
               } else if (strstr($taskjoblog['comment'], " No response from remote host")) {
                  $nb_errors++;
               } else if ($taskjoblog['state'] == "1") {
                  $nb_threads = str_replace(" threads", "", $taskjoblog['comment']);
               } else if (strstr($taskjoblog['comment'], "==fusinvsnmp::6==")) {
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
         
         echo "<td>".convDateTime($start_date)."</td>";
         echo "<td>".convDateTime($end_date)."</td>";
         $date1 = new DateTime($start_date);
         $date2 = new DateTime($end_date);
         $interval = $date1->diff($date2);
         echo "<td>".$interval->h."h ".$interval->i."min ".$interval->s."s</td>";
         echo "<td>".round($nb_query / (strtotime($end_date) - strtotime($start_date)), 2)."</td>";
         echo "<td>".$nb_threads."</td>";
         echo "<td>".$nb_query."</td>";
         echo "<td>".$nb_errors."</td>";
         echo "</tr>";      
      }

      echo "</table>";

   }
}

?>