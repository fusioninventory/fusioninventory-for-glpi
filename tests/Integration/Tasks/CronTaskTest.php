<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2021 by the FusionInventory Development Team.

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
   @copyright Copyright (C) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class CronTaskTest extends TestCase {

   public static function setUpBeforeClass(): void {

      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }

      // Delete all agents (force)
      $pfAgent = new PluginFusioninventoryAgent();
      $items = $pfAgent->find();
      foreach ($items as $item) {
         $pfAgent->delete(['id' => $item['id']], true);
      }

      // Delete all tasks
      $pfTask = new PluginFusioninventoryTask();
      $items = $pfTask->find();
      foreach ($items as $item) {
         $pfTask->delete(['id' => $item['id']], true);
      }

      // Delete al deploygroups
      $pfDeployGroup   = new PluginFusioninventoryDeployGroup();
      $items = $pfDeployGroup->find();
      foreach ($items as $item) {
         $pfDeployGroup->delete(['id' => $item['id']], true);
      }

      // Delete al deploypackages
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $items = $pfDeployPackage->find();
      foreach ($items as $item) {
         $pfDeployPackage->delete(['id' => $item['id']], true);
      }

      $module = new PluginFusioninventoryAgentmodule();
      $module->getFromDBByCrit(['modulename' => 'DEPLOY']);
      $module->update([
         'id'        => $module->fields['id'],
         'is_active' => 1
      ]);
   }


   /**
    * @test
    */
   public function prepareDb() {

      $computer        = new Computer();
      $pfAgent         = new PluginFusioninventoryAgent();
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $pfDeployGroup   = new PluginFusioninventoryDeployGroup();
      $pfTask          = new PluginFusioninventoryTask();
      $pfTaskjob       = new PluginFusioninventoryTaskjob;
      $pfDeployGroup_Dynamicdata = new PluginFusioninventoryDeployGroup_Dynamicdata();
      $pfEntity        = new PluginFusioninventoryEntity();

      $pfEntity = new PluginFusioninventoryEntity();

      $pfEntity->getFromDBByCrit(['entities_id' => 0]);
      if (isset($pfEntity->fields['id'])) {
         $pfEntity->update([
            'id'                => $pfEntity->fields['id'],
            'agent_base_url' => 'http://127.0.0.1/glpi'
         ]);
      } else {
         $pfEntity->add([
            'entities_id'       => 0,
            'agent_base_url' => 'http://127.0.0.1/glpi'
         ]);
      }

      // Create package
      $input = [
          'entities_id' => 0,
          'name'        => 'package'
      ];
      $packages_id = $pfDeployPackage->add($input);
      $this->assertNotFalse($packages_id);

      // Create fusioninventory dynamic group
      $input = [
          'name' => 'all computers have name computer',
          'type' => 'DYNAMIC'
      ];
      $groups_id = $pfDeployGroup->add($input);
      $this->assertNotFalse($groups_id);

      $input = [
          'plugin_fusioninventory_deploygroups_id' => $groups_id,
          'fields_array' => 'a:2:{s:8:"criteria";a:1:{i:0;a:3:{s:5:"field";s:1:"1";s:10:"searchtype";s:8:"contains";s:5:"value";s:8:"computer";}}s:12:"metacriteria";s:0:"";}'
      ];
      $groupDynamicId = $pfDeployGroup_Dynamicdata->add($input);
      $this->assertNotFalse($groupDynamicId);

      // create task
      $input = [
          'entities_id' => 0,
          'name'        => 'deploy',
          'is_active'   => 1
      ];
      $tasks_id = $pfTask->add($input);
      $this->assertNotFalse($tasks_id);

      // create takjob
      $input = [
          'plugin_fusioninventory_tasks_id' => $tasks_id,
          'entities_id'                     => 0,
          'name'                            => 'deploy',
          'method'                          => 'deployinstall',
          'targets'                         => '[{"PluginFusioninventoryDeployPackage":"'.$packages_id.'"}]',
          'actors'                          => '[{"PluginFusioninventoryDeployGroup":"'.$groups_id.'"}]'
      ];
      $taskjobId = $pfTaskjob->add($input);
      $this->assertNotFalse($taskjobId);

      // Create computers + agents
      $input = [
          'entities_id' => 0,
          'name'        => 'computer1'
      ];
      $computers_id = $computer->add($input);
      $this->assertNotFalse($computers_id);

      $input = [
          'entities_id' => 0,
          'name'        => 'computer1',
          'version'     => '{"INVENTORY":"v2.3.11"}',
          'device_id'   => 'computer1',
          'useragent'   => 'FusionInventory-Agent_v2.3.11',
          'computers_id'=> $computers_id
      ];
      $agentId = $pfAgent->add($input);
      $this->assertNotFalse($agentId);

      $input = [
          'entities_id' => 0,
          'name'        => 'computer2'
      ];
      $computers_id = $computer->add($input);
      $this->assertNotFalse($computers_id);

      $input = [
          'entities_id' => 0,
          'name'        => 'computer2',
          'version'     => '{"INVENTORY":"v2.3.11"}',
          'device_id'   => 'computer2',
          'useragent'   => 'FusionInventory-Agent_v2.3.11',
          'computers_id'=> $computers_id
      ];
      $agentId = $pfAgent->add($input);
      $this->assertNotFalse($agentId);

      $input = [
          'entities_id' => 0,
          'name'        => 'computer3'
      ];
      $computers_id = $computer->add($input);
      $this->assertNotFalse($computers_id);

      $input = [
          'entities_id' => 0,
          'name'        => 'computer3',
          'version'     => '{"INVENTORY":"v2.3.11"}',
          'device_id'   => 'computer3',
          'useragent'   => 'FusionInventory-Agent_v2.3.11',
          'computers_id'=> $computers_id
      ];
      $agentId = $pfAgent->add($input);
      $this->assertNotFalse($agentId);

      // Create package
      $input = [
          'entities_id' => 0,
          'name'        => 'on demand package',
          'is_recursive' => 0,
          'plugin_fusioninventory_deploygroups_id' => $groups_id,
          'json' => '{"jobs":{"checks":[],"associatedFiles":[],"actions":[]},"associatedFiles":[]}'
      ];
      $packages_id_2 = $pfDeployPackage->add($input);
      $this->assertNotFalse($packages_id_2);

      // create task
      $input = [
          'entities_id'             => 0,
          'name'                    => 'ondemand',
          'is_active'               => 1,
          'is_deploy_on_demand'     => 1,
          'reprepare_if_successful' => 0
      ];
      $tasks_id_2 = $pfTask->add($input);
      $this->assertNotFalse($tasks_id_2);

      // create takjob
      $input = [
          'plugin_fusioninventory_tasks_id' => $tasks_id_2,
          'entities_id'                     => 0,
          'name'                            => 'deploy',
          'method'                          => 'deployinstall',
          'targets'                         => '[{"PluginFusioninventoryDeployPackage":"'.$packages_id_2.'"}]',
          'actors'                          => '[{"PluginFusioninventoryDeployGroup":"'.$groups_id.'"}]'
      ];
      $taskjobId = $pfTaskjob->add($input);
      $this->assertNotFalse($taskjobId);
   }


   /**
    * @test
    */
   public function prepareTask() {
      global $DB;

      PluginFusioninventoryTask::cronTaskscheduler();

      $pfTask = new PluginFusioninventoryTask();

      $pfTask->getFromDBByCrit(['name' => 'deploy']);
      $this->assertArrayHasKey('id', $pfTask->fields);
      $data = $pfTask->getJoblogs([$pfTask->fields['id']]);

      $pfAgent = new PluginFusioninventoryAgent();
      $reference = [];
      $pfAgent->getFromDBByCrit(['name' => 'computer1']);
      $reference[$pfAgent->fields['id']] = 'computer1';
      $pfAgent->getFromDBByCrit(['name' => 'computer2']);
      $reference[$pfAgent->fields['id']] = 'computer2';
      $pfAgent->getFromDBByCrit(['name' => 'computer3']);
      $reference[$pfAgent->fields['id']] = 'computer3';

      $this->assertEquals($reference, $data['agents']);
      foreach ($data['tasks'] as $task_id => &$task) {
         foreach ($task['jobs'] as $job_id => &$job) {
            foreach ($job['targets'] as $target_id => &$target) {
               foreach ($target['agents'] as $agent_id => &$agent) {
                  $logs = $data['tasks'][$task_id]['jobs'][$job_id]['targets'][$target_id]['agents'][$agent_id];
                  $this->assertEquals(1, count($logs));
                  /* We get something like:
                     [agent_id] => 1
                     [link] => ./vendor/bin/phpunit/front/computer.form.php?id=1
                     [numstate] => 0
                     [state] => prepared
                     [jobstate_id] => 1
                     [last_log_id] => 1
                     [last_log_date] => 2018-01-20 12:44:06
                     [timestamp] => 1516448646
                     [last_log] =>
                   */
                  foreach ($logs as &$log) {
                     $this->assertEquals($log['agent_id'], $agent_id);
                     $this->assertEquals($log['state'], "prepared");
                     $this->assertEquals($log['last_log'], "");
                  }
               }
            }
         }
      }
   }


   /**
    * @test
    */
   public function prepareTaskWithNewComputer() {

      $computer = new Computer();
      $pfAgent  = new PluginFusioninventoryAgent();

      $input = [
          'entities_id' => 0,
          'name'        => 'computer4'
      ];
      $computers_id = $computer->add($input);
      $this->assertNotFalse($computers_id);

      $input = [
          'entities_id' => 0,
          'name'        => 'computer4',
          'version'     => '{"INVENTORY":"v2.3.11"}',
          'device_id'   => 'computer4',
          'useragent'   => 'FusionInventory-Agent_v2.3.11',
          'computers_id'=> $computers_id
      ];
      $agentId = $pfAgent->add($input);
      $this->assertNotFalse($agentId);

      PluginFusioninventoryTask::cronTaskscheduler();

      $pfTask = new PluginFusioninventoryTask();

      // All tasks (active or not) and get logs
      $pfTask->getFromDBByCrit(['name' => 'deploy']);
      $this->assertArrayHasKey('id', $pfTask->fields);
      $data = $pfTask->getJoblogs([$pfTask->fields['id']]);

      $pfAgent = new PluginFusioninventoryAgent();
      $reference = [];
      $pfAgent->getFromDBByCrit(['name' => 'computer1']);
      $reference[$pfAgent->fields['id']] = 'computer1';
      $pfAgent->getFromDBByCrit(['name' => 'computer2']);
      $reference[$pfAgent->fields['id']] = 'computer2';
      $pfAgent->getFromDBByCrit(['name' => 'computer3']);
      $reference[$pfAgent->fields['id']] = 'computer3';
      $pfAgent->getFromDBByCrit(['name' => 'computer4']);
      $reference[$pfAgent->fields['id']] = 'computer4';

      $this->assertEquals($reference, $data['agents']);
   }


   /**
    * @test
    */
   public function prepareTaskWithdynamicgroupchanged() {

      $computer = new Computer();
      $computer->getFromDBByCrit(['name' => 'computer2']);
      $computer->update([
          'id'   => $computer->fields['id'],
          'name' => 'koin']);

      PluginFusioninventoryTask::cronTaskscheduler();

      $pfTask = new PluginFusioninventoryTask();

      $pfTask->getFromDBByCrit(['name' => 'deploy']);
      $this->assertArrayHasKey('id', $pfTask->fields);
      $data = $pfTask->getJoblogs([$pfTask->fields['id']]);

      $pfAgent = new PluginFusioninventoryAgent();
      $reference = [];
      $ref_prepared = [];
      $pfAgent->getFromDBByCrit(['name' => 'computer1']);
      $reference[$pfAgent->fields['id']] = 'computer1';
      $agentId1 = $pfAgent->fields['id'];

      $pfAgent->getFromDBByCrit(['name' => 'computer2']);
      $reference[$pfAgent->fields['id']] = 'computer2';

      $pfAgent->getFromDBByCrit(['name' => 'computer3']);
      $reference[$pfAgent->fields['id']] = 'computer3';
      $agentId2 = $pfAgent->fields['id'];

      $pfAgent->getFromDBByCrit(['name' => 'computer4']);
      $reference[$pfAgent->fields['id']] = 'computer4';
      $ref_prepared[] = $pfAgent->fields['id'];
      $ref_prepared[] = $agentId2;
      $ref_prepared[] = $agentId1;

      $this->assertEquals($reference, $data['agents']);

      $pfTaskjob       = new PluginFusioninventoryTaskjob();
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();

      $pfTaskjob->getFromDBByCrit(['plugin_fusioninventory_tasks_id' => $pfTask->fields['id']]);
      $pfDeployPackage->getFromDBByCrit(['name' => 'package']);

      $this->assertEquals($ref_prepared, array_keys($data['tasks'][$pfTask->fields['id']]['jobs'][$pfTaskjob->fields['id']]['targets']['PluginFusioninventoryDeployPackage_'.$pfDeployPackage->fields['id']]['counters']['agents_prepared']));
   }


   /**
    * @test
    */
   public function prepareTaskDisabled() {

      $pfTask = new PluginFusioninventoryTask();

      $pfTask->getFromDBByCrit(['name' => 'deploy']);
      $this->assertArrayHasKey('id', $pfTask->fields);
      $pfTask->update([
         'id'        => $pfTask->fields['id'],
         'is_active' => 0
      ]);

      PluginFusioninventoryTask::cronTaskscheduler();

      // Only for active tasks and with logs
      $data = $pfTask->getJoblogs([$pfTask->fields['id']], true, true);

      $ref = [];

      $this->assertEquals($ref, $data['agents'], 'Task inactive, so no agent prepared');

      $ref_prepared = [];

      $this->assertEquals($ref_prepared, $data['tasks']);
   }

   /**
    * @test
    */
   public function prepareTaskNoLogs() {
      global $DB;

      $pfTask = new PluginFusioninventoryTask();

      $pfTask->getFromDBByCrit(['name' => 'deploy']);
      $this->assertArrayHasKey('id', $pfTask->fields);
      $pfTask->update([
         'id'        => $pfTask->fields['id'],
         'is_active' => 1
      ]);

      PluginFusioninventoryTask::cronTaskscheduler();

      $data = $pfTask->getJoblogs([$pfTask->fields['id']], false, false);

      $pfAgent = new PluginFusioninventoryAgent();
      $reference = [];
      $pfAgent->getFromDBByCrit(['name' => 'computer1']);
      $reference[$pfAgent->fields['id']] = 'computer1';
      $pfAgent->getFromDBByCrit(['name' => 'computer2']);
      $reference[$pfAgent->fields['id']] = 'computer2';
      $pfAgent->getFromDBByCrit(['name' => 'computer3']);
      $reference[$pfAgent->fields['id']] = 'computer3';
      $pfAgent->getFromDBByCrit(['name' => 'computer4']);
      $reference[$pfAgent->fields['id']] = 'computer4';

      $this->assertEquals($reference, $data['agents']);

      foreach ($data['tasks'] as $task_id => &$task) {
         foreach ($task['jobs'] as $job_id => &$job) {
            foreach ($job['targets'] as $target_id => &$target) {
               foreach ($target['agents'] as $agent_id => &$agent) {
                  $logs = $data['tasks'][$task_id]['jobs'][$job_id]['targets'][$target_id]['agents'][$agent_id];
                  // No logs
                  $this->assertEquals(0, count($logs), print_r($logs, true));
               }
            }
         }
      }
   }


   /**
    * @test
    */
   public function prepareTaskNotRePrepareIfSuccessful() {
      global $DB;

      $_SESSION['glpi_plugin_fusioninventory']['includeoldjobs'] = 2;

      $pfAgent      = new PluginFusioninventoryAgent();
      $pfTask       = new PluginFusioninventoryTask();
      $deploycommon = new PluginFusioninventoryDeployCommon();

      $DB->query("TRUNCATE TABLE `glpi_plugin_fusioninventory_taskjoblogs`");
      $DB->query("TRUNCATE TABLE `glpi_plugin_fusioninventory_taskjobstates`");

      $pfTask->getFromDBByCrit(['name' => 'deploy']);
      $this->assertArrayHasKey('id', $pfTask->fields);
      $pfTask->update([
         'id'                      => $pfTask->fields['id'],
         'reprepare_if_successful' => 0,
         'is_active'               => 1
      ]);

      // prepare
      PluginFusioninventoryTask::cronTaskscheduler();

      $pfTaskjob       = new PluginFusioninventoryTaskjob();
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();

      $pfTaskjob->getFromDBByCrit(['plugin_fusioninventory_tasks_id' => $pfTask->fields['id']]);
      $pfDeployPackage->getFromDBByCrit(['name' => 'package']);

      $pfAgent->getFromDBByCrit(['name' => 'computer1']);
      $agentComputer1Id = $pfAgent->fields['id'];
      $pfAgent->getFromDBByCrit(['name' => 'computer2']);
      $agentComputer2Id = $pfAgent->fields['id'];
      $pfAgent->getFromDBByCrit(['name' => 'computer3']);
      $agentComputer3Id = $pfAgent->fields['id'];
      $pfAgent->getFromDBByCrit(['name' => 'computer4']);
      $agentComputer4Id = $pfAgent->fields['id'];

      $data = $pfTask->getJoblogs([$pfTask->fields['id']]);

      $reference = [
         'agents_prepared' => [
            $agentComputer1Id => 1,
            $agentComputer3Id => 2,
            $agentComputer4Id => 3
         ],
         'agents_cancelled' => [],
         'agents_running' => [],
         'agents_success' => [],
         'agents_error' => [],
         'agents_notdone' => [
            $agentComputer4Id => 3,
            $agentComputer3Id => 2,
            $agentComputer1Id => 1
         ]
      ];

      $counters = $data['tasks'][$pfTask->fields['id']]['jobs'][$pfTaskjob->fields['id']]['targets']['PluginFusioninventoryDeployPackage_'.$pfDeployPackage->fields['id']]['counters'];
      $this->assertEquals($reference, $counters);

      // 1 computer deploy successfully
      $agent = $pfAgent->infoByKey('computer1');
      $taskjobstates = $pfTask->getTaskjobstatesForAgent(
         $agent['id'],
         ['deployinstall']
      );
      foreach ($taskjobstates as $taskjobstate) {
         $jobstate_order = $deploycommon->run($taskjobstate);
         $params = [
            'machineid' => 'computer1',
            'uuid'      => $jobstate_order['job']['uuid'],
            'code'      => 'ok',
            'msg'       => 'seems ok',
            'sendheaders' => false
         ];
         PluginFusioninventoryCommunicationRest::updateLog($params);
      }

      // 1 computer in error
      $agent = $pfAgent->infoByKey('computer3');
      $taskjobstates = $pfTask->getTaskjobstatesForAgent(
         $agent['id'],
         ['deployinstall']
      );
      foreach ($taskjobstates as $taskjobstate) {
         $jobstate_order = $deploycommon->run($taskjobstate);
         $params = [
           'machineid' => 'computer3',
           'uuid'      => $jobstate_order['job']['uuid'],
           'code'      => 'running',
           'msg'       => 'gogogo',
           'sendheaders' => false
         ];
         PluginFusioninventoryCommunicationRest::updateLog($params);
         $params = [
           'machineid' => 'computer3',
           'uuid'      => $jobstate_order['job']['uuid'],
           'code'      => 'ko',
           'msg'       => 'failure of check #1 (error)',
           'sendheaders' => false
         ];
         PluginFusioninventoryCommunicationRest::updateLog($params);
      }

      // re-prepare and will have only the computer in error be in prepared mode
      $data = $pfTask->getJoblogs([$pfTask->fields['id']]);

      $reference = [
         'agents_prepared' => [
            $agentComputer4Id => 3,
         ],
         'agents_cancelled' => [],
         'agents_running' => [],
         'agents_success' => [
            $agentComputer1Id => 1
         ],
         'agents_error' => [
            $agentComputer3Id => 2
         ],
         'agents_notdone' => [
            $agentComputer4Id => 3
         ]
      ];

      $counters = $data['tasks'][$pfTask->fields['id']]['jobs'][$pfTaskjob->fields['id']]['targets']['PluginFusioninventoryDeployPackage_'.$pfDeployPackage->fields['id']]['counters'];
      $this->assertEquals($reference, $counters);

      PluginFusioninventoryTask::cronTaskscheduler();
      $data = $pfTask->getJoblogs([$pfTask->fields['id']]);
      $reference = [
         'agents_prepared' => [
            $agentComputer3Id => 7,
            $agentComputer4Id => 3
            ],
         'agents_cancelled' => [],
         'agents_running' => [],
         'agents_success' => [
            $agentComputer1Id => 1
         ],
         'agents_error' => [
            $agentComputer3Id => 2
         ],
         'agents_notdone' => [
            $agentComputer4Id => 3
         ]
      ];
      $counters = $data['tasks'][$pfTask->fields['id']]['jobs'][$pfTaskjob->fields['id']]['targets']['PluginFusioninventoryDeployPackage_'.$pfDeployPackage->fields['id']]['counters'];
      $this->assertEquals($reference, $counters);

      $pfTask->update([
         'id'                      => $pfTask->fields['id'],
         'reprepare_if_successful' => 1,
      ]);
      PluginFusioninventoryTask::cronTaskscheduler();
      $data = $pfTask->getJoblogs([$pfTask->fields['id']]);
      $reference = [
         'agents_prepared' => [
         $agentComputer1Id => 9,
         $agentComputer3Id => 7,
         $agentComputer4Id => 3
            ],
         'agents_cancelled' => [],
         'agents_running' => [],
         'agents_success' => [
         $agentComputer1Id => 1,
         ],
         'agents_error' => [
            $agentComputer3Id => 2
         ],
         'agents_notdone' => [
            $agentComputer4Id => 3
         ]
      ];
      $counters = $data['tasks'][$pfTask->fields['id']]['jobs'][$pfTaskjob->fields['id']]['targets']['PluginFusioninventoryDeployPackage_'.$pfDeployPackage->fields['id']]['counters'];
      $this->assertEquals($reference, $counters);

   }


   /**
    * @test
    */
   public function cleanTasksAndJobs() {
      global $DB;

      $pfTask         = new PluginFusioninventoryTask();
      $pfTaskJob      = new PluginFusioninventoryTaskJob();
      $pfTaskJobstate = new PluginFusioninventoryTaskjobstate();

      //We only work on 1 task
      $pfTask->getFromDBByCrit(['name' => 'deploy']);
      $pfTask->delete(['id' => $pfTask->fields['id']], true);

      //Clean all taskjoblogs & states
      $DB->query("TRUNCATE TABLE `glpi_plugin_fusioninventory_taskjoblogs`");
      $DB->query("TRUNCATE TABLE `glpi_plugin_fusioninventory_taskjobstates`");

      //Find the on demand task
      $tasks = $pfTask->find(['name' => 'ondemand']);
      $this->assertEquals(1, count($tasks));

      $task     = current($tasks);
      $tasks_id = $task['id'];

      //Prepare the task
      PluginFusioninventoryTask::cronTaskscheduler();

      //Set the first job as successfull
      $query = "SELECT DISTINCT `plugin_fusioninventory_taskjobstates_id`
                FROM glpi_plugin_fusioninventory_taskjoblogs LIMIT 1";
      foreach ($DB->request($query) as $data) {
         $pfTaskJobstate->changeStatusFinish($data['plugin_fusioninventory_taskjobstates_id'], '', 0);
      }

      //No task & jobtates should be removed because ask for cleaning 5 days from now
      $index = $pfTask->cleanTasksAndJobs(5);

      $this->assertEquals(0, $index);

      //Set the joblogs date at 2 days ago
      $datetime = new Datetime($_SESSION['glpi_currenttime']);
      $datetime->modify('-4 days');

      $query = "UPDATE `glpi_plugin_fusioninventory_taskjoblogs`
                SET `date`='".$datetime->format('Y-m-d')." 00:00:00'";
      $DB->query($query);

      //No task & jobs should be removed because ask for cleaning 5 days from now
      $index = $pfTask->cleanTasksAndJobs(5);
      $this->assertEquals(0, $index);

      $this->assertEquals(true, $pfTask->getFromDB($tasks_id));

      $computer = new Computer();
      $pfAgent  = new PluginFusioninventoryAgent();

      //Add a new computer into the dynamic group
      $input = [
          'entities_id' => 0,
          'name'        => 'computer5'
      ];
      $computers_id = $computer->add($input);
      $this->assertNotFalse($computers_id);

      $input = [
          'entities_id'  => 0,
          'name'         => 'computer5',
          'version'      => '{"INVENTORY":"v2.3.21"}',
          'device_id'    => 'computer5',
          'useragent'    => 'FusionInventory-Agent_v2.3.21',
          'computers_id' => $computers_id
      ];
      $pfAgent->add($input);

      //Reprepare the task
      PluginFusioninventoryTask::cronTaskscheduler();

      //One taskjob is finished and should be cleaned
      $index = $pfTask->cleanTasksAndJobs(3);

      $this->assertGreaterThan(0, $index);

      //The task is still in DB because one job is not done
      $this->assertEquals(1, countElementsInTable('glpi_plugin_fusioninventory_tasks',
                                                  ['id' => $tasks_id]));

      //Set the first job as successfull
      $query = "SELECT DISTINCT `plugin_fusioninventory_taskjobstates_id`
                FROM glpi_plugin_fusioninventory_taskjoblogs";
      foreach ($DB->request($query) as $data) {
         $pfTaskJobstate->changeStatusFinish($data['plugin_fusioninventory_taskjobstates_id'], '', 0);
      }

      $query = "UPDATE `glpi_plugin_fusioninventory_taskjoblogs`
                SET `date`='".$datetime->format('Y-m-d')." 00:00:00'";
      $DB->query($query);

      //One taskjob is finished and should be cleaned
      $index = $pfTask->cleanTasksAndJobs(2);

      $this->assertGreaterThan(0, $index);

      //The task is still in DB because one job is not done
      $this->assertEquals(0, countElementsInTable('glpi_plugin_fusioninventory_tasks',
                                                  ['id' => $tasks_id]));
   }
}
