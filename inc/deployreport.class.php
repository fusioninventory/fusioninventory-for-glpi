<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2012

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryDeployReport extends CommonDBTM {


   /**
    * Display tab
    *
    * @param CommonGLPI $item
    * @param integer $withtemplate
    *
    * @return varchar name of the tab(s) to display
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getType()=='PluginFusioninventoryTask') {
         if ($item->getID() > 0) {
            $pfTaskjob = new PluginFusioninventoryTaskjob();
            $a_jobs = $pfTaskjob->find("`plugin_fusioninventory_tasks_id`='".$item->getID()."'
               AND (`method`='deployinstall' OR `method`='deployuninstall')");
            if (count($a_jobs) > 0) {
               return __('Display report')." (deploy)";
            }
         }
      }
      return '';
   }



   /**
    * Display content of tab
    *
    * @param CommonGLPI $item
    * @param integer $tabnum
    * @param interger $withtemplate
    *
    * @return boolean TRUE
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType()=='PluginFusioninventoryTask') {
         if ($item->getID() > 0) {
            $pfReport = new self();
            $pfReport->showReport($item->getID());
         }
      }
      return TRUE;
   }



   function showReport($tasks_id) {

      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfPackage = new PluginFusioninventoryDeployPackage();
      $pfAgent = new PluginFusioninventoryAgent();
      $computer = new Computer();

      $a_taskjobs = $pfTaskjob->find("`plugin_fusioninventory_tasks_id`='".$tasks_id."'
         AND (`method`='deployinstall' OR `method`='deployuninstall')");

      echo "<table class='tab_cadre_fixe'>";
      foreach ($a_taskjobs as $datajob) {
         $a_taskjobstates = $pfTaskjobstate->find("`plugin_fusioninventory_taskjobs_id`='".
                 $datajob['id']."'
             GROUP BY `plugin_fusioninventory_agents_id`,`uniqid`",
                 "`uniqid` DESC");
         $uniqid = '';
         foreach ($a_taskjobstates as $datastate) {

            if ($uniqid != $datastate['uniqid']) {
               echo "<tr class='tab_bg_1'>";
               echo "<th>";
               echo __('Package', 'fusioninventory');
               echo "</th>";
               echo "<th>";
               echo __('Computer');
               echo "</th>";
               echo "<th>";
               echo __('Status');
               echo "</th>";
               echo "<th>";
               echo __('Date');
               echo "</th>";
               echo "<th>";
               echo __('Comments');
               echo "</th>";
               echo "</tr>";
               $uniqid = $datastate['uniqid'];
            }

            $a_taskjobstatesuniqid = $pfTaskjobstate->find("`uniqid`='".$datastate['uniqid']."'
               AND `plugin_fusioninventory_agents_id`='".
                    $datastate['plugin_fusioninventory_agents_id']."'");
            $pfPackage->getFromDB($datastate['items_id']);

            $pfAgent->getFromDB($datastate['plugin_fusioninventory_agents_id']);
            $agent = '';
            if (!isset($pfAgent->fields['name'])) {
               $agent = NOT_AVAILABLE;
            } else {
               $computer->getFromDB($pfAgent->fields['items_id']);
               $agent = $computer->getLink(1);
            }

            foreach ($a_taskjobstatesuniqid as $datastateuniqid) {

               $state = 'successfull';
               $message = '';
               $date = '';
               $a_failed = $pfTaskjoblog->find("`plugin_fusioninventory_taskjobstates_id`='".
                       $datastateuniqid['id']."'
                  AND (`comment` LIKE '%failed%' OR `comment` LIKE '%failure%' ".
                       "OR `comment`='==agentcrashed==')");
               if (count($a_failed) > 0) {
                  $state = 'failed';
                  $a_message = array();
                  foreach ($a_failed as $datafail) {
                     $comment = $datafail['comment'];
                     $matches = array();
                     preg_match_all("/==(\w*)\:\:([0-9]*)==/", $comment, $matches);
                     if ($matches[1]) {
                        foreach($matches[0] as $num=>$commentvalue) {
                           // TODO : change $LANG to gettext
                           $comment = str_replace($commentvalue,
                              $LANG['plugin_'.$matches[1][$num]]["codetasklog"][$matches[2][$num]],
                                                  $comment);
                        }
                     }
                     $a_message[] = $comment;
                     $date = $datafail['date'];
                  }
                  $a_faildetail = $pfTaskjoblog->find(
                          "`plugin_fusioninventory_taskjobstates_id`='".$datastateuniqid['id']."'
                           AND `comment` LIKE '%--------------------------------%'");
                  foreach ($a_faildetail as $datadetail) {
                     $a_message[] = $datadetail['comment'];
                  }
                  $message = implode("<br/>", $a_message);
               }
               $a_failed = $pfTaskjoblog->find(
                       "`plugin_fusioninventory_taskjobstates_id`='".$datastateuniqid['id']."'
                        AND `comment`='Action cancelled by user'");
               if (count($a_failed) > 0) {
                  $state = 'failed';
                  foreach ($a_failed as $datafail) {
                     $message = 'Action cancelled by user';
                     $date = $datafail['date'];
                  }
                  $a_faildetail = $pfTaskjoblog->find(
                          "`plugin_fusioninventory_taskjobstates_id`='".$datastateuniqid['id']."'
                           AND `comment` LIKE '%--------------------------------%'");
                  foreach ($a_faildetail as $datadetail) {
                     $message .= "<br/>".$datadetail['comment'];
                  }
               }


               if ($state != 'failed') {
                  $a_successful = $pfTaskjoblog->find(
                          "`plugin_fusioninventory_taskjobstates_id`='".$datastateuniqid['id']."'
                           AND `comment` LIKE '%---------------------------%'");
                  if (count($a_successful) > 0) {
                     foreach ($a_successful as $datasuccessful) {
                        $date = $datasuccessful['date'];
                        if (strstr($datasuccessful['comment'], 'exit status is ok')
                              || strstr($datasuccessful['comment'], 'ok pattern found in log')) {

                        } else if (strstr($datasuccessful['comment'], 'ok, no check to evaluate.')) {
                           $state = 'unknown';
                           $message .= $datasuccessful['comment'];
                        } else if (strstr($datasuccessful['comment'], 'exit status is not ok')
                              || strstr($datasuccessful['comment'], 'error pattern found in log')) {
                           $state = 'failed';
                           $message .= $datasuccessful['comment'];
                        }
                     }
                  } else {
                     $state = '';
                  }
               }
               if ($state != '') {
                  echo "<tr class='tab_bg_3'>";
                  echo "<td>";
                  echo $pfPackage->getLink();
                  echo "</td>";
                  echo "<td>";
                  echo $agent;
                  echo "</td>";
                  $display = '';
                  if ($state == 'successfull') {
                     $display = $pfTaskjoblog->getDivState(2, "td");
                  } else if ($state == 'unknown') {
                     $display = $pfTaskjoblog->getDivState(5, "td");
                  } else if ($state == 'failed') {
                     $display = $pfTaskjoblog->getDivState(4, "td");
                  }
                  echo $display;
                  echo "<td>";
                  echo Html::convDateTime($date);
                  echo "</td>";
                  echo "<td>";
                  echo $message;
                  echo "</td>";
                  echo "</tr>";
               }
            }
         }
      }
      echo "</table>";
   }

}

?>
