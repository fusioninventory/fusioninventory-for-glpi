<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2014 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2014 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

class NetworkInventoryTest extends RestoreDatabase_TestCase {

   /**
    * @test
    */
   public function prepareDB() {
      global $DB;

      $DB->connect();

      $entity          = new Entity();
      $computer        = new Computer();
      $pfAgent         = new PluginFusioninventoryAgent();
      $pfTask          = new PluginFusioninventoryTask();
      $pfTaskjob       = new PluginFusioninventoryTaskjob;
      $pfIPRange       = new PluginFusioninventoryIPRange();
      $networkEquipment= new NetworkEquipment();
      $networkPort     = new NetworkPort();
      $printer         = new Printer();
      $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();

      // Create entities
      $_SESSION['glpiactive_entity'] = 0;
      $DB->query("INSERT INTO `glpi_entities`
         (`id`, `name`, `entities_id`, `completename`, `level`)
         VALUES (1, 'ent1', 0, 'Entité racine > ent1', 2)");
      $DB->query("INSERT INTO `glpi_entities`
         (`id`, `name`, `entities_id`, `completename`, `level`)
         VALUES (2, 'ent2', 0, 'Entité racine > ent2', 2)");
      $DB->query("INSERT INTO `glpi_entities`
         (`id`, `name`, `entities_id`, `completename`, `level`)
         VALUES (3, 'ent1.1', 1, 'Entité racine > ent1 > ent1.1', 3)");

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

      // Create Network Equipments
      $input = array(
          'name'        => 'sw0',
          'entities_id' => 0
      );
      $networkEquipment->add($input);
      $input = array(
          'entities_id'        => 0,
          'name'               => 'management',
          'items_id'           => 1,
          'itemtype'           => 'NetworkEquipment',
          'instantiation_type' => 'NetworkPortAggregate',
          'NetworkName__ipaddresses' => array('-1' => '10.0.0.10')
      );
      $networkPort->splitInputForElements($input);
      $networkPort->add($input);
      $networkPort->updateDependencies(1);
      $input = array(
          'networkequipments_id'                       => 1,
          'plugin_fusioninventory_configsecurities_id' => 2
      );
      $pfNetworkEquipment->add($input);


      $input = array(
          'name'        => 'sw1',
          'entities_id' => 1
      );
      $networkEquipment->add($input);
      $input = array(
          'entities_id'        => 1,
          'name'               => 'management',
          'items_id'           => 2,
          'itemtype'           => 'NetworkEquipment',
          'instantiation_type' => 'NetworkPortAggregate',
          'NetworkName__ipaddresses' => array('-1' => '10.0.0.11')
      );
      $networkPort->splitInputForElements($input);
      $networkPort->add($input);
      $networkPort->updateDependencies(1);
      $input = array(
          'networkequipments_id'                       => 2,
          'plugin_fusioninventory_configsecurities_id' => 2
      );
      $pfNetworkEquipment->add($input);


      $input = array(
          'name'        => 'sw2',
          'entities_id' => 2
      );
      $networkEquipment->add($input);
      $input = array(
          'entities_id'        => 2,
          'name'               => 'management',
          'items_id'           => 3,
          'itemtype'           => 'NetworkEquipment',
          'instantiation_type' => 'NetworkPortAggregate',
          'NetworkName__ipaddresses' => array('-1' => '10.0.0.12')
      );
      $networkPort->splitInputForElements($input);
      $networkPort->add($input);
      $networkPort->updateDependencies(1);
      $input = array(
          'networkequipments_id'                       => 3,
          'plugin_fusioninventory_configsecurities_id' => 2
      );
      $pfNetworkEquipment->add($input);


      $input = array(
          'name'        => 'sw3/1.1',
          'entities_id' => 3
      );
      $networkEquipment->add($input);
      $input = array(
          'entities_id'        => 3,
          'name'               => 'management',
          'items_id'           => 4,
          'itemtype'           => 'NetworkEquipment',
          'instantiation_type' => 'NetworkPortAggregate',
          'NetworkName__ipaddresses' => array('-1' => '10.0.0.21')
      );
      $networkPort->splitInputForElements($input);
      $networkPort->add($input);
      $networkPort->updateDependencies(1);
      $input = array(
          'networkequipments_id'                       => 4,
          'plugin_fusioninventory_configsecurities_id' => 2
      );
      $pfNetworkEquipment->add($input);


      // Create Printers




      // Add IPRange
      $input = array(
          'entities_id' => 1,
          'name'        => 'Office',
          'ip_start'    => '10.0.0.1',
          'ip_end'      => '10.0.0.254'
      );
      $ipranges_id = $pfIPRange->add($input);

      // Allow all agents to do network discovery
      $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules` "
              . " SET `is_active`='1' "
              . " WHERE `modulename`='NETWORKINVENTORY'";
      $DB->query($query);

      // create task
      $input = array(
          'entities_id' => 0,
          'name'        => 'network inventory',
          'is_active'   => 1
      );
      $tasks_id = $pfTask->add($input);

      // create taskjob
      $input = array(
          'plugin_fusioninventory_tasks_id' => $tasks_id,
          'entities_id'                     => 0,
          'name'                            => 'inventory',
          'method'                          => 'networkinventory',
          'targets'                         => '[{"PluginFusioninventoryIPRange":"'.$ipranges_id.'"}]',
          'actors'                          => '[{"PluginFusioninventoryAgent":"1"}]'
      );
      $pfTaskjob->add($input);


   }


   /**
    * @test
    */
   public function prepareTask() {
      $this->mark_incomplete(
          "This test needs to be simplified since there seems to be too much"
         ."variables in play."
      );
      global $DB;

      // Verify preparation of a network discovery task
      $DB->connect();

      PluginFusioninventoryTask::cronTaskscheduler();

      $pfTask = new PluginFusioninventoryTask();

      $data = $pfTask->getJoblogs(array(1));

      $ref = array(
          1 => 'computer1',
      );

      $this->assertEquals($ref, $data['agents']);
   }



   /**
    * @test
    */
   public function getDevicesToInventory() {
      global $DB;

      // Verify prepare a network discovery task
      $DB->connect();

      $pfNetworkinventory = new PluginFusioninventoryNetworkinventory();
      $jobstate           = new PluginFusioninventoryTaskjobstate();

      $jobstate->getFromDB(1);
      $data = $pfNetworkinventory->run($jobstate);


   }

}
?>
