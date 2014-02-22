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

class ComputerMonitor extends Common_TestCase {
   public $a_computer1 = array();
   public $a_computer1_beforeformat = array();
   public $a_computer2 = array();

   /*
    * Why do you define a constructor here while you can set this 2 variables up ahead ???
    */
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
          'soundcard'      => array(),
          'graphiccard'    => array(),
          'controller'     => array(),
          'processor'      => array(),
          "computerdisk"   => array(),
          'memory'         => array(),
          'monitor'        => array(
              array(
                  'name'    => 'DELL E1911',
                  'manufacturers_id'=> 2,
                  'serial'  => 'W6VPJ1840E7B',
                  'comment' => '31/2011'
              )
          ),
          'printer'        => array(),
          'peripheral'     => array(),
          'networkport'    => array(),
          'software'       => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'itemtype'       => 'Computer'
      );

      $this->a_computer1_beforeformat = array(
          "CONTENT" => array(
              "HARDWARE" => array(
                  "NAME"   => "pc001"
              ),
              "BIOS" => array(
                  "SSN" => "ggheb7ne7"
              ),
              'MONITORS'        => Array(
                  array(
                      'BASE64' => 'AP///////wAQrDbwQjdFMB8VAQMOKRp47u6Vo1RMmSYPUFS/74CVAHFPgYCVD4EAAQEBAQEBmimg0FGEIjBQmDYAmP8QAAAcAAAA/wBXNlZQSjE4NDBFN0IKAAAA/ABERUxMIEUxOTExCiAgAAAA/QA4Sx5TDgAKICAgICAgALM=',
                      'CAPTION' => 'DELL E1911',
                      'DESCRIPTION' => '31/2011',
                      'MANUFACTURER' => 'Dell Computer Corp.',
                      'SERIAL' => 'W6VPJ1840E7B'
                  )
              )
          )
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
          'soundcard'      => array(),
          'graphiccard'    => array(),
          'controller'     => array(),
          'processor'      => array(),
          "computerdisk"   => array(),
          'memory'         => array(),
          'monitor'        => array(
              array(
                  'name'    => 'DELL E1911',
                  'manufacturers_id'=> 2,
                  'serial'  => 'W6VPJ1840E7B',
                  'comment' => '31/2011'
              )
          ),
          'printer'        => array(),
          'peripheral'     => array(),
          'networkport'    => array(),
          'software'       => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'itemtype'       => 'Computer'
      );
   }


   // Import Monitor of computer with each options:
   //   * 1 = Global import
   //   * 2 = Unique import
   //   * 3 = Unique import on serial number

   /**
    * TODO: use some dataProvider
    */

   /**
    * @test
    */
   public function PrinterGlobalimport() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;

      $pfConfig         = new PluginFusioninventoryConfig();
      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();

      $pfConfig->updateValue('import_monitor', 1);
      PluginFusioninventoryConfig::loadCache();

      $a_computerinventory = $this->a_computer1;
      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id = $computer->add($a_computer);

      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      FALSE,
                                      1);

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer');
      $this->assertEquals(1,
                          countElementsInTable('glpi_computers_items', 'itemtype="Monitor"'),
                          'First computer (links)');

      $a_computerinventory = $this->a_computer2;
      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id = $computer->add($a_computer);

      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      FALSE,
                                      1);

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'Second computer');
      $this->assertEquals(2,
                          countElementsInTable('glpi_computers_items', 'itemtype="Monitor"'),
                          'Second computer (links)');

      // * Retry first computer
      $a_computerinventory = $this->a_computer1;
      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      FALSE,
                                      1);

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer');
      $this->assertEquals(2,
                          countElementsInTable('glpi_computers_items', 'itemtype="Monitor"'),
                          'First computer (links)');

      $this->assertEquals(0,
                          countElementsInTable('glpi_computers_items', 'itemtype="Monitor" AND `id` > 6'),
                          'First computer (number id of links recreated)');


      $pfConfig->updateValue('import_monitor', 2);
      PluginFusioninventoryConfig::loadCache();
   }



   /**
    * @test
    */
   public function MonitorUniqueimport() {
      $this->mark_incomplete();
   }



   /**
    * @test
    */
   public function MonitorUniqueSerialimport() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;

      $pfConfig         = new PluginFusioninventoryConfig();
      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();

      $pfConfig->updateValue('import_monitor', 3);
      PluginFusioninventoryConfig::loadCache();

      $a_computerinventory = $this->a_computer1;
      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id = $computer->add($a_computer);

      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      FALSE,
                                      1);

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer');
      $this->assertEquals(1,
                          countElementsInTable('glpi_computers_items', 'itemtype="Monitor"'),
                          'First computer (links)');

      // Second try (verify not create a second same monitor)
       $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      FALSE,
                                      1);

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly (2)');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer (2)');
      $this->assertEquals(1,
                          countElementsInTable('glpi_computers_items', 'itemtype="Monitor"'),
                          'First computer (links) (2)');

      // Second computer with same monitor
      $a_computerinventory = $this->a_computer2;
      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id = $computer->add($a_computer);

      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      FALSE,
                                      1);

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'Second computer');
      $this->assertEquals(2,
                          countElementsInTable('glpi_computers_items', 'itemtype="Monitor"'),
                          'Second computer (links)');

      // Retry first computer without monitor
      $a_computerinventory = $this->a_computer1;
      $a_computerinventory['monitor'] = array();
      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      FALSE,
                                      1);

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly (3)');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer (3)');
      $this->assertEquals(1,
                          countElementsInTable('glpi_computers_items', 'itemtype="Monitor"'),
                          'First computer (links) (3)');


      // * Retry first computer with monitor
      $a_computerinventory = $this->a_computer1;
      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      FALSE,
                                      1);

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly (4)');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer (4)');
      $this->assertEquals(2,
                          countElementsInTable('glpi_computers_items', 'itemtype="Monitor"'),
                          'First computer (links) (4)');

      $this->assertEquals(0,
                          countElementsInTable('glpi_computers_items', 'itemtype="Monitor" AND `id` > 3'),
                          'First computer (number id of links recreated) (4)');

      // * Retry first computer with monitor have same serial number
      // but have different comment
      $a_computerinventory = $this->a_computer1;
      $a_computerinventory['monitor'][0]['comment'] = '31/2012';
      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      FALSE,
                                      1);

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly (5)');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer (5)');
      $this->assertEquals(2,
                          countElementsInTable('glpi_computers_items', 'itemtype="Monitor"'),
                          'First computer (links) (5)');

      $this->assertEquals(0,
                          countElementsInTable('glpi_computers_items', 'itemtype="Monitor" AND `id` > 3'),
                          'First computer (number id of links recreated) (5)');


      $pfConfig->updateValue('import_monitor', 2);
      PluginFusioninventoryConfig::loadCache();
   }
}
?>
