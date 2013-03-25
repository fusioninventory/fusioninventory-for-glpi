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

class ComputerLog extends PHPUnit_Framework_TestCase {
   
   public function testLogAddComputer() {
      global $DB;

      $DB->connect();
      
      $Install = new Install();
      $Install->testInstall(0);

      // Get last glpi_logs id
      $query = "SELECT * FROM `glpi_logs`
         ORDER BY `id` DESC
         LIMIT 1";
      
      $result = $DB->query($query);
      $a_log = $DB->fetch_assoc($result);
      $logs_id = $a_log['id'];
      
      
      
      
      
      
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
                    'frequence'         => 2400,
                    'serial'            => '',
                    'frequency'         => 2400
                ),
            Array(
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'frequence'         => 2400,
                    'serial'            => '',
                    'frequency'         => 2400
                ),
            Array(
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'frequence'         => 2400,
                    'serial'            => '',
                    'frequency'         => 2400
                ),
            Array(
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'frequence'         => 2400,
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
                    'name'              => '',
                    'comment'           => '',
                    'serial'            => '',
                    'manufacturers_id'  => ''
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
                    'is_deleted_computer'    => 0,
                    'is_dynamic'             => 1
                ),
            'ImageMagick$$$$6.8.0.7_1$$$$2$$$$0' => Array(
                    'name'                   => 'ImageMagick',
                    'version'                => '6.8.0.7_1',
                    'manufacturers_id'       => 2,
                    'entities_id'            => 0,
                    'is_template_computer'   => 0,
                    'is_deleted_computer'    => 0,
                    'is_dynamic'             => 1
                ),
            'ORBit2$$$$2.14.19$$$$3$$$$0' => Array(
                    'name'                   => 'ORBit2',
                    'version'                => '2.14.19',
                    'manufacturers_id'       => 3,
                    'entities_id'            => 0,
                    'is_template_computer'   => 0,
                    'is_deleted_computer'    => 0,
                    'is_dynamic'             => 1
                )
          );

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();
      $pfFormatconvert  = new PluginFusioninventoryFormatconvert();
      
      $a_inventory = $pfFormatconvert->replaceids($a_inventory);
  
      $serialized = gzcompress(serialize($a_inventory));
      $a_inventory['fusioninventorycomputer']['serialized_inventory'] = 
               Toolbox::addslashes_deep($serialized);
      
      $computers_id = $computer->add(array('serial'      => 'XB63J7D',
                                             'entities_id' => 0));

      $this->assertGreaterThan(0, $computers_id, FALSE);
      $_SESSION['glpiactive_entity'] = 0;
      $pfiComputerLib->updateComputer($a_inventory, $computers_id, TRUE);

      $query = "SELECT * FROM `glpi_logs`
         WHERE `id` > '".$logs_id."'";
      $result = $DB->query($query);
      $a_logs = array();
      $a_reference = array();

      while ($data=$DB->fetch_array($result)) {
         $a_logs[$data['id']] = $data;
         $a_reference[$data['id']] = array();
      }
      $this->assertEquals($a_reference, $a_logs, "Log may be empty");
      
return;
      
      // To be sure not have 2 same informations
      $pfiComputerLib->updateComputer($a_inventory, $this->items_id, FALSE);
   
      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }
 }



class ComputerLog_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('ComputerLog');
      return $suite;
   }
}

?>