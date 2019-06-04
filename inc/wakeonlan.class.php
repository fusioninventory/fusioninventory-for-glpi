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
 * This file is used to manage the wake on lan of computers by the agent.
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
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the wake on lan of computers by the agent.
 */
class PluginFusioninventoryWakeonlan extends PluginFusioninventoryCommunication {


   /**
    * Prepare a taskjob
    * Get all devices and put in taskjobstate each task for each device for
    * each agent
    *
    * @global object $DB
    * @param integer $taskjobs_id
    * @return string
    */
   function prepareRun($taskjobs_id) {
      global $DB;

      $pfTask = new PluginFusioninventoryTask();
      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $pfAgent = new PluginFusioninventoryAgent();

      $uniqid = uniqid();

      $pfTaskjob->getFromDB($taskjobs_id);
      $pfTask->getFromDB($pfTaskjob->fields['plugin_fusioninventory_tasks_id']);

      $communication = $pfTask->fields['communication'];
      $a_definitions = importArrayFromDB($pfTaskjob->fields['definition']);

      $a_computers_to_wake = [];
      foreach ($a_definitions as $definition) {
         $itemtype = key($definition);
         $items_id = current($definition);

         switch ($itemtype) {

            case 'Computer':
               $a_computers_to_wake[] = $items_id;
               break;

            case 'PluginFusioninventoryDeployGroup':
               $group = new PluginFusioninventoryDeployGroup;
               $group->getFromDB($items_id);

               switch ($group->getField('type')) {

                  case 'STATIC':
                     $query = "SELECT items_id
                     FROM glpi_plugin_fusioninventory_deploygroups_staticdatas
                     WHERE groups_id = '$items_id'
                     AND itemtype = 'Computer'";
                     $res = $DB->query($query);
                     while ($row = $DB->fetchAssoc($res)) {
                        $a_computers_to_wake[] = $row['items_id'];
                     }
                     break;

                  case 'DYNAMIC':
                     $query = "SELECT fields_array
                     FROM glpi_plugin_fusioninventory_deploygroups_dynamicdatas
                     WHERE groups_id = '$items_id'
                     LIMIT 1";
                     $res = $DB->query($query);
                     $row = $DB->fetchAssoc($res);

                     if (isset($_GET)) {
                        $get_tmp = $_GET;
                     }
                     if (isset($_SESSION["glpisearchcount"]['Computer'])) {
                        unset($_SESSION["glpisearchcount"]['Computer']);
                     }
                     if (isset($_SESSION["glpisearchcount2"]['Computer'])) {
                        unset($_SESSION["glpisearchcount2"]['Computer']);
                     }

                     $_GET = importArrayFromDB($row['fields_array']);

                     $_GET["glpisearchcount"] = count($_GET['field']);
                     if (isset($_GET['field2'])) {
                        $_GET["glpisearchcount2"] = count($_GET['field2']);
                     }

                     $pfSearch = new PluginFusioninventorySearch();
                     Search::manageGetValues('Computer');
                     $glpilist_limit = $_SESSION['glpilist_limit'];
                     $_SESSION['glpilist_limit'] = 999999999;
                     $result = $pfSearch->constructSQL('Computer',
                                                       $_GET);
                     $_SESSION['glpilist_limit'] = $glpilist_limit;
                     while ($data=$DB->fetchArray($result)) {
                        $a_computers_to_wake[] = $data['id'];
                     }
                     if (count($get_tmp) > 0) {
                        $_GET = $get_tmp;
                     }
                     break;

               }
         }
      }
      $a_actions = importArrayFromDB($pfTaskjob->fields['action']);

      $a_agentList = [];

      if ((!strstr($pfTaskjob->fields['action'], '".1"'))
            AND (!strstr($pfTaskjob->fields['action'], '".2"'))) {

         foreach ($a_actions as $a_action) {
            if ((!in_array('.1', $a_action))
               && (!in_array('.2', $a_action))) {

               $agent_id = current($a_action);
               if ($pfAgent->getFromDB($agent_id)) {
                  if ($communication == 'pull') {
                     $a_agentList[] = $agent_id;
                  } else {
                     if ($pfTaskjob->isAgentAlive('1', $agent_id)) {
                        $a_agentList[] = $agent_id;
                     }
                  }
               }
            }
         }
      } else if (strstr($pfTaskjob->fields['action'], '".1"')) {
         /*
          * Case 3 : dynamic agent
          */
         $a_agentList = $this->getAgentsSubnet(count($a_computers_to_wake), $communication);
      } else if (in_array('.2', $a_actions)) {
         /*
          * Case 4 : dynamic agent same subnet
          */
         $subnet = '';
         foreach ($a_computers_to_wake as $items_id) {
            $sql = "SELECT * FROM `glpi_networkports`
               WHERE `items_id`='".$items_id."'
                  AND `itemtype`='Computer'
                  AND `mac`!='' ";
            $result = $DB->query($sql);
            if ($result) {
               while ($data=$DB->fetchArray($result)) {
                  $subnet = $data['subnet'];
               }
            }
         }
         if ($subnet != '') {
            $a_agentList = $this->getAgentsSubnet(count($a_computers_to_wake), $communication, $subnet);
         }
      }

      if (count($a_agentList) == '0') {
         $a_input = [];
         $a_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
         $a_input['state'] = 1;
         $a_input['plugin_fusioninventory_agents_id'] = 0;
         $a_input['itemtype'] = 'Computer';
         $a_input['items_id'] = 0;
         $a_input['uniqid'] = $uniqid;
         $Taskjobstates_id = $pfTaskjobstate->add($a_input);
            //Add log of taskjob
            $a_input['plugin_fusioninventory_taskjobstates_id'] = $Taskjobstates_id;
            $a_input['state'] = 7;
            $a_input['date'] = date("Y-m-d H:i:s");
            $pfTaskjoblog->add($a_input);

         $pfTaskjobstate->changeStatusFinish($Taskjobstates_id,
                                             0,
                                             'Computer',
                                             1,
                                             "Unable to find agent to run this job");
      } else {
         $nb_computers = ceil(count($a_computers_to_wake) / count($a_agentList));

         $a_input = [];
         $a_input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
         $a_input['state'] = 0;
         $a_input['itemtype'] = 'Computer';
         $a_input['uniqid'] = $uniqid;
         while (count($a_computers_to_wake) != 0) {
            $agent_id = array_pop($a_agentList);
            $a_input['plugin_fusioninventory_agents_id'] = $agent_id;
            for ($i=0; $i < $nb_computers; $i++) {
                //Add jobstate and put status
                $a_input['items_id'] = array_pop($a_computers_to_wake);
                $Taskjobstates_id = $pfTaskjobstate->add($a_input);
                  //Add log of taskjob
                  $a_input['plugin_fusioninventory_taskjobstates_id'] = $Taskjobstates_id;
                  $a_input['state'] = 7;
                  $a_input['date'] = date("Y-m-d H:i:s");
                  $pfTaskjoblog->add($a_input);
                  unset($a_input['state']);
               if ($communication == "push") {
                  $_SESSION['glpi_plugin_fusioninventory']['agents'][$agent_id] = 1;
               }
            }
         }
      }
      $pfTaskjob->fields['status'] = 1;
      $pfTaskjob->update($pfTaskjob->fields);

      return $uniqid;
   }


   /**
    * When agent contact server, this function send datas to agent
    *
    * @param object $jobstate
    * @return string
    */
   function run($jobstate) {

      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $NetworkPort                        = new NetworkPort();

      $sxml_option = $this->message->addChild('OPTION');
      $sxml_option->addChild('NAME', 'WAKEONLAN');

      $changestate = 0;
      //      foreach ($taskjobstates as $jobstate) {
         $data = $jobstate->fields;
         $a_networkPort = $NetworkPort->find(['itemtype' => 'Computer', 'items_id' => $data['items_id']]);
         $computerip = 0;
      foreach ($a_networkPort as $datanetwork) {
         //if ($datanetwork['ip'] != "127.0.0.1") {
         if ($datanetwork['mac'] != '') {
            $computerip++;
            $sxml_param = $sxml_option->addChild('PARAM');
            $sxml_param->addAttribute('MAC', $datanetwork['mac']);
            //$sxml_param->addAttribute('IP', $datanetwork['ip']);

            if ($changestate == '0') {
               $pfTaskjobstate->changeStatus($data['id'], 1);
               $pfTaskjoblog->addTaskjoblog($data['id'],
                                       '0',
                                       'Computer',
                                       '1',
                                       '');
               $changestate = $pfTaskjobstate->fields['id'];
            } else {
               $pfTaskjobstate->changeStatusFinish($data['id'],
                                                   $data['items_id'],
                                                   $data['itemtype'],
                                                   0,
                                                   "Merged with ".$changestate);
            }

            // Update taskjobstate (state = 3 : finish); Because we haven't return of agent on this action
            $pfTaskjobstate->changeStatusFinish($data['id'],
                                             $data['items_id'],
                                             $data['itemtype'],
                                             0,
                                             'WakeOnLan have not return state');
         }
         //}
      }
      if ($computerip == '0') {
         $pfTaskjobstate->changeStatusFinish($data['id'],
                                          $data['items_id'],
                                          $data['itemtype'],
                                          1,
                                          "No IP found on the computer");

      }
      //}
      return $this->message;
   }


   /**
    * Get agents on the subnet
    *
    * @global object $DB
    * @param integer $nb_computers
    * @param string $communication
    * @param string $subnet
    * @return array
    */
   function getAgentsSubnet($nb_computers, $communication, $subnet = '') {
      global $DB;

      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $OperatingSystem = new OperatingSystem();

      // Number of computers min by agent
      $nb_computerByAgentMin = 20;
      $nb_agentsMax = ceil($nb_computers / $nb_computerByAgentMin);

      // Get ids of operating systems which can make real wakeonlan
      $a_os = $OperatingSystem->find(['name' => ['LIKE', '%Linux%']]);
      $osfind = '(';
      $i = 0;
      foreach ($a_os as $os_id=>$data) {
         $comma = '';
         if ($i > 0) {
            $comma = ', ';
         }
         $osfind .= $comma.$os_id;
         $i++;
      }
      $osfind .= ')';
      $pass_count = 1;
      if ($osfind == '()') {
         $osfind = '';
      } else {
         $pass_count++;
         $osfind = 'AND operatingsystems_id IN '.$osfind;
      }

      $a_agentList = [];
      for ($pass = 0; $pass < $pass_count; $pass++) {

         if ($pass == "1") {
            // It's not linux
            $osfind = str_replace('AND operatingsystems_id IN ',
                                  'AND operatingsystems_id NOT IN ',
                                  $osfind);
         }

         if ($subnet != '') {
            $subnet = " AND subnet='".$subnet."' ";
         }
         $a_agents = $pfAgentmodule->getAgentsCanDo('WAKEONLAN');
         $a_agentsid = [];
         foreach ($a_agents as $a_agent) {
            $a_agentsid[] = $a_agent['id'];
         }
         if (count($a_agentsid) == '0') {
            return $a_agentList;
         }

         $where = " AND `glpi_plugin_fusioninventory_agents`.`ID` IN (";
         $where .= implode(', ', $a_agentsid);
         $where .= ")
            AND `ip` != '127.0.0.1' ";

         $query = "SELECT `glpi_plugin_fusioninventory_agents`.`id` as `a_id`, ip, subnet, token
            FROM `glpi_plugin_fusioninventory_agents`
            LEFT JOIN `glpi_networkports` ON `glpi_networkports`.`items_id` =
               `glpi_plugin_fusioninventory_agents`.`items_id`
            LEFT JOIN `glpi_computers` ON `glpi_computers`.`id` =
               `glpi_plugin_fusioninventory_agents`.`items_id`
            WHERE `glpi_networkports`.`itemtype`='Computer'
               ".$subnet."
               ".$osfind."
               ".$where." ";
         if ($result = $DB->query($query)) {
            while ($data=$DB->fetchArray($result)) {
               if ($communication == 'push') {
                  if ($pfTaskjob->isAgentAlive(1, $data['a_id'])) {
                     if (!in_array($a_agentList, $data['a_id'])) {
                        $a_agentList[] = $data['a_id'];
                        if (count($a_agentList) >= $nb_agentsMax) {
                           return $a_agentList;
                        }
                     }
                  }
               } else if ($communication == 'pull') {
                  if (!in_array($a_agentList, $data['a_id'])) {
                     $a_agentList[] = $data['a_id'];
                     if (count($a_agentList) > $nb_agentsMax) {
                        return $a_agentList;
                     }
                  }
               }
            }
         }
      }
      return $a_agentList;
   }


}

