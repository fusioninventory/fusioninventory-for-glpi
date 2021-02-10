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

class RestURLTest extends TestCase {

   public static function setUpBeforeClass(): void {

      // Delete all entities exept root entity
      $entity = new Entity();
      $items = $entity->find();
      foreach ($items as $item) {
         if ($item['id'] > 0) {
            $entity->delete(['id' => $item['id']], true);
         }
      }

      // Delete all agents
      $pfAgent = new PluginFusioninventoryAgent();
      $items = $pfAgent->find();
      foreach ($items as $item) {
         $pfAgent->delete(['id' => $item['id']], true);
      }
   }


   /**
    * @test
    */
   public function prepareDb() {
      global $DB;

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $entity   = new Entity();
      $pfAgent  = new PluginFusioninventoryAgent();
      $config   = new PluginFusioninventoryConfig();
      $pfEntity = new PluginFusioninventoryEntity();

      $entityId = $entity->add([
         'name'        => 'ent1',
         'entities_id' => 0,
         'comment'     => ''
      ]);
      $this->assertNotFalse($entityId);

      $input = [
          'name'        => 'toto',
          'entities_id' => $entityId,
          'device_id'   => 'toto-device'
      ];
      $agents_id = $pfAgent->add($input);

      $config->loadCache();

      $pfEntities = $pfEntity->find();
      $this->assertLessThan(2, count($pfEntities));

      $pfEntity->getFromDBByCrit(['entities_id' => 0]);
      $input = [
         'id'             => $pfEntity->fields['id'],
         'agent_base_url' => 'http://127.0.0.1/glpi085'
      ];
      $ret = $pfEntity->update($input);
      $this->assertTrue($ret);

      $input = [
         'entities_id'    => $entityId,
         'agent_base_url' => 'http://10.0.2.2/glpi085'
      ];
      $ret = $pfEntity->add($input);
      $this->assertNotFalse($ret);

      // active all modules
      $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`"
              . " SET `is_active`='1'";
      $DB->query($query);
   }


   /**
    * @test
    */
   public function getCollectUrlEnt1Entity() {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfAgent  = new PluginFusioninventoryAgent();

      $pfAgent->getFromDBByCrit(['name' => 'toto']);
      $input = [
         'itemtype'                         => 'PluginFusioninventoryCollect',
         'plugin_fusioninventory_agents_id' => $pfAgent->fields['id']
      ];
      $ret = $pfTaskjobstate->add($input);
      $this->assertNotFalse($ret);

      // Get answer
      $input = [
          'action'    => 'getConfig',
          'task'      => ['COLLECT' => '1.0.0'],
          'machineid' => 'toto-device'
      ];

      $response = PluginFusioninventoryCommunicationRest::communicate($input);

      $this->assertEquals('http://10.0.2.2/glpi085/plugins/fusioninventory/b/collect/',
                          $response['schedule'][0]['remote'],
                          'Wrong URL');
   }


   /**
    * @test
    */
   public function getDeployUrlRootEntity() {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfAgent  = new PluginFusioninventoryAgent();

      $pfAgent->getFromDBByCrit(['name' => 'toto']);
      $input = [
         'itemtype'                         => 'PluginFusioninventoryDeployPackage',
         'plugin_fusioninventory_agents_id' => $pfAgent->fields['id']
      ];
      $pfTaskjobstate->add($input);

      // Get answer
      $input = [
          'action'    => 'getConfig',
          'task'      => ['Deploy' => '1.0.0'],
          'machineid' => 'toto-device'
      ];

      $response = PluginFusioninventoryCommunicationRest::communicate($input);

      $this->assertEquals('http://10.0.2.2/glpi085/plugins/fusioninventory/b/deploy/',
                          $response['schedule'][0]['remote'],
                          'Wrong URL');
   }


   /**
    * @test
    */
   public function getEsxUrlRootEntity() {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfTaskjobstate = new PluginFusioninventoryTaskjobstate();
      $pfAgent  = new PluginFusioninventoryAgent();

      $pfAgent->getFromDBByCrit(['name' => 'toto']);
      $input = [
         'itemtype'                         => 'PluginFusioninventoryCredentialIp',
         'plugin_fusioninventory_agents_id' => $pfAgent->fields['id']
      ];
      $pfTaskjobstate->add($input);

      // Get answer
      $input = [
          'action'    => 'getConfig',
          'task'      => ['ESX' => '1.0.0'],
          'machineid' => 'toto-device'
      ];

      $response = PluginFusioninventoryCommunicationRest::communicate($input);

      $this->assertEquals('http://10.0.2.2/glpi085/plugins/fusioninventory/b/esx/',
                          $response['schedule'][0]['remote'],
                          'Wrong URL');
   }


   /**
    * @test
    */
   public function getCollectUrlRootEntity() {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $config = new PluginFusioninventoryConfig();
      $config->loadCache();

      $pfEntity = new PluginFusioninventoryEntity();
      $entity = new Entity();
      $entity->getFromDBByCrit(['name' => 'ent1']);
      $this->assertArrayHasKey('id', $entity->fields);

      $pfEntity->getFromDBByCrit(['entities_id' => $entity->fields['id']]);
      $this->assertArrayHasKey('id', $pfEntity->fields);

      $delRet = $pfEntity->delete(['id' => $pfEntity->fields['id']]);
      $this->assertTrue($delRet);

      // Get answer
      $input = [
          'action'    => 'getConfig',
          'task'      => ['COLLECT' => '1.0.0'],
          'machineid' => 'toto-device'
      ];

      $response = PluginFusioninventoryCommunicationRest::communicate($input);
      $this->assertEquals('http://127.0.0.1/glpi085/plugins/fusioninventory/b/collect/',
                          $response['schedule'][0]['remote'],
                          'Wrong URL');
   }
}
