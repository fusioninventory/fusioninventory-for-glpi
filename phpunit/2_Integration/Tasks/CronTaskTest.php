<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

class CronTaskTest extends RestoreDatabase_TestCase {

   /**
    * @test
    */
   public function prepareDB() {
      global $DB;

      $DB->connect();

      $computer        = new Computer();
      $pfAgent         = new PluginFusioninventoryAgent();
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $pfDeployGroup   = new PluginFusioninventoryDeployGroup();
      $pfTask          = new PluginFusioninventoryTask();
      $pfTaskjob       = new PluginFusioninventoryTaskjob;
      $pfDeployGroup_Dynamicdata = new PluginFusioninventoryDeployGroup_Dynamicdata();
      $pfEntity        = new PluginFusioninventoryEntity();


      $input = array(
          'id'             => 1,
          'agent_base_url' => 'http://127.0.0.1/glpi'
      );
      $pfEntity->update($input);

      // Create package
      $input = array(
          'entities_id' => 0,
          'name'        => 'package'
      );
      $packages_id = $pfDeployPackage->add($input);

      // Create fusioninventory dynamic group
      $input = array(
          'name' => 'all computers have name computer',
          'type' => 'DYNAMIC'
      );
      $groups_id = $pfDeployGroup->add($input);

      $input = array(
          'plugin_fusioninventory_deploygroups_id' => $groups_id,
          'fields_array' => 'a:2:{s:8:"criteria";a:1:{i:0;a:3:{s:5:"field";s:1:"1";s:10:"searchtype";s:8:"contains";s:5:"value";s:8:"computer";}}s:12:"metacriteria";s:0:"";}'
      );
      $groups_id = $pfDeployGroup_Dynamicdata->add($input);

      // create task
      $input = array(
          'entities_id' => 0,
          'name'        => 'deploy',
          'is_active'   => 1
      );
      $tasks_id = $pfTask->add($input);

      // create takjob
      $input = array(
          'plugin_fusioninventory_tasks_id' => $tasks_id,
          'entities_id'                     => 0,
          'name'                            => 'deploy',
          'method'                          => 'deployinstall',
          'targets'                         => '[{"PluginFusioninventoryDeployPackage":"'.$packages_id.'"}]',
          'actors'                          => '[{"PluginFusioninventoryDeployGroup":"'.$groups_id.'"}]'
      );
      $pfTaskjob->add($input);

      // Create computers + agents
      $input = array(
          'entities_id' => 0,
          'name'        => 'computer1'
      );
      $computers_id = $computer->add($input);

      $input = array(
          'entities_id' => 0,
          'name'        => 'portdavid',
          'version'     => '{"INVENTORY":"v2.3.11"}',
          'device_id'   => 'portdavid',
          'useragent'   => 'FusionInventory-Agent_v2.3.11',
          'computers_id'=> $computers_id
      );
      $pfAgent->add($input);


      $input = array(
          'entities_id' => 0,
          'name'        => 'computer2'
      );
      $computers_id = $computer->add($input);

      $input = array(
          'entities_id' => 0,
          'name'        => 'computer2',
          'version'     => '{"INVENTORY":"v2.3.11"}',
          'device_id'   => 'computer2',
          'useragent'   => 'FusionInventory-Agent_v2.3.11',
          'computers_id'=> $computers_id
      );
      $pfAgent->add($input);


      $input = array(
          'entities_id' => 0,
          'name'        => 'computer3'
      );
      $computers_id = $computer->add($input);

      $input = array(
          'entities_id' => 0,
          'name'        => 'computer3',
          'version'     => '{"INVENTORY":"v2.3.11"}',
          'device_id'   => 'computer3',
          'useragent'   => 'FusionInventory-Agent_v2.3.11',
          'computers_id'=> $computers_id
      );
      $pfAgent->add($input);

      // Create package
      $input = array(
          'entities_id' => 0,
          'name'        => 'on demand package',
          'is_recursive' => 0,
          'plugin_fusioninventory_deploygroups_id' => $groups_id,
          'json' => '{"jobs":{"checks":[],"associatedFiles":[],"actions":[]},"associatedFiles":[]}'
      );
      $packages_id_2 = $pfDeployPackage->add($input);

      // create task
      $input = array(
          'entities_id'             => 0,
          'name'                    => 'ondemand',
          'is_active'               => 1,
          'is_deploy_on_demand'     => 1,
          'reprepare_if_successful' => 0
      );
      $tasks_id_2 = $pfTask->add($input);

      // create takjob
      $input = array(
          'plugin_fusioninventory_tasks_id' => $tasks_id_2,
          'entities_id'                     => 0,
          'name'                            => 'deploy',
          'method'                          => 'deployinstall',
          'targets'                         => '[{"PluginFusioninventoryDeployPackage":"'.$packages_id_2.'"}]',
          'actors'                          => '[{"PluginFusioninventoryDeployGroup":"'.$groups_id.'"}]'
      );
      $pfTaskjob->add($input);

   }


   /**
    * @test
    */
   public function prepareTask() {
      global $DB;

      // Verify prepare a deploy task
      $DB->connect();

      PluginFusioninventoryTask::cronTaskscheduler();

      $pfTask = new PluginFusioninventoryTask();

      $data = $pfTask->getJoblogs(array(1));

      $ref = array(
          1 => 'portdavid',
          2 => 'computer2',
          3 => 'computer3'
      );

      $this->assertEquals($ref, $data['agents']);
   }



   /**
    * @test
    */
   public function prepareTaskWithNewComputer() {
      global $DB;

      // Verify add new agent when have new computer (dynamic group) in deploy task
      $DB->connect();

      $computer = new Computer();
      $pfAgent  = new PluginFusioninventoryAgent();

      $input = array(
          'entities_id' => 0,
          'name'        => 'computer4'
      );
      $computers_id = $computer->add($input);

      $input = array(
          'entities_id' => 0,
          'name'        => 'computer4',
          'version'     => '{"INVENTORY":"v2.3.11"}',
          'device_id'   => 'computer4',
          'useragent'   => 'FusionInventory-Agent_v2.3.11',
          'computers_id'=> $computers_id
      );
      $pfAgent->add($input);


      PluginFusioninventoryTask::cronTaskscheduler();

      $pfTask = new PluginFusioninventoryTask();

      $data = $pfTask->getJoblogs(array(1));

      $ref = array(
          1 => 'portdavid',
          2 => 'computer2',
          3 => 'computer3',
          4 => 'computer4'
      );

      $this->assertEquals($ref, $data['agents']);
   }



   /**
    * @test
    */
   public function prepareTaskWithdynamicgroupchanged() {
      global $DB;

      // Verify cancel agent prepared when one computer not verify dynamic group in deploy task
      $DB->connect();

      $computer = new Computer();

      $computer->update(array(
          'id' => 2,
          'name' => 'koin'));

      PluginFusioninventoryTask::cronTaskscheduler();

      $pfTask = new PluginFusioninventoryTask();

      $data = $pfTask->getJoblogs(array(1));

      $ref = array(
          1 => 'portdavid',
          2 => 'computer2',
          3 => 'computer3',
          4 => 'computer4'
      );

      $this->assertEquals($ref, $data['agents']);

      $ref_prepared = array(
          1 => 1,
          3 => 3,
          4 => 7
      );

      $this->assertEquals($ref_prepared, $data['tasks'][1]['jobs'][1]['targets']['PluginFusioninventoryDeployPackage_1']['counters']['agents_prepared']);
   }



   /**
    * @test
    */
   public function prepareTaskDisabled() {
      global $DB;

      $DB->connect();

      $pfTask = new PluginFusioninventoryTask();

      $pfTask->update(array(
          'id'        => 1,
          'is_active' => 0));

      PluginFusioninventoryTask::cronTaskscheduler();

      $data = $pfTask->getJoblogs(array(1));

      $ref = array();

      $this->assertEquals($ref, $data['agents'], 'Task inactive, so no agent prepared');

      $ref_prepared = array();

      $this->assertEquals($ref_prepared, $data['tasks']);
   }



   /**
    * @test
    */
   public function prepareTaskNotRePrepareIfSuccessful() {
      global $DB;

      $_SESSION['glpi_plugin_fusioninventory']['includeoldjobs'] = 2;

      // Verify prepare a deploy task
      $DB->connect();

      $pfAgent      = new PluginFusioninventoryAgent();
      $pfTask       = new PluginFusioninventoryTask();
      $deploycommon = new PluginFusioninventoryDeployCommon();

      $DB->query("TRUNCATE TABLE `glpi_plugin_fusioninventory_taskjoblogs`");
      $DB->query("TRUNCATE TABLE `glpi_plugin_fusioninventory_taskjobstates`");

      $input = array(
          'id'                      => 1,
          'reprepare_if_successful' => 0,
          'is_active'               => 1
      );
      $pfTask->update($input);

      // prepare
      PluginFusioninventoryTask::cronTaskscheduler();

      // 1 computer deploy successfully
      $agent = $pfAgent->infoByKey('portdavid');
      $taskjobstates = $pfTask->getTaskjobstatesForAgent(
         $agent['id'],
         array('deployinstall')
      );
      foreach ($taskjobstates as $taskjobstate) {
         $jobstate_order = $deploycommon->run($taskjobstate);
         $params = array(
            'machineid' => 'portdavid',
            'uuid'      => $jobstate_order['job']['uuid'],
            'code'      => 'ok',
            'msg'       => 'seems ok',
            'sendheaders' => False
         );
         PluginFusioninventoryCommunicationRest::updateLog($params);
      }

      // 1 computer in error
      $agent = $pfAgent->infoByKey('computer3');
      $taskjobstates = $pfTask->getTaskjobstatesForAgent(
         $agent['id'],
         array('deployinstall')
      );
      foreach ($taskjobstates as $taskjobstate) {
        $jobstate_order = $deploycommon->run($taskjobstate);
        $params = array(
           'machineid' => 'computer3',
           'uuid'      => $jobstate_order['job']['uuid'],
           'code'      => 'running',
           'msg'       => 'gogogo',
           'sendheaders' => False
        );
        PluginFusioninventoryCommunicationRest::updateLog($params);
        $params = array(
           'machineid' => 'computer3',
           'uuid'      => $jobstate_order['job']['uuid'],
           'code'      => 'ko',
           'msg'       => 'failure of check #1 (error)',
           'sendheaders' => False
        );
        PluginFusioninventoryCommunicationRest::updateLog($params);
      }

      // re-prepare and will have only the computer in error be in prepared mode
      $data = $pfTask->getJoblogs(array(1));
      $reference = array(
          'agents_prepared' => array(
              '4' => 3
              ),
          'agents_cancelled' => array(),
          'agents_running' => array(),
          'agents_success' => array(
              '1' => 1
          ),
          'agents_error' => array(
              '3' => 2
          ),
          'agents_notdone' => array(
              '4' => 3
          )
      );
      $counters = $data['tasks'][1]['jobs'][1]['targets']['PluginFusioninventoryDeployPackage_1']['counters'];
      $this->assertEquals($reference, $counters);

      PluginFusioninventoryTask::cronTaskscheduler();
      $data = $pfTask->getJoblogs(array(1));
      $reference = array(
          'agents_prepared' => array(
              '3' => 7,
              '4' => 3
              ),
          'agents_cancelled' => array(),
          'agents_running' => array(),
          'agents_success' => array(
              '1' => 1
          ),
          'agents_error' => array(
              '3' => 2
          ),
          'agents_notdone' => array(
              '4' => 3
          )
      );
      $counters = $data['tasks'][1]['jobs'][1]['targets']['PluginFusioninventoryDeployPackage_1']['counters'];
      $this->assertEquals($reference, $counters);


      $input = array(
          'id'                      => 1,
          'reprepare_if_successful' => 1,
      );
      $pfTask->update($input);
      PluginFusioninventoryTask::cronTaskscheduler();
      $data = $pfTask->getJoblogs(array(1));
      $reference = array(
          'agents_prepared' => array(
              '1' => 9,
              '3' => 7,
              '4' => 3
              ),
          'agents_cancelled' => array(),
          'agents_running' => array(),
          'agents_success' => array(
              '1' => 1
          ),
          'agents_error' => array(
              '3' => 2
          ),
          'agents_notdone' => array(
              '4' => 3
          )
      );
      $counters = $data['tasks'][1]['jobs'][1]['targets']['PluginFusioninventoryDeployPackage_1']['counters'];
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

      $DB->connect();

      //We only work on 1 task
      $pfTask->delete(['id' => 1], true);

      //Clean all taskjoblogs & states
      $DB->query("TRUNCATE TABLE `glpi_plugin_fusioninventory_taskjoblogs`");
      $DB->query("TRUNCATE TABLE `glpi_plugin_fusioninventory_taskjobstates`");

      //Find the on demand task
      $tasks = $pfTask->find("`name`='ondemand'");
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
          'name'        => 'computer4'
      ];
      $computers_id = $computer->add($input);

      $input = [
          'entities_id'  => 0,
          'name'         => 'computer4',
          'version'      => '{"INVENTORY":"v2.3.21"}',
          'device_id'    => 'computer4',
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

?>
