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
   @copyright Copyright (c) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2021

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class agentTest extends TestCase {

   /**
      * @test
      */
   public function addAgent() {
      $agent = new PluginFusioninventoryAgent();

      $agent_id = $agent->add(
         [
            'name'           => 'port004.bureau.siprossii.com-2012-12-20-16-27-27',
            'device_id'      => 'port004.bureau.siprossii.com-2012-12-20-16-27-27',
            'computers_id'   => 100
         ]
      );
      $this->assertNotEquals(false, $agent_id);
      return $agent;
   }

   /**
    * @test
    * @depends addAgent
    */
   public function linkNewAgentWithAsset($pfAgent) {

      $result = $pfAgent->setAgentWithComputerid(
         100,
         'port004.bureau.siprossii.com-2013-01-01-16-27-27',
         1
      );
      $this->assertTrue($result, "Problem when linking agent to asset");
      return $pfAgent;
   }


   /**
    * @test
    */
   public function agentExists() {

      $pfAgent  = new PluginFusioninventoryAgent();
      $a_agents = $pfAgent->find(
         ['device_id' => 'port004.bureau.siprossii.com-2013-01-01-16-27-27']
      );

      $this->assertEquals(1, count($a_agents), "Agent not found");
   }


   /**
    * @test
    */
   public function newAgentLinkedToSameAsset() {

      $pfAgent = new PluginFusioninventoryAgent();
      $agent = $pfAgent->find(
         ['device_id' => 'port004.bureau.siprossii.com-2013-01-01-16-27-27'],
         [], 1);
      $this->assertEquals(1, count($agent));
      $current_agent = current($agent);
      $agent_id = $current_agent['id'];

      $agent_from_asset = current($pfAgent->find(['computers_id' => 100]));

      $this->assertEquals($agent_id, $agent_from_asset['id']);

   }


   /**
    * @test
    */
   public function newAgentCheckEntity() {

      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents = current($pfAgent->find(['computers_id' => 100]));

      $this->assertEquals(1, $a_agents['entities_id']);
   }


   /**
    * @test
    */
   public function newAgentChangeEntity() {

      $pfAgent = new PluginFusioninventoryAgent();
      // Load Agent
      $this->assertTrue(
         $pfAgent->getFromDBByCrit([
               'device_id' => 'port004.bureau.siprossii.com-2013-01-01-16-27-27'
            ]),
            "Could not load agent"
      );

      $pfAgent->setAgentWithComputerid(100,
                                       'port004.bureau.siprossii.com-2013-01-01-16-27-27',
                                       0);

      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents = current($pfAgent->find(['computers_id' => 100]));

      $this->assertEquals(0, $a_agents['entities_id']);
   }


   /**
    * @test
    */
   public function udpateNotLog() {
      global $DB;
      // test update last_contact field but not have logs/ history

      $DB->connect();

      $pfAgent = new PluginFusioninventoryAgent();
      $query = "UPDATE glpi_plugin_fusioninventory_agents SET `last_contact`='2015-01-01 00:00:01'";
      $DB->query($query);
      $arrayinventory = [
          'DEVICEID' => 'port004.bureau.siprossii.com-2013-01-01-16-27-27',
      ];
      $log = new Log();
      $nb = count($log->find());

      $pfAgent->importToken($arrayinventory);

      $pfAgent->getFromDBByCrit([
         'device_id' => 'port004.bureau.siprossii.com-2013-01-01-16-27-27'
      ]);

      $this->assertStringContainsString(date('Y-m-d'), strstr($pfAgent->fields['last_contact'], date('Y-m-d')));
      $this->assertEquals($nb, count($log->find()));
   }


   /**
    * @test
    * @depends addAgent
    */
   public function disconnectAgent() {

      $pfAgent  = new PluginFusioninventoryAgent();
      $agent    = $pfAgent->find(
         ['device_id' => 'port004.bureau.siprossii.com-2013-01-01-16-27-27']
      );
      $this->assertEquals(1, count($agent));
      $current_agent = current($agent);
      $agent_id      = $current_agent['id'];

      //Disconnect the agent from the computer
      $pfAgent->disconnect(['computers_id' => 100, 'id' => $agent_id]);
      $count = countElementsInTable('glpi_plugin_fusioninventory_inventorycomputercomputers',
                                    ['computers_id' => '100']);
      $this->assertEquals(0, $count);

      //Check that computers_id has been set to 0
      $pfAgent->getFromDB($agent_id);
      $this->assertEquals(0, $pfAgent->fields['computers_id']);
   }
}
