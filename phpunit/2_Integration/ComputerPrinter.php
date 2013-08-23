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

class ComputerPrinter extends PHPUnit_Framework_TestCase {
   public $a_computer1 = array();
   public $a_computer2 = array();
   public $a_computer3 = array();
   
   function __construct() {
      $this->a_computer1 = array(
          "Computer" => array(
              "name"   => "pc001",
              "serial" => "ggheb7ne7"
          ), 
          "fusioninventorycomputer" => Array(
              'last_fusioninventory_update' => date('Y-m-d H:i:s'),
              'serialized_inventory'        => 'something'
          ),
          'soundcard'      => Array(),
          'graphiccard'    => Array(),
          'controller'     => Array(),
          'processor'      => Array(),
          "computerdisk"   => array(),
          'memory'         => Array(),
          'monitor'        => Array(),
          'printer'        => Array(
              array(
                  'name'    => 'p1',
                  'port'    => '',
                  'serial'  => ''
              ),
              array(
                  'name'    => 'p2',
                  'port'    => '',
                  'serial'  => 's1537'
              )
          ),
          'peripheral'     => Array(),
          'networkport'    => Array(),
          'software'       => Array(),
          'harddrive'      => Array(),
          'virtualmachine' => Array(),
          'antivirus'      => Array(),
          'storage'        => Array(),
          'itemtype'       => 'Computer'
      );
      
      $this->a_computer2 = array(
          "Computer" => array(
              "name"   => "pc002",
              "serial" => "ggheb7ne8"
          ), 
          "fusioninventorycomputer" => Array(
              'last_fusioninventory_update' => date('Y-m-d H:i:s'),
              'serialized_inventory'        => 'something'
          ),
          'soundcard'      => Array(),
          'graphiccard'    => Array(),
          'controller'     => Array(),
          'processor'      => Array(),
          "computerdisk"   => array(),
          'memory'         => Array(),
          'monitor'        => Array(),
          'printer'        => Array(
              array(
                  'name'    => 'p1',
                  'port'    => '',
                  'serial'  => 'f275'
              ),
              array(
                  'name'    => 'p2',
                  'port'    => '',
                  'serial'  => 's1537'
              )
          ),
          'peripheral'     => Array(),
          'networkport'    => Array(),
          'software'       => Array(),
          'harddrive'      => Array(),
          'virtualmachine' => Array(),
          'antivirus'      => Array(),
          'storage'        => Array(),
          'itemtype'       => 'Computer'
      );
      
      $this->a_computer3 = array(
          "Computer" => array(
              "name"   => "pc003",
              "serial" => "ggheb7ne9"
          ), 
          "fusioninventorycomputer" => Array(
              'last_fusioninventory_update' => date('Y-m-d H:i:s'),
              'serialized_inventory'        => 'something'
          ),
          'soundcard'      => Array(),
          'graphiccard'    => Array(),
          'controller'     => Array(),
          'processor'      => Array(),
          "computerdisk"   => array(),
          'memory'         => Array(),
          'monitor'        => Array(),
          'printer'        => Array(
              array(
                  'name'    => 'p1',
                  'port'    => '',
                  'serial'  => ''
              ),
              array(
                  'name'    => 'p2',
                  'port'    => '',
                  'serial'  => ''
              )
          ),
          'peripheral'     => Array(),
          'networkport'    => Array(),
          'software'       => Array(),
          'harddrive'      => Array(),
          'virtualmachine' => Array(),
          'antivirus'      => Array(),
          'storage'        => Array(),
          'itemtype'       => 'Computer'
      );
   }
   
   
   // Import printer of computer with each options:
   //   * 1 = Global import
   //   * 2 = Unique import
   //   * 3 = Unique import on serial number
   
   
   public function testPrinterGlobalimport() {
      global $DB;

      $DB->connect();
      
      $Install = new Install();
      $Install->testInstall(0);

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      
      $pfConfig         = new PluginFusioninventoryConfig();
      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();
      $GLPIlog          = new GLPIlogs();
      
      $pfConfig->updateValue('import_printer', 1);
      PluginFusioninventoryConfig::loadCache();
      
      $a_computerinventory = $this->a_computer1;
      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id = $computer->add($a_computer);
      
      $pfiComputerLib->updateComputer($a_computerinventory, 
                                      $computers_id, 
                                      FALSE, 
                                      1);
      
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');
      
      $this->assertEquals(2, countElementsInTable('glpi_printers'), 'First computer');
      $this->assertEquals(2, 
                          countElementsInTable('glpi_computers_items', 'itemtype="Printer"'), 
                          'First computer (links)');
      
      $a_computerinventory = $this->a_computer2;
      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id = $computer->add($a_computer);
      
      $pfiComputerLib->updateComputer($a_computerinventory, 
                                      $computers_id, 
                                      FALSE, 
                                      1);
      
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
      
      $this->assertEquals(2, countElementsInTable('glpi_printers'), 'Second computer');
      $this->assertEquals(4, 
                          countElementsInTable('glpi_computers_items', 'itemtype="Printer"'), 
                          'Second computer (links)');
      
      $a_computerinventory = $this->a_computer3;
      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id = $computer->add($a_computer);
      
      $pfiComputerLib->updateComputer($a_computerinventory, 
                                      $computers_id, 
                                      FALSE, 
                                      1);
      
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
      
      $this->assertEquals(2, countElementsInTable('glpi_printers'), 'Third computer');
      $this->assertEquals(6, 
                          countElementsInTable('glpi_computers_items', 'itemtype="Printer"'), 
                          'Third computer (links)');
      
      // * Retry first computer
      $a_computerinventory = $this->a_computer1;
      $pfiComputerLib->updateComputer($a_computerinventory, 
                                      $computers_id, 
                                      FALSE, 
                                      1);
      
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');
      
      $this->assertEquals(2, countElementsInTable('glpi_printers'), 'First computer');
      $this->assertEquals(6, 
                          countElementsInTable('glpi_computers_items', 'itemtype="Printer"'), 
                          'First computer (links)');
      
      $this->assertEquals(0, 
                          countElementsInTable('glpi_computers_items', 'itemtype="Printer" AND `id` > 6'), 
                          'First computer (number id of links recreated)');
      

      $pfConfig->updateValue('import_printer', 2);
      PluginFusioninventoryConfig::loadCache();
   }   
   
 }



class ComputerPrinter_AllTests  {

   public static function suite() {
     
      $suite = new PHPUnit_Framework_TestSuite('ComputerPrinter');
      return $suite;
   }
}

?>