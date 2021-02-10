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
   @author    Alexandre Delaunay
   @co-author David Durieux
   @copyright Copyright (C) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class DeleteTaskTest extends TestCase {

   private static $taskId = 0;
   private static $taskjobId = 0;
   private static $taskjobstateId = 0;
   private static $taskjoblogId = 0;


   public static function setUpBeforeClass(): void {

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $pfDeployGroup   = new PluginFusioninventoryDeployGroup();
      $pfTask          = new PluginFusioninventoryTask();
      $pfTaskjob       = new PluginFusioninventoryTaskjob;
      $pfTaskjobState  = new PluginFusioninventoryTaskjobstate;
      $pfTaskjoblog    = new PluginFusioninventoryTaskjoblog;
      $pfDeployGrDyndata = new PluginFusioninventoryDeployGroup_Dynamicdata();

      // Delete all task
      $pfTask = new PluginFusioninventoryTask();
      $items = $pfTask->find();
      foreach ($items as $item) {
         $pfTask->delete(['id' => $item['id']], true);
      }

      // Create package
      $input = [
          'entities_id' => 0,
          'name'        => 'package'
      ];
      $$packageId = $pfDeployPackage->add($input);

      // Create fusioninventory dynamic group
      $input = [
          'name' => 'all computers have name computer',
          'type' => 'DYNAMIC'
      ];
      $groupId = $pfDeployGroup->add($input);

      $input = [
          'plugin_fusioninventory_deploygroups_id' => $groupId,
          'fields_array' => 'a:2:{s:8:"criteria";a:1:{i:0;a:3:{s:5:"field";s:1:"1";s:10:"searchtype";s:8:"contains";s:5:"value";s:8:"computer";}}s:12:"metacriteria";s:0:"";}'
      ];
      $pfDeployGrDyndata->add($input);

      // create task
      $input = [
          'entities_id' => 0,
          'name'        => 'deploy',
          'is_active'   => 1
      ];
      self::$taskId = $pfTask->add($input);

      // create taskjob
      $input = [
          'plugin_fusioninventory_tasks_id' => self::$taskId,
          'entities_id'                     => 0,
          'name'                            => 'deploy',
          'method'                          => 'deployinstall',
          'targets'                         => '[{"PluginFusioninventoryDeployPackage":"'.$$packageId.'"}]',
          'actors'                          => '[{"PluginFusioninventoryDeployGroup":"'.self::$taskId.'"}]'
      ];
      self::$taskjobId = $pfTaskjob->add($input);

      //create taskjobstate
      $input = [
          'plugin_fusioninventory_taskjobs_id' => self::$taskjobId,
          'items_id'                           => 0,
          'itemtype'                           => 'Computer',
          'state'                              => PluginFusioninventoryTaskjobstate::FINISHED,
          'plugin_fusioninventory_agents_id'   => 0,
          'specificity'                        => 0,
          'uniqid'                             => 0,

      ];
      self::$taskjobstateId = $pfTaskjobState->add($input);

      //crfeate taskjoblogR
      $input = [
         'plugin_fusioninventory_taskjobstates_id' => self::$taskjobstateId,
         'date '                                   => date('Y-m-d H:i:s'),
         'items_id'                                => 0,
         'itemtype'                                => 'Computer',
         'state'                                   => PluginFusioninventoryTaskjoblog::TASK_RUNNING,
         'comment'                                 => "1 ==devicesfound=="
      ];
      self::$taskjoblogId = $pfTaskjoblog->add($input);
   }


   /**
    * @test
    */
   public function deleteTask() {

      $pfTask         = new PluginFusioninventoryTask();
      $pfTaskjob      = new PluginFusioninventoryTaskjob;
      $pfTaskjobState = new PluginFusioninventoryTaskjobstate;
      $pfTaskjoblog   = new PluginFusioninventoryTaskjoblog;

      //delete task
      $return = $pfTask->delete(['id' => self::$taskId]);
      $this->assertEquals(true, $return);

      //check deletion of job
      $jobsFound = $pfTaskjob->find(['id' => self::$taskjobId]);
      $this->assertEquals([], $jobsFound);

      //check deletion of state
      $statesFound = $pfTaskjobState->find(['id' => self::$taskjobstateId]);
      $this->assertEquals([], $statesFound);

      //check deletion of log
      $logsFound = $pfTaskjoblog->find(['id' => self::$taskjoblogId]);
      $this->assertEquals([], $logsFound);
   }
}
