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
   @author    Johan Cwiklinski
   @co-author David Durieux
   @copyright Copyright (C) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2016

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class ComputerDeviceSimcardTest extends TestCase {
   public $a_computer1 = [];
   public $a_computer1_beforeformat = [];

   public static function setUpBeforeClass(): void {

      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }
   }

   function __construct() {
      parent::__construct();
      $this->a_computer1 = [
         "Computer" => [
            "name"   => "computer_simcard",
            "serial" => "1234567890"
         ],
         "fusioninventorycomputer" => [
            'last_fusioninventory_update' => date('Y-m-d H:i:s'),
            'serialized_inventory'        => 'something'
         ],
         'soundcard'      => [],
         'graphiccard'    => [],
         'controller'     => [],
         'processor'      => [],
         'computerdisk'   => [],
         'memory'         => [],
         'monitor'        => [],
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
         'simcards'       => [
            [
               'serial'            => '11124406000051565111',
               'msin'              => '204043721717241',
               'devicesimcards_id' => 1,
               'is_dynamic'        => 1,
               'entities_id'       => 0
            ],
            [
               'serial'            => '22344406000051565613',
               'msin'              => '126543721717241',
               'devicesimcards_id' => 1,
               'is_dynamic'        => 1,
               'entities_id'       => 0
            ],
         ],
         'itemtype'       => 'Computer'
      ];

      $this->a_computer1_beforeformat = [
            "HARDWARE" => ["NAME"   => "computer_simcard"],
            "BIOS" => ["SSN" => "1234567890"],
            'SIMCARDS' => [
               'COUNTRY'       => 'France',
               'ICCID'         => '11124406000051565111',
               'IMSI'          => '204043721717241',
               'OPERATOR_CODE' => '208.10',
               'OPERATOR_NAME' => 'SFR',
               'STATE'         => 'SIM1 - Ready (PIN checking disabled)',
            ],
            'SIMCARDS' => [
               'COUNTRY'       => 'France',
               'ICCID'         => '89314404000051565613',
               'IMSI'          => '204043724717249',
               'OPERATOR_CODE' => '208.10',
               'OPERATOR_NAME' => 'SFR',
               'STATE'         => 'SIM1 - Ready (PIN checking disabled)',
            ]
      ];

      $this->a_computer2 = [
         "Computer" => [
            "name"   => "computer_simcard_2",
            "serial" => "2345678901"
         ],
         "fusioninventorycomputer" => [
            'last_fusioninventory_update' => date('Y-m-d H:i:s'),
            'serialized_inventory'        => 'something'
         ],
         'soundcard'      => [],
         'graphiccard'    => [],
         'controller'     => [],
         'processor'      => [],
         'computerdisk'   => [],
         'memory'         => [],
         'monitor'        => [],
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
         'simcards'       => [
            ['serial'            => '11124406000051565111',
             'msin'              => '204043721717241',
             'devicesimcards_id' => 1,
             'is_dynamic'        => 1,
             'entities_id'       => 0
            ]
         ],
         'itemtype'       => 'Computer'
      ];

   }

   /**
    * @test
    */
   public function testSimcardTransformation() {
      $formatConvert = new PluginFusioninventoryFormatconvert();

      $input = [
         'SIMCARDS' => [
            0  => [
               'COUNTRY'       => 'France',
               'ICCID'         => "11124406000051565111",
               'IMSI'          => "204043721717241",
               'OPERATOR_CODE' => '208.10',
               'OPERATOR_NAME' => 'SFR',
               'STATE'         => 'SIM1 - Ready (PIN checking disabled)'
            ]
         ]
      ];
      $a_inventory = [];
      $formatConvert->simcardTransformation($input, $a_inventory);
      $this->assertEquals(1, count($a_inventory['simcards']));

      $input['SIMCARDS'] = [
         0 => [
               'COUNTRY'       => 'France',
               'ICCID'         => "11124406000051565111",
               'IMSI'          => "204043721717241",
               'OPERATOR_CODE' => '208.10',
               'OPERATOR_NAME' => 'SFR',
               'STATE'         => 'SIM1 - Ready (PIN checking disabled)',
            ],
         1 => [
               'COUNTRY'       => 'France',
               'ICCID'         => "89314404000051565613",
               'IMSI'          => "204043724717249",
               'OPERATOR_CODE' => '208.10',
               'OPERATOR_NAME' => 'SFR',
               'STATE'         => 'SIM1 - Ready (PIN checking disabled)',
         ]
      ];

      $a_inventory = [];
      $formatConvert->simcardTransformation($input, $a_inventory);
      $this->assertEquals(2, count($a_inventory['simcards']));
   }

   /**
    * @test
    */
   public function testImportSimcards() {
      $computers_id = $this->updateComputer($this->a_computer1);

      $this->assertEquals(
         1,
         countElementsInTable('glpi_devicesimcards'),
         'Simcard may be added in core table'
      );
      $this->assertEquals(
         2,
         countElementsInTable('glpi_items_devicesimcards'),
         'Simcards with item may be added in core table'
      );

      $pfDeviceSimcard = new DeviceSimcard();
      $pfDeviceSimcard->getFromDBByCrit(['designation' => 'Simcard']);
      $date = $pfDeviceSimcard->fields['date_creation'];
      $a_ref = [
         'id'                    => $pfDeviceSimcard->fields['id'],
         'designation'           => 'Simcard',
         'manufacturers_id'      => 0,
         'comment'               => null,
         'entities_id'           => 0,
         'is_recursive'          => 0,
         'devicesimcardtypes_id' => 0,
         'voltage'               => null,
         'allow_voip'            => 0,
         'date_mod'              => $date,
         'date_creation'         => $date
      ];
      $this->assertEquals(
         $a_ref,
         $pfDeviceSimcard->fields,
         'Simcard component data'
      );

      $pfItemDeviceSimcard = new Item_DeviceSimcard();
      $pfItemDeviceSimcard->getFromDBByCrit(['serial' => '11124406000051565111']);
      $this->assertGreaterThan(0, $pfItemDeviceSimcard->fields['id']);

      $a_ref = [
         'id'                 => $pfItemDeviceSimcard->fields['id'],
         'items_id'           => $computers_id,
         'itemtype'           => 'Computer',
         'devicesimcards_id'  => $pfDeviceSimcard->fields['id'],
         'is_deleted'         => 0,
         'is_dynamic'         => 1,
         'entities_id'        => 0,
         'is_recursive'       => 0,
         'serial'             => "11124406000051565111",
         'otherserial'        => null,
         'locations_id'       => 0,
         'lines_id'           => 0,
         'states_id'          => 0,
         'pin'                => '',
         'pin2'               => '',
         'puk'                => '',
         'puk2'               => '',
         'msin'               => "204043721717241",
         'users_id'           => 0,
         'groups_id'          => 0
      ];

      $this->assertEquals(
         $a_ref,
         $pfItemDeviceSimcard->fields,
         'Item Simcard data'
      );

      $pfItemDeviceSimcard->getFromDBByCrit(['serial' => '22344406000051565613']);
      $this->assertGreaterThan(0, $pfItemDeviceSimcard->fields['id']);

      $a_ref = [
         'id'                 => $pfItemDeviceSimcard->fields['id'],
         'items_id'           => $computers_id,
         'itemtype'           => 'Computer',
         'devicesimcards_id'  => 1,
         'is_deleted'         => 0,
         'is_dynamic'         => 1,
         'entities_id'        => 0,
         'is_recursive'       => 0,
         'serial'             => "22344406000051565613",
         'otherserial'        => null,
         'locations_id'       => 0,
         'lines_id'           => 0,
         'states_id'          => 0,
         'pin'                => '',
         'pin2'               => '',
         'puk'                => '',
         'puk2'               => '',
         'msin'               => "126543721717241",
         'users_id'           => 0,
         'groups_id'          => 0
      ];

      $this->assertEquals(
         $a_ref,
         $pfItemDeviceSimcard->fields,
         'Item Simcard data'
      );
   }

   /**
    * @test
    * Test to check that if the simcard is inserted in another asset, the relation
    * remains by the items_id changes
    */
   public function testSameSimcardOnAnotherAsset() {
      $computers_id = $this->updateComputer($this->a_computer2);

      $pfItemDeviceSimcard = new Item_DeviceSimcard();
      $this->assertGreaterThan(0,
                               $pfItemDeviceSimcard->getFromDBByCrit(['itemtype' => 'Computer',
                                                                      'items_id' => $computers_id,
                                                                      'serial'   => '11124406000051565111']));

      $computer = new Computer();
      $this->assertGreaterThan(0, $computer->getFromDBByCrit(['name' => 'computer_simcard']));
      $this->assertGreaterThan(0,
                               $pfItemDeviceSimcard->getFromDBByCrit(['itemtype' => 'Computer',
                                                                      'items_id' => $computer->getID(),
                                                                      'serial'   => '22344406000051565613']));
   }

   /**
    * @test
    * Insert a computer and add an inventory
    */
   private function updateComputer($computer_fields) {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfFormatconvert  = new PluginFusioninventoryFormatconvert();
      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();

      $a_computerinventory       = $computer_fields;
      $a_computer                = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id              = $computer->add($a_computer);

      $a_computerinventory = $pfFormatconvert->replaceids($a_computerinventory, 'Computer', 1);
      $pfiComputerLib->updateComputer(
         $a_computerinventory,
         $computers_id,
         false,
         1
      );

      $computer->getFromDB($computers_id);
      $this->assertEquals($computer_fields['Computer']['serial'],
                          $computer->fields['serial'],
                          'Computer not updated correctly');
      return $computers_id;
   }
}
