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

class ComputerMonitorTest extends TestCase {
   public $a_computer1 = [];
   public $a_computer1_beforeformat = [];
   public $a_computer2 = [];

   public static function setUpBeforeClass(): void {

      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }
      // Delete all monitors
      $monitor = new Monitor();
      $items = $monitor->find();
      foreach ($items as $item) {
         $monitor->delete(['id' => $item['id']], true);
      }
   }


   /*
    * Why do you define a constructor here while you can set this 2 variables up ahead ???
    */
   function __construct() {
      parent::__construct();
      $this->a_computer1 = [
         "Computer" => [
            "name"   => "pc001",
            "serial" => "ggheb7ne7"
         ],
         "fusioninventorycomputer" => [
            'last_fusioninventory_update' => date('Y-m-d H:i:s'),
            'serialized_inventory'        => 'something'
         ],
         'soundcard'      => [],
         'graphiccard'    => [],
         'controller'     => [],
         'processor'      => [],
         "computerdisk"   => [],
         'memory'         => [],
         'monitor'        => [
            [
               'name'             => 'DELL E1911',
               'manufacturers_id' => 2,
               'serial'           => 'W6VPJ1840E7B',
               'is_dynamic'       => 1
            ]
         ],
         'printer'        => [],
         'peripheral'     => [],
         'networkport'    => [],
         'software'       => [],
         'harddrive'      => [],
         'virtualmachine' => [],
         'antivirus'      => [],
         'storage'        => [],
         'licenseinfo'    => [],
         'networkcard'    => [],
         'drive'          => [],
         'batteries'      => [],
         'remote_mgmt'    => [],
         'bios'           => [],
         'itemtype'       => 'Computer'
      ];

      $this->a_computer1_beforeformat = [
         "CONTENT" => [
            "HARDWARE" => [
               "NAME"   => "pc001"
            ],
            "BIOS" => [
               "SSN" => "ggheb7ne7"
            ],
            'MONITORS'        => [
               [
                     'BASE64' => 'AP///////wAQrDbwQjdFMB8VAQMOKRp47u6Vo1RMmSYPUFS/74CVAHFPgYCVD4EAAQEBAQEBmimg0FGEIjBQmDYAmP8QAAAcAAAA/wBXNlZQSjE4NDBFN0IKAAAA/ABERUxMIEUxOTExCiAgAAAA/QA4Sx5TDgAKICAgICAgALM=',
                     'CAPTION' => 'DELL E1911',
                     'DESCRIPTION' => '31/2011',
                     'MANUFACTURER' => 'Dell Computer Corp.',
                     'SERIAL' => 'W6VPJ1840E7B'
               ]
            ]
         ]
      ];

      $this->a_computer2 = [
         "Computer" => [
            "name"   => "pc002",
            "serial" => "ggheb7ne8"
         ],
         "fusioninventorycomputer" => [
            'last_fusioninventory_update' => date('Y-m-d H:i:s'),
            'serialized_inventory'        => 'something'
         ],
         'soundcard'      => [],
         'graphiccard'    => [],
         'controller'     => [],
         'processor'      => [],
         "computerdisk"   => [],
         'memory'         => [],
         'monitor'        => [
            [
               'name'             => 'DELL E1911',
               'manufacturers_id' => 2,
               'serial'           => 'W6VPJ1840E7B',
               'is_dynamic'       => 1
            ]
         ],
         'printer'        => [],
         'peripheral'     => [],
         'networkport'    => [],
         'software'       => [],
         'harddrive'      => [],
         'virtualmachine' => [],
         'antivirus'      => [],
         'storage'        => [],
         'licenseinfo'    => [],
         'networkcard'    => [],
         'drive'          => [],
         'batteries'      => [],
         'remote_mgmt'    => [],
         'bios'           => [],
         'itemtype'       => 'Computer'
      ];
   }


   /**
    * @test
    */
   public function MonitorUniqueSerialimport() {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();

      $a_computerinventory = $this->a_computer1;
      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id = $computer->add($a_computer);

      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      false,
                                      1);

      $computer->getFromDBByCrit(['name' => 'pc001']);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer');
      $this->assertEquals(1,
                          countElementsInTable('glpi_computers_items', ['itemtype' => 'Monitor']),
                          'First computer (links)');
   }


   /**
    * @test
    */
   public function MonitorUniqueSecondImport() {

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();

      // Second try (verify not create a second same monitor)
      $a_computerinventory = $this->a_computer1;
      $computer->getFromDBByCrit(['name' => 'pc001']);

      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computer->fields['id'],
                                      false,
                                      1);

      $computer->getFromDBByCrit(['name' => 'pc001']);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly (2)');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer (2)');
      $this->assertEquals(1,
                          countElementsInTable('glpi_computers_items', ['itemtype' => 'Monitor']),
                          'First computer (links) (2)');
   }


   /**
    * @test
    */
   public function MonitorUniqueConnectedOnSecondComputer() {

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();

      // Second computer with same monitor
      $a_computerinventory = $this->a_computer2;
      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id = $computer->add($a_computer);

      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      false,
                                      1);

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'Second computer');
      $this->assertEquals(1,
                          countElementsInTable('glpi_computers_items', ['itemtype' => 'Monitor']),
                          'Second computer (links)');

      $computer_Item = new Computer_Item();
      $monitor = new Monitor();
      $monitor->getFromDBByCrit(['serial' => 'W6VPJ1840E7B']);
      $computer_Item->getFromDBByCrit([
         'itemtype' => 'Monitor',
      ]);
      $reference = [
         'id'           => $computer_Item->fields['id'],
         'items_id'     => $monitor->fields['id'],
         'computers_id' => $computers_id,
         'itemtype'     => 'Monitor',
         'is_deleted'   => 0,
         'is_dynamic'   => 1
      ];
      $this->assertEquals($reference, $computer_Item->fields);
   }


   /**
    * @test
    */
   public function UpdateFirstComputerWithoutMonitor() {

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();

      // Retry first computer without monitor
      $a_computerinventory = $this->a_computer1;
      $a_computerinventory['monitor'] = [];
      $computer->getFromDBByCrit(['name' => 'pc001']);

      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computer->fields['id'],
                                      false,
                                      1);

      $computer->getFromDBByCrit(['name' => 'pc001']);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly (3)');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer (3)');
      $this->assertEquals(1,
                          countElementsInTable('glpi_computers_items', ['itemtype' => 'Monitor']),
                          'First computer (links) (3)');
   }

   /**
    * @test
    */
   public function UpdateFirstComputerWithMonitor() {

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();

      // * Retry first computer with monitor
      $a_computerinventory = $this->a_computer1;
      $computer->getFromDBByCrit(['name' => 'pc001']);

      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computer->fields['id'],
                                      false,
                                      1);

      $computer->getFromDBByCrit(['name' => 'pc001']);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly (4)');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer (4)');
      $this->assertEquals(1,
                          countElementsInTable('glpi_computers_items', ['itemtype'=> 'Monitor']),
                          'First computer (links) (4)');

      $computer_Item = new Computer_Item();
      $monitor = new Monitor();
      $monitor->getFromDBByCrit(['serial' => 'W6VPJ1840E7B']);
      $computer_Item->getFromDBByCrit([
         'itemtype' => 'Monitor',
      ]);
      $reference = [
         'id'           => $computer_Item->fields['id'],
         'items_id'     => $monitor->fields['id'],
         'computers_id' => $computer->fields['id'],
         'itemtype'     => 'Monitor',
         'is_deleted'   => 0,
         'is_dynamic'   => 1
      ];
      $this->assertEquals($reference, $computer_Item->fields);
   }
}
