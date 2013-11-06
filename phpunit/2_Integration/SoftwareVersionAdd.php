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

class SoftwareVersionAdd extends PHPUnit_Framework_TestCase {
   
  
   public function testAddComputer1() {
      global $DB;

      $DB->connect();
      
      $Install = new Install();
      $Install->testInstall(0);
      
      $GLPIlog = new GLPIlogs();
      
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION['glpiname'] = 'glpi';
      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      
      $a_inventory = array();
      $a_inventory['CONTENT']['HARDWARE'] = array(
          'NAME' => 'pc1'
      );
      $a_inventory['CONTENT']['SOFTWARES'][] = array(
          'COMMENTS' => "Non-interactive tool to get files from FTP, GOPHER, HTTP(S)",
          'NAME'     => "curl",
          'VERSION'  => "7.24.0_1"
          );

      // ** Add agent
         $pfAgent = new PluginFusioninventoryAgent();
         $a_agents_id = $pfAgent->add(array('name'      => 'pc-2013-02-13',
                                            'device_id' => 'pc-2013-02-13'));
         $_SESSION['plugin_fusioninventory_agents_id'] = $a_agents_id;

      // ** Add
         $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $this->assertEquals(1, countElementsInTable("glpi_softwares"), "Number of softwares");
         
         $this->assertEquals(1, countElementsInTable("glpi_softwareversions"), "Number of versions");
         
   }   

   
   
   public function testAddComputer2() {
      global $DB;
      
      $DB->connect();
      
      $GLPIlog = new GLPIlogs();
      
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION['glpiname'] = 'glpi';
      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      
      $a_inventory = array();
      $a_inventory['CONTENT']['HARDWARE'] = array(
          'NAME' => 'pc2'
      );
      $a_inventory['CONTENT']['SOFTWARES'][] = array(
          'COMMENTS' => "Non-interactive tool to get files from FTP, GOPHER, HTTP(S)",
          'NAME'     => "curl",
          'VERSION'  => "7.24.0_1"
          );

      // ** Add agent
         $pfAgent = new PluginFusioninventoryAgent();
         $a_agents_id = $pfAgent->add(array('name'      => 'pc-2013-02-14',
                                            'device_id' => 'pc-2013-02-14'));
         $_SESSION['plugin_fusioninventory_agents_id'] = $a_agents_id;

      // ** Add
         $pfiComputerInv->import("pc-2013-02-14", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $this->assertEquals(1, countElementsInTable("glpi_softwares"), "Number of softwares");
         
         $this->assertEquals(1, countElementsInTable("glpi_softwareversions"), "Number of versions");
         
    }   
    
    
    
   public function testAddComputer3() {
      global $DB;
      
      $DB->connect();
      
      $GLPIlog = new GLPIlogs();
      
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION['glpiname'] = 'glpi';
      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      
      $a_inventory = array();
      $a_inventory['CONTENT']['HARDWARE'] = array(
          'NAME' => 'pc3'
      );
      $a_inventory['CONTENT']['SOFTWARES'][] = array(
          'COMMENTS' => "Non-interactive tool to get files from FTP, GOPHER, HTTP(S)",
          'NAME'     => "curl",
          'VERSION'  => "7.24.0_1-Beta"
          );

      // ** Add agent
         $pfAgent = new PluginFusioninventoryAgent();
         $a_agents_id = $pfAgent->add(array('name'      => 'pc-2013-02-15',
                                            'device_id' => 'pc-2013-02-15'));
         $_SESSION['plugin_fusioninventory_agents_id'] = $a_agents_id;

      // ** Add
         $pfiComputerInv->import("pc-2013-02-15", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $this->assertEquals(1, countElementsInTable("glpi_softwares"), "Number of softwares");
         
         $this->assertEquals(2, countElementsInTable("glpi_softwareversions"), "Number of versions");
         
    }   

    
    
   public function testAddComputer4() {
      global $DB;
      
      $DB->connect();
      
      $GLPIlog = new GLPIlogs();
      
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION['glpiname'] = 'glpi';
      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      
      $a_inventory = array();
      $a_inventory['CONTENT']['HARDWARE'] = array(
          'NAME' => 'pc4'
      );
      $a_inventory['CONTENT']['SOFTWARES'][] = array(
          'COMMENTS' => "Non-interactive tool to get files from FTP, GOPHER, HTTP(S)",
          'NAME'     => "curl",
          'VERSION'  => "7.24.0_1-Beta"
          );

      // ** Add agent
         $pfAgent = new PluginFusioninventoryAgent();
         $a_agents_id = $pfAgent->add(array('name'      => 'pc-2013-02-16',
                                            'device_id' => 'pc-2013-02-16'));
         $_SESSION['plugin_fusioninventory_agents_id'] = $a_agents_id;

      // ** Add
         $pfiComputerInv->import("pc-2013-02-16", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $this->assertEquals(1, countElementsInTable("glpi_softwares"), "Number of softwares");
         
         $this->assertEquals(2, countElementsInTable("glpi_softwareversions"), "Number of versions");
         
    }   

    
    
   public function testAddComputer5() {
      global $DB;
      
      $DB->connect();
      
      $GLPIlog = new GLPIlogs();
      
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION['glpiname'] = 'glpi';
      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      
      $a_inventory = array();
      $a_inventory['CONTENT']['HARDWARE'] = array(
          'NAME' => 'pc5'
      );
      $a_inventory['CONTENT']['SOFTWARES'][] = array(
          'COMMENTS' => "Non-interactive tool to get files from FTP, GOPHER, HTTP(S)",
          'NAME'     => "curl",
          'VERSION'  => "7.24.0_1-beta"
          );

      // ** Add agent
         $pfAgent = new PluginFusioninventoryAgent();
         $a_agents_id = $pfAgent->add(array('name'      => 'pc-2013-02-17',
                                            'device_id' => 'pc-2013-02-17'));
         $_SESSION['plugin_fusioninventory_agents_id'] = $a_agents_id;

      // ** Add
         $pfiComputerInv->import("pc-2013-02-17", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $this->assertEquals(1, countElementsInTable("glpi_softwares"), "Number of softwares");
         
         $this->assertEquals(2, countElementsInTable("glpi_softwareversions"), "Number of versions");
         
    }   
    
    
    
   public function testAddComputer6() {
      global $DB;
      
      $DB->connect();
      
      $GLPIlog = new GLPIlogs();
      
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION['glpiname'] = 'glpi';
      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      
      $a_inventory = array();
      $a_inventory['CONTENT']['HARDWARE'] = array(
          'NAME' => 'pc6'
      );
      $a_inventory['CONTENT']['SOFTWARES'][] = array(
          'COMMENTS' => "Non-interactive tool to get files from FTP, GOPHER, HTTP(S)",
          'NAME'     => "curl",
          'VERSION'  => "2.3.2 (x86 edition)"
          );

      // ** Add agent
         $pfAgent = new PluginFusioninventoryAgent();
         $a_agents_id = $pfAgent->add(array('name'      => 'pc-2013-02-18',
                                            'device_id' => 'pc-2013-02-18'));
         $_SESSION['plugin_fusioninventory_agents_id'] = $a_agents_id;

      // ** Add
         $pfiComputerInv->import("pc-2013-02-18", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $this->assertEquals(1, countElementsInTable("glpi_softwares"), "Number of softwares");
         
         $this->assertEquals(3, countElementsInTable("glpi_softwareversions"), "Number of versions");
         
    }   
    

    
   public function testAddComputer7() {
      global $DB;
      
      $DB->connect();
      
      $GLPIlog = new GLPIlogs();
      
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION['glpiname'] = 'glpi';
      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      
      $a_inventory = array();
      $a_inventory['CONTENT']['HARDWARE'] = array(
          'NAME' => 'pc7'
      );
      $a_inventory['CONTENT']['SOFTWARES'][] = array(
          'COMMENTS' => "Non-interactive tool to get files from FTP, GOPHER, HTTP(S)",
          'NAME'     => "curl",
          'VERSION'  => "2.3.2 (x86 edition)"
          );

      // ** Add agent
         $pfAgent = new PluginFusioninventoryAgent();
         $a_agents_id = $pfAgent->add(array('name'      => 'pc-2013-02-19',
                                            'device_id' => 'pc-2013-02-19'));
         $_SESSION['plugin_fusioninventory_agents_id'] = $a_agents_id;

      // ** Add
         $pfiComputerInv->import("pc-2013-02-19", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $this->assertEquals(1, countElementsInTable("glpi_softwares"), "Number of softwares");
         
         $this->assertEquals(3, countElementsInTable("glpi_softwareversions"), "Number of versions");
         
    }   
 }



class SoftwareVersionAdd_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('SoftwareVersionAdd');
      return $suite;
   }
}

?>