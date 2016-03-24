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
      $tasks_id = $pfTask->add($input);

      // create takjob
      $input = array(
          'plugin_fusioninventory_tasks_id' => $tasks_id,
          'entities_id'                     => 0,
          'name'                            => 'deploy',
          'method'                          => 'deployinstall',
          'targets'                         => '[{"PluginFusioninventoryDeployPackage":"'.$packages_id.'"}]',
          'actors'                          => '[{"PluginFusioninventoryDeployGroup":"'.$tasks_id.'"}]'
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
          3 => 1,
          4 => 1
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


}
?>
