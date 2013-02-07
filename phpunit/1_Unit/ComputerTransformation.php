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

class ComputerTransformation extends PHPUnit_Framework_TestCase {
   
   
   public function testComputerGeneral() {
      global $DB;

      $DB->connect();
      
      $_SESSION["plugin_fusinvinventory_entity"] = 0;

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'ARCHNAME'             => 'i386-freebsd-thread-multi',
                'CHASSIS_TYPE'         => 'Notebook',
                'CHECKSUM'             => '131071',
                'DATELASTLOGGEDUSER'   => 'Fri Feb  1 10:56',
                'DEFAULTGATEWAY'       => '',
                'DESCRIPTION'          => 'amd64/-1-11-30 22:04:44',
                'DNS'                  => '8.8.8.8',
                'ETIME'                => '1',
                'IPADDR'               => '',
                'LASTLOGGEDUSER'       => 'ddurieux',
                'MEMORY'               => '3802',
                'NAME'                 => 'pc',
                'OSCOMMENTS'           => 'GENERIC ()root@farrell.cse.buffalo.edu',
                'OSNAME'               => 'freebsd',
                'OSVERSION'            => '9.1-RELEASE',
                'PROCESSORN'           => '4',
                'PROCESSORS'           => '2400',
                'PROCESSORT'           => 'Core i3',
                'SWAP'                 => '0',
                'USERDOMAIN'           => '',
                'USERID'               => 'ddurieux',
                'UUID'                 => '68405E00-E5BE-11DF-801C-B05981201220',
                'VMSYSTEM'             => 'Physical',
                'WORKGROUP'            => 'mydomain.local'
            );
      
      $pfFormatconvert = new PluginFusioninventoryFormatconvert();
      
      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);
      $date = date('Y-m-d H:i:s');
      if (isset($a_return['fusioninventorycomputer'])
              && isset($a_return['fusioninventorycomputer']['last_fusioninventory_update'])) {
         $date = $a_return['fusioninventorycomputer']['last_fusioninventory_update'];
      }
      $a_reference = array(
          'fusioninventorycomputer' => Array(
              'winowner'                     => '',
              'wincompany'                   => '',
              'last_fusioninventory_update'  => $date
          ), 
          'soundcard'               => Array(),
          'graphiccard'             => Array(),
          'controller'              => Array(),
          'processor'               => Array(),
          'computerdisk'            => Array(),
          'memory'                  => Array(),
          'monitor'                 => Array(),
          'printer'                 => Array(),
          'peripheral'              => Array(),
          'networkport'             => Array(),
          'SOFTWARES'               => Array(),
          'harddrive'               => Array(),
          'virtualmachine'          => Array(),
          'antivirus'               => Array(),
          'storage'                 => Array()
          );
      $a_reference['computer'] = array(
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
          'serial'                           => '',
          'computertypes_id'                 => 'Notebook',
          'is_dynamic'                       => 1
     );
      // users_id = 0 because user notin DB
      $this->assertEquals($a_reference, $a_return);
      
   }   
}



class ComputerTransformation_AllTests  {

   public static function suite() {

//      $Install = new Install();
//      $Install->testInstall(0);
      
      $suite = new PHPUnit_Framework_TestSuite('ComputerTransformation');
      return $suite;
   }
}

?>