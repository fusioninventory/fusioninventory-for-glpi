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

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();
      $pfFormatconvert  = new PluginFusioninventoryFormatconvert();
      
      $a_inventory = $pfFormatconvert->replaceids($a_inventory);
      
      $this->items_id = $computer->add(array('serial'      => 'XB63J7D',
                                             'entities_id' => 0));

      $this->assertGreaterThan(0, $this->items_id, FALSE);
      
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
      $a_reference = array(
          'id'                               => '1',
          'computers_id'                     => '1',
          'bios_date'                        => NULL,
          'bios_version'                     => NULL,
          'bios_manufacturers_id'            => '0',
          'operatingsystem_installationdate' => '2012-10-16 08:12:56',
          'winowner'                         => 'test',
          'wincompany'                       => 'siprossii',
          'remote_addr'                      => NULL,
          'serialized_inventory'             => NULL
      );
      
      $this->assertEquals($a_reference, $a_computer);      
      
   }
 }



class ComputerUpdate_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('ComputerUpdate');
      return $suite;
   }
}

?>