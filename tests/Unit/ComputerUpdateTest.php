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

class ComputerUpdateTest extends TestCase {

   public $datelatupdate = '';


   /**
    * @test
    */
   public function AddComputer() {

      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }
      // Delete all softwares
      $software = new Software();
      $items = $software->find();
      foreach ($items as $item) {
         $software->delete(['id' => $item['id']], true);
      }
      // Delete all deviceprocessors
      $deviceProcessor = new DeviceProcessor();
      $items = $deviceProcessor->find();
      foreach ($items as $item) {
         $deviceProcessor->delete(['id' => $item['id']], true);
      }
      // Delete all devicememories
      $deviceMemory = new DeviceMemory();
      $items = $deviceMemory->find();
      foreach ($items as $item) {
         $deviceMemory->delete(['id' => $item['id']], true);
      }

      // Delete all monitors
      $monitor = new Monitor();
      $items = $monitor->find();
      foreach ($items as $item) {
         $monitor->delete(['id' => $item['id']], true);
      }

      $_SESSION['plugin_fusioninventory_classrulepassed'] = '';

      $date = date('Y-m-d H:i:s');

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_inventory = [
          'fusioninventorycomputer' => [
              'winowner'                        => 'test',
              'wincompany'                      => 'siprossii',
              'operatingsystem_installationdate'=> '2012-10-16 08:12:56',
              'last_fusioninventory_update'     => $date,
              'last_boot'                       => '2018-06-11 08:03:32',
              'items_operatingsystems_id'       => [
                  'operatingsystems_id'              => 'freebsd',
                  'operatingsystemversions_id'       => '9.1-RELEASE',
                  'operatingsystemarchitectures_id'  => '',
                  'operatingsystemkernels_id'        => '',
                  'operatingsystemkernelversions_id' => '',
                  'operatingsystemservicepacks_id'   => 'GENERIC ()root@farrell.cse.buffalo.edu',
                  'operatingsystemeditions_id'       => '',
                  'licenseid'                        => '',
                  'license_number'                   => ''
              ]
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
          'SOFTWARES'      => [],
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
      $a_inventory['Computer'] = [
          'name'                             => 'pc',
          'users_id'                         => 0,
          'uuid'                             => '68405E00-E5BE-11DF-801C-B05981201220',
          'manufacturers_id'                 => '',
          'computermodels_id'                => '',
          'serial'                           => 'XB63J7D',
          'computertypes_id'                 => 'Notebook',
          'is_dynamic'                       => 1,
          'contact'                          => 'ddurieux'
      ];

      $a_inventory['processor'] = [
            [
                    'nbcores'           => 2,
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'serial'            => '',
                    'frequency'         => 2400,
                    'frequence'         => 2400,
                    'nbthreads'         => 2,
                    'frequency_default' => 2400
                ],
            [
                    'nbcores'           => 2,
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'serial'            => '',
                    'frequency'         => 2400,
                    'frequence'         => 2400,
                    'nbthreads'         => 2,
                    'frequency_default' => 2400
                ],
            [
                    'nbcores'           => 4,
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'serial'            => '',
                    'frequency'         => 2405,
                    'frequence'         => 2405,
                    'nbthreads'         => 4,
                    'frequency_default' => 2405
                ],
            [
                    'nbcores'           => 2,
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'serial'            => '',
                    'frequency'         => 2600,
                    'frequence'         => 2600,
                    'nbthreads'         => 4,
                    'frequency_default' => 2600
                ]
        ];

      $a_inventory['memory'] = [
            [
                    'size'                 => 2048,
                    'serial'               => '98F6FF18',
                    'frequence'            => '1067',
                    'devicememorytypes_id' => 'DDR3',
                    'designation'          => 'DDR3 - SODIMM (None)',
                    'busID'                => 1
                ],
            [
                    'size'                 => 2048,
                    'serial'               => '95F1833E',
                    'frequence'            => '1067',
                    'devicememorytypes_id' => 'DDR3',
                    'designation'          => 'DDR3 - SODIMM (None)',
                    'busID'                => 2
                ],
            [
                    'size'                 => 2048,
                    'serial'               => '95F1833G',
                    'frequence'            => '1066',
                    'devicememorytypes_id' => 'DDR3',
                    'designation'          => 'DDR3 - SODIMM (None)',
                    'busID'                => 3
                ],
            [
                    'size'                 => 2048,
                    'serial'               => '95F1833H',
                    'frequence'            => '1333',
                    'devicememorytypes_id' => 'DDR3',
                    'designation'          => 'DDR3 - SODIMM (None)',
                    'busID'                => 4
                ]
        ];

      $a_inventory['monitor'] = [
            [
                    'name'              => 'ThinkPad Display 1280x800',
                    'serial'            => 'UBYVUTFYEIUI',
                    'manufacturers_id'  => 'Lenovo',
                    'is_dynamic'        => 1
                ]
      ];

      $a_inventory['printer'] = [
            [
                    'name'      => 'HP Deskjet 5700 Series',
                    'serial'    => 'MY47L1W1JHEB6',
                    'have_usb'  => 1,
                    'is_dynamic' => 1
                ]
      ];

      $a_inventory['networkport'] = [
            'em0-00:23:18:cf:0d:93' => [
                    'name'                 => 'em0',
                    'netmask'              => '255.255.255.0',
                    'subnet'               => '192.168.30.0',
                    'mac'                  => '00:23:18:cf:0d:93',
                    'instantiation_type'   => 'NetworkPortEthernet',
                    'virtualdev'           => 0,
                    'ssid'                 => '',
                    'gateway'              => '',
                    'dhcpserver'           => '',
                    'logical_number'       => 1,
                    'ipaddress'            => ['192.168.30.198']
                ],
            'lo0-' => [
                    'name'                 => 'lo0',
                    'virtualdev'           => 1,
                    'mac'                  => '',
                    'instantiation_type'   => 'NetworkPortLocal',
                    'subnet'               => '',
                    'ssid'                 => '',
                    'gateway'              => '',
                    'netmask'              => '',
                    'dhcpserver'           => '',
                    'logical_number'       => 0,
                    'ipaddress'            => ['::1', 'fe80::1', '127.0.0.1']
                ]
        ];

      $a_inventory['software'] = [
            'gentiumbasic$$$$110$$$$1$$$$0$$$$0' => [
                    'name'                => 'GentiumBasic',
                    'version'             => 110,
                    'manufacturers_id'    => 1,
                    'entities_id'         => 0,
                    'is_template_item'    => 0,
                    'is_deleted_item'     => 0,
                    'operatingsystems_id' => 0
                ],
            'imagemagick$$$$6.8.0.7_1$$$$2$$$$0$$$$0' => [
                    'name'                => 'ImageMagick',
                    'version'             => '6.8.0.7_1',
                    'manufacturers_id'    => 2,
                    'entities_id'         => 0,
                    'is_template_item'    => 0,
                    'is_deleted_item'     => 0,
                    'operatingsystems_id' => 0
                ],
            'orbit2$$$$2.14.19$$$$3$$$$0$$$$0' => [
                    'name'                => 'ORBit2',
                    'version'             => '2.14.19',
                    'manufacturers_id'    => 3,
                    'entities_id'         => 0,
                    'is_template_item'    => 0,
                    'is_deleted_item'     => 0,
                    'operatingsystems_id' => 0,
                    'date_install'        => '2016-07-20'
                ]
          ];

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();
      $pfFormatconvert  = new PluginFusioninventoryFormatconvert();

      $a_inventory = $pfFormatconvert->replaceids($a_inventory, 'Computer', 0);

      $serialized = gzcompress(serialize($a_inventory));
      $a_inventory['fusioninventorycomputer']['serialized_inventory'] =
               Toolbox::addslashes_deep($serialized);

      $id = $computer->add(['serial'      => 'XB63J7D',
                                        'entities_id' => 0]);

      $this->assertGreaterThan(0, $id, false);
      $pfiComputerLib->updateComputer($a_inventory, $id, false);

      // To be sure not have 2 same informations
      $pfiComputerLib->updateComputer($a_inventory, $id, false);

      return $id;
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerGeneral($id) {
      $computer = new Computer();
      $computerType = new ComputerType();

      $computer->getFromDB($id);
      unset($computer->fields['date_mod']);
      unset($computer->fields['date_creation']);

      $computerType->getFromDBByCrit(['name' => 'Notebook']);

      $a_reference = [
          'name'                             => 'pc',
          'id'                               => $id,
          'entities_id'                      => 0,
          'serial'                           => 'XB63J7D',
          'otherserial'                      => null,
          'contact'                          => 'ddurieux',
          'contact_num'                      => null,
          'users_id_tech'                    => 0,
          'groups_id_tech'                   => 0,
          'comment'                          => null,
          'autoupdatesystems_id'             => 0,
          'locations_id'                     => 0,
          'networks_id'                      => 0,
          'computermodels_id'                => 0,
          'computertypes_id'                 => $computerType->fields['id'],
          'is_template'                      => 0,
          'template_name'                    => null,
          'manufacturers_id'                 => 0,
          'is_deleted'                       => 0,
          'is_dynamic'                       => 1,
          'users_id'                         => 0,
          'groups_id'                        => 0,
          'states_id'                        => 0,
          'ticket_tco'                       => '0.0000',
          'uuid'                             => '68405E00-E5BE-11DF-801C-B05981201220',
          'is_recursive'                     => 0
      ];

      $this->assertEquals($a_reference, $computer->fields);

      //check if operating system has been created
      $ios = new Item_OperatingSystem();
      $this->assertEquals(1, $ios->countForItem($computer));
      $this->assertTrue(
         $ios->getFromDBByCrit([
            'itemtype' => 'Computer',
            'items_id' => $id
         ])
      );

      $os = new OperatingSystem();
      $os->getFromDBByCrit(['name' => 'freebsd']);

      $osVersion = new OperatingSystemVersion();
      $osVersion->getFromDBByCrit(['name' => '9.1-RELEASE']);

      $osServicePack = new OperatingSystemServicePack();
      $osServicePack->getFromDBByCrit(['name' => 'GENERIC ()root@farrell.cse.buffalo.edu']);

      $a_reference = [
         'items_id'                          => $id,
         'itemtype'                          => 'Computer',
         'operatingsystems_id'               => $os->fields['id'],
         'operatingsystemversions_id'        => $osVersion->fields['id'],
         'operatingsystemservicepacks_id'    => $osServicePack->fields['id'],
         'operatingsystemarchitectures_id'   => 0,
         'operatingsystemkernelversions_id'  => 0,
         'license_number'                    => '',
         'licenseid'                         => '',
         'operatingsystemeditions_id'        => 0,
         'is_deleted'                        => 0,
         'is_dynamic'                        => 1,
         'entities_id'                       => 0,
         'is_recursive'                      => 0
      ];

      unset($ios->fields['date_mod']);
      unset($ios->fields['date_creation']);
      unset($ios->fields['id']);
      $this->assertEquals($a_reference, $ios->fields);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerExtension($id) {

      $pfiComputerComputer = new PluginFusioninventoryInventoryComputerComputer();
      $a_computer = current($pfiComputerComputer->find(['computers_id' => $id], [], 1));
      unset($a_computer['last_fusioninventory_update']);
      $serialized_inventory = $a_computer['serialized_inventory'];
      unset($a_computer['serialized_inventory']);
      $a_reference = [
          'computers_id'                              => $id,
          'operatingsystem_installationdate'          => '2012-10-16 08:12:56',
          'last_boot'                                 => '2018-06-11 08:03:32',
          'winowner'                                  => 'test',
          'wincompany'                                => 'siprossii',
          'remote_addr'                               => null,
          'is_entitylocked'                           => 0,
          'oscomment'                                 => null,
          'hostid'                                    => null
      ];

      unset($a_computer['id']);
      $this->assertEquals($a_reference, $a_computer);

      $this->assertNotEquals(null, $serialized_inventory);

   }


   /**
    * @test
    * @depends AddComputer
    */
   public function Softwareadded($id) {

      $nbsoftware = countElementsInTable("glpi_softwares");

      $this->assertEquals(3, $nbsoftware);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function SoftwareGentiumBasicadded($id) {

      $software = new Software();

      $softwares = $software->find(['name' => "GentiumBasic"]);
      $this->assertCount(1, $softwares);
      $item = current($softwares);

      unset($software->fields['date_mod']);
      unset($software->fields['date_creation']);

      $a_reference = [
          'name'                    => 'GentiumBasic',
          'entities_id'             => 0,
          'is_recursive'            => 0,
          'comment'                 => null,
          'locations_id'            => 0,
          'users_id_tech'           => 0,
          'groups_id_tech'          => 0,
          'is_update'               => 0,
          'softwares_id'            => 0,
          'manufacturers_id'        => 1,
          'is_deleted'              => 0,
          'is_template'             => 0,
          'template_name'           => null,
          'users_id'                => 0,
          'groups_id'               => 0,
          'ticket_tco'              => '0.0000',
          'is_helpdesk_visible'     => 1,
          'softwarecategories_id'   => 0,
          'is_valid'                => 1,
      ];
      $this->assertEquals($item['date_mod'], $item['date_creation']);
      $this->assertStringContainsString(date('Y-m-d'), $item['date_mod']);

      unset($item['date_mod']);
      unset($item['date_creation']);
      unset($item['id']);
      $this->assertEquals($a_reference, $item);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function SoftwareImageMagickadded($id) {

      $software = new Software();

      $softwares = $software->find(['name' => "ImageMagick"]);
      $this->assertCount(1, $softwares);
      $item = current($softwares);

      unset($software->fields['date_mod']);
      unset($software->fields['date_creation']);
      $a_reference = [
          'name'                    => 'ImageMagick',
          'entities_id'             => 0,
          'is_recursive'            => 0,
          'comment'                 => null,
          'locations_id'            => 0,
          'users_id_tech'           => 0,
          'groups_id_tech'          => 0,
          'is_update'               => 0,
          'softwares_id'            => 0,
          'manufacturers_id'        => 2,
          'is_deleted'              => 0,
          'is_template'             => 0,
          'template_name'           => null,
          'users_id'                => 0,
          'groups_id'               => 0,
          'ticket_tco'              => '0.0000',
          'is_helpdesk_visible'     => 1,
          'softwarecategories_id'   => 0,
          'is_valid'                => 1,
      ];
      $this->assertEquals($item['date_mod'], $item['date_creation']);
      $this->assertStringContainsString(date('Y-m-d'), $item['date_mod']);

      unset($item['date_mod']);
      unset($item['date_creation']);
      unset($item['id']);
      $this->assertEquals($a_reference, $item);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function SoftwareOrbit2added($id) {

      $software = new Software();

      $softwares = $software->find(['name' => "ORBit2"]);
      $this->assertCount(1, $softwares);
      $item = current($softwares);

      unset($software->fields['date_mod']);
      unset($software->fields['date_creation']);

      $a_reference = [
          'name'                    => 'ORBit2',
          'entities_id'             => 0,
          'is_recursive'            => 0,
          'comment'                 => null,
          'locations_id'            => 0,
          'users_id_tech'           => 0,
          'groups_id_tech'          => 0,
          'is_update'               => 0,
          'softwares_id'            => 0,
          'manufacturers_id'        => 3,
          'is_deleted'              => 0,
          'is_template'             => 0,
          'template_name'           => null,
          'users_id'                => 0,
          'groups_id'               => 0,
          'ticket_tco'              => '0.0000',
          'is_helpdesk_visible'     => 1,
          'softwarecategories_id'   => 0,
          'is_valid'                => 1,
      ];
      $this->assertEquals($item['date_mod'], $item['date_creation']);
      $this->assertStringContainsString(date('Y-m-d'), $item['date_mod']);

      unset($item['date_mod']);
      unset($item['date_creation']);
      unset($item['id']);
      $this->assertEquals($a_reference, $item);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function SoftwareVersionGentiumBasicadded($id) {

      $softwareVersion = new SoftwareVersion();
      $software = new Software();

      $soft = current($software->find(['name' => "GentiumBasic"], [], 1));

      $items = $softwareVersion->find(['softwares_id' => $soft['id']]);
      $this->assertCount(1, $items);
      $item = current($items);

      unset($item['date_mod']);
      unset($item['date_creation']);
      unset($item['id']);
      $a_reference = [
          'name'                 => '110',
          'entities_id'          => 0,
          'is_recursive'         => 0,
          'softwares_id'         => $soft['id'],
          'states_id'            => 0,
          'comment'              => null,
          'operatingsystems_id'  => 0
      ];

      $this->assertEquals($a_reference, $item);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function SoftwareVersionImageMagickadded($id) {

      $softwareVersion = new SoftwareVersion();
      $software = new Software();

      $software = current($software->find(['name' => "ImageMagick"], [], 1));

      $items = $softwareVersion->find(['softwares_id' => $software['id']]);
      $this->assertCount(1, $items);
      $item = current($items);

      unset($item['date_mod']);
      unset($item['date_creation']);
      unset($item['id']);
      $a_reference = [
          'name'                 => '6.8.0.7_1',
          'entities_id'          => 0,
          'is_recursive'         => 0,
          'softwares_id'         => $software['id'],
          'states_id'            => 0,
          'comment'              => null,
          'operatingsystems_id'  => 0
      ];

      $this->assertEquals($a_reference, $item);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function SoftwareVersionOrbit2added($id) {

      $softwareVersion = new SoftwareVersion();
      $software = new Software();

      $software = current($software->find(['name' => "ORBit2"], [], 1));

      $items = $softwareVersion->find(['softwares_id' => $software['id']]);
      $this->assertCount(1, $items);
      $item = current($items);

      unset($item['date_mod']);
      unset($item['date_creation']);
      unset($item['id']);
      $a_reference = [
          'name'                 => '2.14.19',
          'entities_id'          => 0,
          'is_recursive'         => 0,
          'softwares_id'         => $software['id'],
          'states_id'            => 0,
          'comment'              => null,
          'operatingsystems_id'  => 0
      ];

      $this->assertEquals($a_reference, $item);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerSoftwareGentiumBasic($id) {

      $softwareVersion = new SoftwareVersion();
      $software = new Software();
      $computer_SoftwareVersion = new Item_SoftwareVersion();

      $soft = current($software->find(['name' => "GentiumBasic"], [], 1));
      $softwareVersionItem = current($softwareVersion->find(['softwares_id' => $soft['id']], [], 1));

      $computer_SoftwareVersions = $computer_SoftwareVersion->find(['softwareversions_id' => $softwareVersionItem['id']]);
      $this->assertCount(1, $computer_SoftwareVersions);
      $item = current($computer_SoftwareVersions);

      $a_reference = [
         'itemtype'            => 'Computer',
         'items_id'            => $id,
         'softwareversions_id' => $softwareVersionItem['id'],
         'is_deleted_item'     => 0,
         'is_template_item'    => 0,
         'entities_id'         => 0,
         'is_deleted'          => 0,
         'is_dynamic'          => 1,
         'date_install'        => null
      ];
      unset($item['id']);
      $this->assertEquals($a_reference, $item);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerSoftwareImageMagick($id) {

      $softwareVersion = new SoftwareVersion();
      $software = new Software();
      $computer_SoftwareVersion = new Item_SoftwareVersion();

      $soft = current($software->find(['name' => "ImageMagick"], [], 1));
      $softwareVersionItem = current($softwareVersion->find(['softwares_id' => $soft['id']], [], 1));

      $computer_SoftwareVersions = $computer_SoftwareVersion->find(['softwareversions_id' => $softwareVersionItem['id']]);
      $this->assertCount(1, $computer_SoftwareVersions);
      $item = current($computer_SoftwareVersions);

      $a_reference = [
         'itemtype'            => 'Computer',
         'items_id'            => $id,
         'softwareversions_id' => $softwareVersionItem['id'],
         'is_deleted_item'     => 0,
         'is_template_item'    => 0,
         'entities_id'         => 0,
         'is_deleted'          => 0,
         'is_dynamic'          => 1,
         'date_install'        => null
      ];
      unset($item['id']);
      $this->assertEquals($a_reference, $item);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerSoftwareORBit2($id) {

      $softwareVersion = new SoftwareVersion();
      $software = new Software();
      $computer_SoftwareVersion = new Item_SoftwareVersion();

      $soft = current($software->find(['name' => "ORBit2"], [], 1));
      $softwareVersionItem = current($softwareVersion->find(['softwares_id' => $soft['id']], [], 1));

      $computer_SoftwareVersions = $computer_SoftwareVersion->find(['softwareversions_id' => $softwareVersionItem['id']]);
      $this->assertCount(1, $computer_SoftwareVersions);
      $item = current($computer_SoftwareVersions);

      $a_reference = [
         'itemtype'            => 'Computer',
         'items_id'            => $id,
         'softwareversions_id' => $softwareVersionItem['id'],
         'is_deleted_item'     => 0,
         'is_template_item'    => 0,
         'entities_id'         => 0,
         'is_deleted'          => 0,
         'is_dynamic'          => 1,
         'date_install'        => '2016-07-20'
      ];
      unset($item['id']);
      $this->assertEquals($a_reference, $item);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerProcessor($id) {

      $manufacturer = new Manufacturer();
      $manufacturer->getFromDBByCrit(['name' => 'Intel Corporation']);

      $a_data = getAllDataFromTable("glpi_deviceprocessors");
      $items = [];
      foreach ($a_data as $data) {
         unset($data['id']);
         unset($data['date_mod']);
         unset($data['date_creation']);
         $items[] = $data;
      }
      $a_reference = [
         [
            'designation'              => 'Core i3',
            'frequence'                => 2400,
            'comment'                  => null,
            'manufacturers_id'         => $manufacturer->fields['id'],
            'frequency_default'        => 2400,
            'nbcores_default'          => null,
            'nbthreads_default'        => null,
            'entities_id'              => 0,
            'is_recursive'             => 0,
            'deviceprocessormodels_id' => null
         ],
         [
            'designation'              => 'Core i3',
            'frequence'                => 2600,
            'comment'                  => null,
            'manufacturers_id'         => $manufacturer->fields['id'],
            'frequency_default'        => 2600,
            'nbcores_default'          => null,
            'nbthreads_default'        => null,
            'entities_id'              => 0,
            'is_recursive'             => 0,
            'deviceprocessormodels_id' => null
         ]
      ];
      $this->assertEquals($a_reference, $items);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerProcessorLink($id) {

      $a_data = getAllDataFromTable("glpi_deviceprocessors");
      $deviceProcessor2400 = 0;
      $deviceProcessor2600 = 0;
      foreach ($a_data as $data) {
         if ($data['frequence'] == 2400) {
            $deviceProcessor2400 = $data['id'];
         } else if ($data['frequence'] == 2600) {
            $deviceProcessor2600 = $data['id'];
         }
      }

      $a_dataLink = getAllDataFromTable("glpi_items_deviceprocessors",
         ['itemtype' => 'Computer', 'items_id' => $id]);
      $items = [];
      foreach ($a_dataLink as $data) {
         unset($data['id']);
         unset($data['date_mod']);
         unset($data['date_creation']);
         $items[] = $data;
      }
      $a_reference = [
         [
            'items_id'              => $id,
            'itemtype'              => 'Computer',
            'deviceprocessors_id'   => $deviceProcessor2400,
            'frequency'             => 2400,
            'serial'                => '',
            'is_deleted'            => 0,
            'is_dynamic'            => 1,
            'nbcores'               => 2,
            'nbthreads'             => 2,
            'entities_id'           => 0,
            'is_recursive'          => 0,
            'busID'                 => null,
            'otherserial'           => null,
            'locations_id'          => 0,
            'states_id'             => 0
         ],
         [
            'items_id'              => $id,
            'itemtype'              => 'Computer',
            'deviceprocessors_id'   => $deviceProcessor2400,
            'frequency'             => 2400,
            'serial'                => '',
            'is_deleted'            => 0,
            'is_dynamic'            => 1,
            'nbcores'               => 2,
            'nbthreads'             => 2,
            'entities_id'           => 0,
            'is_recursive'          => 0,
            'busID'                 => null,
            'otherserial'           => null,
            'locations_id'          => 0,
            'states_id'             => 0
         ],
         [
            'items_id'              => $id,
            'itemtype'              => 'Computer',
            'deviceprocessors_id'   => $deviceProcessor2400,
            'frequency'             => 2405,
            'serial'                => '',
            'is_deleted'            => 0,
            'is_dynamic'            => 1,
            'nbcores'               => 4,
            'nbthreads'             => 4,
            'entities_id'           => 0,
            'is_recursive'          => 0,
            'busID'                 => null,
            'otherserial'           => null,
            'locations_id'          => 0,
            'states_id'             => 0
         ],
         [
            'items_id'              => $id,
            'itemtype'              => 'Computer',
            'deviceprocessors_id'   => $deviceProcessor2600,
            'frequency'             => 2600,
            'serial'                => '',
            'is_deleted'            => 0,
            'is_dynamic'            => 1,
            'nbcores'               => 2,
            'nbthreads'             => 4,
            'entities_id'           => 0,
            'is_recursive'          => 0,
            'busID'                 => null,
            'otherserial'           => null,
            'locations_id'          => 0,
            'states_id'             => 0
         ]
      ];

      $this->assertEquals($a_reference, $items);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerMemory($id) {

      $a_data = getAllDataFromTable("glpi_devicememories");
      $items = [];
      foreach ($a_data as $data) {
         unset($data['id']);
         unset($data['date_mod']);
         unset($data['date_creation']);
         $items[] = $data;
      }
      $a_reference = [
         [
            'designation'           => 'DDR3 - SODIMM (None)',
            'frequence'             => 1067,
            'comment'               => null,
            'manufacturers_id'      => 0,
            'size_default'          => 0,
            'devicememorytypes_id'  => 5,
            'entities_id'           => 0,
            'is_recursive'          => 0,
            'devicememorymodels_id' => null
         ],
         [
            'designation'           => 'DDR3 - SODIMM (None)',
            'frequence'             => 1333,
            'comment'               => null,
            'manufacturers_id'      => 0,
            'size_default'          => 0,
            'devicememorytypes_id'  => 5,
            'entities_id'           => 0,
            'is_recursive'          => 0,
            'devicememorymodels_id' => null
         ]
      ];
      $this->assertEquals($a_reference, $items);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerMemoryLink($id) {

      $a_data = getAllDataFromTable("glpi_devicememories");
      $deviceMemory1067 = 0;
      $deviceMemory1333 = 0;
      foreach ($a_data as $data) {
         if ($data['frequence'] == 1067) {
            $deviceMemory1067 = $data['id'];
         } else if ($data['frequence'] == 1333) {
            $deviceMemory1333 = $data['id'];
         }
      }

      $a_dataLink = getAllDataFromTable("glpi_items_devicememories",
         ['itemtype' => 'Computer', 'items_id' => $id]);
      $items = [];
      foreach ($a_dataLink as $data) {
         unset($data['id']);
         unset($data['date_mod']);
         unset($data['date_creation']);
         $items[] = $data;
      }
      $a_reference = [
         [
            'items_id'              => $id,
            'itemtype'              => 'Computer',
            'serial'                => '98F6FF18',
            'is_deleted'            => 0,
            'is_dynamic'            => 1,
            'devicememories_id'     => $deviceMemory1067,
            'size'                  => 2048,
            'entities_id'           => 0,
            'is_recursive'          => 0,
            'busID'                 => '1',
            'otherserial'           => null,
            'locations_id'          => 0,
            'states_id'             => 0
         ],
         [
            'items_id'              => $id,
            'itemtype'              => 'Computer',
            'serial'                => '95F1833E',
            'is_deleted'            => 0,
            'is_dynamic'            => 1,
            'devicememories_id'     => $deviceMemory1067,
            'size'                  => 2048,
            'entities_id'           => 0,
            'is_recursive'          => 0,
            'busID'                 => '2',
            'otherserial'           => null,
            'locations_id'          => 0,
            'states_id'             => 0
         ],
         [
            'items_id'              => $id,
            'itemtype'              => 'Computer',
            'serial'                => '95F1833G',
            'is_deleted'            => 0,
            'is_dynamic'            => 1,
            'devicememories_id'     => $deviceMemory1067,
            'size'                  => 2048,
            'entities_id'           => 0,
            'is_recursive'          => 0,
            'busID'                 => '3',
            'otherserial'           => null,
            'locations_id'          => 0,
            'states_id'             => 0
         ],
         [
            'items_id'              => $id,
            'itemtype'              => 'Computer',
            'serial'                => '95F1833H',
            'is_deleted'            => 0,
            'is_dynamic'            => 1,
            'devicememories_id'     => $deviceMemory1333,
            'size'                  => 2048,
            'entities_id'           => 0,
            'is_recursive'          => 0,
            'busID'                 => '4',
            'otherserial'           => null,
            'locations_id'          => 0,
            'states_id'             => 0
         ]
      ];

      $this->assertEquals($a_reference, $items);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerNetworkport($id) {
      $a_dataLink = getAllDataFromTable("glpi_networkports",
         ['itemtype' => 'Computer', 'items_id' => $id]);
      $items = [];
      foreach ($a_dataLink as $data) {
         unset($data['id']);
         unset($data['date_mod']);
         unset($data['date_creation']);
         $items[] = $data;
      }

      $a_reference = [
         [
            'items_id'              => $id,
            'itemtype'              => 'Computer',
            'is_deleted'            => 0,
            'is_dynamic'            => 1,
            'entities_id'           => 0,
            'is_recursive'          => 0,
            'logical_number'        => 1,
            'name'                  => 'em0',
            'instantiation_type'    => 'NetworkPortEthernet',
            'mac'                   => '00:23:18:cf:0d:93',
            'comment'               => null
         ],
         [
            'items_id'              => $id,
            'itemtype'              => 'Computer',
            'is_deleted'            => 0,
            'is_dynamic'            => 1,
            'entities_id'           => 0,
            'is_recursive'          => 0,
            'logical_number'        => 0,
            'name'                  => 'lo0',
            'instantiation_type'    => 'NetworkPortLocal',
            'mac'                   => '',
            'comment'               => null
         ]
      ];

      $this->assertEquals($a_reference, $items);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerMonitor($id) {

      $a_dataMonit = getAllDataFromTable("glpi_monitors");

      $this->assertEquals(1, count($a_dataMonit), "Must have 1 monitor created");

      $a_dataLink = getAllDataFromTable("glpi_computers_items",
         ['itemtype' => 'Monitor', 'computers_id' => $id]);

      $this->assertEquals(1, count($a_dataLink), "Number of monitors not right");

      $a_dataLink = current($a_dataLink);

      $monitor = new Monitor();
      $monitor->getFromDB($a_dataLink['items_id']);

      unset($monitor->fields['id']);
      unset($monitor->fields['date_mod']);
      unset($monitor->fields['date_creation']);

      $manufacturer = new Manufacturer();
      $manufacturer->getFromDBByCrit(['name' => 'Lenovo']);

      $a_reference = [
          'entities_id'       => 0,
          'name'              => 'ThinkPad Display 1280x800',
          'contact'           => 'ddurieux',
          'contact_num'       => null,
          'users_id_tech'     => 0,
          'groups_id_tech'    => 0,
          'comment'           => null,
          'serial'            => 'UBYVUTFYEIUI',
          'otherserial'       => '',
          'size'              => '0.00',
          'have_micro'        => 0,
          'have_speaker'      => 0,
          'have_subd'         => 0,
          'have_bnc'          => 0,
          'have_dvi'          => 0,
          'have_pivot'        => 0,
          'have_hdmi'         => 0,
          'have_displayport'  => 0,
          'locations_id'      => 0,
          'monitortypes_id'   => 0,
          'monitormodels_id'  => 0,
          'manufacturers_id'  => $manufacturer->fields['id'],
          'is_global'         => 0,
          'is_deleted'        => 0,
          'is_template'       => 0,
          'template_name'     => null,
          'users_id'          => 0,
          'groups_id'         => 0,
          'states_id'         => 0,
          'ticket_tco'        => '0.0000',
          'is_dynamic'        => 1,
          'is_recursive'      => 0
      ];

      $this->assertEquals($a_reference, $monitor->fields);
   }


   /**
    * @test
    * @depends AddComputer
    */
   public function ComputerPrinter($id) {

      $a_dataLink = getAllDataFromTable("glpi_computers_items",
         ['itemtype' => 'Printer', 'computers_id' => $id]);

      $this->assertEquals(1, count($a_dataLink), "Number of printers not right");

      $a_dataLink = current($a_dataLink);

      $printer = new Printer();
      $printer->getFromDB($a_dataLink['items_id']);

      unset($printer->fields['id']);
      unset($printer->fields['date_mod']);
      unset($printer->fields['date_creation']);

      $a_reference = [
          'entities_id'          => 0,
          'is_recursive'         => 0,
          'name'                 => 'HP Deskjet 5700 Series',
          'contact'              => 'ddurieux',
          'contact_num'          => null,
          'users_id_tech'        => 0,
          'groups_id_tech'       => 0,
          'serial'               => 'MY47L1W1JHEB6',
          'otherserial'          => null,
          'have_serial'          => 0,
          'have_parallel'        => 0,
          'have_usb'             => 1,
          'have_wifi'            => 0,
          'have_ethernet'        => 0,
          'comment'              => null,
          'memory_size'          => null,
          'locations_id'         => 0,
          'networks_id'          => 0,
          'printertypes_id'      => 0,
          'printermodels_id'     => 0,
          'manufacturers_id'     => 0,
          'is_global'            => 0,
          'is_deleted'           => 0,
          'is_template'          => 0,
          'template_name'        => null,
          'init_pages_counter'   => 0,
          'last_pages_counter'   => 0,
          'users_id'             => 0,
          'groups_id'            => 0,
          'states_id'            => 0,
          'ticket_tco'           => '0.0000',
          'is_dynamic'           => 1,
      ];

      $this->assertEquals($a_reference, $printer->fields);
   }


   /**
    * @test
    */
   public function SoftwareUniqueForTwoComputers() {

      $date = date('Y-m-d H:i:s');

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION['glpiactiveentities_string'] = 0;
      $_SESSION['glpishowallentities'] = 1;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_inventory = [
          'fusioninventorycomputer' => [
              'winowner'                        => 'test',
              'wincompany'                      => 'siprossii',
              'operatingsystem_installationdate'=> '2012-10-16 08:12:56',
              'last_fusioninventory_update'     => $date,
              'last_boot'                       => 'NULL',
              'items_operatingsystems_id'       => [
                  'operatingsystems_id'              => 'freebsd',
                  'operatingsystemversions_id'       => '9.1-RELEASE',
                  'operatingsystemservicepacks_id'   => 'GENERIC ()root@farrell.cse.buffalo.edu',
                  'operatingsystemarchitectures_id'  => '',
                  'operatingsystemkernels_id'        => '',
                  'operatingsystemkernelversions_id' => '',
                  'operatingsystemeditions_id'       => '',
                  'licenseid'                        => '',
                  'license_number'                   => ''
              ]
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
          'SOFTWARES'      => [],
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
      $a_inventory['Computer'] = [
          'name'                             => 'pcJ1',
          'comment'                          => 'amd64/-1-11-30 22:04:44',
          'users_id'                         => 0,
          'uuid'                             => '68405E00-E5BE-11DF-801C-B05981201220',
          'manufacturers_id'                 => '',
          'computermodels_id'                => '',
          'serial'                           => 'XB63J7J1',
          'computertypes_id'                 => 'Notebook',
          'is_dynamic'                       => 1,
          'contact'                          => 'ddurieux'
      ];
      $a_inventory['software'] = [
            'acrobat_reader_9.2$$$$1.0.0.0$$$$192$$$$0$$$$0' => [
                    'name'                   => 'acrobat_Reader_9.2',
                    'version'                => '1.0.0.0',
                    'manufacturers_id'       => 192,
                    'entities_id'            => 0,
                    'is_template_item'   => 0,
                    'is_deleted_item'    => 0,
                    'operatingsystems_id'    => 0
                ]
          ];

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();
      $pfFormatconvert  = new PluginFusioninventoryFormatconvert();
      $software         = new Software();

      $a_inventory = $pfFormatconvert->replaceids($a_inventory, 'Computer', 0);

      $serialized = gzcompress(serialize($a_inventory));
      $a_inventory['fusioninventorycomputer']['serialized_inventory'] =
               Toolbox::addslashes_deep($serialized);

      $this->items_id = $computer->add(['serial'      => 'XB63J7J1',
                                        'entities_id' => 0]);

      $pfiComputerLib->updateComputer($a_inventory, $this->items_id, false);

      $a_software = $software->find(['name' => 'acrobat_Reader_9.2']);
      $this->assertEquals(1, count($a_software), "First computer added");

      $a_inventory['Computer']['name'] = "pcJ2";
      $a_inventory['Computer']['serial'] = "XB63J7J2";
      $pfiComputerLib->updateComputer($a_inventory, $this->items_id, false);

      $a_software = $software->find(['name' => 'acrobat_Reader_9.2']);
      $this->assertEquals(1, count($a_software), "Second computer added");
   }
}
