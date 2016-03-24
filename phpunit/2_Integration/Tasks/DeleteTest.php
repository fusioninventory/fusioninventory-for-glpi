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
   @author    Alexandre Delaunay
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

class DeleteTest extends RestoreDatabase_TestCase {

   private static $tasks_id = 0;
   private static $taskjobs_id = 0;
   private static $taskjobstates_id = 0;
   private static $taskjoblogs_id = 0;

   /**
    * @test
    */
   public function prepareDB() {
      global $DB;

      $DB->connect();

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();
      $pfDeployGroup   = new PluginFusioninventoryDeployGroup();
      $pfTask          = new PluginFusioninventoryTask();
      $pfTaskjob       = new PluginFusioninventoryTaskjob;
      $pfTaskjobState  = new PluginFusioninventoryTaskjobstate;
      $pfTaskjoblog    = new PluginFusioninventoryTaskjoblog;
      $pfDeployGroup_Dynamicdata = new PluginFusioninventoryDeployGroup_Dynamicdata();


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
      $pfDeployGroup_Dynamicdata->add($input);

      // create task
      $input = array(
          'entities_id' => 0,
          'name'        => 'deploy',
          'is_active'   => 1
      );
      self::$tasks_id = $pfTask->add($input);

      // create taskjob
      $input = array(
          'plugin_fusioninventory_tasks_id' => self::$tasks_id,
          'entities_id'                     => 0,
          'name'                            => 'deploy',
          'method'                          => 'deployinstall',
          'targets'                         => '[{"PluginFusioninventoryDeployPackage":"'.$packages_id.'"}]',
          'actors'                          => '[{"PluginFusioninventoryDeployGroup":"'.self::$tasks_id.'"}]'
      );
      self::$taskjobs_id = $pfTaskjob->add($input);

      //create taskjobstate
      $input = array(
          'plugin_fusioninventory_taskjobs_id' => self::$taskjobs_id,
          'items_id'                           => 0,
          'itemtype'                           => 'Computer',
          'state'                              => PluginFusioninventoryTaskjobstate::FINISHED,
          'plugin_fusioninventory_agents_id'   => 0,
          'specificity'                        => 0,
          'uniqid'                             => 0,
         
      );
      self::$taskjobstates_id = $pfTaskjobState->add($input);

      //crfeate taskjoblogR
      $input = array(
         'plugin_fusioninventory_taskjobstates_id' => self::$taskjobstates_id, 
         'date '                                   => date('Y-m-d H:i:s'), 
         'items_id'                                => 0,
         'itemtype'                                => 'Computer',
         'state'                                   => PluginFusioninventoryTaskjoblog::TASK_RUNNING,
         'comment'                                 => "1 ==devicesfound=="
      );
      self::$taskjoblogs_id = $pfTaskjoblog->add($input);
   }


   /**
    * @test
    */
   public function deleteTask() {
      global $DB;

      $pfTask         = new PluginFusioninventoryTask();
      $pfTaskjob      = new PluginFusioninventoryTaskjob;
      $pfTaskjobState = new PluginFusioninventoryTaskjobstate;
      $pfTaskjoblog   = new PluginFusioninventoryTaskjoblog;

      //delete task
      $return = $pfTask->delete(array('id' => self::$tasks_id));
      $this->assertEquals(true, $return);

      //check deletion of job
      $jobs_found = $pfTaskjob->find("id = ".self::$taskjobs_id);
      $this->assertEquals(array(), $jobs_found);

      //check deletion of state
      $states_found = $pfTaskjobState->find("id = ".self::$taskjobstates_id);
      $this->assertEquals(array(), $states_found);

      //check deletion of log
      $logs_found = $pfTaskjoblog->find("id = ".self::$taskjobstates_id);
      $this->assertEquals(array(), $logs_found);
   }
}
?>
