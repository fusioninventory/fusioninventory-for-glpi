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

class AgentTest extends RestoreDatabase_TestCase {


   /**
    * @test
    */
   public function addAgent() {
      $pfAgent = new PluginFusioninventoryAgent();
      $agent_id = $pfAgent->add(
         array(
            'name'           => 'port004.bureau.siprossii.com-2012-12-20-16-27-27',
            'device_id'      => 'port004.bureau.siprossii.com-2012-12-20-16-27-27',
            'computers_id'   => 100
         )
      );
      $this->assertNotEquals(FALSE, $agent_id);
      return $pfAgent;
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

      $pfAgent = new PluginFusioninventoryAgent();

      $a_agents = $pfAgent->find(
         "`device_id` = 'port004.bureau.siprossii.com-2013-01-01-16-27-27'"
      );

      $this->assertEquals(1, count($a_agents), "Agent not found");
   }

   /**
    * @test
    */
   public function newAgentLinkedToSameAsset() {

      $pfAgent = new PluginFusioninventoryAgent();
      $agent = $pfAgent->find(
         "`device_id` = 'port004.bureau.siprossii.com-2013-01-01-16-27-27'",
         "",
         1
      );
      $this->assertEquals(1, count($agent));
      $current_agent = current($agent);
      $agent_id = $current_agent['id'];

      $agent_from_asset = current($pfAgent->find("`computers_id` = '100'"));

      $this->assertEquals($agent_id, $agent_from_asset['id']);

   }

   /**
    * @test
    */
   public function newAgentCheckEntity() {

      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents = current($pfAgent->find("`computers_id`='100'"));

      $this->assertEquals(1, $a_agents['entities_id']);
   }


   /**
    * @test
    */
   public function newAgentChangeEntity() {

      $pfAgent = new PluginFusioninventoryAgent();
      // Load Agent
      $this->assertTrue(
         $pfAgent->getFromDBByQuery(
            "WHERE `device_id` = 'port004.bureau.siprossii.com-2013-01-01-16-27-27' ".
            "LIMIT 1"
         ),
         "Could not load agent"
      );

      $pfAgent->setAgentWithComputerid(100,
                                       'port004.bureau.siprossii.com-2013-01-01-16-27-27',
                                       0);

      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents = current($pfAgent->find("`computers_id`='100'"));

      $this->assertEquals(0, $a_agents['entities_id']);
   }
}

