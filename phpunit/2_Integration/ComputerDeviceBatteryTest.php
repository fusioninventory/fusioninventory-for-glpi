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
   @author    Johan Cwiklinski
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2016

   ------------------------------------------------------------------------
 */

class ComputerDeviceBatteryTest extends RestoreDatabase_TestCase {
   public $a_computer1 = [];
   public $a_computer1_beforeformat = [];

   function __construct() {
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
         'batteries'      => [
            [
               'capacity'              => '57530',
               'designation'           => 'THE BATTERY',
               'manufacturing_date'    => '2015-02-21',
               'devicebatterytypes_id' => 'Li-ION',
               'manufacturers_id'      => 'MANU',
               'voltage'               => '14000',
               'serial'                => '0E52B'
            ]
         ],
         'remote_mgmt'    => [],
         'itemtype'       => 'Computer'
      ];

      $this->a_computer1_beforeformat = [
            "HARDWARE" => ["NAME"   => "pc001"],
            "BIOS" => ["SSN" => "ggheb7ne7"],
            'BATTERIES' => [
               'CAPACITY'     => '57530',
               'CHEMISTRY'    => 'Li-ION',
               'DATE'         => '21/02/2015',
               'NAME'         => 'THE BATTERY',
               'SERIAL'       => '0E52B',
               'MANUFACTURER' => 'MANU',
               'VOLTAGE'      => '14000'
            ]
      ];
   }


   /**
    * @test
    */
   public function BatteryWithFullInfos() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfFormatconvert  = new PluginFusioninventoryFormatconvert();
      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();

      $a_computerinventory = $this->a_computer1;
      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id = $computer->add($a_computer);

      $a_computerinventory = $pfFormatconvert->replaceids($a_computerinventory, 'Computer', 1);
      $pfiComputerLib->updateComputer(
         $a_computerinventory,
         $computers_id,
         FALSE,
         1
      );

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');

      $this->assertEquals(
         1,
         countElementsInTable('glpi_devicebatteries'),
         'Battery may be added in core table'
      );
      $this->assertEquals(
         1,
         countElementsInTable('glpi_items_devicebatteries'),
         'Battery with item may be added in core table'
      );
      $this->assertEquals(
         1,
         countElementsInTable('glpi_devicebatterytypes'),
         'Battery type may be added in core table'
      );

      $pfDevicebattery = new DeviceBattery();
      $pfDevicebattery->getFromDB(1);
      $date = $pfDevicebattery->fields['date_creation'];
      $a_ref = [
         'id'                       => '1',
         'designation'              => 'THE BATTERY',
         'manufacturers_id'         => '1',
         'devicebatterytypes_id'    => '1',
         'comment'                  => null,
         'voltage'                  => '140',
         'capacity'                 => '575',
         'entities_id'              => '0',
         'is_recursive'             => '0',
         'devicebatterymodels_id'   => null,
         'date_mod'                => $date,
         'date_creation'           => $date
      ];

      $this->assertEquals(
         $a_ref,
         $pfDevicebattery->fields,
         'Battery data'
      );

      $pfItemDevicebattery = new Item_DeviceBattery();
      $pfItemDevicebattery->getFromDB(1);

      $a_ref = [
         'id'                 => '1',
         'items_id'           => '1',
         'itemtype'           => 'Computer',
         'devicebatteries_id' => '1',
         'manufacturing_date' => '2015-02-21',
         'is_deleted'         => '0',
         'is_dynamic'         => '1',
         'entities_id'        => '0',
         'is_recursive'       => '0',
         'serial'             => '0E52B',
         'otherserial'        => NULL,
         'locations_id'       => '0',
         'states_id'          => '0'
      ];

      $this->assertEquals(
         $a_ref,
         $pfItemDevicebattery->fields,
         'Battery data'
      );
   }


   /**
    * @test
    */
   public function BatteryWithoutFullInfos() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfFormatconvert  = new PluginFusioninventoryFormatconvert();
      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();

      $a_computerinventory = $this->a_computer1;
      $a_computerinventory['batteries'] = [
         [
            'capacity'              => '0',
            'designation'           => '',
            'manufacturing_date'    => '',
            'devicebatterytypes_id' => 'Li-ION',
            'manufacturers_id'      => 'OTHER MANU',
            'voltage'               => '',
            'serial'                => '00000000'
         ]
      ];

      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id = $computer->add($a_computer);

      $a_computerinventory = $pfFormatconvert->replaceids($a_computerinventory, 'Computer', 1);
      $pfiComputerLib->updateComputer(
         $a_computerinventory,
         $computers_id,
         FALSE,
         1
      );

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');

      $this->assertEquals(
         2,
         countElementsInTable('glpi_devicebatteries'),
         'Battery may be added in core table'
      );
      $this->assertEquals(
         2,
         countElementsInTable('glpi_items_devicebatteries'),
         'Battery with item may be added in core table'
      );
      $this->assertEquals(
         1,
         countElementsInTable('glpi_devicebatterytypes'),
         'Battery type may be added in core table'
      );

      $pfDevicebattery = new DeviceBattery();
      $pfDevicebattery->getFromDB(2);
      $date = $pfDevicebattery->fields['date_creation'];
      $a_ref = [
         'id'                       => '2',
         'designation'              => 'Portable battery',
         'manufacturers_id'         => '2',
         'devicebatterytypes_id'    => '1',
         'comment'                  => null,
         'voltage'                  => '0',
         'capacity'                 => '0',
         'entities_id'              => '0',
         'is_recursive'             => '0',
         'devicebatterymodels_id'   => null,
         'date_mod'                => $date,
         'date_creation'           => $date
      ];

      $this->assertEquals(
         $a_ref,
         $pfDevicebattery->fields,
         'Battery data'
      );

      $pfItemDevicebattery = new Item_DeviceBattery();
      $pfItemDevicebattery->getFromDB(2);

      $a_ref = [
         'id'                 => '2',
         'items_id'           => '2',
         'itemtype'           => 'Computer',
         'devicebatteries_id' => '2',
         'manufacturing_date' => '0000-00-00',
         'is_deleted'         => '0',
         'is_dynamic'         => '1',
         'entities_id'        => '0',
         'is_recursive'       => '0',
         'serial'             => '00000000',
         'otherserial'        => NULL,
         'locations_id'       => '0',
         'states_id'          => '0'
      ];

      $this->assertEquals(
         $a_ref,
         $pfItemDevicebattery->fields,
         'Battery data'
      );
   }

}
