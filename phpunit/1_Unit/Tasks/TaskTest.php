<?php
class TaskTest extends Common_TestCase {

   /**
    * @test
    */
   public function addTask() {
      $pfTask    = new PluginFusioninventoryTask();
      $pfTaskJob = new PluginFusioninventoryTaskJob();

      $input = ['name' => 'MyTask', 'entities_id' => 0,
                'reprepare_if_successful' => 1, 'comment' => 'MyComments',
                'is_active' => 1];
      $tasks_id = $pfTask->add($input);
      $this->assertGreaterThan(0, $tasks_id);

      $this->assertTrue($pfTask->getFromDB($tasks_id));
      $this->assertEquals('MyTask', $pfTask->fields['name']);
      $this->assertEquals(1, $pfTask->fields['is_active']);

      $input = ['plugin_fusioninventory_tasks_id' => $tasks_id,
                'name'        =>'deploy',
                'method'      => 'deploy',
                'actors'      => '[{"PluginFusioninventoryDeployGroup":"1"}]'
               ];
      $taskjobs_id = $pfTaskJob->add($input);
      $this->assertGreaterThan(0, $taskjobs_id);
      $this->assertTrue($pfTaskJob->getFromDB($taskjobs_id));
      $this->assertEquals('deploy', $pfTaskJob->fields['name']);
      $this->assertEquals('[{"PluginFusioninventoryDeployGroup":"1"}]',
                          $pfTaskJob->fields['actors']);
   }

   /**
    * @test
    */
   public function duplicateTask() {
      $pfTask    = new PluginFusioninventoryTask();
      $pfTaskJob = new PluginFusioninventoryTaskJob();

      $data = $pfTask->find("`name`='MyTask'");
      $this->assertEquals(1, count($data));
      $tmp = current($data);
      $source_tasks_id = $tmp['id'];

      $this->assertTrue($pfTask->duplicate($source_tasks_id));

      $data = $pfTask->find("`name`='Copy of MyTask'");
      $this->assertEquals(1, count($data));
      $tmp = current($data);
      $target_tasks_id = $tmp['id'];

      $this->assertTrue($pfTask->getFromDB($target_tasks_id));
      $this->assertEquals(0, $pfTask->fields['is_active']);

      $data = $pfTaskJob->find("`plugin_fusioninventory_tasks_id`='$target_tasks_id'");
      $this->assertEquals(1, count($data));
      $tmp = current($data);
      $target_taskjobs_id = $tmp['id'];
      $this->assertTrue($pfTaskJob->getFromDB($target_taskjobs_id));
      $this->assertEquals('deploy', $pfTaskJob->fields['method']);
      $this->assertEquals('[{"PluginFusioninventoryDeployGroup":"1"}]',
                          $pfTaskJob->fields['actors']);
   }

   /**
    * @test
    */
   public function deleteTask() {
      $pfTask    = new PluginFusioninventoryTask();
      $pfTaskJob = new PluginFusioninventoryTaskJob();

      $data = $pfTask->find("`name`='Copy of MyTask'");
      $this->assertEquals(1, count($data));
      $tmp = current($data);
      $tasks_id = $tmp['id'];

      $data = $pfTaskJob->find("`plugin_fusioninventory_tasks_id`='$tasks_id'");
      $this->assertEquals(1, count($data));
      $tmp = current($data);
      $taskjobs_id = $tmp['id'];

      $this->assertTrue($pfTask->delete(['id' => $tasks_id]));
      $this->assertFalse($pfTask->getFromDB($tasks_id));
      $this->assertFalse($pfTaskJob->getFromDB($taskjobs_id));
   }

   /**
    * @test
    */
   public function getOnDemandTasksToClean() {
      $pfTask    = new PluginFusioninventoryTask();
      $pfTaskJob = new PluginFusioninventoryTaskJob();

      //Task to clean : finished since more than 5 days
      $input = ['name'                    => 'TaskToClean',
                'entities_id'             => 0,
                'reprepare_if_successful' => 1,
                'comment'                 => 'MyComments',
                'is_active'               => 1,
                'is_deploy_on_demand'     => 1,
                'datetime_start'          => '2017-02-27 10:20:02',
                'datetime_end'            => '2017-02-27 10:25:02'
               ];
      $tasks_id = $pfTask->add($input);
      $this->assertGreaterThan(0, $tasks_id);

      //Task to keep : finished since less that 5 days
      $input = ['name'                    => 'TaskNotToClean1',
                'entities_id'             => 0,
                'reprepare_if_successful' => 1,
                'comment'                 => 'MyComments',
                'is_active'               => 1,
                'is_deploy_on_demand'     => 1,
                'datetime_start'          => $_SESSION['glpi_currenttime'],
                'datetime_end'            => $_SESSION['glpi_currenttime']
               ];
      $tasks_id_2 = $pfTask->add($input);
      $this->assertGreaterThan(0, $tasks_id_2);

      //Task to keep : not a on demand deploy task
      $input = ['name'                    => 'TaskNotToClean2',
                'entities_id'             => 0,
                'reprepare_if_successful' => 1,
                'comment'                 => 'MyComments',
                'is_active'               => 1,
                'is_deploy_on_demand'     => 0,
                'datetime_start'          => '2017-02-27 10:20:02',
                'datetime_end'            => '2017-02-27 10:25:02'
               ];
      $tasks_id_3 = $pfTask->add($input);
      $this->assertGreaterThan(0, $tasks_id_3);

      //Clean tasks successfully executed after 5 days
      $tasksToClean = $pfTask->getOnDemandTasksToClean(5);
      $this->assertEquals(count($tasksToClean), 1);
   }

   /**
    * @test
    */
   public function cleanondemand() {
      $crontask = new Crontask();

      $config = new PluginFusioninventoryConfig();
      //test if on demand task delay is disable by default in configuration
      $this->assertEquals($config->getValue('clean_on_demand_tasks'), 0);

      //Launch the crontask : no task should be deleted
      PluginFusioninventoryTask::cronCleanOnDemand($crontask);
      $this->assertEquals(countElementsInTable('glpi_plugin_fusioninventory_tasks',
                                              "`name`='TaskToClean'"), 1);

      //There's still 3 tasks in DB
      $this->assertEquals(countElementsInTable('glpi_plugin_fusioninventory_tasks'), 3);

      //Set the delay to 5 days
      $config->updateValue('clean_on_demand_tasks', 5);


      //Launch the crontask : one task should was deleted
      PluginFusioninventoryTask::cronCleanOnDemand($crontask);
      $this->assertEquals(countElementsInTable('glpi_plugin_fusioninventory_tasks',
                                              "`name`='TaskToClean'"), 0);
      //Only two tasks left in DB
      $this->assertEquals(countElementsInTable('glpi_plugin_fusioninventory_tasks'), 2);
   }
}
