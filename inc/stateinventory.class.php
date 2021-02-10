<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the network inventory state.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * Manage the network inventory state.
 */
class PluginFusioninventoryStateInventory extends CommonDBTM {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_task';


   /**
    * __contruct function where add variable in $CFG_GLPI
    *
    * @global array $CFG_GLPI
    */
   function __construct() {
      global $CFG_GLPI;

      $CFG_GLPI['glpitablesitemtype']['PluginFusioninventoryStateInventory'] =
          'glpi_plugin_fusioninventory_taskjobstates';
   }


   /**
    * Display network inventory state
    *
    * @global object $DB
    * @global array $CFG_GLPI
    * @param array $options
    */
   function display($options = []) {
      global $DB, $CFG_GLPI;

      $pfAgent = new PluginFusioninventoryAgent();
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $pfTaskjob = new PluginFusioninventoryTaskjob();

      $start = 0;
      if (isset($_REQUEST["start"])) {
         $start = $_REQUEST["start"];
      }

      // Total Number of events
      $querycount = "SELECT count(*) AS cpt FROM `glpi_plugin_fusioninventory_taskjobstates`
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobs`
            ON `plugin_fusioninventory_taskjobs_id` = `glpi_plugin_fusioninventory_taskjobs`.`id`
         WHERE `method` = 'networkinventory'
         GROUP BY `uniqid`
         ORDER BY `uniqid` DESC ";
      $resultcount = $DB->query($querycount);
      $number = $DB->numrows($resultcount);

      // Display the pager
      Html::printPager($start, $number, Plugin::getWebDir('fusioninventory')."/front/stateinventory.php", '');

      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<th>".__('Unique id', 'fusioninventory')."</th>";
      echo "<th>".__('Task job', 'fusioninventory')."</th>";
      echo "<th>".__('Agent', 'fusioninventory')."</th>";
      echo "<th>".__('Status')."</th>";
      echo "<th>".__('Starting date', 'fusioninventory')."</th>";
      echo "<th>".__('Ending date', 'fusioninventory')."</th>";
      echo "<th>".__('Total duration')."</th>";
      echo "<th>".__('Number per second', 'fusioninventory')."</th>";
      echo "<th>".__('Threads number', 'fusioninventory')."</th>";
      echo "<th>".__('To inventory', 'fusioninventory')."</th>";
      echo "<th>".__('Error(s)', 'fusioninventory')."</th>";
      echo "</tr>";

      $sql = "SELECT `glpi_plugin_fusioninventory_taskjobstates`.*
            FROM `glpi_plugin_fusioninventory_taskjobstates`
         LEFT JOIN `glpi_plugin_fusioninventory_taskjobs`
            ON `plugin_fusioninventory_taskjobs_id` = `glpi_plugin_fusioninventory_taskjobs`.`id`
         WHERE `method` = 'networkinventory'
         GROUP BY `uniqid`
         ORDER BY `uniqid` DESC
         LIMIT ".intval($start).", " . intval($_SESSION['glpilist_limit']);
      $result=$DB->query($sql);
      while ($data=$DB->fetchArray($result)) {
         echo "<tr class='tab_bg_1'>";
         echo "<td>".$data['uniqid']."</td>";
         $pfTaskjob->getFromDB($data['plugin_fusioninventory_taskjobs_id']);
         echo "<td>";
         $link = $pfTaskjob->getLink();
         $link = str_replace('.form', '', $link);
         echo $link;
         echo "</td>";
         $pfAgent->getFromDB($data['plugin_fusioninventory_agents_id']);
         echo "<td>".$pfAgent->getLink(1)."</td>";
         $nb_query = 0;
         $nb_threads = 0;
         $start_date = "";
         $end_date = "";
         $nb_errors = 0;
         $a_taskjobstates = $pfTaskjobstate->find(['uniqid' => $data['uniqid']]);
         foreach ($a_taskjobstates as $datastate) {
            $a_taskjoblog = $pfTaskjoblog->find(['plugin_fusioninventory_taskjobstates_id' => $datastate['id']]);
            foreach ($a_taskjoblog as $taskjoblog) {
               if (strstr($taskjoblog['comment'], " ==devicesqueried==")) {
                  $nb_query += str_replace(" ==devicesqueried==", "", $taskjoblog['comment']);
               } else if (strstr($taskjoblog['comment'], " No response from remote host")) {
                  $nb_errors++;
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
               echo __('Prepared', 'fusioninventory');
               break;

            case 1:
            case 2:
               echo __('Started', 'fusioninventory');
               break;

            case 3:
               echo __('Finished tasks', 'fusioninventory');
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
            echo "<td>-</td>";
         } else {
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

            echo "<td>".round(($nb_query - $nb_errors) /
                    (strtotime($end_date) - strtotime($start_date)), 2)."</td>";
         }
         echo "<td>".$nb_threads."</td>";
         echo "<td>".$nb_query."</td>";
         echo "<td>".$nb_errors."</td>";
         echo "</tr>";
      }

      echo "</table>";

   }


   /**
    * Display diff between 2 dates, so the time elapsed of execution
    *
    * @param string $date1
    * @param string $date2
    */
   function dateDiff($date1, $date2) {
      $timestamp1 = strtotime($date1);
      $timestamp2 = strtotime($date2);

      $interval = [];
      $timestamp = $timestamp2 - $timestamp1;
      $nb_min = floor($timestamp / 60);
      $interval['s'] = $timestamp - ($nb_min * 60);
      $nb_hour = floor($nb_min / 60);
      $interval['i'] = $nb_min - ($nb_hour * 60);

      $display_date = '';
      if ($nb_hour > 0) {
         $display_date .= $nb_hour."h ";
      } else if ($interval['i'] > 0) {
         $display_date .= $interval['i']."min ";
      }

      echo "<td>".$display_date.$interval['s']."s</td>";
   }
}
