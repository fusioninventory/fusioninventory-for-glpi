<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

class ComputerMonitor extends Common_TestCase {
   public $a_computer1 = [];
   public $a_computer1_beforeformat = [];
   public $a_computer2 = [];

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
                  'name'    => 'DELL E1911',
                  'manufacturers_id'=> 2,
                  'serial'  => 'W6VPJ1840E7B',
                  'is_dynamic' => 1
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
                  'name'    => 'DELL E1911',
                  'manufacturers_id'=> 2,
                  'serial'  => 'W6VPJ1840E7B',
                  'is_dynamic' => 1
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
      global $DB;

      $DB->connect();

      self::restore_database();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfConfig         = new PluginFusioninventoryConfig();
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

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer');
      $this->assertEquals(1,
                          countElementsInTable('glpi_computers_items', ['itemtype' => 'Monitor']),
                          'First computer (links)');

      // Second try (verify not create a second same monitor)
       $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      false,
                                      1);

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly (2)');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer (2)');
      $this->assertEquals(1,
                          countElementsInTable('glpi_computers_items', ['itemtype' => 'Monitor']),
                          'First computer (links) (2)');

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

      // Retry first computer without monitor
      $a_computerinventory = $this->a_computer1;
      $a_computerinventory['monitor'] = [];
      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      false,
                                      1);

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly (3)');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer (3)');
      $this->assertEquals(1,
                          countElementsInTable('glpi_computers_items', ['itemtype' => 'Monitor']),
                          'First computer (links) (3)');

      // * Retry first computer with monitor
      $a_computerinventory = $this->a_computer1;
      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      false,
                                      1);

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly (4)');

      $this->assertEquals(1, countElementsInTable('glpi_monitors'), 'First computer (4)');
      $this->assertEquals(1,
                          countElementsInTable('glpi_computers_items', ['itemtype'=> 'Monitor']),
                          'First computer (links) (4)');

      $this->assertEquals(0,
                          countElementsInTable('glpi_computers_items', ['itemtype' => 'Monitor', 'id' => ['>', 3]]),
                          'First computer (number id of links recreated) (4)');
   }


}
