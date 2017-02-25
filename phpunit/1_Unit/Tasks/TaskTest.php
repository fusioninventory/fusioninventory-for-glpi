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
}
