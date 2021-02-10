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

class ComputerAntivirusTest extends TestCase {
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
          'monitor'        => [],
          'printer'        => [],
          'peripheral'     => [],
          'networkport'    => [],
          'software'       => [],
          'harddrive'      => [],
          'virtualmachine' => [],
          'antivirus'      => [
             [
                'name'              => 'Trend Micro Security Agent',
                'manufacturers_id'  => '',
                'antivirus_version' => '',
                'is_active'         => '1',
                'is_uptodate'       => '1'
             ]
          ],
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
              'ANTIVIRUS' => [
                 'ENABLED'  => 1,
                 'GUID'     => '{8242D66F-41BD-4049-C2E6-E578E73B62A0}',
                 'NAME'     => 'Trend Micro Security Agent',
                 'UPTODATE' => 1
              ]
          ]
      ];
   }


   /**
    * @test
    */
   public function testAntiviruses() {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();

      $a_computerinventory = $this->a_computer1;
      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id = $computer->add($a_computer);

      $pfiComputerLib->updateComputer(
         $a_computerinventory,
         $computers_id,
         false,
         1
      );

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');

      $this->assertEquals(
         1,
         countElementsInTable('glpi_computerantiviruses'),
         'Antivirus may be added in core table'
      );

      $computerAntivirus = new ComputerAntivirus();

      $this->assertEquals(1, countElementsInTable('glpi_computerantiviruses'));
      $computerAntivirus->getFromDBByCrit(['name' => 'Trend Micro Security Agent']);
      $date = $computerAntivirus->fields['date_creation'];
      $a_ref = [
          'id'                  => $computerAntivirus->fields['id'],
          'computers_id'        => $computers_id,
          'name'                => 'Trend Micro Security Agent',
          'manufacturers_id'    => 0,
          'antivirus_version'   => '',
          'signature_version'   => null,
          'is_active'           => 1,
          'is_deleted'          => 0,
          'is_uptodate'         => 1,
          'is_dynamic'          => 1,
          'date_mod'            => $date,
          'date_creation'       => $date,
          'date_expiration'     => null
      ];

      $this->assertEquals(
         $a_ref,
         $computerAntivirus->fields,
         'Antivirus data'
      );

      //update antivirus
      $a_computerinventory['antivirus'][0]['is_active'] = '0';
      $a_computerinventory['antivirus'][0]['is_uptodate'] = '0';

      $pfiComputerLib->updateComputer(
         $a_computerinventory,
         $computers_id,
         false,
         1
      );

      $this->assertEquals(1, countElementsInTable('glpi_computerantiviruses'));
      $computerAntivirus->getFromDBByCrit(['name' => 'Trend Micro Security Agent']);
      $a_ref = [
          'id'                  => $computerAntivirus->fields['id'],
          'computers_id'        => $computers_id,
          'name'                => 'Trend Micro Security Agent',
          'manufacturers_id'    => 0,
          'antivirus_version'   => '',
          'signature_version'   => null,
          'is_active'           => 0,
          'is_deleted'          => 0,
          'is_uptodate'         => 0,
          'is_dynamic'          => 1,
          'date_mod'            => $date,
          'date_creation'       => $date,
          'date_expiration'     => null
      ];

      $this->assertEquals(
         $a_ref,
         $computerAntivirus->fields,
         'Antivirus updated data'
      );
   }
}
