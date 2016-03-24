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

class NetworkDiscoveryTest extends RestoreDatabase_TestCase {

   /**
    * @test
    */
   public function prepareDB() {
      global $DB;

      $DB->connect();

      $computer        = new Computer();
      $pfAgent         = new PluginFusioninventoryAgent();
      $pfTask          = new PluginFusioninventoryTask();
      $pfTaskjob       = new PluginFusioninventoryTaskjob;
      $pfIPRange       = new PluginFusioninventoryIPRange();

      // Create computers + agents
      $input = array(
          'entities_id' => 0,
          'name'        => 'computer1'
      );
      $computers_id = $computer->add($input);

      $input = array(
          'entities_id' => 0,
          'name'        => 'computer1',
          'version'     => '{"INVENTORY":"v2.3.11"}',
          'device_id'   => 'computer1',
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


      // Add IPRange
      $input = array(
          'entities_id' => 0,
          'name'        => 'Office',
          'ip_start'    => '10.0.0.1',
          'ip_end'      => '10.0.0.254'
      );
      $ipranges_id = $pfIPRange->add($input);

      $input = array(
          'entities_id' => 0,
          'name'        => 'Office2',
          'ip_start'    => '10.0.2.1',
          'ip_end'      => '10.0.2.254'
      );
      $ipranges_id2 = $pfIPRange->add($input);

      // Allow all agents to do network discovery
      $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules` "
              . " SET `is_active`='1' "
              . " WHERE `modulename`='NETWORKDISCOVERY'";
      $DB->query($query);

      // create task
      $input = array(
          'entities_id' => 0,
          'name'        => 'network discovery',
          'is_active'   => 1
      );
      $tasks_id = $pfTask->add($input);

      // create taskjob
      $input = array(
          'plugin_fusioninventory_tasks_id' => $tasks_id,
          'entities_id'                     => 0,
          'name'                            => 'discovery',
          'method'                          => 'networkdiscovery',
          'targets'                         => '[{"PluginFusioninventoryIPRange":"'.$ipranges_id.'"}]',
          'actors'                          => '[{"PluginFusioninventoryAgent":"2"}]'
      );
      $pfTaskjob->add($input);


      // create task
      $input = array(
          'entities_id' => 0,
          'name'        => 'network discovery2',
          'is_active'   => 1
      );
      $tasks2_id = $pfTask->add($input);

      // create taskjob
      $input = array(
          'plugin_fusioninventory_tasks_id' => $tasks2_id,
          'entities_id'                     => 0,
          'name'                            => 'discovery',
          'method'                          => 'networkdiscovery',
          'targets'                         => '[{"PluginFusioninventoryIPRange":"'.$ipranges_id2.'"}]',
          'actors'                          => '[{"PluginFusioninventoryAgent":"3"}]'
      );
      $pfTaskjob->add($input);

   }


   /**
    * @test
    */
   public function prepareTask() {
      global $DB;

      // Verify prepare a network discovery task
      $DB->connect();

      PluginFusioninventoryTask::cronTaskscheduler();
      
      $pfTask = new PluginFusioninventoryTask();

      $data = $pfTask->getJoblogs(array(1));

      $ref = array(
          2 => 'computer2',
      );

      $this->assertEquals($ref, $data['agents']);
   }


   /**
    * @test
    */
   public function prepareTask2() {
      global $DB;

      // Verify prepare a network discovery task
      $DB->connect();

      $pfTask = new PluginFusioninventoryTask();

      $data = $pfTask->getJoblogs(array(2));

      $ref = array(
          3 => 'computer3',
      );

      $this->assertEquals($ref, $data['agents']);
   }

}
?>
