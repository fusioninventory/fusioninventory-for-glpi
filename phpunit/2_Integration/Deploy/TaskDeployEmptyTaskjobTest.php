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

class TaskDeployEmptyTaskjob extends Common_TestCase {

   protected function setUp() {
      parent::setUp();
      self::restore_database();
   }

   /**
    * @test
    */
   public function TaskWithoutTarget() {
      global $DB;

      $_SESSION['glpiactiveentities_string'] = 0;

      $pfDeployGroup             = new PluginFusioninventoryDeployGroup();
      $pfDeployGroup_Dynamicdata = new PluginFusioninventoryDeployGroup_Dynamicdata();
      $pfDeployPackage           = new PluginFusioninventoryDeployPackage();
      $pfTask                    = new PluginFusioninventoryTask();
      $pfTaskJob                 = new PluginFusioninventoryTaskjob();
      $computer = new Computer();
      $pfAgent  = new PluginFusioninventoryAgent();

      $computers_id = $computer->add(['name' => 'pc01', 'entities_id' => 0]);
      $this->assertNotFalse($computers_id);
      $agents_id = $pfAgent->add(['computers_id'=> $computers_id, 'entities_id' => 0]);
      $this->assertNotFalse($agents_id);

      $input = [
         'name'        => 'ls',
         'entities_id' => 0,
         'json'        => '{"jobs":{"checks":[],"associatedFiles":[],"actions":[{"cmd":{"exec":"ls","name":"","logLineLimit":"-1","retChecks":[{"type":"okCode","values":["0"]}]}}],"userinteractions":[]},"associatedFiles":[]}'
      ];
      $packages_id = $pfDeployPackage->add($input);
      $this->assertNotFalse($packages_id);

      $input = [
         'name'           => 'deploy',
         'entities_id'    => 0,
         'is_active'      => 1
      ];
      $tasks_id = $pfTask->add($input);
      $this->assertNotFalse($tasks_id);

      $input = [
         'plugin_fusioninventory_tasks_id' => $tasks_id,
         'name'        =>'deploy',
         'entities_id' => 0,
         'method'      => 'deployinstall',
         'actors'      => '[{"Computer":"'.$computers_id.'"}]',
         'targets'     => '[{"PluginFusioninventoryDeployPackage":"'.$packages_id.'"}]'
      ];
      $taskjobs_id = $pfTaskJob->add($input);
      $this->assertNotFalse($taskjobs_id);

      // Force task prepation
      $pfTask->forceRunning();

      $a_jobstates = getAllDatasFromTable("glpi_plugin_fusioninventory_taskjobstates");
      $this->assertEquals(1, count($a_jobstates));
      foreach ($a_jobstates as $num=>$data) {
         unset($data['uniqid']);
         $a_jobstates[$num] = $data;
      }

      $a_reference = [
         1 => [
            'id' => '1',
            'plugin_fusioninventory_taskjobs_id' => $taskjobs_id,
            'items_id' => $packages_id,
            'itemtype' => 'PluginFusioninventoryDeployPackage',
            'state' => PluginFusioninventoryTaskjobstate::PREPARED,
            'plugin_fusioninventory_agents_id' => $agents_id,
            'specificity' => null,
            'date_start' => null,
            'nb_retry' => '0',
            'max_retry' => '1'
         ],
      ];
      $this->assertEquals($a_reference, $a_jobstates);

      $pfTaskJob->update([
         'id'      => $taskjobs_id,
         'targets' => ''
      ]);

      // Force task prepation
      $pfTask->forceRunning();

      $a_jobstates = getAllDatasFromTable("glpi_plugin_fusioninventory_taskjobstates");
      foreach ($a_jobstates as $num=>$data) {
         unset($data['uniqid']);
         $a_jobstates[$num] = $data;
      }

      $a_reference = [
         1 => [
            'id' => '1',
            'plugin_fusioninventory_taskjobs_id' => $taskjobs_id,
            'items_id' => $packages_id,
            'itemtype' => 'PluginFusioninventoryDeployPackage',
            'state' => PluginFusioninventoryTaskjobstate::CANCELLED,
            'plugin_fusioninventory_agents_id' => $agents_id,
            'specificity' => null,
            'date_start' => null,
            'nb_retry' => '0',
            'max_retry' => '1'
         ],
      ];
      $this->assertEquals($a_reference, $a_jobstates);
   }

   /**
    * @test
    */
   public function TaskWithoutTargetActor() {
      global $DB;

      $_SESSION['glpiactiveentities_string'] = 0;

      $pfDeployGroup             = new PluginFusioninventoryDeployGroup();
      $pfDeployGroup_Dynamicdata = new PluginFusioninventoryDeployGroup_Dynamicdata();
      $pfDeployPackage           = new PluginFusioninventoryDeployPackage();
      $pfTask                    = new PluginFusioninventoryTask();
      $pfTaskJob                 = new PluginFusioninventoryTaskjob();
      $computer = new Computer();
      $pfAgent  = new PluginFusioninventoryAgent();

      $computers_id = $computer->add(['name' => 'pc01', 'entities_id' => 0]);
      $agents_id = $pfAgent->add(['computers_id'=> $computers_id, 'entities_id' => 0]);


      $input = [
         'name'        => 'ls',
         'entities_id' => 0
      ];
      $packages_id = $pfDeployPackage->add($input);

      $input = [
         'name'           => 'deploy',
         'is_active'      => 1,
         'communication'  => 'pull'
      ];
      $tasks_id = $pfTask->add($input);

      $a_plugins = current(getAllDatasFromTable('glpi_plugins', ['directory' => 'fusioninventory']));

      $input = [
         'plugin_fusioninventory_tasks_id' => $tasks_id,
         'name'        =>'deploy',
         'is_active'   => 1,
         'method'      => 'deployinstall',
         'actors'      => '[{"Computer":"'.$computers_id.'"}]',
         'targets'     => '[{"PluginFusioninventoryDeployPackage":"'.$packages_id.'"}]'
      ];
      $taskjobs_id = $pfTaskJob->add($input);

      // Force task prepation
      $pfTask->forceRunning();

      $a_jobstates = getAllDatasFromTable("glpi_plugin_fusioninventory_taskjobstates");
      foreach ($a_jobstates as $num=>$data) {
         unset($data['uniqid']);
         $a_jobstates[$num] = $data;
      }

      $a_reference = [
         1 => [
            'id' => '1',
            'plugin_fusioninventory_taskjobs_id' => $taskjobs_id,
            'items_id' => $packages_id,
            'itemtype' => 'PluginFusioninventoryDeployPackage',
            'state' => PluginFusioninventoryTaskjobstate::PREPARED,
            'plugin_fusioninventory_agents_id' => $agents_id,
            'specificity' => null,
            'date_start' => null,
            'nb_retry' => '0',
            'max_retry' => '1'
         ],
      ];
      $this->assertEquals($a_reference, $a_jobstates);

      $pfTaskJob->update([
         'id'     => $taskjobs_id,
         'actors' => ''
      ]);

      // Force task prepation
      $pfTask->forceRunning();

      $a_jobstates = getAllDatasFromTable("glpi_plugin_fusioninventory_taskjobstates");
      foreach ($a_jobstates as $num=>$data) {
         unset($data['uniqid']);
         $a_jobstates[$num] = $data;
      }

      $a_reference = [
         1 => [
            'id' => '1',
            'plugin_fusioninventory_taskjobs_id' => $taskjobs_id,
            'items_id' => $packages_id,
            'itemtype' => 'PluginFusioninventoryDeployPackage',
            'state' => PluginFusioninventoryTaskjobstate::CANCELLED,
            'plugin_fusioninventory_agents_id' => $agents_id,
            'specificity' => null,
            'date_start' => null,
            'nb_retry' => '0',
            'max_retry' => '1'
         ],
      ];
      $this->assertEquals($a_reference, $a_jobstates);
   }

}
