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

class ComputerLicenseTest extends RestoreDatabase_TestCase {
   public $a_computer1 = [];
   public $a_computer1_beforeformat = [];

   /*
    * Why do you define a constructor here while you can set this 2 variables up ahead ???
    */
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
          "computerdisk"   => [],
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
          'licenseinfo'    => [
              [
                  'name'     => 'Microsoft Office 2003',
                  'fullname' => 'Microsoft Office Professional Edition 2003',
                  'serial'   => 'xxxxx-xxxxx-P6RC4-xxxxx-xxxxx'
              ]
          ],
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
              'LICENSEINFOS' => [
                  [
                      'COMPONENTS' => 'Word/Excel/Access/Outlook/PowerPoint/Publisher/InfoPath',
                      'FULLNAME'   => 'Microsoft Office Professional Edition 2003',
                      'KEY'        => 'xxxxx-xxxxx-P6RC4-xxxxx-xxxxx',
                      'NAME'       => 'Microsoft Office 2003',
                      'PRODUCTID'  => 'xxxxx-640-0000xxx-xxxxx'
                  ]
              ]
          ]
      ];
   }


   /**
    * @test
    */
   public function Licenses() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();
      $GLPIlog          = new GLPIlogs();

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

      $this->assertEquals(1,
                          countElementsInTable('glpi_plugin_fusioninventory_computerlicenseinfos'),
                          'License may be added in fusion table');

      $pfComputerLicenseInfo = new PluginFusioninventoryComputerLicenseInfo();
      $pfComputerLicenseInfo->getFromDB(1);
      $a_ref = [
          'id'                   => 1,
          'computers_id'         => 1,
          'softwarelicenses_id'  => 0,
          'name'                 => 'Microsoft Office 2003',
          'fullname'             => 'Microsoft Office Professional Edition 2003',
          'serial'               => 'xxxxx-xxxxx-P6RC4-xxxxx-xxxxx',
          'is_trial'             => '0',
          'is_update'            => '0',
          'is_oem'               => '0',
          'activation_date'      => null
      ];

      $this->assertEquals($a_ref,
                          $pfComputerLicenseInfo->fields,
                          'License data');
   }


   /**
    * @test
    */
   public function testCleanComputer() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      //First, check if license does exist
      $pfComputerLicenseInfo = new PluginFusioninventoryComputerLicenseInfo();
      $pfComputerLicenseInfo->getFromDB(1);

      $a_ref = [
          'id'                   => 1,
          'computers_id'         => 1,
          'softwarelicenses_id'  => 0,
          'name'                 => 'Microsoft Office 2003',
          'fullname'             => 'Microsoft Office Professional Edition 2003',
          'serial'               => 'xxxxx-xxxxx-P6RC4-xxxxx-xxxxx',
          'is_trial'             => '0',
          'is_update'            => '0',
          'is_oem'               => '0',
          'activation_date'      => null
      ];

      $this->assertEquals($a_ref,
                          $pfComputerLicenseInfo->fields,
                          'License data');

      //Second, clean and check if it has been removed
      $pfComputerLicenseInfo = new PluginFusioninventoryComputerLicenseInfo();
      $pfComputerLicenseInfo->cleanComputer(1);

      $pfComputerLicenseInfo->getFromDB(1);
      $this->assertEquals(0, count($pfComputerLicenseInfo->fields));
   }


   /**
    * @test
    */
   public function testDeleteComputer() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      // Create computer
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

      //First, check if license does exist
      $pfComputerLicenseInfo = new PluginFusioninventoryComputerLicenseInfo();
      $pfComputerLicenseInfo->getFromDB(1);

      $a_ref = [
          'id'                   => 1,
          'computers_id'         => 1,
          'softwarelicenses_id'  => 0,
          'name'                 => 'Microsoft Office 2003',
          'fullname'             => 'Microsoft Office Professional Edition 2003',
          'serial'               => 'xxxxx-xxxxx-P6RC4-xxxxx-xxxxx',
          'is_trial'             => '0',
          'is_update'            => '0',
          'is_oem'               => '0',
          'activation_date'      => null
      ];

      $this->assertEquals(
         $a_ref,
         $pfComputerLicenseInfo->fields,
         'License data'
      );

      //delete computer and check if it has been removed
      $computer->delete(['id' => $computers_id]);
      $this->assertTrue($computer->getFromDB($computers_id));

      $pfComputerLicenseInfo = new PluginFusioninventoryComputerLicenseInfo();
      $pfComputerLicenseInfo->getFromDB(1);
      $this->assertEquals(10, count($pfComputerLicenseInfo->fields));

      //purge computer and check if it has been removed
      $computer->delete(['id' => $computers_id], 1);
      $this->assertFalse($computer->getFromDB($computers_id));

      $pfComputerLicenseInfo = new PluginFusioninventoryComputerLicenseInfo();
      $pfComputerLicenseInfo->getFromDB(1);
      $this->assertEquals(0, count($pfComputerLicenseInfo->fields));
   }


}

