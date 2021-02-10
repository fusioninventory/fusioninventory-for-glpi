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
   @author    David Durieux
   @co-author
   @copyright Copyright (C) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class NetworkInventoryTest extends TestCase {

   public static function setUpBeforeClass(): void {

      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }

      // Delete all agents
      $pfAgent = new PluginFusioninventoryAgent();
      $items = $pfAgent->find();
      foreach ($items as $item) {
         $pfAgent->delete(['id' => $item['id']], true);
      }

      // Delete all ipranges
      $pfIPRange = new PluginFusioninventoryIPRange();
      $items = $pfIPRange->find();
      foreach ($items as $item) {
         $pfIPRange->delete(['id' => $item['id']], true);
      }

      // Delete all tasks
      $pfTask = new PluginFusioninventoryTask();
      $items = $pfTask->find();
      foreach ($items as $item) {
         $pfTask->delete(['id' => $item['id']], true);
      }

      // Delete all network equipments
      $networkEquipment = new NetworkEquipment();
      $items = $networkEquipment->find();
      foreach ($items as $item) {
         $networkEquipment->delete(['id' => $item['id']], true);
      }

      // Delete all printers
      $printer = new Printer();
      $items = $printer->find();
      foreach ($items as $item) {
         $printer->delete(['id' => $item['id']], true);
      }

      // Delete all entities exept root entity
      $entity = new Entity();
      $items = $entity->find();
      foreach ($items as $item) {
         if ($item['id'] > 0) {
            $entity->delete(['id' => $item['id']], true);
         }
      }
   }


   /**
    * @test
    */
   public function prepareDb() {
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
      $networkName     = new NetworkName();
      $pfPrinter       = new PluginFusioninventoryPrinter();
      $iPAddress       = new IPAddress();
      $printer         = new Printer();
      $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();

      // Create entities
      $entity1Id = $entity->add([
         'name'        => 'ent1',
         'entities_id' => 0,
         'comment'     => ''
      ]);
      $this->assertNotFalse($entity1Id);

      $entity2Id = $entity->add([
         'name'        => 'ent2',
         'entities_id' => 0,
         'comment'     => ''
      ]);
      $this->assertNotFalse($entity2Id);

      $entity11Id = $entity->add([
         'name'        => 'ent1.1',
         'entities_id' => $entity1Id,
         'comment'     => ''
      ]);
      $this->assertNotFalse($entity11Id);

      // Create computers + agents
      $input = [
          'entities_id' => 0,
          'name'        => 'computer1'
      ];
      $computers_id = $computer->add($input);
      $this->assertNotFalse($computers_id);

      $input = [
          'entities_id' => 0,
          'name'        => 'computer1',
          'version'     => '{"INVENTORY":"v2.3.11"}',
          'device_id'   => 'computer1',
          'useragent'   => 'FusionInventory-Agent_v2.3.11',
          'computers_id'=> $computers_id
      ];
      $agent1Id = $pfAgent->add($input);
      $this->assertNotFalse($agent1Id);

      // Create Network Equipments
      $input = [
          'name'        => 'sw0',
          'entities_id' => 0
      ];
      $netequipId = $networkEquipment->add($input);
      $this->assertNotFalse($netequipId);

      $input = [
          'entities_id'        => 0,
          'name'               => 'management',
          'items_id'           => $netequipId,
          'itemtype'           => 'NetworkEquipment',
          'instantiation_type' => 'NetworkPortAggregate',
          'NetworkName__ipaddresses' => ['-1' => '10.0.0.10']
      ];
      $networkPort->splitInputForElements($input);
      $netportId = $networkPort->add($input);
      $this->assertNotFalse($netportId);

      $networkPort->updateDependencies(true);
      $input = [
          'networkequipments_id'                       => $netequipId,
          'plugin_fusioninventory_configsecurities_id' => 2
      ];
      $pfNetEquipId = $pfNetworkEquipment->add($input);
      $this->assertNotFalse($pfNetEquipId);

      $input = [
          'name'        => 'sw1',
          'entities_id' => $entity1Id
      ];
      $netEquipId = $networkEquipment->add($input);
      $this->assertNotFalse($netEquipId);

      $input = [
          'entities_id'        => $entity1Id,
          'name'               => 'management',
          'items_id'           => $netequipId,
          'itemtype'           => 'NetworkEquipment',
          'instantiation_type' => 'NetworkPortAggregate',
          'NetworkName__ipaddresses' => ['-1' => '10.0.0.11']
      ];
      $networkPort->splitInputForElements($input);
      $netportId = $networkPort->add($input);
      $this->assertNotFalse($netportId);

      $networkPort->updateDependencies(true);
      $input = [
          'networkequipments_id'                       => $netequipId,
          'plugin_fusioninventory_configsecurities_id' => 2
      ];
      $pfNetEquipId = $pfNetworkEquipment->add($input);
      $this->assertNotFalse($pfNetEquipId);

      $input = [
          'name'        => 'sw2',
          'entities_id' => $entity2Id
      ];
      $netequipId = $networkEquipment->add($input);
      $this->assertNotFalse($netequipId);

      $input = [
          'entities_id'        => $entity2Id,
          'name'               => 'management',
          'items_id'           => $netequipId,
          'itemtype'           => 'NetworkEquipment',
          'instantiation_type' => 'NetworkPortAggregate',
          'NetworkName__ipaddresses' => ['-1' => '10.0.0.12']
      ];
      $networkPort->splitInputForElements($input);
      $netportId = $networkPort->add($input);
      $this->assertNotFalse($netportId);

      $networkPort->updateDependencies(true);
      $input = [
          'networkequipments_id'                       => $netequipId,
          'plugin_fusioninventory_configsecurities_id' => 2
      ];
      $pfNetEquipId = $pfNetworkEquipment->add($input);
      $this->assertNotFalse($pfNetEquipId);

      $input = [
          'name'        => 'sw3/1.1',
          'entities_id' => $entity11Id
      ];
      $netequipId = $networkEquipment->add($input);
      $this->assertNotFalse($netequipId);

      $input = [
          'entities_id'        => $entity11Id,
          'name'               => 'management',
          'items_id'           => $netequipId,
          'itemtype'           => 'NetworkEquipment',
          'instantiation_type' => 'NetworkPortAggregate',
          'NetworkName__ipaddresses' => ['-1' => '10.0.0.21']
      ];
      $networkPort->splitInputForElements($input);
      $netportId = $networkPort->add($input);
      $this->assertNotFalse($netportId);

      $networkPort->updateDependencies(true);
      $input = [
          'networkequipments_id'                       => 4,
          'plugin_fusioninventory_configsecurities_id' => 2
      ];
      $pfNetEquipId = $pfNetworkEquipment->add($input);
      $this->assertNotFalse($pfNetEquipId);

      // Create Printers

      $input = [
         'name'        => 'printer 001',
         'entities_id' => 0
      ];
      $printers_id = $printer->add($input);
      $this->assertNotFalse($printers_id);

      $networkports_id = $networkPort->add([
          'itemtype'           => 'Printer',
          'instantiation_type' => 'NetworkPortEthernet',
          'items_id'           => $printers_id,
          'entities_id'        => 0
      ]);
      $this->assertNotFalse($networkports_id);

      $networknames_id = $networkName->add([
          'entities_id' => 0,
          'itemtype'    => 'NetworkPort',
          'items_id'    => $networkports_id
      ]);
      $this->assertNotFalse($networknames_id);

      $ipId = $iPAddress->add([
          'entities_id' => 0,
          'itemtype'    => 'NetworkName',
          'items_id'    => $networknames_id,
          'name'        => '192.168.200.124'
      ]);
      $this->assertNotFalse($ipId);

      $input = [
          'printers_id'                                => $printers_id,
          'plugin_fusioninventory_configsecurities_id' => 2
      ];
      $pfPrinterId = $pfPrinter->add($input);
      $this->assertNotFalse($pfPrinterId);

      // Add IPRange
      $input = [
          'entities_id' => 1,
          'name'        => 'Office',
          'ip_start'    => '10.0.0.1',
          'ip_end'      => '10.0.0.254'
      ];
      $ipranges_id = $pfIPRange->add($input);
      $this->assertNotFalse($ipranges_id);

      // Allow all agents to do network discovery
      $module = new PluginFusioninventoryAgentmodule();
      $module->getFromDBByCrit(['modulename' => 'NETWORKINVENTORY']);
      $module->update([
         'id'        => $module->fields['id'],
         'is_active' => 1
      ]);

      // create task
      $input = [
          'entities_id' => 0,
          'name'        => 'network inventory',
          'is_active'   => 1
      ];
      $tasks_id = $pfTask->add($input);
      $this->assertNotFalse($tasks_id);

      // create taskjob
      $input = [
          'plugin_fusioninventory_tasks_id' => $tasks_id,
          'entities_id'                     => 0,
          'name'                            => 'inventory',
          'method'                          => 'networkinventory',
          'targets'                         => '[{"PluginFusioninventoryIPRange":"'.$ipranges_id.'"}]',
          'actors'                          => '[{"PluginFusioninventoryAgent":"'.$agent1Id.'"}]'
      ];
      $taskjobId = $pfTaskjob->add($input);
      $this->assertNotFalse($taskjobId);

      PluginFusioninventoryTask::cronTaskscheduler();

   }


   /**
    * @test
    */
   public function prepareTask() {

      $pfTask  = new PluginFusioninventoryTask();
      $pfAgent = new PluginFusioninventoryAgent();

      $pfTask->getFromDBByCrit(['name' => 'network inventory']);
      $pfAgent->getFromDBByCrit(['name' => 'computer1']);

      $data = $pfTask->getJoblogs([$pfTask->fields['id']]);

      $ref = [
          $pfAgent->fields['id'] => 'computer1',
      ];

      $this->assertEquals($ref, $data['agents']);

   }


   /**
    * @test
    */
   public function getDevicesToInventory() {

      $pfNetworkinventory = new PluginFusioninventoryNetworkinventory();
      $jobstate           = new PluginFusioninventoryTaskjobstate();
      $jobstate->getFromDBByCrit(['itemtype' => 'NetworkEquipment']);
      $data = $pfNetworkinventory->run($jobstate);

      $this->assertEquals(1, $data->OPTION->DEVICE->count());
      $this->assertEquals('NETWORKING', $data->OPTION->DEVICE[0]['TYPE']);
      $this->assertEquals('10.0.0.10', $data->OPTION->DEVICE[0]['IP']);
      $this->assertEquals('2', $data->OPTION->DEVICE[0]['AUTHSNMP_ID']);
      $this->assertGreaterThan(0, intval($data->OPTION->DEVICE[0]['ID']));
   }


   /**
    * @test
    */
   public function PrinterToInventoryWithIp() {

      $printer       = new Printer();
      $pfTask        = new PluginFusioninventoryTask();
      $pfTaskjob     = new PluginFusioninventoryTaskjob();
      $pfAgent       = new PluginFusioninventoryAgent();
      $communication = new PluginFusioninventoryCommunication();
      $jobstate      = new PluginFusioninventoryTaskjobstate();

      $printer->getFromDBByCrit(['name' => 'printer 001']);
      $pfAgent->getFromDBByCrit(['name' => 'computer1']);

      // Add task
      // create task
      $input = [
          'entities_id' => 0,
          'name'        => 'printer inventory',
          'is_active'   => 1
      ];
      $tasks_id = $pfTask->add($input);
      $this->assertNotFalse($tasks_id);

      // create taskjob
      $input = [
          'plugin_fusioninventory_tasks_id' => $tasks_id,
          'entities_id'                     => 0,
          'name'                            => 'printer inventory',
          'method'                          => 'networkinventory',
          'targets'                         => '[{"Printer":"'.$printer->fields['id'].'"}]',
          'actors'                          => '[{"PluginFusioninventoryAgent":"'.$pfAgent->fields['id'].'"}]'
      ];
      $taskjobId = $pfTaskjob->add($input);
      $this->assertNotFalse($taskjobId);

      PluginFusioninventoryTask::cronTaskscheduler();

      // Task is prepared
      // Agent will get data

      $communication->getTaskAgent($pfAgent->fields['id']);
      $message = $communication->getMessage();
      $json = json_encode($message);
      $array = json_decode($json, true);

      $jobstate->getFromDBByCrit(['itemtype' => 'Printer']);

      $ref = [
         'OPTION' => [
            'NAME' => 'SNMPQUERY',
            'PARAM' => [
               '@attributes' => [
                  'THREADS_QUERY' => '1',
                  'TIMEOUT'       => '15',
                  'PID'           => $jobstate->fields['id']
               ]
            ],
            'DEVICE' => [
               '@attributes' => [
                  'TYPE'        => 'PRINTER',
                  'ID'          => $printer->fields['id'],
                  'IP'          => '192.168.200.124',
                  'AUTHSNMP_ID' => 2
               ]
            ],
            'AUTHENTICATION' => [
               0 => [
                  '@attributes' => [
                     'ID'        => 1,
                     'VERSION'   => 1,
                     'COMMUNITY' => 'public'
                  ]
               ],
               1 => [
                  '@attributes' => [
                     'ID'        => 2,
                     'VERSION'   => '2c',
                     'COMMUNITY' => 'public'
                  ],
               ]
            ]
         ]
      ];

      $this->assertEquals($ref, $array, 'XML of SNMP inventory task');
   }


   /**
    * @test
    */
   public function PrinterToInventoryWithoutIp() {

      $printer       = new Printer();
      $pfTask        = new PluginFusioninventoryTask();
      $pfTaskjob     = new PluginFusioninventoryTaskjob();
      $pfAgent       = new PluginFusioninventoryAgent();
      $communication = new PluginFusioninventoryCommunication();
      $iPAddress     = new IPAddress();

      // Delete all tasks
      $pfTask = new PluginFusioninventoryTask();
      $items = $pfTask->find();
      foreach ($items as $item) {
         $pfTask->delete(['id' => $item['id']], true);
      }

      // Delete ipaddress of the printer
      $iPAddress->getFromDBByCrit(['name' => '192.168.200.124']);
      $iPAddress->delete(['id' => $iPAddress->fields['id']]);

      $printer->getFromDBByCrit(['name' => 'printer 001']);
      $pfAgent->getFromDBByCrit(['name' => 'computer1']);

      // Add task
      // create task
      $input = [
          'entities_id' => 0,
          'name'        => 'network inventory',
          'is_active'   => 1
      ];
      $tasks_id = $pfTask->add($input);
      $this->assertNotFalse($tasks_id);

      // create taskjob
      $input = [
          'plugin_fusioninventory_tasks_id' => $tasks_id,
          'entities_id'                     => 0,
          'name'                            => 'inventory',
          'method'                          => 'networkinventory',
          'targets'                         => '[{"Printer":"'.$printer->fields['id'].'"}]',
          'actors'                          => '[{"PluginFusioninventoryAgent":"'.$pfAgent->fields['id'].'"}]'
      ];
      $pfTaskjob->add($input);

      PluginFusioninventoryTask::cronTaskscheduler();

      // Task is prepared
      // Agent will get data

      $communication->getTaskAgent($pfAgent->fields['id']);
      $message = $communication->getMessage();
      $json = json_encode($message);
      $array = json_decode($json, true);

      $ref = [];

      $this->assertEquals($ref, $array, 'XML of SNMP inventory task');
   }
}
