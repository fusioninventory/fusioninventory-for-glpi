<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

class AgentChangeDeviceid extends PHPUnit_Framework_TestCase {

   private $agents_id = 0;

    
   protected function setUp() {
      global $DB;

      $DB->connect();

      $Install = new Install();
      $Install->testInstall(0);
      
      $pfAgent = new PluginFusioninventoryAgent();
      
      // Add an agent
      $input = array(
          'name'           => 'port004.bureau.siprossii.com-2012-12-20-16-27-27',
          'device_id'      => 'port004.bureau.siprossii.com-2012-12-20-16-27-27',
          'computers_id'   => 100
      );
      $this->agents_id = $pfAgent->add($input);
     
      $pfAgent->setAgentWithComputerid(100, 
                                       'port004.bureau.siprossii.com-2013-01-01-16-27-27', 
                                       1);
   }

   
   
   public function testNbAgent() {
      global $DB;

      $DB->connect();

      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents = $pfAgent->find("`device_id` LIKE 'port004%'");

      $this->assertEquals(1, count($a_agents));
   }


   
   public function testAgentChangeDeviceid() {
      global $DB;

      $DB->connect();

      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents = $pfAgent->find("`device_id`='port004.bureau.siprossii.com-2013-01-01-16-27-27'");

      $this->assertEquals(1, count($a_agents));
   }
   

   
   public function testNewAgentAssociatedWithComputer() {
      global $DB;

      $DB->connect();

      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents = current($pfAgent->find("`computers_id`='100'"));

      $this->assertEquals($this->agents_id, $a_agents['id']);
   }

   
   
   public function testNewAgentChangeEntity() {
      global $DB;

      $DB->connect();

      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents = current($pfAgent->find("`computers_id`='100'"));

      $this->assertEquals(1, $a_agents['entities_id']);
   }
   

   
   public function testNewAgentChangeEntityOnly() {
      global $DB;

      $DB->connect();

      $pfAgent = new PluginFusioninventoryAgent();
      $pfAgent->setAgentWithComputerid(100, 
                                       'port004.bureau.siprossii.com-2013-01-01-16-27-27', 
                                       0);
      
      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents = current($pfAgent->find("`computers_id`='100'"));

      $this->assertEquals(0, $a_agents['entities_id']);
   }
}



class AgentChangeDeviceid_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('AgentChangeDeviceid');
      return $suite;
   }
}

?>