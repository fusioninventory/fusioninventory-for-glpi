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

class TaskDeployDynamicGroup extends Common_TestCase {

   protected function setUp() {

      parent::setUp();

      self::restore_database();

      // Add some computers
      $computer = new Computer();
      $pfAgent  = new PluginFusioninventoryAgent();

      $computer->add(array('name' => 'pc01', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 1, 'entities_id' => 0));
      $computer->add(array('name' => 'pc02', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 2, 'entities_id' => 0));
      $computer->add(array('name' => 'pc03', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 3, 'entities_id' => 0));
      $computer->add(array('name' => 'pc04', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 4, 'entities_id' => 0));
      $computer->add(array('name' => 'pc05', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 5, 'entities_id' => 0));
      $computer->add(array('name' => 'pc06', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 6, 'entities_id' => 0));
      $computer->add(array('name' => 'pc07', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 7, 'entities_id' => 0));
      $computer->add(array('name' => 'pc08', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 8, 'entities_id' => 0));
      $computer->add(array('name' => 'pc09', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 9, 'entities_id' => 0));
      $computer->add(array('name' => 'pc10', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 10, 'entities_id' => 0));
      $computer->add(array('name' => 'pc11', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 11, 'entities_id' => 0));
      $computer->add(array('name' => 'pc12', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 12, 'entities_id' => 0));
      $computer->add(array('name' => 'pc13', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 13, 'entities_id' => 0));
      $computer->add(array('name' => 'srv01', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 14, 'entities_id' => 0));
      $computer->add(array('name' => 'srv02', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 15, 'entities_id' => 0));
      $computer->add(array('name' => 'srv03', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 16, 'entities_id' => 0));
      $computer->add(array('name' => 'srv04', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 17, 'entities_id' => 0));
      $computer->add(array('name' => 'srv05', 'entities_id' => 0));
      $pfAgent->add(array('computers_id'=> 18, 'entities_id' => 0));

   }


   /**
    * @test
    */
   public function TaskWithPC() {
      $this->mark_incomplete();
      global $DB;

      $_SESSION['glpiactiveentities_string'] = 0;

      $pfDeployGroup             = new PluginFusioninventoryDeployGroup();
      $pfDeployGroup_Dynamicdata = new PluginFusioninventoryDeployGroup_Dynamicdata();
      $pfDeployPackage           = new PluginFusioninventoryDeployPackage();
      $pfTask                    = new PluginFusioninventoryTask();
      $pfTaskJob                 = new PluginFusioninventoryTaskjob();

      $input = array(
         'name' => 'test',
         'type' => 'DYNAMIC'
      );
      $pfDeployGroup->add($input);

      $input = array(
         'groups_id'      => 1,
         'fields_array'   => '{"entities_id":"0","name":"","field":["1"],"searchtype":["contains"],"contains":["^pc"],"plugin_fusioninventory_deploygroup_dynamicdatas_id":"1","id":"1","updaterule":"Update this rule","itemtype":"Computer"}'
      );
      $pfDeployGroup_Dynamicdata->add($input);

      $input = array(
         'name'        => 'ls',
         'entities_id' => 0
      );
      $pfDeployPackage->add($input);

      $input = array(
         'name'           => 'deploy',
         'is_active'      => 1,
         'communication'  => 'pull'
      );
      $pfTask->add($input);

      $a_plugins = current(getAllDatasFromTable('glpi_plugins', '`directory`="fusioninventory"'));

      $input = array(
         'plugin_fusioninventory_tasks_id' => 1,
         'name'        =>'deploy',
         'plugins_id'  => $a_plugins['id'],
         'method'      => 'deployinstall',
         'action'      => '[{"PluginFusioninventoryDeployGroup":"1"}]'
      );
      $pfTaskJob->add($input);
      $DB->query('UPDATE `glpi_plugin_fusioninventory_taskjobs`
         SET `definition`=\'[{"PluginFusioninventoryDeployPackage":"1"}]\'
         WHERE `id`="1"');

      // Force task prepation
      $pfTaskJob->forceRunningTask(1);

      $a_jobstates = getAllDatasFromTable("glpi_plugin_fusioninventory_taskjobstates");
      foreach ($a_jobstates as $num=>$data) {
         unset($data['uniqid']);
         $a_jobstates[$num] = $data;
      }

      $a_reference = array(
         1 => array(
            'id'                                 => 1,
            'plugin_fusioninventory_taskjobs_id' => 1,
            'items_id'                           => 1,
            'itemtype'                           => "PluginFusioninventoryDeployPackage",
            'state'                              => 0,
            'plugin_fusioninventory_agents_id'   => 1,
            'specificity'                        => '',
            'execution_id'                       => '0'
         ),
         2 => array(
            'id'                                 => 2,
            'plugin_fusioninventory_taskjobs_id' => 1,
            'items_id'                           => 1,
            'itemtype'                           => "PluginFusioninventoryDeployPackage",
            'state'                              => 0,
            'plugin_fusioninventory_agents_id'   => 2,
            'specificity'                        => '',
            'execution_id'                       => '0'
         ),
         3 => array(
            'id'                                 => 3,
            'plugin_fusioninventory_taskjobs_id' => 1,
            'items_id'                           => 1,
            'itemtype'                           => "PluginFusioninventoryDeployPackage",
            'state'                              => 0,
            'plugin_fusioninventory_agents_id'   => 3,
            'specificity'                        => '',
            'execution_id'                       => '0'
         ),
         4 => array(
            'id'                                 => 4,
            'plugin_fusioninventory_taskjobs_id' => 1,
            'items_id'                           => 1,
            'itemtype'                           => "PluginFusioninventoryDeployPackage",
            'state'                              => 0,
            'plugin_fusioninventory_agents_id'   => 4,
            'specificity'                        => '',
            'execution_id'                       => '0'
         ),
         5 => array(
            'id'                                 => 5,
            'plugin_fusioninventory_taskjobs_id' => 1,
            'items_id'                           => 1,
            'itemtype'                           => "PluginFusioninventoryDeployPackage",
            'state'                              => 0,
            'plugin_fusioninventory_agents_id'   => 5,
            'specificity'                        => '',
            'execution_id'                       => '0'
         ),
         6 => array(
            'id'                                 => 6,
            'plugin_fusioninventory_taskjobs_id' => 1,
            'items_id'                           => 1,
            'itemtype'                           => "PluginFusioninventoryDeployPackage",
            'state'                              => 0,
            'plugin_fusioninventory_agents_id'   => 6,
            'specificity'                        => '',
            'execution_id'                       => '0'
         ),
         7 => array(
            'id'                                 => 7,
            'plugin_fusioninventory_taskjobs_id' => 1,
            'items_id'                           => 1,
            'itemtype'                           => "PluginFusioninventoryDeployPackage",
            'state'                              => 0,
            'plugin_fusioninventory_agents_id'   => 7,
            'specificity'                        => '',
            'execution_id'                       => '0'
         ),
         8 => array(
            'id'                                 => 8,
            'plugin_fusioninventory_taskjobs_id' => 1,
            'items_id'                           => 1,
            'itemtype'                           => "PluginFusioninventoryDeployPackage",
            'state'                              => 0,
            'plugin_fusioninventory_agents_id'   => 8,
            'specificity'                        => '',
            'execution_id'                       => '0'
         ),
         9 => array(
            'id'                                 => 9,
            'plugin_fusioninventory_taskjobs_id' => 1,
            'items_id'                           => 1,
            'itemtype'                           => "PluginFusioninventoryDeployPackage",
            'state'                              => 0,
            'plugin_fusioninventory_agents_id'   => 9,
            'specificity'                        => '',
            'execution_id'                       => '0'
         ),
         10 => array(
            'id'                                 => 10,
            'plugin_fusioninventory_taskjobs_id' => 1,
            'items_id'                           => 1,
            'itemtype'                           => "PluginFusioninventoryDeployPackage",
            'state'                              => 0,
            'plugin_fusioninventory_agents_id'   => 10,
            'specificity'                        => '',
            'execution_id'                       => '0'
         ),
         11 => array(
            'id'                                 => 11,
            'plugin_fusioninventory_taskjobs_id' => 1,
            'items_id'                           => 1,
            'itemtype'                           => "PluginFusioninventoryDeployPackage",
            'state'                              => 0,
            'plugin_fusioninventory_agents_id'   => 11,
            'specificity'                        => '',
            'execution_id'                       => '0'
         ),
         12 => array(
            'id'                                 => 12,
            'plugin_fusioninventory_taskjobs_id' => 1,
            'items_id'                           => 1,
            'itemtype'                           => "PluginFusioninventoryDeployPackage",
            'state'                              => 0,
            'plugin_fusioninventory_agents_id'   => 12,
            'specificity'                        => '',
            'execution_id'                       => '0'
         ),
         13 => array(
            'id'                                 => 13,
            'plugin_fusioninventory_taskjobs_id' => 1,
            'items_id'                           => 1,
            'itemtype'                           => "PluginFusioninventoryDeployPackage",
            'state'                              => 0,
            'plugin_fusioninventory_agents_id'   => 13,
            'specificity'                        => '',
            'execution_id'                       => '0'
         )
      );

      $this->assertEquals($a_reference, $a_jobstates);

   }
}
?>
