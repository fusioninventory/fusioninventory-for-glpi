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

class ComputerUpdate extends PHPUnit_Framework_TestCase {
   
   public $items_id = 0;
   public $datelatupdate = '';

   
   public function testAddComputer() {
      global $DB;

      $DB->connect();
      
      $Install = new Install();
      $Install->testInstall(0);
      
      $date = date('Y-m-d H:i:s');
      
      $_SESSION["plugin_fusinvinventory_entity"] = 0;
      
      $a_inventory = array(
          'fusioninventorycomputer' => Array(
              'winowner'                        => 'test',
              'wincompany'                      => 'siprossii',
              'operatingsystem_installationdate'=> '2012-10-16 08:12:56',
              'last_fusioninventory_update'     => $date
          ), 
          'soundcard'      => Array(),
          'graphiccard'    => Array(),
          'controller'     => Array(),
          'processor'      => Array(),
          'computerdisk'   => Array(),
          'memory'         => Array(),
          'monitor'        => Array(),
          'printer'        => Array(),
          'peripheral'     => Array(),
          'networkport'    => Array(),
          'software'       => Array(),
          'harddrive'      => Array(),
          'virtualmachine' => Array(),
          'antivirus'      => Array(),
          'storage'        => Array(),
          'itemtype'       => 'Computer'
          );
      $a_inventory['computer'] = array(
          'name'                             => 'pc',
          'comment'                          => 'amd64/-1-11-30 22:04:44',
          'users_id'                         => 0,
          'operatingsystems_id'              => 'freebsd',
          'operatingsystemversions_id'       => '9.1-RELEASE',
          'uuid'                             => '68405E00-E5BE-11DF-801C-B05981201220',
          'domains_id'                       => 'mydomain.local',
          'os_licenseid'                     => '',
          'os_license_number'                => '',
          'operatingsystemservicepacks_id'   => 'GENERIC ()root@farrell.cse.buffalo.edu',
          'manufacturers_id'                 => '',
          'computermodels_id'                => '',
          'serial'                           => 'XB63J7D',
          'computertypes_id'                 => 'Notebook',
          'is_dynamic'                       => 1,
          'contact'                          => 'ddurieux'
      );
      
      $a_inventory['processor'] = Array(
            Array(
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'serial'            => '',
                    'frequency'         => 2400
                ),
            Array(
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'serial'            => '',
                    'frequency'         => 2400
                ),
            Array(
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'serial'            => '',
                    'frequency'         => 2400
                ),
            Array(
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'serial'            => '',
                    'frequency'         => 2400
                )
        );

      $a_inventory['memory'] = Array(
            Array(
                    'size'                 => 2048,
                    'serial'               => '98F6FF18',
                    'frequence'            => '1067 MHz',
                    'devicememorytypes_id' => 'DDR3',
                    'designation'          => 'DDR3 - SODIMM (None)'
                ),
            Array(
                    'size'                 => 2048,
                    'serial'               => '95F1833E',
                    'frequence'            => '1067 MHz',
                    'devicememorytypes_id' => 'DDR3',
                    'designation'          => 'DDR3 - SODIMM (None)'
                )
        );

      $a_inventory['monitor'] = Array(
            Array(
                    'name'              => 'ThinkPad Display 1280x800',
                    'comment'           => '',
                    'serial'            => 'UBYVUTFYEIUI',
                    'manufacturers_id'  => 'Lenovo'
                )
      );

      $a_inventory['networkport'] = Array(
            'em0-00:23:18:cf:0d:93' => Array(
                    'name'                 => 'em0',
                    'netmask'              => '255.255.255.0',
                    'subnet'               => '192.168.30.0',
                    'mac'                  => '00:23:18:cf:0d:93',
                    'instantiation_type'   => 'NetworkPortEthernet',
                    'virtualdev'           => 0,
                    'ssid'                 => '',
                    'gateway'              => '',
                    'dhcpserver'           => '',
                    'ipaddress'            => Array('192.168.30.198')
                ),
            'lo0-' => Array(
                    'name'                 => 'lo0',
                    'virtualdev'           => 1,
                    'mac'                  => '',
                    'instantiation_type'   => 'NetworkPortLocal',
                    'subnet'               => '',
                    'ssid'                 => '',
                    'gateway'              => '',
                    'netmask'              => '',
                    'dhcpserver'           => '',
                    'ipaddress'            => Array('::1', 'fe80::1', '127.0.0.1')
                )
        );
      
      $a_inventory['software'] = Array(
            'GentiumBasic$$$$110$$$$1$$$$0' => Array(
                    'name'                   => 'GentiumBasic',
                    'version'                => 110,
                    'manufacturers_id'       => 1,
                    'entities_id'            => 0,
                    'is_template_computer'   => 0,
                    'is_deleted_computer'    => 0
                ),
            'ImageMagick$$$$6.8.0.7_1$$$$2$$$$0' => Array(
                    'name'                   => 'ImageMagick',
                    'version'                => '6.8.0.7_1',
                    'manufacturers_id'       => 2,
                    'entities_id'            => 0,
                    'is_template_computer'   => 0,
                    'is_deleted_computer'    => 0
                ),
            'ORBit2$$$$2.14.19$$$$3$$$$0' => Array(
                    'name'                   => 'ORBit2',
                    'version'                => '2.14.19',
                    'manufacturers_id'       => 3,
                    'entities_id'            => 0,
                    'is_template_computer'   => 0,
                    'is_deleted_computer'    => 0
                )
          );

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();
      $pfFormatconvert  = new PluginFusioninventoryFormatconvert();
      
      $a_inventory = $pfFormatconvert->replaceids($a_inventory);
  
      $serialized = gzcompress(serialize($a_inventory));
      $a_inventory['fusioninventorycomputer']['serialized_inventory'] = 
               Toolbox::addslashes_deep($serialized);
      
      $this->items_id = $computer->add(array('serial'      => 'XB63J7D',
                                             'entities_id' => 0));

      $this->assertGreaterThan(0, $this->items_id, FALSE);
      $_SESSION['glpiactive_entity'] = 0;
      $pfiComputerLib->updateComputer($a_inventory, $this->items_id, FALSE);

      // To be sure not have 2 same informations
      $pfiComputerLib->updateComputer($a_inventory, $this->items_id, FALSE);
   
      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }
   
   
   public function testComputerGeneral() {
      global $DB;

      $DB->connect();
      
      $computer = new Computer();
      
      $computer->getFromDB(1);
      unset($computer->fields['date_mod']);
      $a_reference = array(
          'name'                             => 'pc',
          'id'                               => '1',
          'entities_id'                      => '0',
          'serial'                           => 'XB63J7D',
          'otherserial'                      => NULL,
          'contact'                          => 'ddurieux',
          'contact_num'                      => NULL,
          'users_id_tech'                    => '0',
          'groups_id_tech'                   => '0',
          'comment'                          => NULL,
          'operatingsystems_id'              => '1',
          'operatingsystemversions_id'       => '1',
          'operatingsystemservicepacks_id'   => '1',
          'os_license_number'                => NULL,
          'os_licenseid'                     => NULL,
          'autoupdatesystems_id'             => '0',
          'locations_id'                     => '0',
          'domains_id'                       => '1',
          'networks_id'                      => '0',
          'computermodels_id'                => '0',
          'computertypes_id'                 => '1',
          'is_template'                      => '0',
          'template_name'                    => NULL,
          'manufacturers_id'                 => '0',
          'is_deleted'                       => '0',
          'notepad'                          => NULL,
          'is_dynamic'                       => '1',
          'users_id'                         => '0',
          'groups_id'                        => '0',
          'states_id'                        => '0',
          'ticket_tco'                       => '0.0000',
          'uuid'                             => '68405E00-E5BE-11DF-801C-B05981201220'
      );
      
      $this->assertEquals($a_reference, $computer->fields);      
   }   
   

   
   public function testComputerExtension() {
      global $DB;

      $DB->connect();
      
      $pfiComputerComputer = new PluginFusioninventoryInventoryComputerComputer();
      $a_computer = current($pfiComputerComputer->find("`computers_id`='1'", "", 1));
      unset($a_computer['last_fusioninventory_update']);
      $serialized_inventory = $a_computer['serialized_inventory'];
      unset($a_computer['serialized_inventory']);
      $a_reference = array(
          'id'                               => '1',
          'computers_id'                     => '1',
          'bios_date'                        => NULL,
          'bios_version'                     => NULL,
          'bios_manufacturers_id'            => '0',
          'operatingsystem_installationdate' => '2012-10-16 08:12:56',
          'winowner'                         => 'test',
          'wincompany'                       => 'siprossii',
          'remote_addr'                      => NULL
      );
      
      $this->assertEquals($a_reference, $a_computer);      
      
      $this->assertNotEquals(NULL, $serialized_inventory);      
      
   }  
   

   
   public function testSoftwareadded() {
      global $DB;

      $DB->connect();
      
      $nbsoftware = countElementsInTable("glpi_softwares");
      
      $this->assertEquals(3, $nbsoftware);   
   }
   
   
      
   public function testSoftwareGentiumBasicadded() {
      global $DB;

      $DB->connect();
      
      $software = new Software();
            
      $software->getFromDB(1);
      unset($software->fields['date_mod']);
      $a_reference = array(
          'id'                      => '1',
          'name'                    => 'GentiumBasic',
          'entities_id'             => '0',
          'is_recursive'            => '0',
          'comment'                 => NULL,
          'locations_id'            => '0',
          'users_id_tech'           => '0',
          'groups_id_tech'          => '0',
          'is_update'               => '0',
          'softwares_id'            => '-1',
          'manufacturers_id'        => '1',
          'is_deleted'              => '0',
          'is_template'             => '0',
          'template_name'           => NULL,
          'notepad'                 => NULL,
          'users_id'                => '0',
          'groups_id'               => '0',
          'ticket_tco'              => '0.0000',
          'is_helpdesk_visible'     => '1',
          'softwarecategories_id'   => '0'
      );
      
      $this->assertEquals($a_reference, $software->fields);
   } 

   
   
   public function testSoftwareImageMagickadded() {
      global $DB;

      $DB->connect();
      
      $software = new Software();
      
      $software->getFromDB(2);
      unset($software->fields['date_mod']);
      $a_reference = array(
          'id'                      => '2',
          'name'                    => 'ImageMagick',
          'entities_id'             => '0',
          'is_recursive'            => '0',
          'comment'                 => NULL,
          'locations_id'            => '0',
          'users_id_tech'           => '0',
          'groups_id_tech'          => '0',
          'is_update'               => '0',
          'softwares_id'            => '-1',
          'manufacturers_id'        => '2',
          'is_deleted'              => '0',
          'is_template'             => '0',
          'template_name'           => NULL,
          'notepad'                 => NULL,
          'users_id'                => '0',
          'groups_id'               => '0',
          'ticket_tco'              => '0.0000',
          'is_helpdesk_visible'     => '1',
          'softwarecategories_id'   => '0'
      );
      
      $this->assertEquals($a_reference, $software->fields);
   }
    
   
   
   public function testSoftwareORBit2added() {
      global $DB;

      $DB->connect();
      
      $software = new Software();
      
      $software->getFromDB(3);
      unset($software->fields['date_mod']);
      $a_reference = array(
          'id'                      => '3',
          'name'                    => 'ORBit2',
          'entities_id'             => '0',
          'is_recursive'            => '0',
          'comment'                 => NULL,
          'locations_id'            => '0',
          'users_id_tech'           => '0',
          'groups_id_tech'          => '0',
          'is_update'               => '0',
          'softwares_id'            => '-1',
          'manufacturers_id'        => '3',
          'is_deleted'              => '0',
          'is_template'             => '0',
          'template_name'           => NULL,
          'notepad'                 => NULL,
          'users_id'                => '0',
          'groups_id'               => '0',
          'ticket_tco'              => '0.0000',
          'is_helpdesk_visible'     => '1',
          'softwarecategories_id'   => '0'
      );
      
      $this->assertEquals($a_reference, $software->fields);
   }
      
   
   
   public function testSoftwareVersionGentiumBasicadded() {
      global $DB;

      $DB->connect();
      
      $softwareVersion = new SoftwareVersion();
            
      $softwareVersion->getFromDB(1);
      unset($softwareVersion->fields['date_mod']);
      $a_reference = array(
          'id'                   => '1',
          'name'                 => '110',
          'entities_id'          => '0',
          'is_recursive'         => '0',
          'softwares_id'         => '1',
          'states_id'            => '0',
          'comment'              => NULL,
          'operatingsystems_id'  => '0'
      );
      
      $this->assertEquals($a_reference, $softwareVersion->fields);
   } 
      
   
   
   public function testSoftwareVersionImageMagickadded() {
      global $DB;

      $DB->connect();
      
      $softwareVersion = new SoftwareVersion();
            
      $softwareVersion->getFromDB(2);
      unset($softwareVersion->fields['date_mod']);
      $a_reference = array(
          'id'                   => '2',
          'name'                 => '6.8.0.7_1',
          'entities_id'          => '0',
          'is_recursive'         => '0',
          'softwares_id'         => '2',
          'states_id'            => '0',
          'comment'              => NULL,
          'operatingsystems_id'  => '0'
      );
      
      $this->assertEquals($a_reference, $softwareVersion->fields);
   } 
      
   
   
   public function testSoftwareVersionORBit2added() {
      global $DB;

      $DB->connect();
      
      $softwareVersion = new SoftwareVersion();
            
      $softwareVersion->getFromDB(3);
      unset($softwareVersion->fields['date_mod']);
      $a_reference = array(
          'id'                   => '3',
          'name'                 => '2.14.19',
          'entities_id'          => '0',
          'is_recursive'         => '0',
          'softwares_id'         => '3',
          'states_id'            => '0',
          'comment'              => NULL,
          'operatingsystems_id'  => '0'
      );
      
      $this->assertEquals($a_reference, $softwareVersion->fields);
   }
   

   
   public function testComputerSoftwareGentiumBasic() {
      global $DB;

      $DB->connect();
                  
      $computer_SoftwareVersion = new Computer_SoftwareVersion();
            
      $computer_SoftwareVersion->getFromDB(1);
      
      $a_reference = array(
          'id'                   => '1',
          'computers_id'         => '1',
          'softwareversions_id'  => '1',
          'is_deleted_computer'  => '0',
          'is_template_computer' => '0',
          'entities_id'          => '0',
          'is_deleted'           => '0',
          'is_dynamic'           => '1'
      );
      
      $this->assertEquals($a_reference, $computer_SoftwareVersion->fields);      
   }
   

   
   public function testComputerSoftwareImageMagick() {
      global $DB;

      $DB->connect();
                  
      $computer_SoftwareVersion = new Computer_SoftwareVersion();
            
      $computer_SoftwareVersion->getFromDB(2);
      
      $a_reference = array(
          'id'                   => '2',
          'computers_id'         => '1',
          'softwareversions_id'  => '2',
          'is_deleted_computer'  => '0',
          'is_template_computer' => '0',
          'entities_id'          => '0',
          'is_deleted'           => '0',
          'is_dynamic'           => '1'
      );
      
      $this->assertEquals($a_reference, $computer_SoftwareVersion->fields);      
   }
   

   
   public function testComputerSoftwareORBit2() {
      global $DB;

      $DB->connect();
                  
      $computer_SoftwareVersion = new Computer_SoftwareVersion();
            
      $computer_SoftwareVersion->getFromDB(3);
      
      $a_reference = array(
          'id'                   => '3',
          'computers_id'         => '1',
          'softwareversions_id'  => '3',
          'is_deleted_computer'  => '0',
          'is_template_computer' => '0',
          'entities_id'          => '0',
          'is_deleted'           => '0',
          'is_dynamic'           => '1'
      );
      
      $this->assertEquals($a_reference, $computer_SoftwareVersion->fields);      
   }
   
   
   
   public function testComputerProcessorLink() {
      global $DB;

      $DB->connect();

      $a_dataLink = getAllDatasFromTable("glpi_items_deviceprocessors", 
                                         "`itemtype`='Computer'
                                            AND `items_id`='1'");
      
      $a_reference = array(
          '1' => array(
                     'id'                    => '1',
                     'items_id'              => '1',
                     'itemtype'              => 'Computer',
                     'deviceprocessors_id'   => '1',
                     'frequency'             => '2400',
                     'serial'                => '',
                     'is_deleted'            => '0',
                     'is_dynamic'            => '1'
                 ),
          '2' => array(
                     'id' => '2',
                     'items_id'              => '1',
                     'itemtype'              => 'Computer',
                     'deviceprocessors_id'   => '1',
                     'frequency'             => '2400',
                     'serial'                => '',
                     'is_deleted'            => '0',
                     'is_dynamic'            => '1'
                 ),
          '3' => array(
                     'id' => '3',
                     'items_id'              => '1',
                     'itemtype'              => 'Computer',
                     'deviceprocessors_id'   => '1',
                     'frequency'             => '2400',
                     'serial'                => '',
                     'is_deleted'            => '0',
                     'is_dynamic'            => '1'
                 ),
          '4' => array(
                     'id' => '4',
                     'items_id'              => '1',
                     'itemtype'              => 'Computer',
                     'deviceprocessors_id'   => '1',
                     'frequency'             => '2400',
                     'serial'                => '',
                     'is_deleted'            => '0',
                     'is_dynamic'            => '1'
                 )
      );
      
      $this->assertEquals($a_reference, $a_dataLink);      
   }
   
   
   
   public function testComputerNetworkport() {
      global $DB;

      $DB->connect();

      $a_dataLink = getAllDatasFromTable("glpi_networkports", 
                                         "`itemtype`='Computer'
                                            AND `items_id`='1'");
      
      $a_reference = array(
          '1' => array(
                     'id'                    => '1',
                     'items_id'              => '1',
                     'itemtype'              => 'Computer',
                     'is_deleted'            => '0',
                     'is_dynamic'            => '1',
                     'entities_id'           => '0',
                     'is_recursive'          => '0',
                     'logical_number'        => '0',
                     'name'                  => 'em0',
                     'instantiation_type'    => 'NetworkPortEthernet',
                     'mac'                   => '00:23:18:cf:0d:93',
                     'comment'               => NULL

                 ),
          '2' => array(
                     'id'                    => '2',
                     'items_id'              => '1',
                     'itemtype'              => 'Computer',
                     'is_deleted'            => '0',
                     'is_dynamic'            => '1',
                     'entities_id'           => '0',
                     'is_recursive'          => '0',
                     'logical_number'        => '0',
                     'name'                  => 'lo0',
                     'instantiation_type'    => 'NetworkPortLocal',
                     'mac'                   => '',
                     'comment'               => NULL
                 )
      );
      
      $this->assertEquals($a_reference, $a_dataLink);      
   }
   
   
   
   public function testSoftwareUniqueForTwoComputers() {
      global $DB;

      $DB->connect();

      $date = date('Y-m-d H:i:s');
      
      $_SESSION["plugin_fusinvinventory_entity"] = 0;
      
      $a_inventory = array(
          'fusioninventorycomputer' => Array(
              'winowner'                        => 'test',
              'wincompany'                      => 'siprossii',
              'operatingsystem_installationdate'=> '2012-10-16 08:12:56',
              'last_fusioninventory_update'     => $date
          ), 
          'soundcard'      => Array(),
          'graphiccard'    => Array(),
          'controller'     => Array(),
          'processor'      => Array(),
          'computerdisk'   => Array(),
          'memory'         => Array(),
          'monitor'        => Array(),
          'printer'        => Array(),
          'peripheral'     => Array(),
          'networkport'    => Array(),
          'software'       => Array(),
          'harddrive'      => Array(),
          'virtualmachine' => Array(),
          'antivirus'      => Array(),
          'storage'        => Array(),
          'itemtype'       => 'Computer'
          );
      $a_inventory['computer'] = array(
          'name'                             => 'pcJ1',
          'comment'                          => 'amd64/-1-11-30 22:04:44',
          'users_id'                         => 0,
          'operatingsystems_id'              => 'freebsd',
          'operatingsystemversions_id'       => '9.1-RELEASE',
          'uuid'                             => '68405E00-E5BE-11DF-801C-B05981201220',
          'domains_id'                       => 'mydomain.local',
          'os_licenseid'                     => '',
          'os_license_number'                => '',
          'operatingsystemservicepacks_id'   => 'GENERIC ()root@farrell.cse.buffalo.edu',
          'manufacturers_id'                 => '',
          'computermodels_id'                => '',
          'serial'                           => 'XB63J7J1',
          'computertypes_id'                 => 'Notebook',
          'is_dynamic'                       => 1,
          'contact'                          => 'ddurieux'
      );
      $a_inventory['software'] = Array(
            'acrobat_Reader_9.2$$$$1.0.0.0$$$$192$$$$0' => Array(
                    'name'                   => 'acrobat_Reader_9.2',
                    'version'                => '1.0.0.0',
                    'manufacturers_id'       => 192,
                    'entities_id'            => 0,
                    'is_template_computer'   => 0,
                    'is_deleted_computer'    => 0
                )
          );
      
      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();
      $pfFormatconvert  = new PluginFusioninventoryFormatconvert();
      $software         = new Software();
      
      $a_inventory = $pfFormatconvert->replaceids($a_inventory);
      
      $serialized = gzcompress(serialize($a_inventory));
      $a_inventory['fusioninventorycomputer']['serialized_inventory'] = 
               Toolbox::addslashes_deep($serialized);
      
      $this->items_id = $computer->add(array('serial'      => 'XB63J7J1',
                                             'entities_id' => 0));

      $_SESSION['glpiactive_entity'] = 0;
      $pfiComputerLib->updateComputer($a_inventory, $this->items_id, FALSE);
      
      $a_software = $software->find("`name`='acrobat_Reader_9.2'");
      $this->assertEquals(1, count($a_software), "First computer added");
      
      $a_inventory['computer']['name'] = "pcJ2";
      $a_inventory['computer']['serial'] = "XB63J7J2";
      $pfiComputerLib->updateComputer($a_inventory, $this->items_id, FALSE);
      
      $a_software = $software->find("`name`='acrobat_Reader_9.2'");
      $this->assertEquals(1, count($a_software), "Second computer added");
   }
 }



class ComputerUpdate_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('ComputerUpdate');
      return $suite;
   }
}

?>